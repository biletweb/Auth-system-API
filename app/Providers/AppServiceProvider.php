<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable) {
            $securityCode = random_int(100000, 999999);
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->view('emails.verify-email', ['name' => $notifiable->name, 'securityCode' => $securityCode,]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
