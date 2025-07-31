<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Service
            </h2>
            <a href="{{ route('service-provider.services.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Back to Services
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('service-provider.services.update', $service) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Service Title *</label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $service->title) }}" 
                                   required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Service Description *</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Service Type and Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                                <select id="service_type" 
                                        name="service_type"
                                        required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Select Service Type</option>
                                    <option value="marketing" {{ old('service_type', $service->service_type) == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="construction" {{ old('service_type', $service->service_type) == 'construction' ? 'selected' : '' }}>Construction</option>
                                    <option value="legal" {{ old('service_type', $service->service_type) == 'legal' ? 'selected' : '' }}>Legal</option>
                                    <option value="financial" {{ old('service_type', $service->service_type) == 'financial' ? 'selected' : '' }}>Financial</option>
                                    <option value="design" {{ old('service_type', $service->service_type) == 'design' ? 'selected' : '' }}>Design</option>
                                    <option value="consulting" {{ old('service_type', $service->service_type) == 'consulting' ? 'selected' : '' }}>Consulting</option>
                                    <option value="maintenance" {{ old('service_type', $service->service_type) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="other" {{ old('service_type', $service->service_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('service_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Service Location</label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location', $service->location) }}" 
                                       placeholder="Where do you provide this service?"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing Type and Amount -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pricing_type" class="block text-sm font-medium text-gray-700 mb-2">Pricing Type *</label>
                                <select id="pricing_type" 
                                        name="pricing_type"
                                        required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Select Pricing Type</option>
                                    <option value="hourly" {{ old('pricing_type', $service->pricing_type) == 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                                    <option value="fixed" {{ old('pricing_type', $service->pricing_type) == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                    <option value="project" {{ old('pricing_type', $service->pricing_type) == 'project' ? 'selected' : '' }}>Per Project</option>
                                    <option value="consultation" {{ old('pricing_type', $service->pricing_type) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                </select>
                                @error('pricing_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pricing_amount" class="block text-sm font-medium text-gray-700 mb-2">Price (£) *</label>
                                <input type="number" 
                                       id="pricing_amount" 
                                       name="pricing_amount" 
                                       value="{{ old('pricing_amount', $service->pricing_amount) }}" 
                                       step="0.01"
                                       min="0"
                                       required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('pricing_amount')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Experience and Availability -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                                <input type="number" 
                                       id="experience_years" 
                                       name="experience_years" 
                                       value="{{ old('experience_years', $service->experience_years) }}" 
                                       min="0"
                                       max="50"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('experience_years')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                                <select id="availability" 
                                        name="availability"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Select Availability</option>
                                    <option value="immediate" {{ old('availability', $service->availability) == 'immediate' ? 'selected' : '' }}>Available Immediately</option>
                                    <option value="within_week" {{ old('availability', $service->availability) == 'within_week' ? 'selected' : '' }}>Within a Week</option>
                                    <option value="within_month" {{ old('availability', $service->availability) == 'within_month' ? 'selected' : '' }}>Within a Month</option>
                                    <option value="flexible" {{ old('availability', $service->availability) == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                </select>
                                @error('availability')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Specializations -->
                        <div>
                            <label for="specializations" class="block text-sm font-medium text-gray-700 mb-2">Specializations</label>
                            <input type="text" 
                                   id="specializations" 
                                   name="specializations" 
                                   value="{{ old('specializations', $service->specializations) }}" 
                                   placeholder="e.g., Residential, Commercial, Luxury Properties (comma separated)"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Enter multiple specializations separated by commas.</p>
                            @error('specializations')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- External Link -->
                        <div>
                            <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2">Portfolio/Website Link (Optional)</label>
                            <input type="url" 
                                   id="external_link" 
                                   name="external_link" 
                                   value="{{ old('external_link', $service->external_link) }}" 
                                   placeholder="https://example.com/portfolio"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('external_link')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Images -->
                        @if($service->images && count($service->images) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Portfolio Images</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($service->images as $image)
                                        <div class="relative group border border-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Service portfolio image" class="w-full h-32 object-cover">
                                            
                                            <!-- Delete overlay -->
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                                <div class="text-center">
                                                    <input type="checkbox" 
                                                           name="remove_images[]" 
                                                           value="{{ $image }}" 
                                                           id="remove_{{ $loop->index }}" 
                                                           class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                                           onchange="toggleImageOverlay(this)">
                                                    <label for="remove_{{ $loop->index }}" class="block mt-2 text-white text-sm font-medium cursor-pointer">
                                                        Remove Image
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete indicator when checked -->
                                            <div class="remove-indicator absolute inset-0 bg-red-500 bg-opacity-75 hidden items-center justify-center">
                                                <div class="text-center text-white">
                                                    <svg class="w-8 h-8 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <p class="text-sm font-medium">Will be removed</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Hover over images</strong> to see the remove option. Check the box to mark images for deletion.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                function toggleImageOverlay(checkbox) {
                                    const container = checkbox.closest('.relative');
                                    const indicator = container.querySelector('.remove-indicator');
                                    
                                    if (checkbox.checked) {
                                        indicator.classList.remove('hidden');
                                        indicator.classList.add('flex');
                                        container.classList.add('ring-2', 'ring-red-500');
                                    } else {
                                        indicator.classList.add('hidden');
                                        indicator.classList.remove('flex');
                                        container.classList.remove('ring-2', 'ring-red-500');
                                    }
                                }
                            </script>
                        @endif

                        <!-- New Images -->
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                            <input type="file" 
                                   id="images" 
                                   name="images[]" 
                                   multiple
                                   accept="image/*"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Select up to 10 images showcasing your work. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per image.</p>
                            @error('images')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input id="is_featured" 
                                       name="is_featured" 
                                       type="checkbox" 
                                       value="1"
                                       {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Featured Service
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="is_active" 
                                       name="is_active" 
                                       type="checkbox" 
                                       value="1"
                                       {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active (visible to public)
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Featured services appear prominently in search results and may incur additional fees.</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('service-provider.services.index') }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>