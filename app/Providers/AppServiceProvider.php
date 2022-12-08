<?php

namespace App\Providers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Blade, View, Route};
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_TIME, config('app.locale'));
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        view()->composer('layouts.template', function ($view) {
            $notifications = Notification::where('etat', 'Non lue')->groupBy('type')->selectRaw('count(*) as total, type, max(date) as date, route_name')->get();
            // dd($notifications);
            $title = config('titles.' . Route::currentRouteName());
            $view->with(compact('title', 'notifications'));
        });
    }
}