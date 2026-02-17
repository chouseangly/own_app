<?php

// app/Listeners/SendEmailCodeListener.php
namespace App\Listeners;

use App\Events\SendEmailCode;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailCodeListener implements ShouldQueue // Using ShouldQueue is recommended for email
{
    public function handle(SendEmailCode $event): void
    {
        Mail::to($event->data['email'])->send(new OtpMail($event->data['token']));
    }
}
