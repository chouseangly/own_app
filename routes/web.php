<?php

use Illuminate\Support\Facades\Route;

// This "catch-all" route ensures that any URL (like /register or /home)
// loads the welcome view where your Vue app is mounted.
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
