<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->namespace('User\Auth')->name('user.')->group(function () {
    // User Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('login', 'loginForm')->name('login.form');
        Route::post('login', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('guest')->middleware('auth')->name('logout');
    });

    // User Registration Process
    Route::controller('RegisterController')->group(function(){
        Route::get('register', 'registerForm')->middleware('register.status')->name('register');
        Route::post('register', 'register')->middleware('register.status');
        Route::post('check-user', 'checkUser')->name('check.user');
    });

    // Forgot Password
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function() {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Reset Password
    Route::controller('ResetPasswordController')->prefix('password/reset')->name('password.')->group(function() {
        Route::get('form/{token}', 'resetForm')->name('reset.form');
        Route::post('/', 'resetPassword')->name('reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::namespace('User')->group(function () {
        // Authorization
        Route::controller('AuthorizationController')->group(function() {
            Route::get('authorization', 'authorizeForm')->name('authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
        });

        // User Operation
        Route::middleware('authorize.status')->group(function () {
            Route::controller('UserController')->group(function() {
                // KYC Dashboard
                Route::get('dashboard', 'home')->name('home');
    
                // KYC Check
                Route::prefix('kyc')->name('kyc.')->group(function () {
                    Route::get('data', 'kycData')->name('data');
                    Route::get('form', 'kycForm')->name('form');
                    Route::post('form', 'kycSubmit');
                });
    
                // Profile Update
                Route::get('profile/update', 'profile')->name('profile');
                Route::post('profile/update', 'profileUpdate');

                // Profile Image Update
                Route::post('profile-image/upload', 'profileImageUpload')->name('profile.image.upload');

                // Cover Image Update
                Route::post('cover/image', 'coverImageUpload')->name('cover.image.upload');

                // Follow
                Route::get('follow', 'follow')->name('follow');

                // Like
                Route::get('asset/like', 'like')->name('asset.like');

                // Password Change
                Route::get('change/password', 'password')->name('change.password');
                Route::post('change/password', 'passwordChange');
    
                // 2 Factor Authenticator
                Route::prefix('twofactor')->name('twofactor.')->group(function () {
                    Route::get('/', 'show2faForm')->name('form');
                    Route::post('enable', 'enable2fa')->name('enable');
                    Route::post('disable', 'disable2fa')->name('disable');
                });

                // Report
                Route::get('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');
                Route::get('payments/history', 'paymentHistory')->name('payment.history');
                Route::get('referral/log', 'referralLog')->name('referral.log');
                Route::get('referral/link', 'referralLink')->name('referral.link');

                Route::middleware('author.status')->name('author.')->group(function() {
                    Route::get('donations', 'donations')->name('donations');
                    Route::get('earnings', 'earnings')->name('earnings');
                    Route::get('dashboard', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'index'])->name('dashboard');
                    Route::get('dashboard/data', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'getData'])->name('dashboard.data');
                    Route::get('dashboard/content', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'getContentByType'])->name('dashboard.content');
                    Route::delete('dashboard/content/{id}', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'deleteContent'])->name('dashboard.content.delete');
                    Route::get('dashboard/content/file/{id}', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'getFileUrl'])->name('dashboard.content.file');
                });
    
                // File Download
                Route::get('file-download','fileDownload')->name('file.download');
            });

            // Social Profile Management
            Route::controller('SocialProfileController')->prefix('social')->name('social.')->group(function() {
                Route::get('profile', 'profile')->name('profile');
                Route::post('store/{id?}', 'store')->name('store');
                Route::post('delete/{id}', 'delete')->name('delete');
                Route::post('status/{id}', 'status')->name('status');
            });

            // Collection Management
            Route::controller('CollectionController')->prefix('collection')->name('collection.')->group(function() {
                Route::get('index', 'index')->name('index');
                Route::post('store/{id?}', 'store')->name('store');
                Route::post('delete/{id}', 'delete')->name('delete');
                Route::post('add', 'addCollection')->name('add');
                Route::post('add-image', 'addImage')->name('add.image');
                Route::get('modal-view', 'modalView')->name('modal.view');
            });

            // Author Management
            Route::controller('AuthorController')->prefix('author')->name('author.')->group(function() {
                Route::get('form', 'form')->name('form');
                Route::post('store', 'store')->name('store');
                Route::get('info', 'info')->name('info');
                Route::get('manual/download', 'manualDownload')->name('manual.download');
                Route::post('profile-image/update', 'profileImageUpdate')->name('profile.image.update');
                Route::post('cover-image/update', 'coverImageUpdate')->name('cover.image.update');
            });

            // Image Management
            Route::middleware('author.status')->group(function() {
                Route::controller('ImageController')->prefix('asset')->name('asset.')->group(function() {
                    Route::get('upload', 'add')->name('add');
                    Route::get('update/{id}', 'update')->name('update');
                    Route::post('store/{id?}', 'store')->name('store');
                    Route::get('index', 'index')->name('index');
                    Route::get('pending', 'pending')->name('pending');
                    Route::get('approved', 'approved')->name('approved');
                    Route::get('rejected', 'rejected')->name('rejected');
                    Route::get('download/{id}', 'download')->name('download');
                });
            });

            Route::controller('DownloadController')->prefix('download')->name('download.')->group(function() {
                Route::get('file/{id}', 'download')->name('file');
                Route::get('index', 'index')->name('index');
            });

            // Plan Management
            Route::controller('PlanController')->prefix('plan')->name('plan.')->group(function() {
                Route::post('purchase/{id}', 'purchase')->name('purchase');
                Route::get('exist-check', 'existingPlanCheck')->name('exist.check');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw.')->group(function(){
                Route::middleware('kyc.status')->group(function() {
                    Route::get('/', 'methods')->name('methods');
                    Route::post('/', 'store');
                    Route::get('preview', 'preview')->name('preview');
                    Route::post('preview', 'submit');
                });

                Route::get('index', 'index')->name('index');
            });
        });
    });

    // Deposit
    Route::middleware('authorize.status')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function(){
        Route::any('/payment', 'payment')->name('payment');
        Route::any('/', 'deposit')->name('index');
        Route::post('insert', 'depositInsert')->name('insert');
        Route::get('confirm', 'depositConfirm')->name('confirm');
        Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
        Route::post('manual', 'manualDepositUpdate')->name('manual.update');
    });
});

// Gallery Routes
Route::middleware(['auth', 'author.status'])->group(function () {
    Route::get('dashboard/gallery', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'getGalleryContent'])->name('user.author.dashboard.gallery');
    Route::get('dashboard/gallery/filters', [\App\Http\Controllers\User\AuthorEarningsDashboardController::class, 'getGalleryFilters'])->name('user.author.dashboard.gallery.filters');
});