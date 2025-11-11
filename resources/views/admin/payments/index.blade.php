@extends('admin.layout')

@section('title', 'Manajemen Pembayaran')
@section('header', 'Manajemen Pembayaran')
@section('subheader', 'Kelola semua transaksi pembayaran')

@section('header-actions')
<div class="flex space-x-3">
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
        <i class="fas fa-download mr-2"></i>Export
    </button>
</div>
@endsection

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Pemasukan</p>
                <p class="text-3xl font-bold mt-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold mt-1">{{ $pendingVerification ?? 0 }}</p>
            </div>
            <div class="bg-yellow-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Pembayaran</g>
                <p class="text-3xl font-bold mt-1">{{ $totalPayments ?? 0 }}</p>
            </div>
            <div class="bg-blue-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-file-invoice-dollar text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Total Refund</p>
                <p class="text-3xl font-bold mt-1">Rp {{ number_format($refundedAmount ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-undo text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
    </div>

    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <form action="{{ route('admin.payments.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" placeholder="Cari berdasarkan nama user..." value="{{ request('search') }}"
                    class="flex-grow border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Pending Verifikasi</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refund</option>
                </select>
                <button type="submit" class="bg-purple-600 text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-purple-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($payments as $payment)
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden flex flex-col">

            <div class="p-5 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $payment->booking->user->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Booking #{{ $payment->booking_id }}
                        </p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($payment->status == 'paid') bg-green-100 text-green-800
                            @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status == 'pending_verification') bg-blue-100 text-blue-800
                            @elseif($payment->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                    </span>
                </div>
            </div>

            <div class="p-5 flex-grow space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Jumlah</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Metode</p>
                        <p class="text-sm font-medium text-gray-800 uppercase">
                            {{ $payment->payment_method ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt fa-fw mr-1"></i>
                    Tanggal: {{ $payment->created_at->format('d M Y, H:i') }}
                </div>
            </div>

            <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-end space-x-3">
                @if($payment->status == 'pending_verification')
                <a href="{{ route('admin.payments.verification') }}"
                    class="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors">
                    <i class="fas fa-check mr-2"></i>Verifikasi Sekarang
                </a>
                @else
                <button onclick="showRefundModal({{ $payment->id }})"
                    class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    Refund
                </button>
                <button onclick="showPaymentModal({{ $payment->id }}, '{{ $payment->status }}')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                    Kelola Status
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="lg:col-span-2 text-center p-12">
            <i class="fas fa-file-invoice-dollar text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800">Tidak Ada Pembayaran</h3>
            <p class="text-gray-500 mt-2">Tidak ada pembayaran yang cocok dengan filter Anda.</p>
        </div>
        @endforelse
    </div>

    @if($payments->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
    @endif
</div>


<div id="updatePaymentModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="updatePaymentForm">
                @csrf
                @method('POST')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Kelola Status Pembayaran</h3>
                    <input type="hidden" id="updatePaymentId">
                    <div class="mt-4">
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                        <select id="payment_status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                            <option value="pending">Pending</option>
                            <option value="paid">Lunas (Paid)</option>
                            <option value="pending_verification">Pending Verifikasi</option>
                            <option value="cancelled">Dibatalkan (Cancelled)</option>
                            <option value="refunded">Dikembalikan (Refunded)</option>
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan Perubahan</button>
                    <button type="button" onclick="closePaymentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="refundModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="refundForm">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Proses Refund</h3>
                    <input type="hidden" id="refundPaymentId">
                    <div class="mt-4">
                        <label for="refund_amount" class="block text-sm font-medium text-gray-700">Jumlah Refund</label>
                        <input type="number" name="refund_amount" id="refund_amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Masukkan jumlah">
                    </div>
                    <div class="mt-4">
                        <label for="refund_reason" class="block text-sm font-medium text-gray-700">Alasan</label>
                        <textarea name="reason" id="refund_reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Alasan refund..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Proses Refund</button>
                    <button type="button" onclick="closeRefundModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- JavaScript Anda sudah bagus, saya hanya menerjemahkan teks notifikasi/alert --}}
<script>
    // Modal Functions
    function showPaymentModal(id, status) {
        document.getElementById('updatePaymentId').value = id;
        document.getElementById('payment_status').value = status;
        document.getElementById('updatePaymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('updatePaymentModal').classList.add('hidden');
    }

    function showRefundModal(id) {
        document.getElementById('refundPaymentId').value = id;
        document.getElementById('refundModal').classList.remove('hidden');
    }

    function closeRefundModal() {
        document.getElementById('refundModal').classList.add('hidden');
    }

    // Form Submissions
    document.getElementById('updatePaymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        // Implementasikan fetch logic untuk update status (jika diperlukan)
        // Saat ini, rute untuk ini belum ada di web.php Anda.
        showNotification('Fungsi update status manual belum diimplementasikan.', 'info');
    });

    document.getElementById('refundForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Memproses...';

        try {
            const formData = new FormData(this);
            const paymentId = document.getElementById('refundPaymentId').value;

            // PASTIKAN Anda memiliki Rute 'admin.payments.refund' di web.php
            // Route::post('/payments/{id}/refund', [AdminController::class, 'refundPayment'])->name('payments.refund');

            // Perlu dicek apakah Anda sudah membuat fungsi 'refundPayment' di AdminController
            const response = await fetch(`/admin/payments/${paymentId}/refund`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                closeRefundModal();
                showNotification('Refund berhasil diproses', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error processing refund:', error);
            showNotification(error.message || 'Gagal memproses refund', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Proses Refund';
        }
    });

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'updatePaymentModal') closePaymentModal();
        if (e.target.id === 'refundModal') closeRefundModal();
    });

    // Notification function (placeholder)
    function showNotification(message, type = 'info') {
        // Implementasikan sistem notifikasi Anda di sini
        alert(`${type.toUpperCase()}: ${message}`);
    }
</script>
@endpush