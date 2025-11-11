<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Rating; // Pastikan Anda menggunakan App\Models\Rating
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// use Maatwebsite\Excel\Facades\Excel; // Hilangkan komentar jika Anda menggunakannya
// use App\Exports\UsersExport; // Hilangkan komentar jika Anda menggunakannya

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_verification' => Payment::where('status', 'pending_verification')->count()
        ];

        $recent_bookings = Booking::with(['user', 'services', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    /**
     * Helper function to calculate percentage change.
     */
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Display users management page
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($sort, $order);
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create new user
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,technician',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show form to edit user
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user details
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:user,admin,technician',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone_number = $validated['phone_number'];
        $user->address = $validated['address'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Display user details
     */
    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }


    /**
     * Display bookings management page
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'technician', 'services', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        $bookings = $query->paginate(10);
        $technicians = User::isTechnician()->get();
        return view('admin.bookings.index', compact('bookings', 'technicians'));
    }

    /**
     * Show booking details
     */
    public function showBooking($id)
    {
        $booking = Booking::with(['user', 'technician', 'services', 'payment'])->findOrFail($id);
        $technicians = User::isTechnician()->get();
        return view('admin.bookings.show', compact('booking', 'technicians'));
    }

    /**
     * Approve pending booking
     */
    public function approvePendingBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id'
        ]);

        $booking = Booking::with('payment')->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking ini tidak bisa disetujui (Status: ' . $booking->status . ').');
        }
        if (!$booking->payment) {
            return redirect()->back()->with('error', 'Booking ini tidak memiliki data pembayaran.');
        }
        if ($booking->payment->status !== 'awaiting_confirmation') {
            return redirect()->back()->with('error', 'Status pembayaran booking ini sudah diproses (Status: ' . $booking->payment->status . ').');
        }

        try {
            DB::beginTransaction();
            $booking->update([
                'status' => 'confirmed',
                'technician_id' => $validated['technician_id']
            ]);
            $booking->payment->update(['status' => 'pending']);
            DB::commit();

            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking approved and technician assigned. User can now pay.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal approve booking #' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pending booking
     */
    public function rejectPendingBooking(Request $request, $id)
    {
        $request->validate(['reject_reason' => 'required|string|max:500']);

        $booking = Booking::with('payment')->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking ini tidak dalam status pending.');
        }

        try {
            DB::beginTransaction();
            $booking->update([
                'status' => 'cancelled',
                'notes' => ($booking->notes ? $booking->notes . "\n\n" : '') . "Admin Note (Rejected): " . $request->reject_reason,
            ]);

            if ($booking->payment) {
                $booking->payment->update(['status' => 'cancelled']);
            }
            DB::commit();

            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal reject booking #' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Update booking status (for COD or other adjustments)
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,assigned,in_progress,completed,cancelled,on_hold',
            'technician_id' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('status') == 'assigned';
                }),
                'nullable',
                'exists:users,id'
            ],
        ]);

        try {
            $booking = Booking::findOrFail($id);
            $booking->status = $validated['status'];

            if (!empty($validated['technician_id'])) {
                $booking->technician_id = $validated['technician_id'];
            }

            $booking->save();
            return redirect()->route('admin.bookings.show', $id)->with('success', 'Booking status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Display services management page
     */
    public function services()
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show form to create new service
     */
    public function createService()
    {
        return view('admin.services.create');
    }

    /**
     * Store new service
     */
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
        ]);

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Show form to edit service
     */
    public function editService(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update service details
     */
    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
        ]);

        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Delete a service
     */
    public function deleteService(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }

    /**
     * Display payments management page
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['booking.user'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $payments = $query->paginate(10);
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $pendingVerification = Payment::where('status', 'pending_verification')->count();
        $totalPayments = Payment::count();
        $refundedAmount = Payment::where('status', 'refunded')->sum('amount');

        return view('admin.payments.index', compact(
            'payments',
            'totalRevenue',
            'pendingVerification',
            'totalPayments',
            'refundedAmount'
        ));
    }

    /**
     * Display payment verification page
     */
    public function paymentVerification()
    {
        $payments = Payment::where('status', 'pending_verification')
            ->with('booking.user')
            ->orderBy('updated_at', 'asc')
            ->paginate(10);

        $pendingCount = $payments->total();
        $approvedTodayCount = Payment::where('status', 'paid')
            ->where('verification_status', 'approved')
            ->whereDate('paid_at', Carbon::today())
            ->count();

        $technicians = User::isTechnician()->get();

        return view('admin.payments.verification', compact('payments', 'pendingCount', 'approvedTodayCount', 'technicians'));
    }


    /**
     * Verify payment from user (approve/reject) + Assign Technician.
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'verification_action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500',
            'technician_id' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('verification_action') == 'approve';
                }),
                'nullable',
                'exists:users,id'
            ],
        ]);

        try {
            $action = $validated['verification_action'];
            $notes = $validated['admin_notes'];

            if ($action == 'approve') {
                $technicianId = $validated['technician_id'];

                DB::beginTransaction();

                $payment->update([
                    'status' => 'paid',
                    'verification_status' => 'approved',
                    'paid_at' => Carbon::now(),
                    'admin_notes' => $notes,
                ]);

                if ($payment->booking) {
                    $payment->booking->update([
                        'status' => 'assigned',
                        'technician_id' => $technicianId
                    ]);
                }
                DB::commit();
            } elseif ($action == 'reject') {
                DB::beginTransaction();

                $oldProof = $payment->payment_proof;

                $payment->update([
                    'status' => 'pending',
                    'verification_status' => 'rejected',
                    'admin_notes' => $notes,
                    'payment_proof' => null,
                ]);

                if ($oldProof) {
                    Storage::disk('public')->delete($oldProof);
                }

                DB::commit();
            }

            return response()->json([
                'success' => true,
                'message' => "Payment {$action} successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal verifikasi payment #{$payment->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display reports and analytics
     */
    /**
     * Display reports and analytics
     */
    public function reports()
    {
        // 1. Data untuk Summary Cards
        $now = Carbon::now();
        $lastMonth = $now->clone()->subMonthNoOverflow();

        // Revenue
        $currentMonthRevenue = Payment::where('status', 'paid')->whereYear('paid_at', $now->year)->whereMonth('paid_at', $now->month)->sum('amount');
        $lastMonthRevenue = Payment::where('status', 'paid')->whereYear('paid_at', $lastMonth->year)->whereMonth('paid_at', $lastMonth->month)->sum('amount');

        // Bookings
        $currentMonthCompletedBookings = Booking::where('status', 'completed')->whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->count();
        $lastMonthCompletedBookings = Booking::where('status', 'completed')->whereYear('created_at', $lastMonth->year)->whereMonth('created_at', $lastMonth->month)->count();

        // Users
        $currentMonthNewUsers = User::where('role', 'user')->whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->count();
        $lastMonthNewUsers = User::where('role', 'user')->whereYear('created_at', $lastMonth->year)->whereMonth('created_at', $lastMonth->month)->count();

        // Satisfaction (dari 1-5 diubah ke %)
        $currentMonthSatisfaction = Rating::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->avg('rating') * 20;
        $lastMonthSatisfaction = Rating::whereYear('created_at', $lastMonth->year)->whereMonth('created_at', $lastMonth->month)->avg('rating') * 20;

        $summaryStats = [
            'monthlyRevenue' => $currentMonthRevenue,
            'revenuePercentageChange' => $this->calculatePercentageChange($currentMonthRevenue, $lastMonthRevenue),
            'completedBookings' => $currentMonthCompletedBookings,
            'bookingsPercentageChange' => $this->calculatePercentageChange($currentMonthCompletedBookings, $lastMonthCompletedBookings),
            'newCustomers' => $currentMonthNewUsers,
            'usersPercentageChange' => $this->calculatePercentageChange($currentMonthNewUsers, $lastMonthNewUsers),
            'satisfactionRate' => $currentMonthSatisfaction,
            'satisfactionChange' => $currentMonthSatisfaction - $lastMonthSatisfaction,
        ];


        // 2. Data untuk Grafik Revenue (30 hari terakhir)
        $revenueDataRaw = Payment::where('status', 'paid')
            ->where('paid_at', '>=', $now->clone()->subDays(30))
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // [PERBAIKAN] Memproses data di controller, bukan di view
        $revenueLabels = $revenueDataRaw->map(function ($item) {
            return (new Carbon($item->date))->format('d M');
        });
        $revenueValues = $revenueDataRaw->pluck('revenue');


        // 3. Data untuk Booking Stats
        $bookingStats = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // 4. Data untuk Popular Services (Top 3)
        $popularServices = Service::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(3)
            ->get();

        // 5. Data untuk Recent Activity (5 aktivitas terakhir)
        $recentActivities = Booking::with('user', 'services')
            ->whereIn('status', ['completed', 'assigned', 'pending_verification'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();


        return view('admin.reports.index', compact(
            'summaryStats',
            'revenueLabels', // [BARU] Kirim label
            'revenueValues', // [BARU] Kirim nilai
            'bookingStats',
            'popularServices',
            'recentActivities'
        ));
    }
}
