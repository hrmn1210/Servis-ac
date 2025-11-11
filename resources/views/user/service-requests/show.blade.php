@extends('layouts.user')

@section('title', 'Service Request Details - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Service Request Details</h2>
                <p class="text-gray-600">Request #{{ $request->id }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($request->status == 'completed') bg-green-100 text-green-800
                @elseif($request->status == 'cancelled') bg-red-100 text-red-800
                @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($request->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Service Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Type</label>
                        <p class="mt-1 text-gray-900">{{ $request->service_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Description</label>
                        <p class="mt-1 text-gray-900">{{ $request->description }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Request Date</label>
                        <p class="mt-1 text-gray-900">{{ $request->created_at->format('F d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Schedule & Contact</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Preferred Date</label>
                        <p class="mt-1 text-gray-900">{{ $request->preferred_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Preferred Time</label>
                        <p class="mt-1 text-gray-900">{{ $request->preferred_time }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Contact Phone</label>
                        <p class="mt-1 text-gray-900">{{ $request->phone_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Address</label>
                        <p class="mt-1 text-gray-900">{{ $request->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        @if($request->payment)
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Information</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Amount</label>
                        <p class="mt-1 text-gray-900 font-semibold">Rp {{ number_format($request->payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Status</label>
                        <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($request->payment->status == 'paid') bg-green-100 text-green-800
                            @elseif($request->payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($request->payment->status) }}
                        </span>
                    </div>
                    @if($request->payment->payment_method)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Method</label>
                        <p class="mt-1 text-gray-900 capitalize">{{ $request->payment->payment_method }}</p>
                    </div>
                    @endif
                    @if($request->payment->paid_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Paid At</label>
                        <p class="mt-1 text-gray-900">{{ $request->payment->paid_at->format('F d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Admin Notes -->
        @if($request->admin_notes)
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Admin Notes</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-700">{{ $request->admin_notes }}</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="border-t pt-6 flex justify-between">
            <a href="{{ route('user.service-requests') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                Back to List
            </a>

            @if($request->status == 'pending')
            <form action="{{ route('user.service-requests.cancel', $request->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-150"
                    onclick="return confirm('Are you sure you want to cancel this service request?')">
                    Cancel Request
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection