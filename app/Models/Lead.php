<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leadable_type',
        'leadable_id',
        'ip_address',
        'user_agent',
        'referrer',
        'session_data',
        'rate_charged',
        'is_invoiced',
        'invoice_id',
    ];

    protected function casts(): array
    {
        return [
            'session_data' => 'array',
            'rate_charged' => 'decimal:2',
            'is_invoiced' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leadable(): MorphTo
    {
        return $this->morphTo();
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopeUninvoiced($query)
    {
        return $query->where('is_invoiced', false);
    }

    public function scopeInvoiced($query)
    {
        return $query->where('is_invoiced', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ]);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function getLeadTypeAttribute(): string
    {
        return $this->leadable_type === Property::class ? 'Property' : 'Service';
    }

    public function getLeadTitleAttribute(): string
    {
        return $this->leadable ? $this->leadable->title : 'Deleted Item';
    }

    public function markAsInvoiced(Invoice $invoice): void
    {
        $this->update([
            'is_invoiced' => true,
            'invoice_id' => $invoice->id,
        ]);
    }
}