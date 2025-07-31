<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'status',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'total_leads',
        'rate_per_lead',
        'lead_breakdown',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'paid_at',
        'payment_data',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'rate_per_lead' => 'decimal:2',
            'lead_breakdown' => 'array',
            'paid_at' => 'datetime',
            'payment_data' => 'array',
            'total_leads' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('invoice_date', now()->month)
                    ->whereYear('invoice_date', now()->year);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereMonth('invoice_date', now()->subMonth()->month)
                    ->whereYear('invoice_date', now()->subMonth()->year);
    }

    public function getFormattedTotalAttribute(): string
    {
        return '£' . number_format($this->total_amount, 2);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'paid' => 'green',
            'pending' => 'yellow',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function isDue(): bool
    {
        return $this->due_date->isPast() && $this->status === 'pending';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(array $paymentData = []): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_data' => $paymentData,
        ]);

        $this->user->update([
            'last_payment_date' => now(),
        ]);

        $this->reactivateUserListings();
    }

    public function markAsOverdue(): void
    {
        $this->update(['status' => 'overdue']);
        $this->deactivateUserListings();
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    protected function reactivateUserListings(): void
    {
        $this->user->properties()->where('is_active', false)->whereNull('archived_at')->each(function ($property) {
            $property->reactivate();
        });

        $this->user->services()->where('is_active', false)->whereNull('archived_at')->each(function ($service) {
            $service->reactivate();
        });
    }

    protected function deactivateUserListings(): void
    {
        $this->user->properties()->where('is_active', true)->each(function ($property) {
            $property->deactivate();
        });

        $this->user->services()->where('is_active', true)->each(function ($service) {
            $service->deactivate();
        });
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = now()->year;
        $month = now()->format('m');
        
        $lastInvoice = static::whereYear('created_at', $year)
                           ->whereMonth('created_at', now()->month)
                           ->orderBy('id', 'desc')
                           ->first();

        $sequence = $lastInvoice ? 
            (int) substr($lastInvoice->invoice_number, -4) + 1 : 1;

        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $sequence);
    }
}