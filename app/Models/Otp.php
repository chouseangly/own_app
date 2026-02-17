<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';
    // Add these lines
    protected $primaryKey = null; 
    public $incrementing = false;

   protected $fillable = [
    'email',      // Match migration
    'token',      // Match migration
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
