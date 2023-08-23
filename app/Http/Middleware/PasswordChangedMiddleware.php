<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordChangedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return !auth()->user()->has_changed_password
        ? redirect(route('profile.edit').'#password')
        : $next($request);
    }
}
