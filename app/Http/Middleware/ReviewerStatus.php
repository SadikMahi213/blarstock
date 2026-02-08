<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewerStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('reviewer')->user()->status != ManageStatus::ACTIVE) {

            auth()->guard('reviewer')->logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();


            $toast[] = ['error', 'Your account is currently inactive'];
            return to_route('reviewer.login')->withToasts($toast);
        }

        return $next($request);
    }
}
