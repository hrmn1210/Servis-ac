@extends('layouts.user')

@section('title', 'Create Service Request - ServisAC')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">New Service Request</h2>

        <form action="{{ route('user.service-requests.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                    <select name="service_type" id="service_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Service Type</option>
                        <option value="AC Cleaning">AC Cleaning</option>
                        <option value="AC Repair">AC Repair</option>
                        <option value="AC Installation">AC Installation</option>
                        <option value="AC Maintenance">AC Maintenance</option>
                        <option value="Freon Refill">Freon Refill</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('service_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Problem Description *</label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Please describe the problem with your AC...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Service Address *</label>
                    <textarea name="address" id="address" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter the complete address where service is needed...">{{ old('address', Auth::user()->address) }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone *</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Date *</label>
                    <input type="date" name="preferred_date" id="preferred_date" value="{{ old('preferred_date') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('preferred_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">Preferred Time *</label>
                    <select name="preferred_time" id="preferred_time" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Time</option>
                        <option value="08:00-10:00">08:00 - 10:00</option>
                        <option value="10:00-12:00">10:00 - 12:00</option>
                        <option value="13:00-15:00">13:00 - 15:00</option>
                        <option value="15:00-17:00">15:00 - 17:00</option>
                    </select>
                    @error('preferred_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('user.service-requests') }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('preferred_date').min = today;
    });
</script>
@endsection