@extends('admin.layout')

@section('title', 'Reports & Analytics')
@section('header', 'Reports & Analytics')
@section('subheader', 'Analisis data dan laporan performa sistem')

@section('header-actions')
<div class="flex space-x-3">
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
        <i class="fas fa-download mr-2"></i>Export PDF
    </button>
    <a href="{{ route('admin.reports.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
        <i class="fas fa-sync-alt mr-2"></i>Refresh Data
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Monthly Revenue Card --}}
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Monthly Revenue</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($summaryStats['monthlyRevenue'], 0, ',', '.') }}</p>

                @if($summaryStats['revenuePercentageChange'] >= 0)
                <p class="text-blue-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +{{ number_format($summaryStats['revenuePercentageChange'], 1) }}% from last month
                </p>
                @else
                <p class="text-red-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>
                    {{ number_format($summaryStats['revenuePercentageChange'], 1) }}% from last month
                </p>
                @endif
            </div>
            <div class="bg-blue-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- Completed Bookings Card --}}
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Completed Bookings (This Month)</p>
                <p class="text-2xl font-bold mt-1">{{ $summaryStats['completedBookings'] }}</p>

                @if($summaryStats['bookingsPercentageChange'] >= 0)
                <p class="text-green-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +{{ number_format($summaryStats['bookingsPercentageChange'], 1) }}% from last month
                </p>
                @else
                <p class="text-red-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>
                    {{ number_format($summaryStats['bookingsPercentageChange'], 1) }}% from last month
                </p>
                @endif
            </div>
            <div class="bg-green-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- New Customers Card --}}
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">New Customers (This Month)</p>
                <p class="text-2xl font-bold mt-1">{{ $summaryStats['newCustomers'] }}</p>

                @if($summaryStats['usersPercentageChange'] >= 0)
                <p class="text-purple-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +{{ number_format($summaryStats['usersPercentageChange'], 1) }}% from last month
                </p>
                @else
                <p class="text-red-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>
                    {{ number_format($summaryStats['usersPercentageChange'], 1) }}% from last month
                </p>
                @endif
            </div>
            <div class="bg-purple-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- Satisfaction Rate Card --}}
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Satisfaction Rate (This Month)</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($summaryStats['satisfactionRate'], 1) }}%</p>

                @if($summaryStats['satisfactionChange'] >= 0)
                <p class="text-orange-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +{{ number_format($summaryStats['satisfactionChange'], 1) }} pts from last month
                </p>
                @else
                <p class="text-red-100 text-sm mt-2 flex items-center">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>
                    {{ number_format($summaryStats['satisfactionChange'], 1) }} pts from last month
                </p>
                @endif
            </div>
            <div class="bg-orange-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-star text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Revenue Overview (Last 30 Days)</h3>
        </div>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Booking Statistics (All Time)</h3>
        <div class="space-y-4">
            @php
            $totalBookings = max($bookingStats->sum('count'), 1); // Hindari division by zero
            @endphp
            @forelse($bookingStats as $stat)
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $stat->status) }}</span>
                <div class="flex items-center space-x-3">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($stat->count / $totalBookings) * 100 }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 w-8 text-right">{{ $stat->count }}</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500">No booking statistics found.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- Recent Activity --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentActivities as $booking)
                <div class="flex items-start space-x-3">
                    <div class="p-2 rounded-full mt-1 
                        @if($booking->status == 'completed') bg-green-100
                        @elseif($booking->status == 'assigned') bg-blue-100
                        @elseif($booking->status == 'pending_verification') bg-yellow-100
                        @else bg-gray-100 @endif">

                        @if($booking->status == 'completed') <i class="fas fa-check-circle text-green-500 text-sm"></i>
                        @elseif($booking->status == 'assigned') <i class="fas fa-user-check text-blue-500 text-sm"></i>
                        @elseif($booking->status == 'pending_verification') <i class="fas fa-clock text-yellow-500 text-sm"></i>
                        @else <i class="fas fa-info-circle text-gray-500 text-sm"></i> @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            Booking #{{ $booking->id }} {{ $booking->status }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $booking->services->pluck('name')->join(', ') }}
                            for <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $booking->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No recent activity found.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Popular Services --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Popular Services</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($popularServices as $service)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-tools text-blue-500"></i>
                        </div>
                        <span class="font-medium text-gray-700">{{ $service->name }}</span>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $service->bookings_count }} bookings
                    </span>
                </div>
                @empty
                <p class="text-gray-500">No services found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Tambahkan Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Revenue Chart ---
        const ctx = document.getElementById('revenueChart');
        if (ctx) {



            const revenueLabels = @json($revenueLabels);
            const revenueData = @json($revenueValues);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData,
                        borderColor: '#4F46E5', // ungu
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format Sumbu Y (Rupiah)
                                callback: function(value, index, values) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + 'jt';
                                    }
                                    if (value >= 1000) {
                                        return 'Rp ' + (value / 1000) + 'k';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endpush