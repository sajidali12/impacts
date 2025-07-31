<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Site Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <p class="text-gray-600">
                    Customize your website's appearance including logo, homepage banner, and branding.
                </p>
            </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-8">
            <!-- Site Logo Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Website Logo</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Upload your company logo that will appear in the navigation and across the site.
                    </p>
                </div>
                
                @if($settings && $settings->site_logo)
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Current logo" class="h-16 w-auto">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Current Logo</p>
                            <p class="text-xs text-gray-500">This logo is currently being used across the website</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Homepage Banner Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Homepage Banner</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Customize the hero section that visitors see when they first arrive at your website.
                    </p>
                </div>
                
                @if($settings)
                <div class="p-6 border-b border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Current Banner Preview</h4>
                    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg overflow-hidden" style="min-height: 300px; {{ $settings->background_image ? 'background-image: url(' . asset('storage/' . $settings->background_image) . '); background-size: cover; background-position: center;' : '' }}">
                        @if($settings->background_image)
                            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        @endif
                        
                        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
                            <div class="text-center">
                                <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                                    {{ $settings->title }}
                                </h1>
                                @if($settings->subtitle)
                                    <p class="mt-3 max-w-md mx-auto text-base text-white text-opacity-90 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                                        {{ $settings->subtitle }}
                                    </p>
                                @endif
                                @if($settings->description)
                                    <p class="mt-4 max-w-2xl mx-auto text-base text-white text-opacity-80 md:text-lg">
                                        {{ $settings->description }}
                                    </p>
                                @endif
                                <div class="mt-8 max-w-md mx-auto sm:flex sm:justify-center md:mt-12">
                                    <div class="rounded-md shadow">
                                        <span class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white md:py-4 md:text-lg md:px-10">
                                            {{ $settings->cta_text }}
                                        </span>
                                    </div>
                                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                                        <span class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-80 md:py-4 md:text-lg md:px-10">
                                            {{ $settings->secondary_cta_text }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Settings Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Update Site Settings</h3>
                </div>

                <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Logo Upload Section -->
                    <div class="border-b border-gray-200 pb-8">
                        <h4 class="text-base font-medium text-gray-900 mb-4">Site Logo</h4>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                @if($settings && $settings->site_logo)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Current logo" class="mx-auto h-20 w-auto">
                                        <p class="mt-2 text-sm text-gray-500">Current logo</p>
                                    </div>
                                @endif
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="site_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a new logo</span>
                                        <input id="site_logo" name="site_logo" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                        @error('site_logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Banner Content Section -->
                    <div class="space-y-6">
                        <h4 class="text-base font-medium text-gray-900">Banner Content</h4>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Title -->
                            <div class="sm:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Banner Title</label>
                                <input type="text" name="title" id="title" 
                                       value="{{ old('title', $settings->title ?? '') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-300 @enderror" 
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subtitle -->
                            <div class="sm:col-span-2">
                                <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                                <textarea name="subtitle" id="subtitle" rows="2" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('subtitle') border-red-300 @enderror" 
                                          required>{{ old('subtitle', $settings->subtitle ?? '') }}</textarea>
                                @error('subtitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $settings->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Background Image -->
                            <div class="sm:col-span-2">
                                <label for="background_image" class="block text-sm font-medium text-gray-700">Background Image</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        @if($settings && $settings->background_image)
                                            <div class="mb-4">
                                                <img src="{{ asset('storage/' . $settings->background_image) }}" alt="Current background" class="mx-auto h-32 w-auto rounded-lg">
                                                <p class="mt-2 text-sm text-gray-500">Current background image</p>
                                            </div>
                                        @endif
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="background_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a background image</span>
                                                <input id="background_image" name="background_image" type="file" accept="image/*" class="sr-only">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                                    </div>
                                </div>
                                @error('background_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Primary CTA -->
                            <div>
                                <label for="cta_text" class="block text-sm font-medium text-gray-700">Primary Button Text</label>
                                <input type="text" name="cta_text" id="cta_text" 
                                       value="{{ old('cta_text', $settings->cta_text ?? 'Explore Properties') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('cta_text') border-red-300 @enderror" 
                                       required>
                                @error('cta_text')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cta_link" class="block text-sm font-medium text-gray-700">Primary Button Link</label>
                                <input type="text" name="cta_link" id="cta_link" 
                                       value="{{ old('cta_link', $settings->cta_link ?? '/properties') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('cta_link') border-red-300 @enderror" 
                                       required>
                                @error('cta_link')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Secondary CTA -->
                            <div>
                                <label for="secondary_cta_text" class="block text-sm font-medium text-gray-700">Secondary Button Text</label>
                                <input type="text" name="secondary_cta_text" id="secondary_cta_text" 
                                       value="{{ old('secondary_cta_text', $settings->secondary_cta_text ?? 'Browse Services') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('secondary_cta_text') border-red-300 @enderror" 
                                       required>
                                @error('secondary_cta_text')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="secondary_cta_link" class="block text-sm font-medium text-gray-700">Secondary Button Link</label>
                                <input type="text" name="secondary_cta_link" id="secondary_cta_link" 
                                       value="{{ old('secondary_cta_link', $settings->secondary_cta_link ?? '/services') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('secondary_cta_link') border-red-300 @enderror" 
                                       required>
                                @error('secondary_cta_link')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="sm:col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', $settings->is_active ?? true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Banner is active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-200 pt-6">
                        <button type="submit" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Site Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>