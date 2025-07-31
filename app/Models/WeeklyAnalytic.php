<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'week_start',
        'week_end',
        'total_views',
        'total_leads',
        'unique_visitors',
        'conversion_rate',
        'detailed_data',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'week_end' => 'date',
            'total_views' => 'integer',
            'total_leads' => 'integer',
            'unique_visitors' => 'integer',
            'conversion_rate' => 'decimal:2',
            'detailed_data' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCurrentWeek($query)
    {
        return $query->where('week_start', now()->startOfWeek()->toDateString());
    }

    public function scopeLastWeek($query)
    {
        return $query->where('week_start', now()->subWeek()->startOfWeek()->toDateString());
    }

    public function getFormattedConversionRateAttribute(): string
    {
        return number_format($this->conversion_rate, 2) . '%';
    }

    public static function generateForUser(User $user, \Carbon\Carbon $weekStart = null): self
    {
        $weekStart = $weekStart ?? now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $pageViews = PageView::whereBetween('viewed_at', [$weekStart, $weekEnd])
            ->whereHasMorph('viewable', [Property::class, Service::class], function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        $leads = Lead::forUser($user->id)
            ->betweenDates($weekStart, $weekEnd);

        $totalViews = $pageViews->count();
        $totalLeads = $leads->count();
        $uniqueVisitors = $pageViews->distinct('ip_address')->count();
        $conversionRate = $totalViews > 0 ? ($totalLeads / $totalViews) * 100 : 0;

        $detailedData = [
            'properties' => [],
            'services' => [],
        ];

        foreach ($user->properties as $property) {
            $propertyViews = PageView::where('viewable_type', Property::class)
                ->where('viewable_id', $property->id)
                ->betweenDates($weekStart, $weekEnd)
                ->count();

            $propertyLeads = Lead::where('leadable_type', Property::class)
                ->where('leadable_id', $property->id)
                ->betweenDates($weekStart, $weekEnd)
                ->count();

            $detailedData['properties'][] = [
                'id' => $property->id,
                'title' => $property->title,
                'views' => $propertyViews,
                'leads' => $propertyLeads,
                'conversion_rate' => $propertyViews > 0 ? ($propertyLeads / $propertyViews) * 100 : 0,
            ];
        }

        foreach ($user->services as $service) {
            $serviceViews = PageView::where('viewable_type', Service::class)
                ->where('viewable_id', $service->id)
                ->betweenDates($weekStart, $weekEnd)
                ->count();

            $serviceLeads = Lead::where('leadable_type', Service::class)
                ->where('leadable_id', $service->id)
                ->betweenDates($weekStart, $weekEnd)
                ->count();

            $detailedData['services'][] = [
                'id' => $service->id,
                'title' => $service->title,
                'views' => $serviceViews,
                'leads' => $serviceLeads,
                'conversion_rate' => $serviceViews > 0 ? ($serviceLeads / $serviceViews) * 100 : 0,
            ];
        }

        return static::updateOrCreate(
            [
                'user_id' => $user->id,
                'week_start' => $weekStart->toDateString(),
            ],
            [
                'week_end' => $weekEnd->toDateString(),
                'total_views' => $totalViews,
                'total_leads' => $totalLeads,
                'unique_visitors' => $uniqueVisitors,
                'conversion_rate' => $conversionRate,
                'detailed_data' => $detailedData,
            ]
        );
    }
}