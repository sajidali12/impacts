<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Services
            </h2>
            <a href="{{ route('service-provider.services.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Add New Service
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($services->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($services as $service)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <!-- Feature Image -->
                                    <div class="relative h-48 bg-gray-200">
                                        @if($service->images && count($service->images) > 0)
                                            <img src="{{ asset('storage/' . $service->images[0]) }}" 
                                                 alt="{{ $service->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-200">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM5 20a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2h2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Status Badges -->
                                        <div class="absolute top-3 right-3 flex flex-col space-y-1">
                                            @if($service->is_featured)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Featured
                                                </span>
                                            @endif
                                            @if($service->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500 text-white shadow-sm">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-500 text-white shadow-sm">
                                                    Inactive
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Service Type Badge -->
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-90 text-gray-800 shadow-sm">
                                                {{ $service->service_type_display }}
                                            </span>
                                        </div>
                                        
                                        <!-- Experience Badge -->
                                        @if($service->experience_years)
                                            <div class="absolute bottom-3 left-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-500 text-white shadow-sm">
                                                    {{ $service->experience_years }}+ years exp
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="p-6">
                                        <div class="mb-3">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $service->title }}</h3>
                                            @if($service->location)
                                                <p class="text-gray-600 text-sm flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $service->location }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-2xl font-bold text-indigo-600">{{ $service->formatted_pricing }}</p>
                                            @if($service->availability)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $service->availability_display }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($service->description, 100) }}</p>
                                        
                                        <!-- Specializations -->
                                        @if($service->specializations)
                                        <div class="mb-4 border-t border-gray-100 pt-3">
                                            <div class="text-xs text-gray-500 mb-2">Specializations:</div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice(explode(',', $service->specializations), 0, 3) as $spec)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ trim($spec) }}
                                                    </span>
                                                @endforeach
                                                @if(count(explode(',', $service->specializations)) > 3)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        +{{ count(explode(',', $service->specializations)) - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Stats -->
                                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4 bg-gray-50 rounded-lg px-3 py-2">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>{{ $service->view_count }} views</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                <span>{{ $service->lead_count }} inquiries</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('service-provider.services.show', $service) }}" 
                                               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('service-provider.services.edit', $service) }}" 
                                               class="flex-1 text-center bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('service-provider.services.destroy', $service) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete this service?')" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($services->hasPages())
                            <div class="mt-6">
                                {{ $services->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM5 20a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2h2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No services</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first service.</p>
                            <div class="mt-6">
                                <a href="{{ route('service-provider.services.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Add Service
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>