<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using:function() {
            Route::namespace('App\Http\Controllers')->group(function () {
                Route::middleware(['web','maintenance'])
                    ->namespace('Gateway')
                    ->prefix('ipn')
                    ->name('ipn.')
                    ->group(base_path('routes/ipn.php'));

                Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                Route::middleware(['web'])
                    ->namespace('Reviewer')
                    ->prefix('reviewer')
                    ->name('reviewer.')
                    ->group(base_path('routes/reviewer.php'));

                Route::middleware(['web','maintenance'])
                    ->prefix('user')
                    ->group(base_path('routes/user.php'));

                Route::middleware('web','maintenance')
                    ->group(base_path('routes/web.php'));
            });

            Route::get('maintenance-mode','App\Http\Controllers\WebsiteController@maintenance')->name('maintenance');
        }

    )
    ->withMiddleware(function (Middleware $middleware) {
        // Set middleware priority to ensure TrustProxies runs first
        $middleware->priority([
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LanguageMiddleware::class,
        ]);

        $middleware->group('web', [
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LanguageMiddleware::class,
        ]);

        $middleware->alias([
            'auth'             => \App\Http\Middleware\Authenticate::class,
            'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'     => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'              => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive'     => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed'           => \App\Http\Middleware\ValidateSignature::class,
            'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            'admin'            => \App\Http\Middleware\RedirectIfNotAdmin::class,
            'admin.guest'      => \App\Http\Middleware\RedirectIfAdmin::class,

            'reviewer'            => \App\Http\Middleware\RedirectIfNotReviewer::class,
            'reviewer.guest'      => \App\Http\Middleware\RedirectIfReviewer::class,
            'reviewer.status'     => \App\Http\Middleware\ReviewerStatus::class,
            'reviewer.permission' => \App\Http\Middleware\ReviewerActionPermission::class,

            'demo'             => \App\Http\Middleware\Demo::class,
            'kyc.status'       => \App\Http\Middleware\KycCheck::class,
            'maintenance'      => \App\Http\Middleware\MaintenanceMode::class,
            'register.status'  => \App\Http\Middleware\AllowRegistration::class,
            'authorize.status' => \App\Http\Middleware\AuthorizationStatus::class,

            'author.status'  => \App\Http\Middleware\EnsureAuthorApproved::class,
        ]);

        $middleware->validateCsrfTokens(
            except: ['user/deposit', 'ipn*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
