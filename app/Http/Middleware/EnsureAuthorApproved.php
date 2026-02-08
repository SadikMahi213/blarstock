<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['error', 'Approved authors are allowed only'];
            return to_route('user.author.dashboard')->withToasts($toast);
        }

        return $next($request);
    }
}
