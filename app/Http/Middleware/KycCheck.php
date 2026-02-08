<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->kc == ManageStatus::UNVERIFIED) {
            $toast[] = ['error', 'You have not been KYC verified yet. Please submit the requested information to complete the process.'];
            return back()->withToasts($toast);
        }

        if ($user->kc == ManageStatus::PENDING) {
            $toast[] = ['warning', 'We\'re in the process of reviewing your KYC verification documents. Please be patient for admin approval.'];
            return back()->withToasts($toast);
        }
        
        return $next($request);
    }
}
