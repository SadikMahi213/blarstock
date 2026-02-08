<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {   
        if (bs('signup') == ManageStatus::INACTIVE) {
            $toast[] = ['info', 'We are not accepting registrations at the moment'];
            return back()->withToasts($toast);
        }

        return $next($request);
    }
}
