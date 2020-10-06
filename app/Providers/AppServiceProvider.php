<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        if (App::environment('local') && env('LOG_QUERY') == 'true') {
            Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
                // filter oauth ones
                if (!str_contains($query->sql, 'oauth')) {
                    Log::debug($query->sql . ' - ' . serialize($query->bindings));
                }
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format(config('api.date_format'));
        });
    }
}
