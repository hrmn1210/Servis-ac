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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                <div class="flex items-center mt-2 text-green-500">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    <span class="text-sm font-medium">+12% vs last month</span>
                </div>
            </div>
            <div class="bg-blue-50 p-4 rounded-xl">
                <i class="fas fa-users text-blue-500 text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
                <div class="flex items-center mt-2 text-green-500">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    <span class="text-sm font-medium">+8.5% vs last month</span>
                </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-xl">
                <i class="fas fa-calendar-alt text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <div class="flex items-center mt-2 text-green-500">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    <span class="text-sm font-medium">+15.2% vs last month</span>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-xl">
                <i class="fas fa-wallet text-green-500 text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <a href="{{ route('admin.payments.verification') }}" class="block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending Verification</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_verification'] }}</p>
                    <div class="flex items-center mt-2 text-yellow-600">
                        <i class="fas fa-exclamation-triangle text-xs mr-1"></i>
                        <span class="text-sm font-medium">Needs Action</span>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-xl">
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Services</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recent_bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $booking->services->pluck('name')->join(', ') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($booking->status == 'completed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-purple-600 hover:text-purple-900">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
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
                        <i class="fas fa-tools text-purple-500 text-2xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900 mt-3">Total Services</p>
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Service::count() }}</p> {{-- Query langsung, bisa dipindahkan ke controller --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection