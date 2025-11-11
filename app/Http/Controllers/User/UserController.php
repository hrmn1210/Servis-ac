<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display user dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        $userStats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_bookings' => Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'pending_verification'])->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'active_bookings' => Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'assigned', 'in_progress'])
                ->count(),
            'total_spent' => Payment::whereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'paid')->sum('amount')
        ];

        $recent_bookings = Booking::where('user_id', $user->id)
            ->with(['services', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact('userStats', 'recent_bookings'));
    }

    /**
     * Display user profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * [PERBAIKAN] Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // [PERBAIKAN] Diubah dari 'nullable' menjadi 'required'
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone_number = $validated['phone_number'];
            $user->address = $validated['address'];

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }


    /**
     * Display user bookings list
     */
    public function bookings(Request $request)
    {
        $user = Auth::user();
        $query = Booking::where('user_id', $user->id)
            ->with(['services', 'technician', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);
        return view('user.bookings.index', compact('bookings'));
    }

    /**
     * [PERBAIKAN] Show create booking form
     */
    public function createBooking()
    {
        $user = Auth::user();

        // [PERBAIKAN] Cek nomor WA sebelum menampilkan form booking
        if (empty($user->phone_number)) {
            return redirect()->route('user.profile')
                ->with('error', 'Harap lengkapi Nomor WhatsApp Anda sebelum membuat booking.');
        }

        $services = Service::where('price', '>', 0)->orderBy('name')->get();
        return view('user.bookings.create', compact('services'));
    }

    /**
     * Store new booking
     */
    public function storeBooking(Request $request)
    {
        $user = Auth::user();

        // [PERBAIKAN] Cek sekali lagi jika user lolos dari createBooking
        if (empty($user->phone_number)) {
            return redirect()->route('user.profile')
                ->with('error', 'Nomor WhatsApp Anda wajib diisi untuk melanjutkan.');
        }

        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'quantity' => 'nullable|array',
            'quantity.*' => 'integer|min:1',
            'address' => 'required|string|max:1000',
            'booking_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'payment_type' => 'required|in:full,down_payment,cod',
            'payment_proof' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('payment_type') !== 'cod';
                }),
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048'
            ],
        ]);

        try {
            DB::beginTransaction();

            // 2. Kalkulasi Total Harga
            $serviceIds = $validated['services'];
            $quantities = $validated['quantity'] ?? [];
            $selectedServices = Service::whereIn('id', $serviceIds)->get();

            $totalPrice = 0;
            $servicesToAttach = [];

            foreach ($selectedServices as $service) {
                $quantity = $quantities[$service->id] ?? 1;
                $currentPrice = $service->price;
                $totalPrice += $currentPrice * $quantity;
                $servicesToAttach[$service->id] = [
                    'quantity' => $quantity,
                    'price' => $currentPrice
                ];
            }

            // 3. Tentukan Status & Handle Upload
            $paymentStatus = 'pending';
            $verificationStatus = 'pending';
            $bookingStatus = 'pending';
            $downPaymentAmount = 0;
            $remainingAmount = $totalPrice;
            $filePath = null;

            switch ($validated['payment_type']) {
                case 'full':
                    $downPaymentAmount = $totalPrice;
                    $remainingAmount = 0;
                    $paymentStatus = 'pending_verification';
                    $verificationStatus = 'pending';
                    break;
                case 'down_payment':
                    $downPaymentAmount = $totalPrice * 0.5;
                    $remainingAmount = $totalPrice - $downPaymentAmount;
                    $paymentStatus = 'pending_verification';
                    $verificationStatus = 'pending';
                    break;
                case 'cod':
                    $downPaymentAmount = 0;
                    $remainingAmount = $totalPrice;
                    $paymentStatus = 'pending';
                    $verificationStatus = 'approved';
                    $bookingStatus = 'confirmed';
                    break;
            }

            if ($request->hasFile('payment_proof')) {
                $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // 4. Buat Booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'address' => $validated['address'],
                'booking_date' => $validated['booking_date'],
                'notes' => $validated['notes'],
                'status' => $bookingStatus,
                'total_price' => $totalPrice
            ]);

            // 5. Lampirkan Layanan
            $booking->services()->attach($servicesToAttach);

            // 6. Buat Catatan Pembayaran
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalPrice,
                'payment_type' => $validated['payment_type'],
                'down_payment_amount' => $downPaymentAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $paymentStatus,
                'payment_method' => $validated['payment_type'] === 'cod' ? 'cash' : 'transfer',
                'payment_proof' => $filePath,
                'verification_status' => $verificationStatus
            ]);

            DB::commit();

            // 7. Tentukan Pesan Sukses
            $message = $validated['payment_type'] === 'cod'
                ? 'Booking berhasil dibuat. Anda akan membayar saat layanan selesai.'
                : 'Booking dibuat dan pembayaran dikirim. Mohon tunggu verifikasi admin.';

            return redirect()->route('user.bookings.show', $booking->id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating booking: ' . $e->getMessage());
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            return redirect()->back()
                ->with('error', 'Gagal membuat booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show booking details
     */
    public function showBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
            ->with(['services', 'technician', 'payment', 'rating'])
            ->findOrFail($id);

        return view('user.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Booking tidak dapat dibatalkan pada tahap ini.');
        }

        try {
            DB::beginTransaction();
            $booking->update([
                'status' => 'cancelled',
                'notes' => ($booking->notes ? $booking->notes . "\n\n" : '') . "Dibatalkan oleh user: " . $request->input('cancel_reason', 'Tidak ada alasan.'),
            ]);

            if ($booking->payment) {
                $booking->payment->update(['status' => 'cancelled']);
            }
            DB::commit();
            return redirect()->route('user.bookings.index')->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    /**
     * Display user payments list
     */
    public function payments()
    {
        $user = Auth::user();
        $payments = Payment::whereHas('booking', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['booking.services'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.payments.index', compact('payments'));
    }

    /**
     * Show payment details
     */
    public function showPayment($id)
    {
        $user = Auth::user();
        $payment = Payment::whereHas('booking', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['booking.services', 'booking.technician'])
            ->findOrFail($id);

        return view('user.payments.show', compact('payment'));
    }

    /**
     * [FUNGSI INI KOSONG / TIDAK LAGI DIPAKAI DI ALUR UTAMA]
     */
    public function processPayment(Request $request, $id)
    {
        return redirect()->route('user.payments')->with('info', 'Alur pembayaran telah diperbarui.');
    }

    /**
     * Submit rating and review
     */
    public function submitRating(Request $request, $booking_id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->findOrFail($booking_id);

        if ($booking->rating) {
            return redirect()->back()->with('error', 'Anda sudah memberi ulasan untuk booking ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        try {
            $booking->rating()->create([
                'user_id' => $user->id,
                'rating' => $validated['rating'],
                'review' => $validated['review']
            ]);

            return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim ulasan: ' . $e->getMessage());
        }
    }

    /**
     * Request booking reschedule
     */
    public function requestReschedule(Request $request, $id)
    {
        $user = Auth::user();

        $booking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->findOrFail($id);

        $validated = $request->validate([
            'new_booking_date' => 'required|date|after:today',
            'reschedule_reason' => 'required|string|max:500'
        ]);

        try {
            $booking->update([
                'booking_date' => $validated['new_booking_date'],
                'notes' => ($booking->notes ? $booking->notes . "\n\n" : '') .
                    "Permintaan Jadwal Ulang: " . $validated['reschedule_reason'],
                'status' => 'pending' // Reset ke pending untuk persetujuan admin
            ]);

            return redirect()->back()->with('success', 'Permintaan jadwal ulang terkirim. Mohon tunggu konfirmasi admin.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim permintaan: ' . $e->getMessage());
        }
    }
}
