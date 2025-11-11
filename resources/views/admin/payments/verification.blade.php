@extends('admin.layout')

@section('title', 'Verifikasi Pembayaran')
@section('header', 'Verifikasi Pembayaran')
@section('subheader', 'Verifikasi bukti pembayaran dari pelanggan')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold mt-1">{{ $pendingCount }}</p>
            </div>
            <div class="bg-yellow-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Disetujui Hari Ini</p>
                <p class="text-3xl font-bold mt-1">{{ $approvedTodayCount }}</p>
            </div>
            <div class="bg-green-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Antrian</p>
                <p class="text-3xl font-bold mt-1">{{ $payments->total() }}</p> {{-- Total di halaman ini --}}
            </div>
            <div class="bg-blue-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-list-ol text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<h3 class="text-xl font-semibold text-gray-900 mb-4">Antrian Verifikasi</h3>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @forelse($payments as $payment)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden flex flex-col">

        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-base font-semibold text-gray-900">
                        Booking #{{ $payment->booking_id }}
                    </p>
                    <p class="text-sm text-gray-600">
                        {{ $payment->booking->user->name ?? 'N/A' }}
                    </p>
                </div>
                <p class="text-xs text-gray-500">{{ $payment->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="p-5 flex-grow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Jumlah Pembayaran</p>
                    <p class="text-2xl font-bold text-blue-600">
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

            @if($payment->payment_proof)
            <button onclick="showPaymentProof('{{ Storage::url($payment->payment_proof) }}')"
                class="mt-4 w-full text-center px-4 py-2.5 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 text-sm font-medium transition-colors duration-200">
                <i class="fas fa-eye mr-2"></i>Lihat Bukti Pembayaran
            </button>
            @else
            <div class="mt-4 w-full text-center px-4 py-2.5 border border-gray-300 text-gray-400 rounded-lg text-sm font-medium">
                <i class="fas fa-times-circle mr-2"></i>Bukti Bayar Tidak Ada
            </div>
            @endif
        </div>

        <div class="bg-gray-50 p-4 border-t border-gray-200 grid grid-cols-2 gap-3">
            <button onclick="showVerificationModal({{ $payment->id }}, 'reject')"
                class="w-full px-4 py-2 bg-white border border-gray-300 text-red-600 rounded-lg hover:bg-red-50 font-medium transition-all duration-200">
                <i class="fas fa-times mr-2"></i>Tolak
            </button>
            <button onclick="showVerificationModal({{ $payment->id }}, 'approve')"
                class="w-full px-4 py-2 bg-green-600 border border-transparent text-white rounded-lg hover:bg-green-700 font-medium shadow-sm transition-all duration-200">
                <i class="fas fa-check mr-2"></i>Setujui
            </button>
        </div>
    </div>
    @empty
    <div class="lg:col-span-2 text-center bg-white rounded-xl shadow p-12 border border-gray-100">
        <i class="fas fa-check-double text-green-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800">Semua Sudah Terverifikasi</h3>
        <p class="text-gray-500 mt-2">Tidak ada antrian pembayaran yang menunggu verifikasi saat ini.</p>
    </div>
    @endforelse
</div>

@if($payments->hasPages())
<div class="mt-8">
    {{ $payments->links() }}
</div>
@endif


<div id="paymentProofModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Bukti Pembayaran</h3>
                <div class="mt-4">
                    <img id="paymentProofImage" src="" alt="Bukti Pembayaran" class="w-full h-auto rounded-md">
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closePaymentProofModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="verificationModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <form id="verificationForm">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="verificationModalTitle">Konfirmasi Verifikasi</h3>
                    <input type="hidden" id="verifyPaymentId">
                    <input type="hidden" id="verification_action" name="verification_action">

                    <div class="mt-4">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin (Opsional)</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Alasan penolakan..."></textarea>
                    </div>

                    <div class="mt-4 hidden" id="technicianAssignSection">
                        <label for="technician_id" class="block text-sm font-medium text-gray-700">Tugaskan Teknisi *</label>
                        <select name="technician_id" id="technician_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                            <option value="">-- Pilih Teknisi --</option>
                            @isset($technicians)
                            @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                            @endisset
                        </select>
                    </div>

                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="confirmButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm">Konfirmasi</button>
                    <button type="button" onclick="closeVerificationModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- JavaScript Anda (modal) sudah benar, tidak perlu diubah, hanya menerjemahkan teks --}}
<script>
    // Payment Proof Modal
    function showPaymentProof(imageUrl) {
        document.getElementById('paymentProofImage').src = imageUrl;
        document.getElementById('paymentProofModal').classList.remove('hidden');
    }

    function closePaymentProofModal() {
        document.getElementById('paymentProofModal').classList.add('hidden');
    }

    // Verification Modal (Approve/Reject)
    function showVerificationModal(id, action) {
        document.getElementById('verifyPaymentId').value = id;
        document.getElementById('verification_action').value = action;

        const modalTitle = document.getElementById('verificationModalTitle');
        const confirmButton = document.getElementById('confirmButton');
        const adminNotes = document.getElementById('admin_notes');
        const techSection = document.getElementById('technicianAssignSection');
        const techSelect = document.getElementById('technician_id');

        if (action === 'approve') {
            modalTitle.innerText = 'Setujui Pembayaran?';
            confirmButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm';
            adminNotes.placeholder = 'Catatan opsional...';

            techSection.classList.remove('hidden');
            techSelect.required = true;

        } else { // action === 'reject'
            modalTitle.innerText = 'Tolak Pembayaran?';
            confirmButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm';
            adminNotes.placeholder = 'Alasan penolakan...';

            techSection.classList.add('hidden');
            techSelect.required = false;
            techSelect.value = ''; // Reset pilihan
        }

        document.getElementById('verificationModal').classList.remove('hidden');
    }

    function closeVerificationModal() {
        document.getElementById('verificationModal').classList.add('hidden');
    }

    // Form Submission (AJAX)
    document.getElementById('verificationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        try {
            const formData = new FormData(this);
            const paymentId = document.getElementById('verifyPaymentId').value;

            const response = await fetch(`/admin/payments/${paymentId}/verify`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                closeVerificationModal();
                alert('Verifikasi pembayaran berhasil'); // Ganti dengan notifikasi yang lebih baik
                window.location.reload();
            } else {
                if (response.status === 422) {
                    let errorMsg = result.message || 'Kesalahan validasi.';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors)[0][0];
                    }
                    throw new Error(errorMsg);
                }
                throw new Error(result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal memverifikasi: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Konfirmasi';
        }
    });

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        const paymentModal = document.getElementById('paymentProofModal');
        const verificationModal = document.getElementById('verificationModal');

        // Cek apakah target adalah background modal
        if (e.target === paymentModal) {
            closePaymentProofModal();
        }
        if (e.target === verificationModal) {
            closeVerificationModal();
        }
    });
</script>
@endpush