@extends('layouts.user')

@section('title', 'Booking Saya - ServisAC')
@section('header-title', 'Booking Saya')

@section('content')

{{-- Header Halaman --}}
<div class="mb-6 flex flex-col md:flex-row justify-between md:items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Booking Saya</h2>
        <p class="text-gray-600">Lihat semua status booking Anda di sini.</p>
    </div>
    <a href="{{ route('user.bookings.create') }}"
        class="mt-4 md:mt-0 w-full md:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition duration-150 shadow-md hover:shadow-lg">
        <i class="fas fa-plus mr-2"></i>Buat Booking Baru
    </a>
</div>

{{-- Filter (Opsional tapi bagus) --}}
<div class="mb-6 bg-white rounded-xl shadow p-4">
    <div class="flex flex-wrap items-center gap-2 md:gap-4">
        <span class="text-sm font-medium text-gray-600">Filter Status:</span>
        <a href="{{ route('user.bookings') }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('user.bookings', ['status' => 'pending']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Pending
        </a>
        <a href="{{ route('user.bookings', ['status' => 'assigned']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'assigned' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Ditugaskan
        </a>
        <a href="{{ route('user.bookings', ['status' => 'completed']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Selesai
        </a>
        <a href="{{ route('user.bookings', ['status' => 'cancelled']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Dibatalkan
        </a>
    </div>
</div>

{{-- [PERBAIKAN] Daftar Booking diubah menjadi Grid 3 Kolom --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    @forelse($bookings as $booking)
    {{-- Kartu Booking --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl flex flex-col">

        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center mb-2">
                <p class="text-sm text-gray-600">Booking <span class="font-bold text-gray-800">#{{ $booking->id }}</span></p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($booking->status == 'pending' || $booking->status == 'pending_verification') bg-yellow-100 text-yellow-800 border border-yellow-200
                        @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800 border border-blue-200
                        @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800 border border-indigo-200
                        @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800 border border-purple-200
                        @elseif($booking->status == 'completed') bg-green-100 text-green-800 border border-green-200
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800 border border-red-200
                        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                    {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                </span>
            </div>
            <p class="text-sm text-gray-500"><i class="fas fa-calendar-alt fa-fw mr-1"></i> {{ $booking->booking_date->format('d F Y, H:i') }}</p>
        </div>

        <div class="p-5 flex-grow">
            <h4 class="text-base font-semibold text-gray-900 mb-2">Layanan:</h4>
            <ul class="list-disc list-inside space-y-1 text-sm text-gray-700 mb-4">
                @foreach($booking->services as $service)
                <li>{{ $service->name }} ({{ $service->pivot->quantity }}x)</li>
                @endforeach
            </ul>

            <div class="space-y-3">
                <div>
                    <p class="text-xs font-medium text-gray-500">Teknisi</p>
                    <p class="text-sm font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-cog fa-fw mr-2 text-gray-400"></i>
                        {{ $booking->technician->name ?? 'Belum Ditugaskan' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Total Harga</p>
                    <p class="text-lg font-bold text-blue-600">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center space-x-2">

            {{-- Tombol Batal --}}
            @if(in_array($booking->status, ['pending', 'confirmed']))
            <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100"
                    onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                    Batalkan
                </button>
            </form>
            @endif

            {{-- Tombol Beri Rating (Prioritas) --}}
            @if($booking->status == 'completed' && !$booking->rating)
            <a href="{{ route('user.bookings.show', $booking->id) }}#rating-form"
                class="px-3 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm">
                <i class="fas fa-star mr-1"></i> Beri Rating
            </a>
            @endif

            {{-- Tombol Lihat Detail (Default) --}}
            <a href="{{ route('user.bookings.show', $booking->id) }}"
                class="px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm">
                Lihat Detail
            </a>
        </div>
    </div>
    @empty
    {{-- Tampilan jika tidak ada booking --}}
    <div class="lg:col-span-3 text-center bg-white rounded-xl shadow p-12">
        <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800">Belum Ada Booking</h3>
        <p class="text-gray-500 mt-2 mb-6">Anda belum pernah melakukan booking. Mari mulai pesan layanan pertama Anda!</p>
        <a href="{{ route('user.bookings.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-150 font-semibold shadow-md hover:shadow-lg">
            <i class="fas fa-plus mr-2"></i>Buat Booking Pertama Anda
        </a>
    </div>
    @endforelse
</div>

@if($bookings->hasPages())
<div class="mt-8">
    {{ $bookings->links() }}
</div>
@endif

@endsection