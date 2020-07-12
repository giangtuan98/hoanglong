<?php

namespace App\Providers;

use App\Policies\AdminPolicy;
use App\Policies\BrandPolicy;
use App\Policies\DistrictPolicy;
use App\Policies\PlacePolicy;
use App\Policies\ProvincePolicy;
use App\Policies\ReportPolicy;
use App\Policies\RolePolicy;
use App\Policies\RoutePolicy;
use App\Policies\TicketPolicy;
use App\Policies\TripPolicy;
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
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('brand', BrandPolicy::class);
        Gate::resource('role', RolePolicy::class);
        Gate::resource('admin', AdminPolicy::class);
        Gate::resource('place', PlacePolicy::class);
        Gate::resource('route', RoutePolicy::class);
        Gate::resource('trip', TripPolicy::class);
        Gate::resource('province', ProvincePolicy::class);
        Gate::resource('district', DistrictPolicy::class);
        Gate::resource('ticket', TicketPolicy::class);
        Gate::resource('report', ReportPolicy::class);
    }
}
