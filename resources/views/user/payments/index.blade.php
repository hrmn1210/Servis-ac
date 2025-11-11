@extends('layouts.user')

@section('title', 'Pembayaran Saya - ServisAC')
@section('header-title', 'Pembayaran Saya')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-blue-700 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-blue-100">Total Pengeluaran</p>
                <p class="text-2xl font-bold">
                    Rp {{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-yellow-600 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-clock fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-yellow-100">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold">
                    {{ $payments->where('status', 'pending_verification')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-green-700 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-green-100">Pembayaran Lunas</p>
                <p class="text-2xl font-bold">
                    {{ $payments->where('status', 'paid')->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800">Riwayat Pembayaran</h3>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($payments as $payment)
        <div class="p-6 flex flex-col md:flex-row justify-between md:items-center hover:bg-gray-50 transition-colors">
            <div class="flex-1 mb-4 md:mb-0">
                <div class="flex items-center mb-2">
                    <span class="text-lg font-semibold text-gray-900 mr-3">
                        Booking #{{ $payment->booking_id }}
                    </span>

                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($payment->status == 'pending_verification') bg-yellow-100 text-yellow-800 border border-yellow-200
                            @elseif($payment->status == 'paid') bg-green-100 text-green-800 border border-green-200
                            @elseif($payment->status == 'cancelled' || $payment->status == 'failed') bg-red-100 text-red-800 border border-red-200
                            @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                        {{ str_replace('_', ' ', ucfirst($payment->status)) }}
                    </span>
                </div>
                <p class="text-sm text-gray-600">
                    @if($payment->booking)
                    {{ $payment->booking->services->pluck('name')->join(', ') }}
                    @else
                    Layanan tidak ditemukan
                    @endif
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $payment->created_at->format('d F Y, H:i') }}
                </p>
            </div>

            <div class="flex-shrink-0 md:mx-6 md:text-right">
                <p class="text-lg font-bold text-blue-600">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-600 capitalize">
                    {{ str_replace('_', ' ', $payment->payment_type) }}
                </p>
            </div>

            <div class="flex-shrink-0 mt-4 md:mt-0">
                <a href="{{ route('user.payments.show', $payment->id) }}"
                    class="w-full md:w-auto text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 font-medium shadow-sm">
                    Lihat Detail
                </a>

                {{-- [DIHAPUS] Tombol "Pay Now" dihapus karena alur pembayaran sudah pindah ke halaman "Create Booking" --}}
            </div>
        </div>
        @empty
        <div class="text-center p-12">
            <i class="fas fa-file-invoice-dollar text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800">Belum Ada Riwayat Pembayaran</h3>
            <p class="text-gray-500 mt-2">Semua riwayat transaksi Anda akan muncul di sini.</p>
        </div>
        @endforelse
    </div>

    @if($payments->hasPages())
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        {{ $payments->links() }}
    </div>
    @endif
</div>


@endsection