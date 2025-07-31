<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'company',
        'bio',
        'phone',
        'website',
        'custom_rate_per_lead',
        'last_payment_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_payment_date' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'custom_rate_per_lead' => 'decimal:2',
        ];
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function weeklyAnalytics(): HasMany
    {
        return $this->hasMany(WeeklyAnalytic::class);
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isServiceProvider(): bool
    {
        return $this->role === 'service_provider';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    public function getRatePerLead(): float
    {
        return $this->custom_rate_per_lead ?? (float) settings('default_rate_per_lead', 5.00);
    }

    public function getTotalActiveListings(): int
    {
        $properties = $this->properties()->where('is_active', true)->count();
        $services = $this->services()->where('is_active', true)->count();
        return $properties + $services;
    }

    public function hasOverdueInvoices(): bool
    {
        return $this->invoices()
            ->where('status', 'overdue')
            ->exists();
    }
}