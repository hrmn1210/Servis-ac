@extends('layouts.user')

@section('title', 'My Bookings - ServisAC')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">My Bookings</h2>
    <a href="{{ route('user.bookings.create') }}"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-150">
        <i class="fas fa-plus mr-2"></i>New Booking
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Services</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->booking_date->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @foreach($booking->services as $service)
                            <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                                {{ $service->name }} ({{ $service->pivot->quantity }})
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $booking->address }}</div>
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
                        <a href="{{ route('user.bookings.show', $booking->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                                Cancel
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No bookings found.
                        <a href="{{ route('user.bookings.create') }}" class="text-blue-500 hover:text-blue-700 ml-1">
                            Create your first booking
                        </a>
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