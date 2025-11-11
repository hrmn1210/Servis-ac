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
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                    @if($payment->status == 'paid') bg-green-100 text-green-800
                    @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($payment->status == 'pending_verification') bg-blue-100 text-blue-800
                    @elseif($payment->status == 'failed') bg-red-100 text-red-800
                    @elseif($payment->status == 'refunded') bg-gray-100 text-gray-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                </span>
                @if($payment->verification_status == 'pending')
                <span class="block mt-2 px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    Waiting Verification
                </span>
                @elseif($payment->verification_status == 'approved')
                <span class="block mt-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Verified
                </span>
                @elseif($payment->verification_status == 'rejected')
                <span class="block mt-2 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Rejected
                </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Type</label>
                        <p class="mt-1 text-gray-900 capitalize">
                            @if($payment->payment_type === 'full')
                            Full Payment
                            @elseif($payment->payment_type === 'down_payment')
                            Down Payment (50%)
                            @else
                            Cash on Delivery
                            @endif
                        </p>
                    </div>

                    @if($payment->payment_type === 'down_payment')
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Down Payment</label>
                        <p class="mt-1 text-xl font-bold text-blue-600">Rp {{ number_format($payment->down_payment_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Remaining</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}</p>
                    </div>
                    @else
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Total Amount</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Method</label>
                        <p class="mt-1 text-gray-900 capitalize">{{ $payment->payment_method ?? 'Not specified' }}</p>
                    </div>

                    @if($payment->transaction_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Transaction ID</label>
                        <p class="mt-1 text-gray-900">{{ $payment->transaction_id }}</p>
                    </div>
                    @endif

                    @if($payment->paid_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Date</label>
                        <p class="mt-1 text-gray-900">{{ $payment->paid_at->format('F d, Y H:i') }}</p>
                    </div>
                    @endif
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
                        <div class="mt-1">
                            @foreach($payment->booking->services as $service)
                            <span class="inline-block bg-gray-100 px-2 py-1 rounded text-sm mr-1 mb-1">
                                {{ $service->name }} ({{ $service->pivot->quantity }})
                            </span>
                            @endforeach
                        </div>
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
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Notes -->
        @if($payment->admin_notes)
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Admin Notes</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-700">{{ $payment->admin_notes }}</p>
            </div>
        </div>
        @endif

        <!-- Payment Instructions -->
        @if($payment->status == 'pending' && !$payment->payment_method)
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Complete Your Payment</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-yellow-800 mb-3 font-medium">Please select a payment method:</p>
            </div>

            <form action="{{ route('user.payments.process', $payment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" id="paymentMethodSelect" required onchange="togglePaymentProof()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash on Delivery (Pay to technician)</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <div id="paymentProofSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof *</label>
                    <input type="file" name="payment_proof" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Upload screenshot of transfer confirmation or QRIS payment</p>
                </div>

                <div id="bankInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="font-medium text-blue-900 mb-2">Bank Transfer Details:</p>
                    <p class="text-sm text-blue-800">Bank: BCA</p>
                    <p class="text-sm text-blue-800">Account: 123-456-7890</p>
                    <p class="text-sm text-blue-800">Name: PT. ServisAC Indonesia</p>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    Submit Payment
                </button>
            </form>
        </div>
        @elseif($payment->status == 'pending_verification')
        <div class="border-t pt-6 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3 text-xl"></i>
                    <div>
                        <p class="text-blue-800 font-medium">Payment Submitted</p>
                        <p class="text-blue-700 text-sm mt-1">Your payment is being verified by admin. This usually takes 1-2 hours.</p>
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
        </div>
    </div>
</div>

<script>
    function togglePaymentProof() {
        const method = document.getElementById('paymentMethodSelect').value;
        const proofSection = document.getElementById('paymentProofSection');
        const bankInfo = document.getElementById('bankInfo');

        if (method === 'transfer') {
            proofSection.classList.remove('hidden');
            bankInfo.classList.remove('hidden');
            proofSection.querySelector('input').required = true;
        } else if (method === 'qris') {
            proofSection.classList.remove('hidden');
            bankInfo.classList.add('hidden');
            proofSection.querySelector('input').required = true;
        } else {
            proofSection.classList.add('hidden');
            bankInfo.classList.add('hidden');
            proofSection.querySelector('input').required = false;
        }
    }
</script>
@endsection