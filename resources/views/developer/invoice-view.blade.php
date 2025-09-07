<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invoice #{{ $invoice->invoice_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('developer.invoices.download', $invoice) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Download PDF
                </a>
                <a href="{{ route('developer.invoices') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Back to Invoices
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Invoice Header -->
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Invoice</h1>
                            <p class="text-lg text-gray-600">#{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Invoice Date</p>
                            <p class="text-lg font-semibold">{{ $invoice->invoice_date->format('F j, Y') }}</p>
                            <p class="text-sm text-gray-600 mt-2">Due Date</p>
                            <p class="text-lg font-semibold">{{ $invoice->due_date->format('F j, Y') }}</p>
                        </div>
                    </div>

                    <!-- Invoice Status -->
                    <div class="mb-6">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'overdue' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>

                    <!-- Billing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bill To</h3>
                            <div class="text-gray-700">
                                <p class="font-medium">{{ $invoice->user->name }}</p>
                                <p>{{ $invoice->user->email }}</p>
                                @if($invoice->user->company)
                                    <p>{{ $invoice->user->company }}</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">From</h3>
                            <div class="text-gray-700">
                                <p class="font-medium">Impact Referral</p>
                                <p>Referral Platform</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lead Breakdown -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lead Breakdown</h3>
                        @if($invoice->lead_breakdown)
                            @foreach($invoice->lead_breakdown as $breakdown)
                                <div class="mb-6 border rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-medium text-gray-900">{{ $breakdown['type'] }}</h4>
                                        <div class="text-right">
                                            <span class="text-sm text-gray-600">{{ $breakdown['count'] }} leads × £{{ number_format($breakdown['rate'], 2) }}</span>
                                            <p class="font-semibold">£{{ number_format($breakdown['total'], 2) }}</p>
                                        </div>
                                    </div>
                                    @if(isset($breakdown['items']) && count($breakdown['items']) > 0)
                                        <div class="border-t pt-3">
                                            <table class="min-w-full text-sm">
                                                <thead>
                                                    <tr class="border-b text-left">
                                                        <th class="py-2 text-gray-600">Lead ID</th>
                                                        <th class="py-2 text-gray-600">Item</th>
                                                        <th class="py-2 text-gray-600">Date</th>
                                                        <th class="py-2 text-gray-600 text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($breakdown['items'] as $item)
                                                        <tr class="border-b">
                                                            <td class="py-2">{{ $item['id'] }}</td>
                                                            <td class="py-2">{{ $item['title'] }}</td>
                                                            <td class="py-2">{{ $item['date'] }}</td>
                                                            <td class="py-2 text-right">£{{ number_format($item['rate'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Invoice Totals -->
                    <div class="border-t pt-6">
                        <div class="flex justify-end">
                            <div class="w-64">
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">£{{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Tax:</span>
                                    <span class="font-medium">£{{ number_format($invoice->tax_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-t font-bold text-lg">
                                    <span>Total:</span>
                                    <span>£{{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Actions -->
                    @if($invoice->status === 'pending')
                        <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-medium text-yellow-800">Payment Required</h3>
                                    <p class="text-sm text-yellow-700">This invoice is pending payment. Due by {{ $invoice->due_date->format('F j, Y') }}.</p>
                                </div>
                                <form method="POST" action="{{ route('developer.invoices.pay', $invoice) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        Pay Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($invoice->status === 'paid')
                        <div class="mt-8 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-medium text-green-800">Payment Received</h3>
                                    <p class="text-sm text-green-700">This invoice has been paid.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>