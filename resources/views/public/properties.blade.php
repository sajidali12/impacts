<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Properties - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                                {{ config('app.name', 'IMPACTS Referral') }}
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</a>
                            <a href="{{ route('properties') }}" class="border-indigo-400 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Properties</a>
                            <a href="{{ route('services') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Services</a>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                        @else
                            <div class="space-x-4">
                                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Login</a>
                                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Properties</h1>
                        
                        <!-- Search and Filters -->
                        <form method="GET" action="{{ route('properties') }}" class="mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <input type="text" name="search" placeholder="Search properties..." 
                                       value="{{ request('search') }}" 
                                       class="border-gray-300 rounded-md shadow-sm">
                                
                                <select name="location" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                            {{ $location }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <select name="property_type" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="">All Types</option>
                                    @foreach($propertyTypes as $type)
                                        <option value="{{ $type }}" {{ request('property_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                    Search
                                </button>
                            </div>
                        </form>

                        <!-- Properties Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($properties as $property)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <!-- Feature Image -->
                                    <div class="relative h-48 bg-gray-200">
                                        @if($property->images && count($property->images) > 0)
                                            <img src="{{ asset('storage/' . $property->images[0]) }}" 
                                                 alt="{{ $property->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Featured Badge -->
                                        @if($property->is_featured)
                                            <div class="absolute top-3 right-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Featured
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Property Type -->
                                        @if($property->property_type)
                                            <div class="absolute top-3 left-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-90 text-gray-800 shadow-sm">
                                                    {{ $property->property_type }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-6">
                                        <div class="mb-3">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $property->title }}</h3>
                                            @if($property->location)
                                                <p class="text-gray-600 text-sm flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $property->location }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <p class="text-2xl font-bold text-indigo-600 mb-3">{{ $property->formatted_price }}</p>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($property->description, 120) }}</p>
                                        
                                        @if($property->bedrooms || $property->bathrooms || $property->area)
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4 border-t border-gray-100 pt-3">
                                            @if($property->bedrooms)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0"/>
                                                    </svg>
                                                    <span>{{ $property->bedrooms }} bed{{ $property->bedrooms != 1 ? 's' : '' }}</span>
                                                </div>
                                            @endif
                                            @if($property->bathrooms)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M9 7l1-1V4a1 1 0 112 0v2l1 1m2-1V4a1 1 0 112 0v4"/>
                                                    </svg>
                                                    <span>{{ $property->bathrooms }} bath{{ $property->bathrooms != 1 ? 's' : '' }}</span>
                                                </div>
                                            @endif
                                            @if($property->area)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4a1 1 0 011-1h4m10 0h4a1 1 0 011 1v4m0 8v4a1 1 0 01-1 1h-4m-10 0H4a1 1 0 01-1-1v-4"/>
                                                    </svg>
                                                    <span>{{ $property->area }} sqm</span>
                                                </div>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('property.show', $property) }}" 
                                               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Details
                                            </a>
                                            <div class="flex items-center text-xs text-gray-400">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ $property->view_count }} views
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                                    <p class="mt-1 text-sm text-gray-500">No properties match your search criteria.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($properties->hasPages())
                            <div class="mt-8">
                                {{ $properties->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>