@extends('admin.layout')

@section('title', 'Payment Verification')
@section('header', 'Payment Verification')
@section('subheader', 'Verifikasi bukti pembayaran dari customer')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Stats -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Verification</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingCount }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-xl">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Approved Today</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $approvedTodayCount }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-xl">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Rejected Today</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $rejectedTodayCount }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-xl">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Pending Verification</h3>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('admin.payments.verification') }}" class="flex items-center">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono text-gray-900">#PAY{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y') }}</div>
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
                                    {{ $payment->serviceRequest->user->phone_number ?? 'No Phone' }}
                                    @elseif($payment->booking)
                                    {{ $payment->booking->user->phone_number ?? 'No Phone' }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payment->serviceRequest)
                        <div class="text-sm text-gray-900">Service Request</div>
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
                        @if($payment->payment_type === 'down_payment')
                        <div class="text-xs text-gray-500">
                            DP: Rp {{ number_format($payment->down_payment_amount, 0, ',', '.') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                            {{ $payment->payment_type == 'full' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $payment->payment_type == 'down_payment' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $payment->payment_type == 'cod' ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                        {{ $payment->payment_method ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payment->payment_proof)
                        <button onclick="viewPaymentProof('{{ Storage::url($payment->payment_proof) }}')"
                            class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            View Proof
                        </button>
                        @else
                        <span class="text-gray-400 text-sm">No Proof</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="verifyPayment({{ $payment->id }}, 'approved')"
                                class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-150 text-xs">
                                Approve
                            </button>
                            <button onclick="verifyPayment({{ $payment->id }}, 'rejected')"
                                class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150 text-xs">
                                Reject
                            </button>
                            <button onclick="viewPaymentDetails({{ $payment->id }})"
                                class="px-3 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-150 text-xs">
                                Details
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
        {{ $payments->links() }}
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Payment Proof</h3>
            <button onclick="closePaymentProofModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="flex justify-center">
            <img id="proofImage" src="" alt="Payment Proof" class="max-w-full max-h-96 rounded-lg">
        </div>
        <div class="flex justify-end mt-4">
            <button onclick="closePaymentProofModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900" id="verificationTitle">Verify Payment</h3>
            <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="verificationForm">
            @csrf
            <input type="hidden" name="payment_id" id="verifyPaymentId">
            <input type="hidden" name="action" id="verificationAction">

            <div id="rejectSection" class="hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason *</label>
                    <textarea name="admin_notes" id="adminNotes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Reason for rejecting this payment..."></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeVerificationModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" id="verifyButton">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewPaymentProof(imageUrl) {
        document.getElementById('proofImage').src = imageUrl;
        document.getElementById('paymentProofModal').classList.remove('hidden');
    }

    function closePaymentProofModal() {
        document.getElementById('paymentProofModal').classList.add('hidden');
    }

    function verifyPayment(paymentId, action) {
        document.getElementById('verifyPaymentId').value = paymentId;
        document.getElementById('verificationAction').value = action;

        if (action === 'approved') {
            document.getElementById('verificationTitle').textContent = 'Approve Payment';
            document.getElementById('verifyButton').textContent = 'Approve Payment';
            document.getElementById('verifyButton').className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700';
            document.getElementById('rejectSection').classList.add('hidden');
        } else {
            document.getElementById('verificationTitle').textContent = 'Reject Payment';
            document.getElementById('verifyButton').textContent = 'Reject Payment';
            document.getElementById('verifyButton').className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700';
            document.getElementById('rejectSection').classList.remove('hidden');
        }

        document.getElementById('verificationModal').classList.remove('hidden');
    }

    function closeVerificationModal() {
        document.getElementById('verificationModal').classList.add('hidden');
    }

    // Form submission
    document.getElementById('verificationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

        try {
            const formData = new FormData(this);
            const paymentId = document.getElementById('verifyPaymentId').value;
            const action = document.getElementById('verificationAction').value;

            const response = await fetch(`/admin/payments/${paymentId}/verify`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                closeVerificationModal();
                showNotification(`Payment ${action} successfully`, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error verifying payment:', error);
            showNotification(error.message || 'Error verifying payment', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Confirm';
        }
    });

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'paymentProofModal') closePaymentProofModal();
        if (e.target.id === 'verificationModal') closeVerificationModal();
    });

    function showNotification(message, type = 'info') {
        // Implement notification system
        alert(`${type.toUpperCase()}: ${message}`);
    }
</script>
@endpush