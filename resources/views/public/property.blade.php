<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $property->title }} - {{ config('app.name', 'Laravel') }}</title>

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
                </div>
            </div>
        </nav>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="flex mb-8" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Home</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <a href="{{ route('properties') }}" class="text-gray-700 hover:text-gray-900">Properties</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <span class="text-gray-500">{{ $property->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Property Header -->
                        <div class="mb-8">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
                                    <p class="text-gray-600 mb-2">{{ $property->location }}</p>
                                    <p class="text-3xl font-bold text-indigo-600">{{ $property->formatted_price }}</p>
                                </div>
                                @if($property->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Featured Property
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2">
                                <!-- Description -->
                                <div class="mb-8">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                                    <div class="prose max-w-none">
                                        {!! nl2br(e($property->description)) !!}
                                    </div>
                                </div>

                                <!-- Property Features -->
                                @if($property->bedrooms || $property->bathrooms || $property->area)
                                <div class="mb-8">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Features</h2>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @if($property->bedrooms)
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <p class="text-2xl font-bold text-gray-900">{{ $property->bedrooms }}</p>
                                            <p class="text-sm text-gray-600">Bedroom{{ $property->bedrooms != 1 ? 's' : '' }}</p>
                                        </div>
                                        @endif
                                        @if($property->bathrooms)
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <p class="text-2xl font-bold text-gray-900">{{ $property->bathrooms }}</p>
                                            <p class="text-sm text-gray-600">Bathroom{{ $property->bathrooms != 1 ? 's' : '' }}</p>
                                        </div>
                                        @endif
                                        @if($property->area)
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <p class="text-2xl font-bold text-gray-900">{{ $property->area }}</p>
                                            <p class="text-sm text-gray-600">sq m</p>
                                        </div>
                                        @endif
                                        @if($property->property_type)
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <p class="text-lg font-semibold text-gray-900">{{ $property->property_type }}</p>
                                            <p class="text-sm text-gray-600">Property Type</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Contact Card -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 rounded-lg p-6 sticky top-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Interested in this property?</h3>
                                    
                                    <div class="mb-6">
                                        <p class="text-sm text-gray-600 mb-2">Developer:</p>
                                        <p class="font-medium text-gray-900">{{ $property->user->name }}</p>
                                        @if($property->user->company)
                                            <p class="text-sm text-gray-600">{{ $property->user->company }}</p>
                                        @endif
                                    </div>

                                    <!-- Contact Buttons -->
                                    <div class="space-y-3">
                                        @if($property->user->phone)
                                        <button onclick="trackLead('phone')" 
                                                class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-medium">
                                            📞 Call: {{ $property->user->phone }}
                                        </button>
                                        @endif
                                        
                                        @if($property->user->email)
                                        <button onclick="trackLead('email')" 
                                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-medium">
                                            ✉️ Email Developer
                                        </button>
                                        @endif
                                        
                                        @if($property->external_link)
                                        <a href="{{ $property->external_link }}" target="_blank" 
                                           onclick="trackLead('external')"
                                           class="block w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium text-center">
                                            🔗 View Full Details
                                        </a>
                                        @endif
                                    </div>

                                    <div class="mt-6 text-xs text-gray-500">
                                        <p>Lead tracking: By contacting this developer, a referral fee may be charged.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Properties -->
                @if($relatedProperties->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Properties</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedProperties as $related)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $related->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $related->location }}</p>
                                <p class="text-lg font-bold text-indigo-600 mb-2">{{ $related->formatted_price }}</p>
                                <a href="{{ route('property.show', $related) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function trackLead(method) {
            fetch("{{ route('lead.track', ['type' => 'property', 'id' => $property->id]) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    contact_method: method
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      console.log('Lead tracked successfully');
                  }
              }).catch(error => {
                  console.error('Error tracking lead:', error);
              });
        }
    </script>
</body>
</html>