<?php

use Illuminate\Support\Facades\Route;

Route::middleware('reviewer.guest')->namespace('Auth')->group(function() {
    // Reviewer login and logout process
    Route::controller('LoginController')->group(function() {
        Route::get('/', 'loginForm')->name('login.form');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('reviewer.guest')->middleware('reviewer')->name('logout');
    });

    // Reviewer Forgot Password and Verification Process
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function() {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Reviewer Reset Password
    Route::controller('ResetPasswordController')->prefix('password')->name('password.')->group(function() {
        Route::get('reset/form/{email}/{code}', 'resetForm')->name('reset.form');
        Route::post('reset', 'resetPassword')->name('reset');
    });
});

Route::middleware(['reviewer', 'reviewer.status'])->group(function() {
    Route::controller('ReviewerController')->group(function() {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate');
        Route::post('password', 'passwordChange')->name('password.update');
    });

    Route::controller('AssetController')->prefix('asset')->name('asset.')->group(function() {
        Route::get('pending', 'pending')->name('pending');
        Route::get('approved', 'approved')->name('approved');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('detail/{id}', 'detail')->name('detail');

        Route::middleware('reviewer.permission')->group(function() {
            Route::post('update', 'update')->name('update');
        });

        Route::get('download/{id}', 'downloadAssetFile')->name('download');
    });
});