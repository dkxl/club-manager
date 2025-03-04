<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        //define some gates to limit access to routes

        Gate::define('administer', function (User $user) {
           return $user->hasRole('admin');
        });

        Gate::define('operate', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('staff');
        });

        Gate::define('allow-entry', function (User $user) {
            return $user->hasRole('kiosk');
        });


        // Log sql queries during local development
        if (App::environment('local'))
        {
            DB::listen(function(QueryExecuted $query) {
                Log::debug(
                    $query->toRawSql(),
//                $query->sql,
//                [
//                    'bindings' => $query->bindings,
//                    'time' => $query->time
//                ]
                );
            });
        }

    }

}
