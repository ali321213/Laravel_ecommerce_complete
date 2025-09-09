<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Services\SiteSettingsService;

class AppServiceProvider extends ServiceProvider
{
    /* Register any application services */
    public function register(): void
    {
        // Register SiteSettingsService as a singleton
        $this->app->singleton('site-settings', function () {
            return new SiteSettingsService();
        });
    }

    /* Bootstrap any application services */
    public function boot(): void
    {
        // Custom reset password URL for frontend
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
