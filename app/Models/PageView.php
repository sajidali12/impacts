<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'ip_address',
        'user_agent',
        'referrer',
        'session_data',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'session_data' => 'array',
            'viewed_at' => 'datetime',
        ];
    }

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeToday($query)
    {
        return $query->whereDate('viewed_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('viewed_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('viewed_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('viewed_at', [$startDate, $endDate]);
    }

    public function scopeUniqueVisitors($query)
    {
        return $query->distinct('ip_address');
    }

    public static function track($viewable, string $ipAddress, ?string $userAgent = null, ?string $referrer = null, array $sessionData = []): void
    {
        static::create([
            'viewable_type' => get_class($viewable),
            'viewable_id' => $viewable->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referrer' => $referrer,
            'session_data' => $sessionData,
            'viewed_at' => now(),
        ]);

        $viewable->increment('view_count');
    }
}