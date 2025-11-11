@extends('layouts.user')

@section('title', 'Booking Details - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Booking Details</h2>
                <p class="text-gray-600">Booking #{{ $booking->id }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                @elseif($booking->status == 'completed') bg-green-100 text-green-800
                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ str_replace('_', ' ', ucfirst($booking->status)) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Booking Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Booking Date & Time</label>
                        <p class="mt-1 text-gray-900">{{ $booking->booking_date->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created Date</label>
                        <p class="mt-1 text-gray-900">{{ $booking->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Address</label>
                        <p class="mt-1 text-gray-900">{{ $booking->address }}</p>
                    </div>
                    @if($booking->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Additional Notes</label>
                        <p class="mt-1 text-gray-900">{{ $booking->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Services</h3>
                <div class="space-y-3">
                    @foreach($booking->services as $service)
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $service->name }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ $service->pivot->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp {{ number_format($service->pivot->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">each</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="border-t pt-3">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span class="text-gray-800">Total Price:</span>
                            <span class="text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        @if($booking->payment)
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Information</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Amount</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Status</label>
                        <span class="mt-1 inline-flex px-3 py-1 rounded-full text-sm font-medium 
                            @if($booking->payment->status == 'paid') bg-green-100 text-green-800
                            @elseif($booking->payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->payment->status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($booking->payment->status) }}
                        </span>
                    </div>
                    @if($booking->payment->payment_method)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Method</label>
                        <p class="mt-1 text-gray-900 capitalize">{{ $booking->payment->payment_method }}</p>
                    </div>
                    @endif
                    @if($booking->payment->paid_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Paid At</label>
                        <p class="mt-1 text-gray-900">{{ $booking->payment->paid_at->format('F d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                @if($booking->payment->status == 'pending')
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Please complete your payment to confirm this booking</p>
                        </div>
                        <a href="{{ route('user.payments.show', $booking->payment->id) }}"
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-150">
                            Pay Now
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- Jika belum ada payment record -->
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Information</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                    <div>
                        <p class="text-yellow-800 font-medium">Payment record not found</p>
                        <p class="text-yellow-700 text-sm mt-1">Please contact administrator for payment information.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Technician Information -->
        @if($booking->technician)
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Assigned Technician</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-user-cog text-blue-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $booking->technician->name }}</p>
                        <p class="text-sm text-gray-600">Professional AC Technician</p>
                        @if($booking->technician->phone_number)
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-1"></i>{{ $booking->technician->phone_number }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="border-t pt-6 flex justify-between">
            <a href="{{ route('user.bookings') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                Back to List
            </a>

            <div class="flex space-x-3">
                @if($booking->payment && $booking->payment->status == 'pending')
                <a href="{{ route('user.payments.show', $booking->payment->id) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-150">
                    Make Payment
                </a>
                @endif

                @if(in_array($booking->status, ['pending', 'confirmed']))
                <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-150"
                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                        Cancel Booking
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection