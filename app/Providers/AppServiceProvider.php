<?php

namespace App\Providers;

use App\Models\User;
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
            $verificationCode = random_int(100000, 999999);
            $user = User::find($notifiable->id);
            $user->verification_code = $verificationCode;
            $user->save();

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->view('emails.verify-email', ['name' => $notifiable->name, 'verificationCode' => $verificationCode]);
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
