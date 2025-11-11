@extends('layouts.user')

@section('title', 'Service Requests - ServisAC')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">My Service Requests</h2>
    <a href="{{ route('user.service-requests.create') }}"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-150">
        <i class="fas fa-plus mr-2"></i>New Request
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preferred Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $request->service_type }}</div>
                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($request->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $request->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $request->preferred_date->format('M d, Y') }} at {{ $request->preferred_time }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($request->status == 'completed') bg-green-100 text-green-800
                            @elseif($request->status == 'cancelled') bg-red-100 text-red-800
                            @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($request->price)
                        Rp {{ number_format($request->price, 0, ',', '.') }}
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('user.service-requests.show', $request->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        @if($request->status == 'pending')
                        <form action="{{ route('user.service-requests.cancel', $request->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Are you sure you want to cancel this request?')">
                                Cancel
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No service requests found.
                        <a href="{{ route('user.service-requests.create') }}" class="text-blue-500 hover:text-blue-700 ml-1">
                            Create your first request
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
    <div class="bg-white px-6 py-3 border-t border-gray-200">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection