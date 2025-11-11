@extends('layouts.user')

@section('title', 'Payment Details - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Payment Details</h2>
                <p class="text-gray-600">Payment #{{ $payment->id }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
                @if($payment->status == 'paid') bg-green-100 text-green-800
                @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($payment->status == 'failed') bg-red-100 text-red-800
                @elseif($payment->status == 'refunded') bg-gray-100 text-gray-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($payment->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Amount</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Method</label>
                        <p class="mt-1 text-gray-900 capitalize">{{ $payment->payment_method ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Transaction ID</label>
                        <p class="mt-1 text-gray-900">{{ $payment->transaction_id ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Date</label>
                        <p class="mt-1 text-gray-900">
                            @if($payment->paid_at)
                            {{ $payment->paid_at->format('F d, Y H:i') }}
                            @else
                            -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Service Information</h3>
                <div class="space-y-3">
                    @if($payment->booking)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Booking ID</label>
                        <p class="mt-1 text-gray-900">#{{ $payment->booking->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Services</label>
                        <p class="mt-1 text-gray-900">
                            @foreach($payment->booking->services as $service)
                            <span class="bg-gray-100 px-2 py-1 rounded text-sm mr-1">{{ $service->name }}</span>
                            @endforeach
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Booking Date</label>
                        <p class="mt-1 text-gray-900">{{ $payment->booking->booking_date->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Address</label>
                        <p class="mt-1 text-gray-900">{{ $payment->booking->address }}</p>
                    </div>
                    @elseif($payment->serviceRequest)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Type</label>
                        <p class="mt-1 text-gray-900">{{ $payment->serviceRequest->service_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Description</label>
                        <p class="mt-1 text-gray-900">{{ $payment->serviceRequest->description }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Date</label>
                        <p class="mt-1 text-gray-900">{{ $payment->serviceRequest->preferred_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Address</label>
                        <p class="mt-1 text-gray-900">{{ $payment->serviceRequest->address }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($payment->status == 'pending')
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Instructions</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-yellow-800 mb-3">Please complete your payment using one of the following methods:</p>
                <div class="space-y-3 text-sm text-yellow-700">
                    <div class="flex items-start">
                        <i class="fas fa-money-bill-wave mt-1 mr-3"></i>
                        <div>
                            <strong>Cash:</strong> Pay directly to our technician when they arrive
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-university mt-1 mr-3"></i>
                        <div>
                            <strong>Bank Transfer:</strong> Transfer to BCA 123-456-7890 (PT. ServisAC Indonesia)
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-qrcode mt-1 mr-3"></i>
                        <div>
                            <strong>QRIS:</strong> Scan the QR code that will be provided by our technician
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="border-t pt-6 flex justify-between">
            <a href="{{ route('user.payments') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                Back to List
            </a>

            @if($payment->status == 'pending')
            <button onclick="showPaymentModal()"
                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-150">
                Process Payment
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Process Payment</h3>

            <form action="{{ route('user.payments.process', $payment->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Process Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('paymentModal');
        if (event.target === modal) {
            closePaymentModal();
        }
    }
</script>
@endsection