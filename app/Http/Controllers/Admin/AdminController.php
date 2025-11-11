<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

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
            'total_revenue' => Payment::where('status', 'paid')->sum('amount')
        ];

        $recent_bookings = Booking::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    /**
     * Display users management page with advanced features
     */
    public function users(Request $request)
    {
        $query = User::where('role', 'user')
            ->withCount([
                'bookings',
                'bookings as completed_bookings_count' => function ($q) {
                    $q->where('status', 'completed');
                },
                'bookings as pending_bookings_count' => function ($q) {
                    $q->where('status', 'pending');
                }
            ]);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'suspended':
                    $query->where('is_active', false);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', Carbon::now()->subDays(7));
                    break;
                case 'with_bookings':
                    $query->has('bookings');
                    break;
                case 'without_bookings':
                    $query->doesntHave('bookings');
                    break;
            }
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $users = $query->paginate(15)->withQueryString();

        // Statistics for filters
        // Statistics for filters
        $userStats = [
            'total' => User::where('role', 'user')->count(),
            'verified' => User::where('role', 'user')->whereNotNull('email_verified_at')->count(),
            'unverified' => User::where('role', 'user')->whereNull('email_verified_at')->count(),
            'with_bookings' => User::where('role', 'user')->has('bookings')->count(), // DIUBAH
            'without_bookings' => User::where('role', 'user')->doesntHave('bookings')->count(), // DIUBAH
            'recent' => User::where('role', 'user')->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];

        return view('admin.users.index', compact('users', 'userStats'));
    }

    /**
     * Show user creation form
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'username' => 'nullable|string|max:255|unique:users',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'password' => 'required|string|min:8|confirmed',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Di dalam storeUser method, update userData:
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'], // Tambahkan ini
                'email_verified_at' => $request->has('email_verified') ? now() : null,
                'is_active' => !$request->has('suspend_user')
            ];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }

            $user = User::create($userData);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display user details
     */
    public function showUser(User $user)
    {
        $user->load([
            'bookings' => function ($q) {
                $q->orderBy('created_at', 'desc')->limit(10);
            },
            'bookings.services'
        ]);

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
            'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
            'total_spent' => $user->bookings()->where('status', 'completed')->sum('total_price')
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show user edit form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user information
     */
    public function updateUser(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
            ];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }

            // Handle avatar removal
            if ($request->has('remove_avatar') && $user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $updateData['avatar'] = null;
            }

            $user->update($updateData);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'role' => 'required|in:user,technician,admin'
            ]);

            $user->update(['role' => $validated['role']]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User role updated successfully',
                    'user' => $user
                ]);
            }

            return redirect()->back()->with('success', 'User role updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user role: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating user role: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function destroyUser(Request $request, User $user)
    {
        try {
            // Check if user has bookings
            if ($user->bookings()->count() > 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete user with existing bookings. Please delete or reassign the bookings first.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Cannot delete user with existing bookings.');
            }

            // Delete avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Verify user email
     */
    public function verifyUser(Request $request, User $user)
    {
        try {
            $user->update(['email_verified_at' => now()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User email verified successfully'
                ]);
            }

            return redirect()->back()->with('success', 'User email verified successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error verifying user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error verifying user: ' . $e->getMessage());
        }
    }

    /**
     * Suspend user
     */
    public function suspendUser(Request $request, User $user)
    {
        try {
            $user->update(['is_active' => false]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User suspended successfully'
                ]);
            }

            return redirect()->back()->with('success', 'User suspended successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error suspending user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error suspending user: ' . $e->getMessage());
        }
    }

    /**
     * Activate user
     */
    public function activateUser(Request $request, User $user)
    {
        try {
            $user->update(['is_active' => true]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User activated successfully'
                ]);
            }

            return redirect()->back()->with('success', 'User activated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error activating user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error activating user: ' . $e->getMessage());
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user->update(['password' => Hash::make($validated['password'])]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset successfully'
                ]);
            }

            return redirect()->back()->with('success', 'Password reset successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error resetting password: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error resetting password: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions for users
     */
    public function bulkActions(Request $request)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|string|in:delete,verify,suspend,activate',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id'
            ]);

            $userIds = $validated['user_ids'];
            $action = $validated['action'];

            switch ($action) {
                case 'delete':
                    // Check if any user has bookings
                    $usersWithBookings = User::whereIn('id', $userIds)->has('bookings')->count();
                    if ($usersWithBookings > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot delete users with existing bookings.'
                        ], 400);
                    }
                    User::whereIn('id', $userIds)->delete();
                    break;

                case 'verify':
                    User::whereIn('id', $userIds)->update(['email_verified_at' => now()]);
                    break;

                case 'suspend':
                    User::whereIn('id', $userIds)->update(['is_active' => false]);
                    break;

                case 'activate':
                    User::whereIn('id', $userIds)->update(['is_active' => true]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export users to Excel
     */
    public function exportUsers(Request $request)
    {
        try {
            $format = $request->get('format', 'xlsx');

            return Excel::download(new UsersExport, 'users-' . date('Y-m-d') . '.' . $format);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting users: ' . $e->getMessage());
        }
    }

    // === BOOKINGS MANAGEMENT ===

    /**
     * Display bookings management page
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'technician', 'services'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $technicians = User::where('role', 'technician')->get();

        return view('admin.bookings.index', compact('bookings', 'technicians'));
    }

    /**
     * Display booking details
     */
    public function showBooking($id)
    {
        $booking = Booking::with(['user', 'technician', 'services'])->findOrFail($id);
        $technicians = User::where('role', 'technician')->get();

        return view('admin.bookings.show', compact('booking', 'technicians'));
    }

    /**
     * Update booking
     */
    public function updateBooking(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,assigned,in_progress,completed,cancelled',
                'admin_notes' => 'nullable|string|max:1000'
            ]);

            $booking->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign technician to booking
     */
    public function assignTechnician(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);

            $validated = $request->validate([
                'technician_id' => 'required|exists:users,id'
            ]);

            $booking->update([
                'technician_id' => $validated['technician_id'],
                'status' => 'assigned'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Technician assigned successfully',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning technician: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete booking
     */
    public function deleteBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display payments management page
     */
    public function payments()
    {
        $payments = Payment::with('serviceRequest.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Get payment data for editing
     */
    public function getPaymentData($id)
    {
        try {
            $payment = Payment::with('serviceRequest')->findOrFail($id);
            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Payment not found'
            ], 404);
        }
    }

    /**
     * Update payment
     */
    public function updatePayment(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:pending,paid,failed,refunded',
                'payment_method' => 'nullable|string|max:50',
                'transaction_id' => 'nullable|string|max:100'
            ]);

            $updateData = $validated;

            // Set paid_at timestamp if status changed to paid
            if ($validated['status'] === 'paid' && $payment->status !== 'paid') {
                $updateData['paid_at'] = now();
            }

            $payment->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process refund
     */
    public function refundPayment(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $validated = $request->validate([
                'refund_reason' => 'required|string|max:500',
                'refund_amount' => 'required|numeric|min:0|max:' . $payment->amount
            ]);

            $payment->update([
                'status' => 'refunded',
                'refund_reason' => $validated['refund_reason'],
                'refund_amount' => $validated['refund_amount'],
                'refunded_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage()
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
        $revenueData = Payment::where('status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $bookingStats = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.reports.index', compact('revenueData', 'bookingStats'));
    }

    /**
     * Display payment verification page
     */
    public function paymentVerification(Request $request)
    {
        $query = Payment::where('verification_status', 'pending')
            ->where('status', 'pending_verification')
            ->with(['serviceRequest.user', 'booking.user', 'booking.services']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('serviceRequest.user', function ($userQ) use ($search) {
                    $userQ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('booking.user', function ($userQ) use ($search) {
                    $userQ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'pendingCount' => Payment::where('verification_status', 'pending')->count(),
            'approvedTodayCount' => Payment::where('verification_status', 'approved')
                ->whereDate('updated_at', today())->count(),
            'rejectedTodayCount' => Payment::where('verification_status', 'rejected')
                ->whereDate('updated_at', today())->count()
        ];

        return view('admin.payments.verification', array_merge($stats, compact('payments')));
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $action = $request->input('action'); // approved or rejected

            $updateData = [
                'verification_status' => $action,
                'admin_notes' => $request->input('admin_notes')
            ];

            if ($action === 'approved') {
                $updateData['status'] = 'paid';
                $updateData['paid_at'] = now();

                // Update related booking status
                if ($payment->booking) {
                    $payment->booking->update(['status' => 'confirmed']);
                }
            } else {
                $updateData['status'] = 'failed';

                // Update related booking status to cancelled if payment rejected
                if ($payment->booking) {
                    $payment->booking->update(['status' => 'cancelled']);
                }
            }

            $payment->update($updateData);

            return response()->json([
                'success' => true,
                'message' => "Payment {$action} successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
