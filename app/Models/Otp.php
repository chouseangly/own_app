<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    protected $casts = [
        'email' => 'string',
        'token' => 'string',
        'is_verified' => 'string',
        'created_at' => 'datetime'
    ];
    public $timestamps = false;
}
