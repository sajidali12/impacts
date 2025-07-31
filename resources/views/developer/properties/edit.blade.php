<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Property
            </h2>
            <a href="{{ route('developer.properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Back to Properties
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('developer.properties.update', $property) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title *</label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $property->title) }}" 
                                   required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price and Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (£) *</label>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $property->price) }}" 
                                       step="0.01"
                                       min="0"
                                       required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location', $property->location) }}" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Property Type and Area -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                                <select id="property_type" 
                                        name="property_type"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Select Type</option>
                                    <option value="Apartment" {{ old('property_type', $property->property_type) == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="House" {{ old('property_type', $property->property_type) == 'House' ? 'selected' : '' }}>House</option>
                                    <option value="Townhouse" {{ old('property_type', $property->property_type) == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                                    <option value="Studio" {{ old('property_type', $property->property_type) == 'Studio' ? 'selected' : '' }}>Studio</option>
                                    <option value="Penthouse" {{ old('property_type', $property->property_type) == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                                    <option value="Flat" {{ old('property_type', $property->property_type) == 'Flat' ? 'selected' : '' }}>Flat</option>
                                </select>
                                @error('property_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Area (sq m)</label>
                                <input type="number" 
                                       id="area" 
                                       name="area" 
                                       value="{{ old('area', $property->area) }}" 
                                       step="0.1"
                                       min="0"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('area')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bedrooms and Bathrooms -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                                <input type="number" 
                                       id="bedrooms" 
                                       name="bedrooms" 
                                       value="{{ old('bedrooms', $property->bedrooms) }}" 
                                       min="0"
                                       max="10"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('bedrooms')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                                <input type="number" 
                                       id="bathrooms" 
                                       name="bathrooms" 
                                       value="{{ old('bathrooms', $property->bathrooms) }}" 
                                       min="0"
                                       max="10"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('bathrooms')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- External Link -->
                        <div>
                            <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2">External Link (Optional)</label>
                            <input type="url" 
                                   id="external_link" 
                                   name="external_link" 
                                   value="{{ old('external_link', $property->external_link) }}" 
                                   placeholder="https://example.com/property-details"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('external_link')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Images -->
                        @if($property->images && count($property->images) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($property->images as $image)
                                        <div class="relative group border border-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Property image" class="w-full h-32 object-cover">
                                            
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
                            <p class="mt-1 text-sm text-gray-500">Select up to 10 images. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per image.</p>
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
                                       {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Featured Property
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="is_active" 
                                       name="is_active" 
                                       type="checkbox" 
                                       value="1"
                                       {{ old('is_active', $property->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active (visible to public)
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Featured properties appear prominently on the homepage and search results.</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('developer.properties.index') }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>