<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            color: #333;
        }
        .header { 
            margin-bottom: 30px; 
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        .invoice-title { 
            font-size: 32px; 
            font-weight: bold; 
            color: #1f2937;
        }
        .invoice-number { 
            font-size: 18px; 
            color: #6b7280; 
            margin-top: 5px;
        }
        .billing-section { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 30px;
        }
        .billing-info { 
            width: 45%;
        }
        .billing-title { 
            font-size: 16px; 
            font-weight: bold; 
            margin-bottom: 10px; 
            color: #1f2937;
        }
        .status { 
            display: inline-block; 
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: bold; 
            margin-bottom: 20px;
        }
        .status.pending { background-color: #fef3c7; color: #d97706; }
        .status.paid { background-color: #d1fae5; color: #065f46; }
        .status.overdue { background-color: #fee2e2; color: #dc2626; }
        .breakdown-section { 
            margin-bottom: 30px;
        }
        .breakdown-title { 
            font-size: 18px; 
            font-weight: bold; 
            margin-bottom: 15px; 
            color: #1f2937;
        }
        .breakdown-item { 
            border: 1px solid #e5e7eb; 
            border-radius: 8px; 
            padding: 15px; 
            margin-bottom: 15px;
        }
        .breakdown-header { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 10px;
        }
        .breakdown-type { 
            font-weight: bold; 
            color: #1f2937;
        }
        .breakdown-amount { 
            font-weight: bold; 
            color: #1f2937;
        }
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        .items-table th, .items-table td { 
            border: 1px solid #e5e7eb; 
            padding: 8px; 
            text-align: left; 
            font-size: 12px;
        }
        .items-table th { 
            background-color: #f9fafb; 
            font-weight: bold;
        }
        .items-table td:last-child { 
            text-align: right;
        }
        .totals-section { 
            border-top: 2px solid #e5e7eb; 
            padding-top: 20px; 
            text-align: right;
        }
        .total-line { 
            display: flex; 
            justify-content: flex-end; 
            margin-bottom: 8px;
        }
        .total-label { 
            width: 120px; 
            text-align: right; 
            margin-right: 20px;
        }
        .total-amount { 
            width: 100px; 
            text-align: right; 
            font-weight: bold;
        }
        .grand-total { 
            font-size: 18px; 
            font-weight: bold; 
            border-top: 1px solid #e5e7eb; 
            padding-top: 10px; 
            margin-top: 10px;
        }
        .dates { 
            text-align: right; 
            margin-bottom: 20px;
        }
        .date-item { 
            margin-bottom: 10px;
        }
        .date-label { 
            font-size: 12px; 
            color: #6b7280;
        }
        .date-value { 
            font-size: 16px; 
            font-weight: bold; 
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div class="invoice-title">Invoice</div>
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            </div>
            <div class="dates">
                <div class="date-item">
                    <div class="date-label">Invoice Date</div>
                    <div class="date-value">{{ $invoice->invoice_date->format('F j, Y') }}</div>
                </div>
                <div class="date-item">
                    <div class="date-label">Due Date</div>
                    <div class="date-value">{{ $invoice->due_date->format('F j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="status {{ $invoice->status }}">
        {{ ucfirst($invoice->status) }}
    </div>

    <div class="billing-section">
        <div class="billing-info">
            <div class="billing-title">Bill To</div>
            <div>{{ $invoice->user->name }}</div>
            <div>{{ $invoice->user->email }}</div>
            @if($invoice->user->company)
                <div>{{ $invoice->user->company }}</div>
            @endif
        </div>
        <div class="billing-info">
            <div class="billing-title">From</div>
            <div>Impact Referral</div>
            <div>Referral Platform</div>
        </div>
    </div>

    <div class="breakdown-section">
        <div class="breakdown-title">Lead Breakdown</div>
        @if($invoice->lead_breakdown)
            @foreach($invoice->lead_breakdown as $breakdown)
                <div class="breakdown-item">
                    <div class="breakdown-header">
                        <div class="breakdown-type">{{ $breakdown['type'] }}</div>
                        <div class="breakdown-amount">
                            {{ $breakdown['count'] }} leads × £{{ number_format($breakdown['rate'], 2) }} = £{{ number_format($breakdown['total'], 2) }}
                        </div>
                    </div>
                    @if(isset($breakdown['items']) && count($breakdown['items']) > 0)
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Lead ID</th>
                                    <th>Item</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($breakdown['items'] as $item)
                                    <tr>
                                        <td>{{ $item['id'] }}</td>
                                        <td>{{ $item['title'] }}</td>
                                        <td>{{ $item['date'] }}</td>
                                        <td>£{{ number_format($item['rate'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <div class="totals-section">
        <div class="total-line">
            <div class="total-label">Subtotal:</div>
            <div class="total-amount">£{{ number_format($invoice->subtotal, 2) }}</div>
        </div>
        <div class="total-line">
            <div class="total-label">Tax:</div>
            <div class="total-amount">£{{ number_format($invoice->tax_amount, 2) }}</div>
        </div>
        <div class="total-line grand-total">
            <div class="total-label">Total:</div>
            <div class="total-amount">£{{ number_format($invoice->total_amount, 2) }}</div>
        </div>
    </div>
</body>
</html>