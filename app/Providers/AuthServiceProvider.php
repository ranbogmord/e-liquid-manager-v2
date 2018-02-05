<?php

namespace App\Providers;

use App\Flavour;
use App\Liquid;
use App\Policies\FlavourPolicy;
use App\Policies\LiquidPolicy;
use App\Policies\VendorPolicy;
use App\Vendor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Flavour::class => FlavourPolicy::class,
        Liquid::class => LiquidPolicy::class,
        Vendor::class => VendorPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
