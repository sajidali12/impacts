<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Lead Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Settings</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="default_rate_per_lead" class="block text-sm font-medium text-gray-700 mb-2">
                                            Default Rate per Lead (£)
                                        </label>
                                        <input type="number" 
                                               id="default_rate_per_lead"
                                               name="default_rate_per_lead" 
                                               step="0.01"
                                               min="0"
                                               value="{{ old('default_rate_per_lead', $settings['default_rate_per_lead']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Settings</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="invoice_due_days" class="block text-sm font-medium text-gray-700 mb-2">
                                            Invoice Due Days
                                        </label>
                                        <input type="number" 
                                               id="invoice_due_days"
                                               name="invoice_due_days" 
                                               min="1"
                                               max="90"
                                               value="{{ old('invoice_due_days', $settings['invoice_due_days']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- User Management Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">User Management Settings</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="deactivation_grace_days" class="block text-sm font-medium text-gray-700 mb-2">
                                            Deactivation Grace Days
                                        </label>
                                        <input type="number" 
                                               id="deactivation_grace_days"
                                               name="deactivation_grace_days" 
                                               min="1"
                                               max="30"
                                               value="{{ old('deactivation_grace_days', $settings['deactivation_grace_days']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label for="archive_after_weeks" class="block text-sm font-medium text-gray-700 mb-2">
                                            Archive After (Weeks)
                                        </label>
                                        <input type="number" 
                                               id="archive_after_weeks"
                                               name="archive_after_weeks" 
                                               min="1"
                                               max="12"
                                               value="{{ old('archive_after_weeks', $settings['archive_after_weeks']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Company Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Company Name
                                        </label>
                                        <input type="text" 
                                               id="company_name"
                                               name="company_name" 
                                               value="{{ old('company_name', $settings['company_name']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Company Email
                                        </label>
                                        <input type="email" 
                                               id="company_email"
                                               name="company_email" 
                                               value="{{ old('company_email', $settings['company_email']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">
                                            Company Address
                                        </label>
                                        <textarea id="company_address"
                                                  name="company_address" 
                                                  rows="3"
                                                  class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('company_address', $settings['company_address']->value ?? '') }}</textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="vat_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            VAT Number
                                        </label>
                                        <input type="text" 
                                               id="vat_number"
                                               name="vat_number" 
                                               value="{{ old('vat_number', $settings['vat_number']->value ?? '') }}" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Settings</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="enable_email_notifications"
                                               name="enable_email_notifications" 
                                               value="1"
                                               {{ old('enable_email_notifications', $settings['enable_email_notifications']->value ?? false) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="enable_email_notifications" class="ml-2 block text-sm text-gray-900">
                                            Enable Email Notifications
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="enable_weekly_reports"
                                               name="enable_weekly_reports" 
                                               value="1"
                                               {{ old('enable_weekly_reports', $settings['enable_weekly_reports']->value ?? false) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="enable_weekly_reports" class="ml-2 block text-sm text-gray-900">
                                            Enable Weekly Reports
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 font-medium">
                                    Update Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>