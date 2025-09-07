<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Payment Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Payment Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Today's Payments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Today's Payments</p>
                                <p class="text-2xl font-semibold text-gray-900">£{{ number_format($stats['total_payments_today'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Payments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">This Month</p>
                                <p class="text-2xl font-semibold text-gray-900">£{{ number_format($stats['total_payments_this_month'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Amount -->
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
                                <p class="text-sm font-medium text-gray-500">Pending ({{ $stats['total_invoices_pending'] }})</p>
                                <p class="text-2xl font-semibold text-gray-900">£{{ number_format($stats['pending_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Amount -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-2-9a1 1 0 000 2v4a1 1 0 102 0V7a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Overdue ({{ $stats['total_invoices_overdue'] }})</p>
                                <p class="text-2xl font-semibold text-gray-900">£{{ number_format($stats['overdue_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Trends Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Trends (Last 7 Days)</h3>
                    <div class="h-64">
                        <canvas id="paymentChart" class="w-full h-full"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-7 gap-2 text-sm text-center">
                        @foreach($paymentTrends as $trend)
                            <div class="p-2 bg-gray-50 rounded">
                                <div class="font-medium">{{ $trend['day'] }}</div>
                                <div class="text-green-600">£{{ number_format($trend['amount'], 0) }}</div>
                                <div class="text-gray-500">{{ $trend['count'] }} payments</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Payments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Payments</h3>
                        <div class="space-y-4">
                            @forelse($recentPayments as $payment)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                            <div class="text-sm text-gray-500">Invoice #{{ $payment->invoice_number }}</div>
                                            <div class="text-xs text-gray-400">{{ $payment->paid_at->format('M j, Y g:i A') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">£{{ number_format($payment->total_amount, 2) }}</div>
                                        <div class="text-xs text-green-600">Paid</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No recent payments</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Payers This Month -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Payers This Month</h3>
                        <div class="space-y-4">
                            @forelse($topPayers as $index => $payer)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">#{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $payer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst($payer->role) }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">£{{ number_format($payer->invoices_sum_total_amount, 2) }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No payments this month</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending and Overdue Invoices -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Pending Invoices -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Invoices ({{ $pendingInvoices->count() }})</h3>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @forelse($pendingInvoices as $invoice)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->user->name }}</div>
                                        <div class="text-sm text-gray-500">Invoice #{{ $invoice->invoice_number }}</div>
                                        <div class="text-xs text-gray-400">Due: {{ $invoice->due_date->format('M j, Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">£{{ number_format($invoice->total_amount, 2) }}</div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No pending invoices</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Overdue Invoices ({{ $overdueInvoices->count() }})</h3>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @forelse($overdueInvoices as $invoice)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->user->name }}</div>
                                        <div class="text-sm text-gray-500">Invoice #{{ $invoice->invoice_number }}</div>
                                        <div class="text-xs text-red-500">Overdue: {{ $invoice->due_date->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">£{{ number_format($invoice->total_amount, 2) }}</div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Overdue
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No overdue invoices</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $stats['total_invoices_paid'] }}</div>
                            <div class="text-sm text-gray-500">Total Invoices Paid</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $stats['average_payment_time'] }}</div>
                            <div class="text-sm text-gray-500">Average Days to Pay</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">
                                {{ $stats['total_invoices_paid'] > 0 ? round(($stats['total_invoices_paid'] / ($stats['total_invoices_paid'] + $stats['total_invoices_pending'] + $stats['total_invoices_overdue'])) * 100, 1) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-500">Payment Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json(array_column($paymentTrends, 'day')),
                datasets: [{
                    label: 'Daily Payments (£)',
                    data: @json(array_column($paymentTrends, 'amount')),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '£' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Payments: £' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>