@extends('layouts.user')

@section('title', 'Detail Booking - ServisAC')
@section('header-title', 'Detail Booking')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('user.bookings') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Booking
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Booking #{{ $booking->id }}</h2>
                    <p class="text-gray-600">
                        Tanggal: <span class="font-medium text-gray-700">{{ $booking->booking_date->format('d F Y \p\u\k\u\l H:i') }}</span>
                    </p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold 
                        @if($booking->status == 'pending' || $booking->status == 'pending_verification') bg-yellow-100 text-yellow-800
                        @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                        @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                        @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                        @elseif($booking->status == 'completed') bg-green-100 text-green-800
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{-- Mengganti _ dengan spasi dan membesarkan huruf pertama --}}
                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-8">

            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-tools text-gray-400 mr-3"></i>
                    Detail Layanan
                </h3>
                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Layanan</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-600 uppercase tracking-wider">Jumlah</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-600 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($booking->services as $service)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $service->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-center">{{ $service->pivot->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">Rp {{ number_format($service->pivot->price * $service->pivot->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-base font-semibold text-gray-900">Total</td>
                                <td class="px-4 py-3 text-right text-xl font-bold text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-cog text-gray-400 mr-3"></i>
                        Teknisi
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        @if($booking->technician)
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-indigo-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $booking->technician->name }}</p>
                                <p class="text-sm text-gray-600">Akan segera menghubungi Anda</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-clock text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700">Belum Ditugaskan</p>
                                <p class="text-sm text-gray-500">Menunggu konfirmasi admin</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-3"></i>
                        Alamat Pengerjaan
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-800 font-medium leading-relaxed">{{ $booking->address }}</p>
                    </div>
                </div>
            </div>

            @if($booking->notes)
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-gray-400 mr-3"></i>
                    Catatan dari Anda
                </h3>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-wrap italic leading-relaxed">"{{ $booking->notes }}"</p>
                </div>
            </div>
            @endif

            @if($booking->payment)
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-credit-card text-gray-400 mr-3"></i>
                    Detail Pembayaran
                </h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 divide-y divide-gray-200">
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-gray-600">Tipe Pembayaran</span>
                        <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->payment->payment_type)) }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->payment->status)) }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-gray-600">Verifikasi Admin</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($booking->payment->verification_status) }}</span>
                    </div>
                    @if($booking->payment->payment_proof)
                    <div class="pt-4">
                        <a href="{{ Storage::url($booking->payment->payment_proof) }}" target="_blank"
                            class="w-full text-center block px-4 py-2.5 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>Lihat Bukti Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($booking->status == 'completed')
            <div class="border-t border-gray-200 pt-6" id="rating-form">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Beri Ulasan Layanan Ini</h3>

                @if($booking->rating)
                {{-- Tampilkan rating yang sudah ada --}}
                <div>
                    <p class="text-gray-700 mb-2 font-medium">Ulasan Anda:</p>
                    <div class="flex text-yellow-400 text-xl">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=$booking->rating->rating)
                            <i class="fas fa-star"></i>
                            @else
                            <i class="far fa-star"></i>
                            @endif
                            @endfor
                    </div>
                    @if($booking->rating->review)
                    <p class="text-gray-600 italic mt-3 p-4 bg-gray-100 rounded-md border border-gray-200">"{{ $booking->rating->review }}"</p>
                    @endif
                </div>

                @else
                {{-- Tampilkan form jika BELUM ada rating --}}
                <form action="{{ route('user.bookings.rate', $booking->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="text-gray-700 font-medium mb-2 block">Rating Anda (Bintang):</label>
                        <select name="rating" class="w-full md:w-1/2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="5">⭐⭐⭐⭐⭐ (Luar Biasa)</option>
                            <option value="4">⭐⭐⭐⭐ (Baik)</option>
                            <option value="3" selected>⭐⭐⭐ (Cukup)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-700 font-medium mb-2 block">Ulasan Anda (Opsional):</label>
                        <textarea name="review" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Bagikan pengalaman Anda..."></textarea>
                    </div>
                    <button type="submit" class="mt-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow font-medium transition-all duration-200">
                        Kirim Ulasan
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>

        <div class="border-t border-gray-200 pt-6 p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
            <a href="{{ route('user.bookings') }}"
                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-200 transition duration-150 w-full sm:w-auto text-center font-medium">
                Kembali ke Daftar
            </a>

            <div class="flex space-x-3 w-full sm:w-auto">
                {{-- Tombol bayar (hanya jika alur lama masih ada) --}}
                @if($booking->payment && $booking->payment->status == 'pending')
                <a href="{{ route('user.payments.show', $booking->payment->id) }}"
                    class="w-full text-center px-5 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-150 font-medium shadow">
                    Lakukan Pembayaran
                </a>
                @endif

                @if(in_array($booking->status, ['pending', 'confirmed']))
                <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full text-center px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150 font-medium shadow"
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                        Batalkan Booking
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection