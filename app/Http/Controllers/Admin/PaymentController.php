<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen payment.
     * (Sesuai dengan index.blade.php admin)
     */
    public function index(Request $request)
    {
        $query = Payment::with('booking.user')->orderBy('created_at', 'desc');

        // Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('method') && $request->method != '') {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->paginate(10);

        // Stats untuk index.blade.php
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
     * Menampilkan halaman verifikasi payment.
     * (Sesuai dengan verification.blade.php)
     */
    public function showVerificationPage()
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

        return view('admin.payments.verification', compact('payments', 'pendingCount', 'approvedTodayCount'));
    }

    /**
     * Memproses verifikasi (Approve / Reject) dari form modal.
     * (Dipanggil dari verification.blade.php)
     * Ini adalah method async (AJAX/Fetch)
     */
    public function verify(Request $request, $id)
    {
        // Validasi dari form modal
        $validator = Validator::make($request->all(), [
            'verification_action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        $payment = Payment::with('booking')->findOrFail($id);

        if ($payment->status !== 'pending_verification') {
            return response()->json(['success' => false, 'message' => 'Status pembayaran ini bukan "pending_verification".'], 422);
        }

        $action = $request->input('verification_action');
        $notes = $request->input('admin_notes');

        try {
            DB::beginTransaction();

            if ($action === 'approve') {

                $payment->update([
                    'status' => 'paid', // Status utama: LUNAS
                    'verification_status' => 'approved',
                    'admin_notes' => $notes,
                    'paid_at' => Carbon::now()
                ]);

                // Jika payment lunas, update status booking
                if ($payment->booking) {
                    $payment->booking->update(['status' => 'paid']); // Atau 'ready_to_assign'
                }
            } elseif ($action === 'reject') {

                // Simpan bukti lama sebelum dihapus (opsional)
                $oldProof = $payment->payment_proof;

                $payment->update([
                    'status' => 'pending', // Kembalikan status ke 'pending' agar user bisa upload ulang
                    'verification_status' => 'rejected',
                    'admin_notes' => $notes,
                    'payment_proof' => null // Hapus path bukti bayar dari DB
                ]);

                // Hapus file payment_proof dari storage
                if ($oldProof) {
                    Storage::disk('public')->delete($oldProof);
                }
            }

            DB::commit();

            // TODO: Kirim notifikasi ke user (Approved / Rejected)
            // ...

            return response()->json(['success' => true, 'message' => 'Payment verification ' . $action . ' success.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal verifikasi payment #' . $id . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
