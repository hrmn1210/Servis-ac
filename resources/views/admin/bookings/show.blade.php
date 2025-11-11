@extends('admin.layout')

@section('title', 'Booking Details - ServisAC')
@section('header', 'Booking Details')
@section('subheader', 'Lihat dan kelola booking #' . $booking->id)

@section('content')
<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Booking #{{ $booking->id }}</h2>
                    <p class="text-gray-600">
                        {{ $booking->booking_date->format('F d, Y \a\t H:i A') }}
                    </p>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Customer</h3>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-900 font-medium">{{ $booking->user->name ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $booking->user->email ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $booking->user->phone_number ?? 'N/A' }}</p>
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>{{ $booking->address }}
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Technician</h3>
                    <div class="space-y-2 text-sm">
                        @if($booking->technician)
                        <p class="text-gray-900 font-medium">{{ $booking->technician->name }}</p>
                        <p class="text-gray-600">{{ $booking->technician->email }}</p>
                        <p class="text-gray-600">{{ $booking->technician->phone_number ?? 'N/A' }}</p>
                        @else
                        <p class="text-gray-500 italic">Not Assigned</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($booking->notes)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Notes from Customer</h3>
                <div class="p-4 bg-gray-50 rounded-lg border">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $booking->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Services</h3>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Service</th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Qty</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($booking->services as $service)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $service->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 text-center">{{ $service->pivot->quantity }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 text-right">Rp {{ number_format($service->pivot->price * $service->pivot->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Payment</h3>
                    @if($booking->payment)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Price:</span>
                            <span class="font-bold text-lg text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Payment Type:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->payment->payment_type)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Payment Status:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->payment->status)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Verification:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($booking->payment->verification_status) }}</span>
                        </div>
                        @if($booking->payment->payment_proof)
                        <div class="pt-2">
                            <a href="{{ Storage::url($booking->payment->payment_proof) }}" target="_blank"
                                class="w-full text-center block px-4 py-2 border border-blue-500 text-blue-600 rounded-md hover:bg-blue-50 text-sm">
                                View Payment Proof
                            </a>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500 italic text-sm">No payment details available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-28">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

            {{-- [PERBAIKAN] Form ini mengirim ke rute 'admin.bookings.updateStatus' yang sudah ada --}}
            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- 1. Ganti Status --}}
                    <div>
                        <label for="statusSelect" class="block text-sm font-medium text-gray-700">Update Status</label>
                        <select id="statusSelect" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="assigned" {{ $booking->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    {{-- 2. Pilih Teknisi (Hanya muncul jika status 'assigned') --}}
                    <div id="technicianSelectSection" class="{{ $booking->status == 'assigned' ? '' : 'hidden' }}">
                        <label for="technician_id" class="block text-sm font-medium text-gray-700">Assign Technician *</label>
                        <select id="technician_id" name="technician_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                            <option value="">-- Select Technician --</option>
                            @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $booking->technician_id == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('technician_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. Tombol Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition duration-150">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

            {{-- Tombol Aksi Tambahan --}}
            <div class="mt-4 border-t pt-4 space-y-2">
                @if($booking->payment && $booking->payment->status == 'pending_verification')
                <a href="{{ route('admin.payments.verification') }}"
                    class="w-full block text-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-150">
                    Go to Verification
                </a>
                @endif

                <a href="{{ route('admin.bookings.index') }}"
                    class="w-full block text-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- JavaScript untuk menampilkan/menyembunyikan dropdown teknisi --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('statusSelect');
        const technicianSection = document.getElementById('technicianSelectSection');

        function toggleTechnician(status) {
            if (status === 'assigned') {
                technicianSection.classList.remove('hidden');
            } else {
                technicianSection.classList.add('hidden');
            }
        }

        // Jalankan saat status diubah
        statusSelect.addEventListener('change', function() {
            toggleTechnician(this.value);
        });

        // Jalankan saat halaman dimuat
        toggleTechnician(statusSelect.value);
    });
</script>
@endpush