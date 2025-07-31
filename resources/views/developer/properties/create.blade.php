<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Property
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
                    <form method="POST" action="{{ route('developer.properties.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title *</label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
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
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
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
                                       value="{{ old('price') }}" 
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
                                       value="{{ old('location') }}" 
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
                                    <option value="Apartment" {{ old('property_type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="House" {{ old('property_type') == 'House' ? 'selected' : '' }}>House</option>
                                    <option value="Townhouse" {{ old('property_type') == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                                    <option value="Studio" {{ old('property_type') == 'Studio' ? 'selected' : '' }}>Studio</option>
                                    <option value="Penthouse" {{ old('property_type') == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                                    <option value="Flat" {{ old('property_type') == 'Flat' ? 'selected' : '' }}>Flat</option>
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
                                       value="{{ old('area') }}" 
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
                                       value="{{ old('bedrooms') }}" 
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
                                       value="{{ old('bathrooms') }}" 
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
                                   value="{{ old('external_link') }}" 
                                   placeholder="https://example.com/property-details"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('external_link')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Images -->
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Property Images</label>
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
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Featured Property
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
                                Create Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>