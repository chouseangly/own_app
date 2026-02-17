<?php

// app/Events/SendEmailCode.php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendEmailCode
{
    use Dispatchable, SerializesModels;

    /**
     * @param array $data Contains 'email' and 'token'
     */
    public function __construct(public array $data)
    {
    }
}
