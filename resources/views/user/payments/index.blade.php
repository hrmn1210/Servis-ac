@extends('layouts.user')

@section('title', 'My Payments - ServisAC')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">My Payments</h2>
    <p class="text-gray-600">View and manage your payment history</p>
</div>

<!-- Payment Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-clock text-blue-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pending Payments</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $payments->where('status', 'pending')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Paid Payments</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $payments->where('status', 'paid')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-full mr-4">
                <i class="fas fa-dollar-sign text-purple-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Spent</p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp {{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            @if($payment->booking)
                            Booking #{{ $payment->booking->id }}
                            <div class="text-sm text-gray-500">
                                @foreach($payment->booking->services as $service)
                                {{ $service->name }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                            @elseif($payment->serviceRequest)
                            {{ $payment->serviceRequest->service_type }}
                            @else
                            Unknown Service
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $payment->created_at->format('M d, Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                        {{ $payment->payment_method ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($payment->status == 'paid') bg-green-100 text-green-800
                            @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status == 'failed') bg-red-100 text-red-800
                            @elseif($payment->status == 'refunded') bg-gray-100 text-gray-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('user.payments.show', $payment->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        @if($payment->status == 'pending')
                        <button onclick="showPaymentModal({{ $payment->id }})"
                            class="text-green-600 hover:text-green-900">
                            Pay Now
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="bg-white px-6 py-3 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
    @endif
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Process Payment</h3>

            <form id="paymentForm" method="POST">
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
    function showPaymentModal(paymentId) {
        const form = document.getElementById('paymentForm');
        form.action = `/user/payments/${paymentId}/process`;
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