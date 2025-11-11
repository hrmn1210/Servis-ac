@extends('layouts.user')

@section('title', 'Buat Booking Baru - ServisAC')
@section('header-title', 'Buat Booking Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Form sekarang memiliki 'multipart' untuk upload file --}}
    <form action="{{ route('user.bookings.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Langkah 1: Pilih Layanan</h2>
            <p class="text-gray-600 mb-6">Pilih satu atau lebih layanan yang Anda butuhkan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="services-container">
                @forelse($services as $service)
                {{-- Kartu Layanan --}}
                <div class_card="service-card border-2 border-gray-200 rounded-lg p-4 transition-all duration-200 ease-in-out cursor-pointer hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                id="service-{{ $service->id }}"
                                class="service-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1"
                                data-price="{{ $service->price }}">
                            <div class="ml-3">
                                <label for="service-{{ $service->id }}" class="text-base font-semibold text-gray-900 cursor-pointer">{{ $service->name }}</label>
                                <p class="text-sm text-gray-500 mt-1">{{ $service->description }}</p>
                                <p class="text-base font-bold text-blue-600 mt-2">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class_quantity_wrapper="mt-1">
                            <label for="quantity-{{ $service->id }}" class="text-xs font-medium text-gray-500">Jumlah</label>
                            <input type="number" name="quantity[{{ $service->id }}]" id="quantity-{{ $service->id }}" value="1" min="1"
                                class="quantity-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm text-center"
                                data-service="{{ $service->id }}" disabled>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 md:col-span-2 text-center">Belum ada layanan yang tersedia saat ini.</p>
                @endforelse
            </div>
            @error('services')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Langkah 2: Detail Booking</h2>
            <p class="text-gray-600 mb-6">Tentukan kapan dan di mana kami harus datang.</p>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Booking</label>
                    <input type="datetime-local" name="booking_date" id="booking_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('booking_date') }}">
                    @error('booking_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan alamat lengkap Anda...">{{ old('address', Auth::user()->address) }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="cth: AC tidak dingin, unit berisik, butuh 2 teknisi...">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="button" id="openBookingModalBtn"
                class="px-8 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg hover:bg-blue-700 transition duration-150 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Lanjutkan ke Pembayaran
            </button>
        </div>


        <div id="bookingModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-file-invoice-dollar text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900">Konfirmasi Pesanan Anda</h3>
                                <div class="mt-4 space-y-4">

                                    {{-- Total Tagihan --}}
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-base font-medium text-gray-700">Total Tagihan:</span>
                                        <span id="modalTotalPrice" class="text-2xl font-bold text-gray-900 block">Rp 0</span>
                                    </div>

                                    {{-- Tipe Pembayaran --}}
                                    <div>
                                        <label for="modalPaymentType" class="block text-sm font-medium text-gray-700">Tipe Pembayaran *</label>
                                        <select name="payment_type" id="modalPaymentType" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="full">Bayar Lunas</option>
                                            <option value="down_payment">Uang Muka (50%)</option>
                                            <option value="cod">Bayar di Tempat (COD)</option>
                                        </select>
                                    </div>

                                    {{-- Upload Bukti Pembayaran --}}
                                    <div id="paymentProofSection" class="hidden">
                                        <label for="paymentProofInput" class="block text-sm font-medium text-gray-700">Unggah Bukti Pembayaran *</label>
                                        <input type="file" name="payment_proof" id="paymentProofInput" class="w-full mt-1 text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-lg file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-blue-50 file:text-blue-700
                                            hover:file:bg-blue-100 cursor-pointer">
                                        <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk Bayar Lunas atau Uang Muka.</p>
                                        @error('payment_proof')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Konfirmasi & Pesan Sekarang
                        </button>
                        <button type="button" id="closeBookingModalBtn"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk menghitung total harga
    function calculateTotalPrice() {
        let total = 0;
        const checkboxes = document.querySelectorAll('.service-checkbox:checked');

        checkboxes.forEach(checkbox => {
            const serviceId = checkbox.value;
            const price = parseFloat(checkbox.dataset.price);
            const quantityInput = document.querySelector(`.quantity-input[data-service="${serviceId}"]`);
            const quantity = parseInt(quantityInput.value) || 1;
            total += price * quantity;
        });
        return total;
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.addEventListener('DOMContentLoaded', function() {
        // --- Ambil semua elemen ---
        const openModalBtn = document.getElementById('openBookingModalBtn');
        const closeModalBtn = document.getElementById('closeBookingModalBtn');
        const modal = document.getElementById('bookingModal');
        const modalTotalPriceEl = document.getElementById('modalTotalPrice');
        const modalPaymentTypeEl = document.getElementById('modalPaymentType');
        const paymentProofSectionEl = document.getElementById('paymentProofSection');
        const paymentProofInputEl = document.getElementById('paymentProofInput');

        const checkboxes = document.querySelectorAll('.service-checkbox');
        const quantityInputs = document.querySelectorAll('.quantity-input');

        // --- Logika untuk input quantity & style card ---
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const serviceId = this.value;
                const quantityInput = document.querySelector(`.quantity-input[data-service="${serviceId}"]`);
                const card = this.closest('._card'); // Ambil elemen kartu terdekat

                if (this.checked) {
                    quantityInput.disabled = false;
                    card.classList.add('border-blue-500', 'bg-blue-50', 'shadow-md');
                    card.classList.remove('border-gray-200');
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = 1;
                    card.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-md');
                    card.classList.add('border-gray-200');
                }
            });
        });

        // --- Logika untuk Tombol Buka Modal ---
        openModalBtn.addEventListener('click', function() {
            // 1. Cek dulu apakah ada layanan yang dipilih
            const selectedServicesCount = document.querySelectorAll('.service-checkbox:checked').length;
            if (selectedServicesCount === 0) {
                alert('Harap pilih minimal satu layanan sebelum melanjutkan.');
                return;
            }

            // 2. Hitung total harga
            const total = calculateTotalPrice();

            // 3. Tampilkan harga di modal
            modalTotalPriceEl.textContent = 'Rp ' + formatNumber(total);

            // 4. Tampilkan modal
            modal.classList.remove('hidden');

            // 5. Atur tampilan awal bagian bukti bayar
            togglePaymentProof();
        });

        // --- Logika untuk Tombol Tutup Modal ---
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // --- Logika untuk menampilkan/menyembunyikan Upload Bukti Bayar ---
        function togglePaymentProof() {
            const method = modalPaymentTypeEl.value;

            if (method === 'full' || method === 'down_payment') {
                paymentProofSectionEl.classList.remove('hidden');
                paymentProofInputEl.required = true; // Wajib diisi
            } else { // 'cod'
                paymentProofSectionEl.classList.add('hidden');
                paymentProofInputEl.required = false; // Tidak wajib
            }
        }

        // Tambahkan listener ke dropdown payment
        modalPaymentTypeEl.addEventListener('change', togglePaymentProof);

        // --- Logika untuk Tanggal Minimum ---
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const minDateTime = now.toISOString().slice(0, 16);
        document.getElementById('booking_date').min = minDateTime;

        // Atur nilai default jika belum diisi
        if (!document.getElementById('booking_date').value) {
            document.getElementById('booking_date').value = minDateTime;
        }
    });
</script>
@endpush