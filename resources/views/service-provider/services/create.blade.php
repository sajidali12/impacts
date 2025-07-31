<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Service
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
                    <form method="POST" action="{{ route('service-provider.services.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Service Title *</label>
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
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Service Description *</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
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
                                    <option value="marketing" {{ old('service_type') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="construction" {{ old('service_type') == 'construction' ? 'selected' : '' }}>Construction</option>
                                    <option value="legal" {{ old('service_type') == 'legal' ? 'selected' : '' }}>Legal</option>
                                    <option value="financial" {{ old('service_type') == 'financial' ? 'selected' : '' }}>Financial</option>
                                    <option value="design" {{ old('service_type') == 'design' ? 'selected' : '' }}>Design</option>
                                    <option value="consulting" {{ old('service_type') == 'consulting' ? 'selected' : '' }}>Consulting</option>
                                    <option value="maintenance" {{ old('service_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                       value="{{ old('location') }}" 
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
                                    <option value="hourly" {{ old('pricing_type') == 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                                    <option value="fixed" {{ old('pricing_type') == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                    <option value="project" {{ old('pricing_type') == 'project' ? 'selected' : '' }}>Per Project</option>
                                    <option value="consultation" {{ old('pricing_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
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
                                       value="{{ old('pricing_amount') }}" 
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
                                       value="{{ old('experience_years') }}" 
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
                                    <option value="immediate" {{ old('availability') == 'immediate' ? 'selected' : '' }}>Available Immediately</option>
                                    <option value="within_week" {{ old('availability') == 'within_week' ? 'selected' : '' }}>Within a Week</option>
                                    <option value="within_month" {{ old('availability') == 'within_month' ? 'selected' : '' }}>Within a Month</option>
                                    <option value="flexible" {{ old('availability') == 'flexible' ? 'selected' : '' }}>Flexible</option>
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
                                   value="{{ old('specializations') }}" 
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
                                   value="{{ old('external_link') }}" 
                                   placeholder="https://example.com/portfolio"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('external_link')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Images -->
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Service Images/Portfolio</label>
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
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Featured Service
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
                                Create Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>