@extends('layouts.user')

@section('title', 'User Dashboard - ServisAC')
@section('header-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-blue-700 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-calendar-check fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-blue-100">Total Booking</p>
                <p class="text-2xl font-bold">{{ $userStats['total_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-yellow-600 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-clock fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-yellow-100">Booking Pending</p>
                <p class="text-2xl font-bold">{{ $userStats['pending_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-green-700 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-green-100">Booking Selesai</p>
                <p class="text-2xl font-bold">{{ $userStats['completed_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center">
            <div class="bg-indigo-700 bg-opacity-50 p-3 rounded-full mr-4">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-indigo-100">Total Pengeluaran</p>
                <p class="text-2xl font-bold">Rp {{ number_format($userStats['total_spent'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 bg-white rounded-xl shadow p-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Aksi Cepat</h3>
        <div class="space-y-3">
            <a href="{{ route('user.bookings.create') }}"
                class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 font-medium shadow-md">
                <i class="fas fa-plus mr-2"></i>Booking Baru
            </a>
            <a href="{{ route('user.payments') }}"
                class="block w-full text-center px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                <i class="fas fa-wallet mr-2"></i>Pembayaran Saya
            </a>
            <a href="{{ route('user.profile') }}"
                class="block w-full text-center px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                <i class="fas fa-user-edit mr-2"></i>Ubah Profil
            </a>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Booking Terbaru</h3>
        <div class="space-y-4">
            @forelse($recent_bookings as $booking)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                <div>
                    <p class="font-medium text-gray-900">
                        @foreach($booking->services as $service)
                        {{ $service->name }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                    <p class="text-sm text-gray-500">{{ $booking->booking_date->format('d M Y, H:i') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                        @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status == 'completed') bg-green-100 text-green-800
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                        @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                        @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                    {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                </span>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada booking terbaru</p>
            </div>
            @endforelse
        </div>
        @if($recent_bookings->count() > 0)
        <div class="mt-6 text-center">
            <a href="{{ route('user.bookings') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                Lihat Semua Booking
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection