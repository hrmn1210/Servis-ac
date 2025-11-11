@extends('layouts.user')

@section('title', 'Create Booking - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">New Booking</h2>

        <form action="{{ route('user.bookings.store') }}" method="POST" id="bookingForm">
            @csrf

            <div class="grid grid-cols-1 gap-6 mb-6">
                <!-- Service Selection -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Select Services</h3>
                    <div class="space-y-3" id="services-container">
                        @foreach($services as $service)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                    class="service-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    data-price="{{ $service->price }}"
                                    onchange="updateTotalPrice()">
                                <div class="ml-3">
                                    <label class="text-sm font-medium text-gray-900">{{ $service->name }}</label>
                                    <p class="text-sm text-gray-500">{{ $service->description }}</p>
                                    <p class="text-sm font-semibold text-blue-600">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <label class="text-sm text-gray-700 mr-2">Quantity:</label>
                                <input type="number" name="quantities[]" min="1" value="1"
                                    class="quantity-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm"
                                    data-service="{{ $service->id }}"
                                    onchange="updateTotalPrice()"
                                    disabled>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('services')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">Booking Date *</label>
                        <input type="datetime-local" name="booking_date" id="booking_date"
                            min="{{ date('Y-m-d\TH:i') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('booking_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Service Address *</label>
                        <textarea name="address" id="address" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter the complete address where service is needed...">{{ old('address', Auth::user()->address) }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Any special instructions or notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">Price Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium" id="subtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span class="text-gray-800">Total:</span>
                            <span class="text-blue-600" id="total-price">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('user.bookings') }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                    Create Booking
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateTotalPrice() {
        let total = 0;
        const checkboxes = document.querySelectorAll('.service-checkbox:checked');

        checkboxes.forEach(checkbox => {
            const serviceId = checkbox.value;
            const price = parseFloat(checkbox.dataset.price);
            const quantityInput = document.querySelector(`.quantity-input[data-service="${serviceId}"]`);
            const quantity = parseInt(quantityInput.value) || 1;

            total += price * quantity;
        });

        document.getElementById('subtotal').textContent = 'Rp ' + formatNumber(total);
        document.getElementById('total-price').textContent = 'Rp ' + formatNumber(total);
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Enable/disable quantity inputs based on checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.service-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const serviceId = this.value;
                const quantityInput = document.querySelector(`.quantity-input[data-service="${serviceId}"]`);
                quantityInput.disabled = !this.checked;

                if (!this.checked) {
                    quantityInput.value = 1;
                }
            });
        });

        // Set minimum datetime to current time
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('booking_date').min = now.toISOString().slice(0, 16);
    });
</script>
@endsection