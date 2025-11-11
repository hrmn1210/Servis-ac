@extends('admin.layout')

@section('title', 'Booking Details - ServisAC')

@section('content')
<div class="max-w-6xl mx-auto">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Customer Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <p class="mt-1 text-gray-900">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-gray-900">{{ $booking->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Phone</label>
                        <p class="mt-1 text-gray-900">{{ $booking->user->phone_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Address</label>
                        <p class="mt-1 text-gray-900">{{ $booking->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
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
                    @if($booking->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Customer Notes</label>
                        <p class="mt-1 text-gray-900">{{ $booking->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="border-t pt-6 mb-6">
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

        <!-- Technician Assignment -->
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Technician Assignment</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                @if($booking->technician)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-user-cog text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $booking->technician->name }}</p>
                            <p class="text-sm text-gray-600">Assigned Technician</p>
                            @if($booking->technician->phone_number)
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-1"></i>{{ $booking->technician->phone_number }}
                            </p>
                            @endif
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                        Assigned
                    </span>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-gray-500 mb-4">No technician assigned yet</p>
                    <button onclick="showAssignModal()"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                        Assign Technician
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="border-t pt-6 flex justify-between items-center">
            <a href="{{ route('admin.bookings') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                Back to List
            </a>

            <div class="flex space-x-3">
                <!-- Status Update -->
                <select id="statusSelect" onchange="updateStatus({{ $booking->id }})"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="assigned" {{ $booking->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Assign Technician Modal -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Technician</h3>

            <form action="{{ route('admin.bookings.assign-technician', $booking->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Technician</label>
                    <select name="technician_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Technician</option>
                        @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeAssignModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showAssignModal() {
        document.getElementById('assignModal').classList.remove('hidden');
    }

    function closeAssignModal() {
        document.getElementById('assignModal').classList.add('hidden');
    }

    function updateStatus(bookingId) {
        const status = document.getElementById('statusSelect').value;

        fetch(`/admin/bookings/${bookingId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating status');
                }
            });
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('assignModal');
        if (event.target === modal) {
            closeAssignModal();
        }
    }
</script>
@endsection