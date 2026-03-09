<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class InstalledMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!File::exists(storage_path('app/installed'))) {
            return response()->json(['message' => 'Application not installed.'], 400);
        }

        return $next($request);
    }
}