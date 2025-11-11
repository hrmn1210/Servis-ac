<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User; // Untuk assign teknisi
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar semua booking.
     */
    public function index(Request $request)
    {
        $query = Booking::with('user', 'technician', 'services', 'payment')
            ->orderBy('created_at', 'desc');

        // Filter sederhana
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

        return view('admin.bookings.index', compact('bookings')); // Anda perlu view: admin.bookings.index
    }

    /**
     * Menampilkan detail satu booking.
     */
    public function show($id)
    {
        $booking = Booking::with('user', 'technician', 'services', 'payment')->findOrFail($id);
        $technicians = User::isTechnician()->get(); // Ambil daftar teknisi

        return view('admin.bookings.show', compact('booking', 'technicians')); // Anda perlu view: admin.bookings.show
    }

    /**
     * [KODE PENTING] Menyetujui booking yang masih pending.
     * Ini akan mengubah status payment menjadi 'pending' agar user bisa bayar.
     */
    public function approveBooking(Request $request, $id)
    {
        $booking = Booking::with('payment')->findOrFail($id);

        // 1. Validasi: Pastikan booking statusnya 'pending'
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking ini tidak bisa disetujui (Status: ' . $booking->status . ').');
        }

        // 2. Validasi: Pastikan ada payment terkait
        if (!$booking->payment) {
            return redirect()->back()->with('error', 'Booking ini tidak memiliki data pembayaran.');
        }

        // 3. Validasi: Pastikan status payment 'awaiting_confirmation'
        if ($booking->payment->status !== 'awaiting_confirmation') {
            return redirect()->back()->with('error', 'Status pembayaran booking ini sudah diproses (Status: ' . $booking->payment->status . ').');
        }

        try {
            DB::beginTransaction();

            // 1. Ubah status Booking
            $booking->update([
                'status' => 'confirmed', // Booking dikonfirmasi
            ]);

            // 2. Ubah status Payment
            // Ini akan memicu tombol "Pay Now" di sisi user
            $booking->payment->update([
                'status' => 'pending', // Payment siap untuk dibayar
            ]);

            DB::commit();

            // TODO: Kirim notifikasi ke user bahwa bookingnya diterima
            // Mail::to($booking->user->email)->send(new BookingConfirmed($booking));

            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking berhasil disetujui. User sekarang bisa melakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal approve booking #' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menolak booking yang masih pending.
     */
    public function rejectBooking(Request $request, $id)
    {
        $request->validate(['reject_reason' => 'required|string|max:500']);

        $booking = Booking::with('payment')->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking ini tidak dalam status pending.');
        }

        try {
            DB::beginTransaction();

            $booking->update([
                'status' => 'cancelled', // Atau 'rejected'
                'notes' => ($booking->notes ? $booking->notes . "\n\n" : '') . "Admin Note (Rejected): " . $request->reject_reason,
            ]);

            // Jika ada payment, ubah statusnya juga
            if ($booking->payment) {
                $booking->payment->update([
                    'status' => 'cancelled', // Atau 'failed'
                ]);
            }

            DB::commit();

            // TODO: Kirim notifikasi ke user bahwa bookingnya ditolak
            // Mail::to($booking->user->email)->send(new BookingRejected($booking, $request->reject_reason));

            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal reject booking #' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
