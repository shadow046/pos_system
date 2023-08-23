<?php

namespace App\Http\Middleware;

use App\Actions\DeviceIsNotVerified;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return DeviceIsNotVerified::run() ? redirect(route('device.verification')) : $next($request);
    }
}
