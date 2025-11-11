@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Overview')
@section('subheader', 'Selamat datang di panel administrasi ServisAC')

@section('header-actions')
<button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
    <i class="fas fa-download mr-2"></i>Export
</button>
<button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
    <i class="fas fa-plus mr-2"></i>New Report
</button>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>12%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
            <div class="bg-blue-50 p-4 rounded-xl">
                <i class="fas fa-users text-blue-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Bookings Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>8%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-xl">
                <i class="fas fa-calendar-check text-green-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Bookings Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_bookings'] }}</p>
                <div class="flex items-center mt-2">
                    <span class="text-orange-500 text-sm font-medium">
                        <i class="fas fa-clock mr-1"></i>Need attention
                    </span>
                </div>
            </div>
            <div class="bg-orange-50 p-4 rounded-xl">
                <i class="fas fa-clock text-orange-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>23%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-xl">
                <i class="fas fa-money-bill-wave text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                <a href="{{ route('admin.bookings') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    View all
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recent_bookings as $booking)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition duration-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                            <p class="text-sm text-gray-500">
                                @foreach($booking->services as $service)
                                {{ $service->name }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $booking->status == 'assigned' ? 'bg-indigo-100 text-indigo-800' : '' }}
                            {{ $booking->status == 'in_progress' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $booking->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $booking->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.bookings') }}" class="flex flex-col items-center justify-center p-6 bg-blue-50 rounded-xl hover:bg-blue-100 transition duration-200 group">
                    <div class="bg-blue-100 p-3 rounded-lg group-hover:bg-blue-200 transition duration-200">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900 mt-3">Manage Bookings</p>
                    <p class="text-sm text-gray-500 text-center mt-1">View and manage service bookings</p>
                </a>

                <a href="{{ route('admin.users') }}" class="flex flex-col items-center justify-center p-6 bg-green-50 rounded-xl hover:bg-green-100 transition duration-200 group">
                    <div class="bg-green-100 p-3 rounded-lg group-hover:bg-green-200 transition duration-200">
                        <i class="fas fa-users text-green-500 text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900 mt-3">User Management</p>
                    <p class="text-sm text-gray-500 text-center mt-1">Manage system users</p>
                </a>

                <a href="{{ route('admin.payments') }}" class="flex flex-col items-center justify-center p-6 bg-purple-50 rounded-xl hover:bg-purple-100 transition duration-200 group">
                    <div class="bg-purple-100 p-3 rounded-lg group-hover:bg-purple-200 transition duration-200">
                        <i class="fas fa-credit-card text-purple-500 text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900 mt-3">Payments</p>
                    <p class="text-sm text-gray-500 text-center mt-1">View payment history</p>
                </a>

                <a href="{{ route('admin.reports') }}" class="flex flex-col items-center justify-center p-6 bg-orange-50 rounded-xl hover:bg-orange-100 transition duration-200 group">
                    <div class="bg-orange-100 p-3 rounded-lg group-hover:bg-orange-200 transition duration-200">
                        <i class="fas fa-chart-bar text-orange-500 text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900 mt-3">Reports</p>
                    <p class="text-sm text-gray-500 text-center mt-1">View analytics & reports</p>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Activity Overview -->
<div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">System Overview</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="bg-blue-50 p-4 rounded-xl inline-block">
                    <i class="fas fa-user-check text-blue-500 text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-900 mt-3">Active Users</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
            </div>

            <div class="text-center">
                <div class="bg-green-50 p-4 rounded-xl inline-block">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-900 mt-3">Completed Jobs</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['completed_bookings'] }}</p>
            </div>

            <div class="text-center">
                <div class="bg-purple-50 p-4 rounded-xl inline-block">
                    <i class="fas fa-star text-purple-500 text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-900 mt-3">Satisfaction Rate</p>
                <p class="text-2xl font-bold text-purple-600">98%</p>
            </div>
        </div>
    </div>
</div>
@endsection