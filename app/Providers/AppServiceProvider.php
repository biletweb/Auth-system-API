<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable) {
            $securityCode = random_int(100000, 999999);
            $user = User::find($notifiable->id);
            $user->security_code = $securityCode;
            $user->save();
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
