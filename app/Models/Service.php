<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'service_type',
        'pricing',
        'pricing_type',
        'service_areas',
        'logo',
        'images',
        'external_url',
        'is_active',
        'is_featured',
        'deactivated_at',
        'archived_at',
        'view_count',
        'lead_count',
    ];

    protected function casts(): array
    {
        return [
            'pricing' => 'decimal:2',
            'service_areas' => 'array',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'deactivated_at' => 'datetime',
            'archived_at' => 'datetime',
            'view_count' => 'integer',
            'lead_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leads(): MorphMany
    {
        return $this->morphMany(Lead::class, 'leadable');
    }

    public function pageViews(): MorphMany
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('archived_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    public function scopeByArea($query, $area)
    {
        return $query->whereJsonContains('service_areas', $area);
    }

    public function getFormattedPricingAttribute(): string
    {
        if (!$this->pricing) {
            return 'Contact for pricing';
        }

        $formatted = '£' . number_format($this->pricing, 2);
        
        return match($this->pricing_type) {
            'hourly' => $formatted . '/hour',
            'consultation' => $formatted . '/consultation',
            default => $formatted,
        };
    }

    public function getServiceTypeDisplayAttribute(): string
    {
        return match($this->service_type) {
            'solicitor' => 'Solicitor',
            'mortgage_advisor' => 'Mortgage Advisor',
            'technical_specialist' => 'Technical Specialist',
            'surveyor' => 'Surveyor',
            'architect' => 'Architect',
            'financial_advisor' => 'Financial Advisor',
            default => ucfirst(str_replace('_', ' ', $this->service_type)),
        };
    }

    public function getMainImageAttribute(): ?string
    {
        if ($this->logo) {
            return $this->logo;
        }
        
        return $this->images && count($this->images) > 0 ? $this->images[0] : null;
    }

    public function isOverdue(): bool
    {
        return $this->user->hasOverdueInvoices() && !$this->is_active;
    }

    public function canBeReactivated(): bool
    {
        return !$this->is_active && is_null($this->archived_at);
    }

    public function deactivate(): void
    {
        $this->update([
            'is_active' => false,
            'deactivated_at' => now(),
        ]);
    }

    public function archive(): void
    {
        $this->update([
            'is_active' => false,
            'archived_at' => now(),
        ]);
    }

    public function reactivate(): void
    {
        $this->update([
            'is_active' => true,
            'deactivated_at' => null,
            'archived_at' => null,
        ]);
    }
}