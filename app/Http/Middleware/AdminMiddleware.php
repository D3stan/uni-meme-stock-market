<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Verify user authentication and admin role before allowing access.
     * 
     * Redirects unauthenticated users to login page, and non-admin users to market page.
     * Only allows the request to proceed if the user is both authenticated and has admin privileges.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect('/login')->with('error', 'Effettua il login.');
        }

        if (! Auth::user()->isAdmin()) {
            return redirect('/market')->with('error', 'Non hai i permessi necessari.');
        }

        return $next($request);
    }
}
