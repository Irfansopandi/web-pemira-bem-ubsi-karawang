<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCommittee
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check() || (! Auth::guard('admin')->user()->isCommittee() && ! Auth::guard('admin')->user()->isAdmin())) {
            abort(403, 'Akses khusus Panitia TPS atau Administrator.');
        }

        return $next($request);
    }
}
