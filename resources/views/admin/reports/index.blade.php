@extends('admin.layout')

@section('title', 'Reports & Analytics')
@section('header', 'Reports & Analytics')
@section('subheader', 'Analisis data dan laporan performa sistem')

@section('header-actions')
<div class="flex space-x-3">
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
        <i class="fas fa-download mr-2"></i>Export PDF
    </button>
    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
        <i class="fas fa-sync-alt mr-2"></i>Refresh Data
    </button>
</div>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Monthly Revenue</p>
                <p class="text-2xl font-bold mt-1">Rp 12.5M</p>
                <p class="text-blue-100 text-sm mt-2">+15% from last month</p>
            </div>
            <div class="bg-blue-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Completed Bookings</p>
                <p class="text-2xl font-bold mt-1">156</p>
                <p class="text-green-100 text-sm mt-2">+8% from last month</p>
            </div>
            <div class="bg-green-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">New Customers</p>
                <p class="text-2xl font-bold mt-1">42</p>
                <p class="text-purple-100 text-sm mt-2">+12% from last month</p>
            </div>
            <div class="bg-purple-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Satisfaction Rate</p>
                <p class="text-2xl font-bold mt-1">98%</p>
                <p class="text-orange-100 text-sm mt-2">+2% from last month</p>
            </div>
            <div class="bg-orange-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-star text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
            <select class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option selected>Last 90 days</option>
            </select>
        </div>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-chart-bar text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Revenue chart will be displayed here</p>
                @if($revenueData->count() > 0)
                <p class="text-sm text-gray-400 mt-2">Data available: {{ $revenueData->count() }} days</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Booking Statistics</h3>
        <div class="space-y-4">
            @foreach($bookingStats as $stat)
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $stat->status) }}</span>
                <div class="flex items-center space-x-3">
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($stat->count / max($bookingStats->sum('count'), 1)) * 100 }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 w-8 text-right">{{ $stat->count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Activity & Top Services -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="bg-green-100 p-2 rounded-full mt-1">
                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Booking completed</p>
                        <p class="text-xs text-gray-500">AC cleaning for Andi Pelanggan</p>
                        <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 p-2 rounded-full mt-1">
                        <i class="fas fa-user-plus text-blue-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">New user registered</p>
                        <p class="text-xs text-gray-500">Sari Pelanggan joined the system</p>
                        <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-purple-100 p-2 rounded-full mt-1">
                        <i class="fas fa-credit-card text-purple-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Payment received</p>
                        <p class="text-xs text-gray-500">Rp 150,000 from customer</p>
                        <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Services -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Popular Services</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-snowflake text-blue-500"></i>
                        </div>
                        <span class="font-medium text-gray-700">AC Cleaning</span>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">45 bookings</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fas fa-tools text-green-500"></i>
                        </div>
                        <span class="font-medium text-gray-700">AC Repair</span>
                    </div>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">32 bookings</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <i class="fas fa-gas-pump text-purple-500"></i>
                        </div>
                        <span class="font-medium text-gray-700">Freon Refill</span>
                    </div>
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">28 bookings</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection