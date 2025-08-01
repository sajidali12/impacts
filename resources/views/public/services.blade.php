<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Services - {{ config('app.name', 'Laravel') }}</title>

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
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                @if($siteSettings && $siteSettings->site_logo)
                                    <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
                                @else
                                    <span class="text-xl font-bold text-gray-800">{{ config('app.name', 'IMPACTS Referral') }}</span>
                                @endif
                            </a>
                        </div>
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Home
                            </a>
                            <a href="{{ route('properties') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Properties
                            </a>
                            <a href="{{ route('services') }}" class="border-indigo-400 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Services
                            </a>
                        </div>
                    </div>
                    <!-- Auth Links -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <div class="ml-3 relative">
                                <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                                <a href="{{ route('logout') }}" class="ml-4 text-red-600 hover:text-red-800"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
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
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Professional Services</h1>
                        
                        <!-- Search and Filters -->
                        <form method="GET" action="{{ route('services') }}" class="mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <input type="text" name="search" placeholder="Search services..." 
                                       value="{{ request('search') }}" 
                                       class="border-gray-300 rounded-md shadow-sm">
                                
                                <select name="service_type" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="">All Service Types</option>
                                    @foreach($serviceTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('service_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <select name="service_area" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="">All Areas</option>
                                    @foreach($serviceAreas as $area)
                                        <option value="{{ $area }}" {{ request('service_area') == $area ? 'selected' : '' }}>
                                            {{ $area }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                    Search
                                </button>
                            </div>
                        </form>

                        <!-- Services Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($services as $service)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
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
                                        
                                        <!-- Featured Badge -->
                                        @if($service->is_featured)
                                            <div class="absolute top-3 right-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Featured
                                                </span>
                                            </div>
                                        @endif
                                        
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
                                                    {{ $service->experience_years }}+ years
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
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
                                            <p class="text-2xl font-bold text-green-600">{{ $service->formatted_pricing }}</p>
                                            @if($service->availability)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $service->availability_display }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($service->description, 120) }}</p>
                                        
                                        @if($service->specializations)
                                        <div class="mb-4 border-t border-gray-100 pt-3">
                                            <p class="text-xs text-gray-500 mb-2">Specializations:</p>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice(explode(',', $service->specializations), 0, 2) as $spec)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ trim($spec) }}
                                                    </span>
                                                @endforeach
                                                @if(count(explode(',', $service->specializations)) > 2)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        +{{ count(explode(',', $service->specializations)) - 2 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('service.show', $service) }}" 
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
                                                {{ $service->view_count }} views
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM5 20a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2h2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No services found</h3>
                                    <p class="mt-1 text-sm text-gray-500">No services match your search criteria.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($services->hasPages())
                            <div class="mt-8">
                                {{ $services->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>