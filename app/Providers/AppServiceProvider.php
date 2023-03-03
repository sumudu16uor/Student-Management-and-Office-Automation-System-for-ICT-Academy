<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'Student' => 'App\Models\Student',
            'Employee' => 'App\Models\Employee',
            'Staff' => 'App\Models\Staff',
            'Teacher' => 'App\Models\Teacher',
            'User' => 'App\Models\User',
        ]);
    }
}
