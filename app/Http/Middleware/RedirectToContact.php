<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectToContact
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
        // Eedirect to contact downf
        if ($request->is('/') || $request->is('user/*') || $request->is('contact/mijaz')) {
            return $next($request);
        }

        // Redirect to the contact dowhf route for all other requests
        return redirect()->route('mijaz.contact');
    }
}
