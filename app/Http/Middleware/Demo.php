<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Demo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')){
            $toast[] = ['warning', 'Modifications are restricted within the confines of this demo for an optimal showcase experience'];

            $toast[] = ['info', 'With select actions intentionally restricted for demonstration clarity'];
            return back()->withToasts($toast);
        }
        return $next($request);
    }
}
