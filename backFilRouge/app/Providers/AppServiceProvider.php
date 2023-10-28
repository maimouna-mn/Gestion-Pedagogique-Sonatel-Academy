<?php

namespace App\Providers;

use App\Events\SessionEnCours;
use App\Listeners\SessionEnCoursListener;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Foundation\Support\Providers\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $listen = [
        SessionEnCours::class => [
            SessionEnCoursListener::class,
        ],
    ];
    
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    // public function boot()
    // {
    //     parent::boot();
    // }
}
