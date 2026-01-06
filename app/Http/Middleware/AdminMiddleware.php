<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is not authenticated, redirect to the login page URL.
        if (! Auth::check()) {
            return redirect('/login')->with('error', 'Effettua il login.');
        }

        // If the authenticated user is not an admin, redirect to the market page.
        if (! Auth::user()->isAdmin()) {
            return redirect('/market')->with('error', 'Non hai i permessi necessari.');
        }

        return $next($request);
    }
}
