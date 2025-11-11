@extends('admin.layout')

@section('title', 'Payments Management')
@section('header', 'Payments Management')
@section('subheader', 'Kelola semua transaksi pembayaran')

@section('header-actions')
<div class="flex space-x-3">
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
        <i class="fas fa-download mr-2"></i>Export
    </button>
    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
        <i class="fas fa-filter mr-2"></i>Filter
    </button>
</div>
@endsection

@section('content')
<!-- Payment Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-xl">
                <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Payments</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $payments->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-xl">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Successful</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $payments->where('status', 'paid')->count() }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-xl">
                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Failed</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $payments->where('status', 'failed')->count() }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-xl">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Payment Status Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-wrap gap-3">
        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
            class="px-4 py-2 {{ !request('status') ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-800' }} rounded-lg font-medium hover:bg-purple-700 transition duration-150">
            All ({{ $payments->total() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
            class="px-4 py-2 {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-800' }} rounded-lg font-medium hover:bg-yellow-200 transition duration-150">
            Pending ({{ $payments->where('status', 'pending')->count() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}"
            class="px-4 py-2 {{ request('status') == 'paid' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800' }} rounded-lg font-medium hover:bg-green-200 transition duration-150">
            Paid ({{ $payments->where('status', 'paid')->count() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'failed']) }}"
            class="px-4 py-2 {{ request('status') == 'failed' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-800' }} rounded-lg font-medium hover:bg-red-200 transition duration-150">
            Failed ({{ $payments->where('status', 'failed')->count() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'refunded']) }}"
            class="px-4 py-2 {{ request('status') == 'refunded' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-800' }} rounded-lg font-medium hover:bg-gray-200 transition duration-150">
            Refunded ({{ $payments->where('status', 'refunded')->count() }})
        </a>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Payment Transactions</h3>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('admin.payments') }}" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payments..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button type="submit" class="ml-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-150">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service/Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50 transition duration-150" id="payment-row-{{ $payment->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono text-gray-900">#PAY{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                                @if($payment->serviceRequest)
                                {{ strtoupper(substr($payment->serviceRequest->user->name, 0, 1)) }}
                                @elseif($payment->booking)
                                {{ strtoupper(substr($payment->booking->user->name, 0, 1)) }}
                                @else
                                ?
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    @if($payment->serviceRequest)
                                    {{ $payment->serviceRequest->user->name }}
                                    @elseif($payment->booking)
                                    {{ $payment->booking->user->name }}
                                    @else
                                    Unknown Customer
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($payment->serviceRequest)
                                    {{ $payment->serviceRequest->user->email }}
                                    @elseif($payment->booking)
                                    {{ $payment->booking->user->email }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payment->serviceRequest)
                        <div class="text-sm text-gray-900">Service Request #{{ $payment->service_request_id }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->serviceRequest->service_type }}</div>
                        @elseif($payment->booking)
                        <div class="text-sm text-gray-900">Booking #{{ $payment->booking_id }}</div>
                        <div class="text-xs text-gray-500">
                            @foreach($payment->booking->services as $service)
                            {{ $service->name }}@if(!$loop->last), @endif
                            @endforeach
                        </div>
                        @else
                        <div class="text-sm text-gray-900">Unknown</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            {{ $payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $payment->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $payment->status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $payment->status == 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->payment_method ? ucfirst($payment->payment_method) : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($payment->paid_at)
                        {{ $payment->paid_at->format('M d, Y') }}
                        <div class="text-xs text-gray-400">{{ $payment->paid_at->format('H:i') }}</div>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if($payment->status == 'pending')
                            <button onclick="markAsPaid({{ $payment->id }})" class="text-green-600 hover:text-green-900 transition duration-150" title="Mark as Paid">
                                <i class="fas fa-check-circle"></i>
                            </button>
                            @endif
                            <button onclick="updatePayment({{ $payment->id }})" class="text-blue-600 hover:text-blue-900 transition duration-150" title="Edit Payment">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="processRefund({{ $payment->id }}, {{ $payment->amount }})"
                                class="text-red-600 hover:text-red-900 transition duration-150 {{ $payment->status != 'paid' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                title="Process Refund"
                                {{ $payment->status != 'paid' ? 'disabled' : '' }}>
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-700">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
            </div>
            <div class="flex space-x-2">
                @if($payments->onFirstPage())
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    Previous
                </span>
                @else
                <a href="{{ $payments->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                    Previous
                </a>
                @endif

                @if($payments->hasMorePages())
                <a href="{{ $payments->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                    Next
                </a>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    Next
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Update Payment Modal -->
<div id="updatePaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Update Payment</h3>
            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="updatePaymentForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="payment_id" id="paymentId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" id="paymentStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" id="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="card">Credit Card</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transactionId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Transaction reference">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closePaymentModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Update Payment</button>
            </div>
        </form>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Process Refund</h3>
            <button onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="refundForm">
            @csrf
            <input type="hidden" name="payment_id" id="refundPaymentId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Refund Amount *</label>
                    <input type="number" name="refund_amount" id="refundAmount" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Enter refund amount" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Refund Reason *</label>
                    <textarea name="refund_reason" id="refundReason" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Reason for refund..."></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeRefundModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Process Refund</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Quick action untuk mark as paid
    async function markAsPaid(paymentId) {
        if (!confirm('Are you sure you want to mark this payment as paid?')) {
            return;
        }

        try {
            const response = await fetch(`/admin/payments/${paymentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'paid',
                    paid_at: new Date().toISOString()
                })
            });

            const result = await response.json();

            if (result.success) {
                showNotification('Payment marked as paid successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error marking payment as paid:', error);
            showNotification(error.message || 'Error updating payment', 'error');
        }
    }

    // Payments Functions
    async function updatePayment(paymentId) {
        try {
            const response = await fetch(`/admin/payments/${paymentId}`);
            if (!response.ok) throw new Error('Failed to fetch payment data');

            const payment = await response.json();

            document.getElementById('paymentId').value = paymentId;
            document.getElementById('paymentStatus').value = payment.status;
            document.getElementById('paymentMethod').value = payment.payment_method || '';
            document.getElementById('transactionId').value = payment.transaction_id || '';

            document.getElementById('updatePaymentForm').action = `/admin/payments/${paymentId}`;
            document.getElementById('updatePaymentModal').classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching payment:', error);
            showNotification('Error loading payment data', 'error');
        }
    }

    function closePaymentModal() {
        document.getElementById('updatePaymentModal').classList.add('hidden');
    }

    function processRefund(paymentId, amount) {
        if (amount <= 0) {
            showNotification('Cannot process refund for zero amount', 'error');
            return;
        }

        document.getElementById('refundPaymentId').value = paymentId;
        document.getElementById('refundAmount').value = amount;
        document.getElementById('refundAmount').max = amount;
        document.getElementById('refundForm').action = `/admin/payments/${paymentId}/refund`;
        document.getElementById('refundModal').classList.remove('hidden');
    }

    function closeRefundModal() {
        document.getElementById('refundModal').classList.add('hidden');
    }

    // Form submission handlers
    document.getElementById('updatePaymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';

        try {
            const formData = new FormData(this);
            const paymentId = document.getElementById('paymentId').value;

            // Jika status diubah menjadi paid, tambahkan paid_at
            if (document.getElementById('paymentStatus').value === 'paid') {
                formData.append('paid_at', new Date().toISOString());
            }

            const response = await fetch(`/admin/payments/${paymentId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                }
            });

            const result = await response.json();

            if (result.success) {
                closePaymentModal();
                showNotification('Payment updated successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error updating payment:', error);
            showNotification(error.message || 'Error updating payment', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Update Payment';
        }
    });

    document.getElementById('refundForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

        try {
            const formData = new FormData(this);
            const paymentId = document.getElementById('refundPaymentId').value;

            const response = await fetch(`/admin/payments/${paymentId}/refund`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                closeRefundModal();
                showNotification('Refund processed successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error processing refund:', error);
            showNotification(error.message || 'Error processing refund', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Process Refund';
        }
    });

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'updatePaymentModal') {
            closePaymentModal();
        }
        if (e.target.id === 'refundModal') {
            closeRefundModal();
        }
    });

    // Notification function
    function showNotification(message, type = 'info') {
        // Implement your notification system here
        alert(`${type.toUpperCase()}: ${message}`);
    }
</script>
@endpush