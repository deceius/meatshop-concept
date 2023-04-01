<?php

namespace App\Providers;

use App\Models\AccessTier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('user_branch_id', function () {
            $branchId = AccessTier::where('user_id', Auth::user()->id)->first()->branch_id;
            return $branchId;
        });

        $this->app->singleton('user_tier_id', function () {
            $tierId = AccessTier::where('user_id', Auth::user()->id)->first()->tier_id;
            return $tierId;
        });

        $this->app->singleton('admin_access', function () {
            return [1];
        });

        $this->app->singleton('manager_access', function () {
            return [1, 2];
        });

        $this->app->singleton('employee_access', function () {
            return [1, 2, 3];
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
