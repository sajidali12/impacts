<?php

namespace App\Providers;

use App\Models\Property;
use App\Models\Service;
use App\Models\Invoice;
use App\Policies\PropertyPolicy;
use App\Policies\ServicePolicy;
use App\Policies\InvoicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Property::class => PropertyPolicy::class,
        Service::class => ServicePolicy::class,
        Invoice::class => InvoicePolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
    }
}