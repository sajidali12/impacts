<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $service->title }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('service-provider.services.edit', $service) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Edit Service
                </a>
                <a href="{{ route('service-provider.services.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Back to Services
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Badges -->
            <div class="mb-6 flex space-x-2">
                @if($service->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Inactive
                    </span>
                @endif

                @if($service->is_featured)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Featured
                    </span>
                @endif

                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ $service->service_type_display }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Service Images -->
                    @if($service->images && count($service->images) > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Portfolio Images</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($service->images as $image)
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Service portfolio image" class="w-full h-48 object-cover rounded-md">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Service Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Service Description</h3>
                            
                            <div class="prose max-w-none">
                                <p class="text-gray-700">{{ $service->description }}</p>
                            </div>

                            @if($service->specializations)
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Specializations</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $service->specializations) as $spec)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ trim($spec) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($service->external_link)
                                <div class="mt-4">
                                    <a href="{{ $service->external_link }}" 
                                       target="_blank" 
                                       class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        View Portfolio/Website
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Performance Analytics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Performance</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $service->view_count }}</div>
                                    <div class="text-sm text-gray-600">Total Views</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $service->lead_count }}</div>
                                    <div class="text-sm text-gray-600">Total Inquiries</div>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $service->view_count > 0 ? number_format(($service->lead_count / $service->view_count) * 100, 1) : 0 }}%
                                    </div>
                                    <div class="text-sm text-gray-600">Conversion Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Service Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Service Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Pricing</span>
                                    <span class="text-sm text-gray-900 font-semibold">{{ $service->formatted_pricing }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Service Type</span>
                                    <span class="text-sm text-gray-900">{{ $service->service_type_display }}</span>
                                </div>
                                
                                @if($service->location)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Location</span>
                                        <span class="text-sm text-gray-900">{{ $service->location }}</span>
                                    </div>
                                @endif
                                
                                @if($service->experience_years)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Experience</span>
                                        <span class="text-sm text-gray-900">{{ $service->experience_years }} years</span>
                                    </div>
                                @endif
                                
                                @if($service->availability)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Availability</span>
                                        <span class="text-sm text-gray-900">{{ $service->availability_display }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Created</span>
                                    <span class="text-sm text-gray-900">{{ $service->created_at->format('M j, Y') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Last Updated</span>
                                    <span class="text-sm text-gray-900">{{ $service->updated_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('service-provider.services.edit', $service) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                    Edit Service
                                </a>
                                
                                @if($service->is_active)
                                    <form method="POST" action="{{ route('service-provider.services.update', $service) }}" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Deactivate Service
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('service-provider.services.update', $service) }}" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Activate Service
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('service-provider.services.destroy', $service) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.')" 
                                      class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Delete Service
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Inquiries -->
                    @if($service->leads()->exists())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Inquiries</h3>
                                
                                <div class="space-y-3">
                                    @foreach($service->leads()->latest()->limit(5)->get() as $lead)
                                        <div class="border-b border-gray-200 pb-2">
                                            <div class="text-sm font-medium text-gray-900">{{ $lead->lead_title }}</div>
                                            <div class="text-xs text-gray-500">{{ $lead->created_at->diffForHumans() }}</div>
                                            <div class="text-xs text-green-600 font-medium">£{{ number_format($lead->rate_charged, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($service->leads()->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('service-provider.analytics') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                                            View all inquiries →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>