<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Lead;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class GenerateInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $users = User::whereIn('role', ['developer', 'service_provider'])
            ->where('is_active', true)
            ->get();

        foreach ($users as $user) {
            $this->generateInvoiceForUser($user);
        }
    }

    private function generateInvoiceForUser(User $user): void
    {
        $uninvoicedLeads = Lead::forUser($user->id)
            ->uninvoiced()
            ->get();

        if ($uninvoicedLeads->isEmpty()) {
            return;
        }

        $rate = $user->getRatePerLead();
        $totalLeads = $uninvoicedLeads->count();
        $subtotal = $totalLeads * $rate;
        $taxRate = (float) Setting::get('tax_rate', 0.20);
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;

        $dueDays = (int) Setting::get('invoice_due_days', 30);
        $invoiceDate = now();
        $dueDate = $invoiceDate->copy()->addDays($dueDays);

        $leadBreakdown = $uninvoicedLeads->groupBy('leadable_type')->map(function ($leads, $type) use ($rate) {
            return [
                'type' => $type === 'App\\Models\\Property' ? 'Properties' : 'Services',
                'count' => $leads->count(),
                'rate' => $rate,
                'total' => $leads->count() * $rate,
                'items' => $leads->map(function ($lead) {
                    return [
                        'id' => $lead->id,
                        'title' => $lead->leadable ? $lead->leadable->title : 'Deleted Item',
                        'date' => $lead->created_at->format('Y-m-d'),
                        'rate' => $lead->rate_charged,
                    ];
                })->toArray(),
            ];
        })->toArray();

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'user_id' => $user->id,
            'status' => 'pending',
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'total_leads' => $totalLeads,
            'rate_per_lead' => $rate,
            'lead_breakdown' => $leadBreakdown,
        ]);

        foreach ($uninvoicedLeads as $lead) {
            $lead->markAsInvoiced($invoice);
        }
    }
}