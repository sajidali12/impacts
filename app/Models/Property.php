<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'location',
        'property_type',
        'bedrooms',
        'bathrooms',
        'area',
        'images',
        'external_link',
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
            'price' => 'decimal:2',
            'area' => 'decimal:2',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'deactivated_at' => 'datetime',
            'archived_at' => 'datetime',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
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

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeByPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '£' . number_format($this->price, 0);
    }

    public function getMainImageAttribute(): ?string
    {
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