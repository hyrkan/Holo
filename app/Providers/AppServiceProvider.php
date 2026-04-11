<?php

namespace App\Providers;

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
        if (config('app.env') !== 'local') {
            \URL::forceScheme('https');
        }
        \Illuminate\Support\Facades\Gate::before(function ($user, $capability) {
            return $user->hasRole(\App\Models\Role::ADMIN) ? true : null;
        });

        // Register Brevo Mailer Transport
        \Illuminate\Support\Facades\Mail::extend('brevo', function (array $config) {
            return new \Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport(
                $config['key']
            );
        });
    }
}
