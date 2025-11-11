@extends('admin.layout')

@section('title', 'Service Requests')
@section('header', 'Service Requests Management')
@section('subheader', 'Kelola semua permintaan servis dari pelanggan')

@section('header-actions')
<div class="flex space-x-3">
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-200">
        <i class="fas fa-download mr-2"></i>Export
    </button>
    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200" onclick="window.location.reload()">
        <i class="fas fa-sync-alt mr-2"></i>Refresh
    </button>
</div>
@endsection

@section('content')
<!-- Status Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-wrap gap-3">
        <button class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium">All ({{ $requests->total() }})</button>
        <button class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium hover:bg-yellow-200 transition duration-150">
            Pending ({{ $requests->where('status', 'pending')->count() }})
        </button>
        <button class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-medium hover:bg-blue-200 transition duration-150">
            Approved ({{ $requests->where('status', 'approved')->count() }})
        </button>
        <button class="px-4 py-2 bg-orange-100 text-orange-800 rounded-lg font-medium hover:bg-orange-200 transition duration-150">
            In Progress ({{ $requests->where('status', 'in_progress')->count() }})
        </button>
        <button class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium hover:bg-green-200 transition duration-150">
            Completed ({{ $requests->where('status', 'completed')->count() }})
        </button>
        <button class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium hover:bg-red-200 transition duration-150">
            Cancelled ({{ $requests->where('status', 'cancelled')->count() }})
        </button>
    </div>
</div>

<!-- Requests Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Service Requests</h3>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" placeholder="Search requests..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($requests as $request)
                <tr class="hover:bg-gray-50 transition duration-150" id="request-row-{{ $request->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono text-gray-900">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                                {{ strtoupper(substr($request->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $request->user->phone_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $request->service_type }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($request->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $request->status == 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $request->status == 'in_progress' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $request->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $request->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($request->price)
                        Rp {{ number_format($request->price, 0, ',', '.') }}
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $request->created_at->format('M d, Y') }}
                        <div class="text-xs text-gray-400">{{ $request->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900 transition duration-150" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="updateServiceStatus({{ $request->id }})" class="text-purple-600 hover:text-purple-900 transition duration-150" title="Update Status">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button onclick="deleteServiceRequest({{ $request->id }})" class="text-red-600 hover:text-red-900 transition duration-150" title="Delete">
                                <i class="fas fa-trash"></i>
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
                Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} results
            </div>
            <div class="flex space-x-2">
                @if($requests->onFirstPage())
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    Previous
                </span>
                @else
                <a href="{{ $requests->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                    Previous
                </a>
                @endif

                @if($requests->hasMorePages())
                <a href="{{ $requests->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
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

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Update Service Status</h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="updateStatusForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="request_id" id="requestId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" id="statusSelect" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                    <input type="number" name="price" id="priceInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Enter service price">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                    <textarea name="admin_notes" id="adminNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Add any notes..."></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Update Status</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Service Requests Functions
    async function updateServiceStatus(requestId) {
        try {
            const response = await fetch(`/admin/service-requests/${requestId}`);
            if (!response.ok) throw new Error('Failed to fetch request data');

            const request = await response.json();

            document.getElementById('requestId').value = requestId;
            document.getElementById('statusSelect').value = request.status;
            document.getElementById('priceInput').value = request.price || '';
            document.getElementById('adminNotes').value = request.admin_notes || '';

            document.getElementById('updateStatusForm').action = `/admin/service-requests/${requestId}`;
            document.getElementById('updateStatusModal').classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching request:', error);
            showNotification('Error loading request data', 'error');
        }
    }

    function closeStatusModal() {
        document.getElementById('updateStatusModal').classList.add('hidden');
    }

    async function deleteServiceRequest(requestId) {
        if (!confirm('Are you sure you want to delete this service request?')) {
            return;
        }

        try {
            const response = await fetch(`/admin/service-requests/${requestId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById(`request-row-${requestId}`).remove();
                showNotification('Service request deleted successfully', 'success');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error deleting request:', error);
            showNotification(error.message || 'Error deleting service request', 'error');
        }
    }

    // Form submission handler for status update
    document.getElementById('updateStatusForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';

        try {
            const formData = new FormData(this);
            const requestId = document.getElementById('requestId').value;

            const response = await fetch(`/admin/service-requests/${requestId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                }
            });

            const result = await response.json();

            if (result.success) {
                closeStatusModal();
                showNotification('Service request updated successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error updating request:', error);
            showNotification(error.message || 'Error updating service request', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Update Status';
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'updateStatusModal') {
            closeStatusModal();
        }
    });
</script>
@endpush