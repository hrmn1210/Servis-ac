@extends('admin.layout')

@section('title', 'Bookings Management - ServisAC')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Bookings Management</h2>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Services</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->user->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->booking_date->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @foreach($booking->services as $service)
                            <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-700 mr-1 mb-1">
                                {{ $service->name }} ({{ $service->pivot->quantity }})
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($booking->technician)
                        {{ $booking->technician->name }}
                        @else
                        <span class="text-gray-400">Not assigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                            @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                            @elseif($booking->status == 'completed') bg-green-100 text-green-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No bookings found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="bg-white px-6 py-3 border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection