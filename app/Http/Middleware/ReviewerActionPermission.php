<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewerActionPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!bs('reviewer_action_permission')) {
            $toast[] = ['error', 'Reviewers have not permission to action'];
            return to_route('reviewer.dashboard');
        }

        return $next($request);
    }
}
