<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Invoices
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Payment Modal -->
            @if (session('payment_intent'))
                <div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 text-center mb-4">Complete Payment</h3>
                            
                            <form id="payment-form">
                                <div id="payment-element" class="mb-4">
                                    <!-- Stripe Elements will create form elements here -->
                                </div>
                                
                                <div class="flex justify-between">
                                    <button type="button" onclick="closePaymentModal()" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                        Cancel
                                    </button>
                                    <button type="submit" id="submit-payment" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                                        <span id="button-text">Pay Now</span>
                                        <div id="spinner" class="hidden inline-block w-4 h-4 ml-2 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    </button>
                                </div>
                            </form>
                            
                            <div id="payment-messages" class="mt-4 text-sm" role="alert"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Invoices</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $invoices->total() }}</p>
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
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $invoices->where('status', 'pending')->count() }}</p>
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
                                <p class="text-sm font-medium text-gray-500">Paid</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $invoices->where('status', 'paid')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Invoices</h3>
                    
                    @if($invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice Number
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoices as $invoice)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">#{{ $invoice->invoice_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $invoice->invoice_date->format('M j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $invoice->due_date->format('M j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                £{{ number_format($invoice->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'paid' => 'bg-green-100 text-green-800',
                                                        'overdue' => 'bg-red-100 text-red-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('service-provider.invoices.view', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                <a href="{{ route('service-provider.invoices.download', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">Download</a>
                                                @if($invoice->status === 'pending')
                                                    <form method="POST" action="{{ route('service-provider.invoices.pay', $invoice) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                            Pay Now
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($invoices->hasPages())
                            <div class="mt-6">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices yet</h3>
                            <p class="mt-1 text-sm text-gray-500">You don't have any invoices yet. Invoices will appear here when generated.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Payment Information</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>• Invoices are generated monthly based on your service leads from the previous month</p>
                            <p>• Payment is due within the specified due date on each invoice</p>
                            <p>• You can pay invoices securely using the "Pay Now" button</p>
                            <p>• Late payments may result in account restrictions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('payment_intent'))
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements({
                clientSecret: '{{ session('payment_intent.client_secret') }}'
            });

            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const submitButton = document.getElementById('submit-payment');
                const buttonText = document.getElementById('button-text');
                const spinner = document.getElementById('spinner');
                
                // Disable the button and show loading state
                submitButton.disabled = true;
                buttonText.textContent = 'Processing...';
                spinner.classList.remove('hidden');

                const {error} = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: '{{ route('service-provider.payment.return') }}',
                    }
                });

                if (error) {
                    // Show error to customer
                    showMessage(error.message);
                    
                    // Re-enable the button
                    submitButton.disabled = false;
                    buttonText.textContent = 'Pay Now';
                    spinner.classList.add('hidden');
                } else {
                    // Payment succeeded, user will be redirected
                    showMessage('Payment successful! Redirecting...', 'success');
                }
            });

            function showMessage(messageText, type = 'error') {
                const messageContainer = document.getElementById('payment-messages');
                messageContainer.textContent = messageText;
                messageContainer.className = `mt-4 text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
            }

            function closePaymentModal() {
                document.getElementById('payment-modal').remove();
            }

            // Auto-close modal on successful payment
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('payment') === 'success') {
                const paymentIntentId = urlParams.get('payment_intent');
                
                // Remove the payment parameters from URL
                window.history.replaceState({}, document.title, window.location.pathname);
                
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative';
                successDiv.innerHTML = '<span class="block sm:inline">Payment completed successfully! Your invoice will be updated shortly.</span>';
                document.querySelector('.max-w-7xl').prepend(successDiv);
                
                // Optionally refresh the page after a short delay to show updated invoice status
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        </script>
    @endif
</x-app-layout>