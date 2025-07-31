<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Service Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.services') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Services</label>
                                <input type="text" 
                                       id="search"
                                       name="search" 
                                       placeholder="Title or description..." 
                                       value="{{ request('search') }}" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" 
                                        id="status"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                                <select name="service_type" 
                                        id="service_type"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Types</option>
                                    <option value="consulting" {{ request('service_type') == 'consulting' ? 'selected' : '' }}>Consulting</option>
                                    <option value="development" {{ request('service_type') == 'development' ? 'selected' : '' }}>Development</option>
                                    <option value="design" {{ request('service_type') == 'design' ? 'selected' : '' }}>Design</option>
                                    <option value="marketing" {{ request('service_type') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="maintenance" {{ request('service_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="other" {{ request('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Service Provider</label>
                                <select name="user_id" 
                                        id="user_id"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Providers</option>
                                    @foreach($serviceProviders as $provider)
                                        <option value="{{ $provider->id }}" {{ request('user_id') == $provider->id ? 'selected' : '' }}>
                                            {{ $provider->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 font-medium">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Services Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Provider
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rate per Lead
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($services as $service)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($service->featured_image)
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-lg object-cover" 
                                                             src="{{ Storage::url($service->featured_image) }}" 
                                                             alt="{{ $service->title }}">
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-300 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $service->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $service->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $service->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($service->service_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            £{{ number_format($service->rate_per_lead, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    1 => 'bg-green-100 text-green-800', // Active
                                                    0 => 'bg-red-100 text-red-800'     // Inactive
                                                ];
                                                $statusText = $service->is_active ? 'Active' : 'Inactive';
                                                if ($service->archived_at) {
                                                    $statusColors = ['archived' => 'bg-gray-100 text-gray-800'];
                                                    $statusText = 'Archived';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$service->is_active] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $service->created_at->format('M j, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('service.show', $service) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3"
                                               target="_blank">
                                                View
                                            </a>
                                            <button type="button" 
                                                    onclick="toggleServiceStatus({{ $service->id }}, {{ $service->is_active ? 'false' : 'true' }})"
                                                    class="text-{{ $service->is_active ? 'red' : 'green' }}-600 hover:text-{{ $service->is_active ? 'red' : 'green' }}-900">
                                                {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No services found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($services->hasPages())
                        <div class="mt-6">
                            {{ $services->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for status toggle (if needed) -->
    <script>
        function toggleServiceStatus(serviceId, newStatus) {
            if (confirm('Are you sure you want to ' + (newStatus === 'true' ? 'activate' : 'deactivate') + ' this service?')) {
                // You would implement the AJAX call here to toggle the service status
                // For now, we'll just reload the page
                window.location.reload();
            }
        }
    </script>
</x-app-layout>