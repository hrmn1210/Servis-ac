<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display user dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'active_bookings' => Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'assigned', 'in_progress'])
                ->count(),
            'total_spent' => Booking::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('total_price')
        ];

        $recent_bookings = Booking::where('user_id', $user->id)
            ->with('services')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recent_bookings'));
    }

    /**
     * Display user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Display bookings history
     */
    public function bookings()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->with(['services', 'technician', 'payment']) // Tambahkan payment eager loading
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    /**
     * Show booking creation form
     */
    public function createBooking()
    {
        $services = Service::all();
        return view('user.bookings.create', compact('services'));
    }

    /**
     * Store new booking
     */
    public function storeBooking(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'booking_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'payment_type' => 'required|in:full,down_payment,cod'
        ]);

        // Calculate total price
        $totalPrice = 0;
        $selectedServices = [];

        foreach ($validated['services'] as $index => $serviceId) {
            $service = Service::find($serviceId);
            $quantity = $validated['quantities'][$index] ?? 1;
            $selectedServices[$serviceId] = [
                'quantity' => $quantity,
                'price' => $service->price
            ];
            $totalPrice += $service->price * $quantity;
        }

        // Calculate payment amounts based on type
        $downPaymentAmount = null;
        $remainingAmount = null;
        $initialStatus = 'pending';

        switch ($validated['payment_type']) {
            case 'full':
                $downPaymentAmount = $totalPrice;
                $remainingAmount = 0;
                $initialStatus = 'pending'; // Butuh verifikasi admin
                break;
            case 'down_payment':
                $downPaymentAmount = $totalPrice * 0.5; // 50% down payment
                $remainingAmount = $totalPrice - $downPaymentAmount;
                $initialStatus = 'pending'; // Butuh verifikasi admin
                break;
            case 'cod':
                $downPaymentAmount = 0;
                $remainingAmount = $totalPrice;
                $initialStatus = 'pending'; // COD langsung pending, bayar ke teknisi
                break;
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'address' => $validated['address'],
            'booking_date' => $validated['booking_date'],
            'notes' => $validated['notes'],
            'status' => 'pending',
            'total_price' => $totalPrice
        ]);

        // Attach services
        $booking->services()->attach($selectedServices);

        // Auto-create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'payment_type' => $validated['payment_type'],
            'down_payment_amount' => $downPaymentAmount,
            'remaining_amount' => $remainingAmount,
            'status' => $initialStatus,
            'payment_method' => null,
            'verification_status' => $validated['payment_type'] === 'cod' ? 'approved' : 'pending'
        ]);

        return redirect()->route('user.bookings.show', $booking->id)
            ->with('success', 'Booking created successfully. ' .
                ($validated['payment_type'] === 'cod' ?
                    'You will pay when the service is completed.' :
                    'Please complete the payment.'));
    }

    /**
     * Display booking details
     */
    public function showBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
            ->with(['services', 'technician', 'payment']) // Tambahkan payment eager loading
            ->findOrFail($id);

        return view('user.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->findOrFail($id);

        $booking->update(['status' => 'cancelled']);

        // Jika ada payment, update status payment juga
        if ($booking->payment) {
            $booking->payment->update(['status' => 'cancelled']);
        }

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Display payment history
     */
    public function payments()
    {
        $user = Auth::user();

        // Get payments from both service requests and bookings
        $payments = Payment::where(function ($query) use ($user) {
            $query->whereHas('serviceRequest', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
            ->with(['serviceRequest', 'booking.services']) // Eager loading untuk relasi
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

        $payment = Payment::where(function ($query) use ($user) {
            $query->whereHas('serviceRequest', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
            ->with(['serviceRequest', 'booking.services']) // Eager loading untuk relasi
            ->findOrFail($id);

        return view('user.payments.show', compact('payment'));
    }

    /**
     * Process payment
     */
    /**
     * Process payment
     */
    public function processPayment(Request $request, $id)
    {
        $user = Auth::user();

        $payment = Payment::where(function ($query) use ($user) {
            $query->whereHas('serviceRequest', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
            ->where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $updateData = [
            'payment_method' => $validated['payment_method'],
            'status' => 'pending_verification', // Menunggu verifikasi admin
            'verification_status' => 'pending'
        ];

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            $updateData['payment_proof'] = $proofPath;
        }

        // Untuk transfer wajib upload bukti
        if ($validated['payment_method'] === 'transfer' && !$request->hasFile('payment_proof')) {
            return redirect()->back()->with('error', 'Please upload payment proof for bank transfer.');
        }

        $payment->update($updateData);

        return redirect()->back()->with('success', 'Payment submitted successfully. Waiting for admin verification.');
    }

    /**
     * Download payment invoice
     */
    public function downloadInvoice($id)
    {
        $user = Auth::user();

        $payment = Payment::where(function ($query) use ($user) {
            $query->whereHas('serviceRequest', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
            ->with(['serviceRequest', 'booking.services'])
            ->findOrFail($id);

        // TODO: Implement PDF invoice generation
        // Untuk sementara redirect ke payment details
        return redirect()->route('user.payments.show', $payment->id)
            ->with('info', 'Invoice download feature will be available soon.');
    }
}
