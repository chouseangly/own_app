<?php

namespace App\Providers;

use App\Events\SendEmailCode;
use App\Listeners\SendEmailCodeListener;
use Illuminate\Support\ServiceProvider;
// DELETE: use Symfony\Contracts\EventDispatcher\Event;
use Illuminate\Support\Facades\Event; // ADD THIS INSTEAD

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
        Event::listen(
            SendEmailCode::class,
            SendEmailCodeListener::class,
        );
    }
}
