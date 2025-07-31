<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Service Provider Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-green-500 to-teal-600 text-white">
                    <h3 class="text-lg font-medium">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="mt-1 text-green-100">Manage your services and track your client leads.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Services</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_services'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Services</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_services'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Leads This Month</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_leads_this_month'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Conversion Rate</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['conversion_rate'] }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Alerts -->
            @if($stats['pending_invoices'] > 0 || $stats['overdue_invoices'] > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Invoice Attention Required</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        @if($stats['overdue_invoices'] > 0)
                                            <p>You have {{ $stats['overdue_invoices'] }} overdue invoice(s). Please pay immediately to avoid service interruption.</p>
                                        @endif
                                        @if($stats['pending_invoices'] > 0)
                                            <p>You have {{ $stats['pending_invoices'] }} pending invoice(s) awaiting payment.</p>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('service-provider.invoices') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                            View Invoices →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Services -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Your Services</h3>
                            <a href="{{ route('service-provider.services.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">View all →</a>
                        </div>
                        
                        @if($recentServices->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentServices as $service)
                                    <div class="flex items-center justify-between border-b pb-3">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $service->title }}</h4>
                                            <p class="text-xs text-gray-500">{{ $service->service_type_display }}</p>
                                            <p class="text-sm font-semibold text-green-600">{{ $service->formatted_pricing }}</p>
                                        </div>
                                        <div class="text-right text-xs text-gray-500">
                                            <div>{{ $service->view_count }} views</div>
                                            <div>{{ $service->lead_count }} leads</div>
                                            <div class="mt-1">
                                                @if($service->is_active)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM5 20a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2h2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No services yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding your first service.</p>
                                <div class="mt-6">
                                    <a href="{{ route('service-provider.services.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Add Service
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Leads -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Client Inquiries</h3>
                            <a href="{{ route('service-provider.analytics') }}" class="text-sm text-indigo-600 hover:text-indigo-500">View analytics →</a>
                        </div>
                        
                        @if($recentLeads->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentLeads as $lead)
                                    <div class="flex items-center justify-between border-b pb-3">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $lead->lead_title }}</h4>
                                            <p class="text-xs text-gray-500">{{ $lead->lead_type }} • {{ $lead->created_at->format('M j, Y g:i A') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                £{{ number_format($lead->rate_charged, 2) }}
                                            </span>
                                            @if($lead->is_invoiced)
                                                <div class="text-xs text-gray-500 mt-1">Invoiced</div>
                                            @else
                                                <div class="text-xs text-yellow-600 mt-1">Pending</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No leads yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Client inquiries will appear here when people contact you about your services.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Weekly Analytics Chart -->
            @if($weeklyAnalytics->count() > 0)
                <div class="mt-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Weekly Performance</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                @foreach($weeklyAnalytics as $week)
                                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                                        <div class="text-sm text-gray-600">{{ $week->week_start->format('M j') }} - {{ $week->week_end->format('M j') }}</div>
                                        <div class="mt-2">
                                            <div class="text-2xl font-bold text-indigo-600">{{ $week->total_leads }}</div>
                                            <div class="text-xs text-gray-500">leads</div>
                                        </div>
                                        <div class="mt-1">
                                            <div class="text-lg font-semibold text-gray-900">{{ $week->total_views }}</div>
                                            <div class="text-xs text-gray-500">views</div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600">{{ $week->formatted_conversion_rate }} conversion</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('service-provider.services.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Add Service
                            </a>
                            
                            <a href="{{ route('service-provider.services.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Manage Services
                            </a>
                            
                            <a href="{{ route('service-provider.analytics') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                                View Analytics
                            </a>
                            
                            <a href="{{ route('service-provider.invoices') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                View Invoices
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>