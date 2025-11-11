@extends('layouts.user')

@section('title', 'User Dashboard - ServisAC')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-calendar-check text-blue-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Bookings</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pending Bookings</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Completed</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['completed_bookings'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-full mr-4">
                <i class="fas fa-dollar-sign text-purple-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Spent</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('user.bookings.create') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition duration-150">
                <i class="fas fa-calendar-plus text-green-500 mr-3 text-lg"></i>
                <div>
                    <p class="font-medium text-gray-900">New Booking</p>
                    <p class="text-sm text-gray-500">Book AC service</p>
                </div>
            </a>
            <a href="{{ route('user.bookings') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                <i class="fas fa-history text-blue-500 mr-3 text-lg"></i>
                <div>
                    <p class="font-medium text-gray-900">View Bookings</p>
                    <p class="text-sm text-gray-500">See all your bookings</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Recent Bookings</h3>
        <div class="space-y-3">
            @forelse($recent_bookings as $booking)
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">
                        @foreach($booking->services as $service)
                        {{ $service->name }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                    <p class="text-sm text-gray-500">{{ $booking->booking_date->format('M d, Y H:i') }}</p>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium 
                        @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status == 'completed') bg-green-100 text-green-800
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No recent bookings</p>
            @endforelse
        </div>
        @if($recent_bookings->count() > 0)
        <div class="mt-4 text-center">
            <a href="{{ route('user.bookings') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">View All Bookings</a>
        </div>
        @endif
    </div>
</div>
@endsection