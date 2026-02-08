<?php

use Illuminate\Support\Facades\Route;

Route::middleware('admin.guest')->namespace('Auth')->group(function () {
    // Admin Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'loginForm')->name('login.form');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('admin.guest')->middleware('admin')->name('logout');
    });

    // Admin Forgot Password and Verification Process
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function() {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Admin Reset Password
    Route::controller('ResetPasswordController')->prefix('password')->name('password.')->group(function() {
        Route::get('reset/form/{email}/{code}', 'resetForm')->name('reset.form');
        Route::post('reset', 'resetPassword')->name('reset');
    });
});

// Operations for Admin
Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function() {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate');
        Route::post('password', 'passwordChange')->name('password.update');

        // Notification
        Route::name('system.notification.')->prefix('notification')->group(function() {
            Route::get('all','notificationAll')->name('all');
            Route::get('read/{id}','notificationRead')->name('read');
            Route::post('read-all','notificationReadAll')->name('read.all');
            Route::post('remove/{id}','notificationRemove')->name('remove');
            Route::post('remove-all','notificationRemoveAll')->name('remove.all');
        });

        // Transactions
        Route::get('transaction', 'transaction')->name('transaction.index');

        // File Download
        Route::get('file-download', 'fileDownload')->name('file.download');

        // Earning Record
        Route::get('earning-record', 'earningRecord')->name('earning.record');

        // Download Record
        Route::get('download-record', 'downloadRecord')->name('download.record');

        // Collection Management
        Route::get('collections', 'collections')->name('collections');
        Route::post('collection-status/{id}', 'collectionStatus')->name('collection.status');
        Route::post('delete/{id}', 'collectionDelete')->name('collection.delete');
    });

    // User Management
    Route::controller('UserController')->name('user.')->prefix('user')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('banned', 'banned')->name('banned');
        Route::get('kyc-pending', 'kycPending')->name('kyc.pending');
        Route::get('kyc-unconfirmed', 'kycUnConfirmed')->name('kyc.unconfirmed');
        Route::get('email-unconfirmed', 'emailUnConfirmed')->name('email.unconfirmed');
        Route::get('mobile-unconfirmed', 'mobileUnConfirmed')->name('mobile.unconfirmed');

        // User KYC Operation
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-cancel/{id}', 'kycCancel')->name('kyc.cancel');

        // User Details Operation
        Route::get('details/{id}', 'details')->name('details');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('balance-update/{id}', 'balanceUpdate')->name('add.sub.balance');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Advertisement Management
    Route::controller('AdvertisementController')->prefix('advertisement')->name('advertisement.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Category Management
    Route::controller('CategoryController')->prefix('category')->name('category.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Reviewer Management
    Route::controller('ReviewerController')->prefix('reviewer')->name('reviewer.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::get('login/{id}', 'login')->name('login');
    });

    // Color Management
    Route::controller('ColorController')->prefix('color')->name('color.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Reason Management
    Route::controller('ReasonController')->prefix('reason')->name('reason.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Plan Management
    Route::controller('PlanController')->prefix('plan')->name('plan.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Filetype Management
    Route::controller('FileTypeController')->prefix('filetype')->name('filetype.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });

    //Author Management
    Route::controller('AuthorController')->prefix('author')->name('author.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('approved', 'approved')->name('approved');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('banned', 'banned')->name('banned');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Archive Manager Management
    Route::controller('ArchiveManagerController')->prefix('archive')->name('archive.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Asset Management
    Route::controller('AssetController')->prefix('asset')->name('asset.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('approved', 'approved')->name('approved');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update', 'update')->name('update');
        Route::post('featured/{id}', 'featured')->name('featured');
        Route::get('download/{id}', 'download')->name('download');
    });

    // Resolution Management
    Route::controller('ResolutionController')->prefix('resolution')->name('resolution.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('inactive', 'inactive')->name('inactive');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('store/{id?}', 'store')->name('store');
    });

    // Donation Management
    Route::controller('DonationController')->prefix('donation')->name('donation.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('pending', 'pending')->name('pending');
        Route::get('done', 'done')->name('done');
        Route::get('rejected', 'rejected')->name('rejected');
        
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function() {
        // Automated Gateway
        Route::controller('AutomatedGatewayController')->prefix('automated')->name('automated.')->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Manual Gateway
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'new')->name('new');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // Deposit Management
    Route::controller('DepositController')->prefix('deposit')->name('deposit.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('done', 'done')->name('done');
        Route::get('canceled', 'canceled')->name('canceled');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('cancel', 'cancel')->name('cancel');
    });

    // Payment Management
    Route::controller('PaymentController')->prefix('payment')->name('payment.')->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('done', 'done')->name('done');
        Route::get('canceled', 'canceled')->name('canceled');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('cancel', 'cancel')->name('cancel');
    });

    // Withdrawal Management
    Route::name('withdraw.')->prefix('withdraw')->group(function() {
        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('new', 'new')->name('new');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Withdrawal Management
        Route::controller('WithdrawController')->group(function() {
            Route::get('index', 'index')->name('index');
            Route::get('pending', 'pending')->name('pending');
            Route::get('done', 'done')->name('done');
            Route::get('canceled', 'canceled')->name('canceled');
            Route::post('approve', 'approve')->name('approve');
            Route::post('cancel', 'cancel')->name('cancel');
        });
    });

    // Subscriber
    Route::controller('ContactController')->group(function(){
        Route::prefix('subscriber')->name('subscriber.')->group(function(){
            Route::get('/', 'subscriberIndex')->name('index');
            Route::post('remove/{id}', 'subscriberRemove')->name('remove');
            Route::post('send-email', 'sendEmailSubscriber')->name('send.email');
        });

        Route::prefix('contact')->name('contact.')->group(function(){
            Route::get('/', 'contactIndex')->name('index');
            Route::post('remove/{id}', 'contactRemove')->name('remove');
            Route::post('status/{id}', 'contactStatus')->name('status');
        });
    });

    // Setting
    Route::controller('SettingController')->group(function() {
        Route::prefix('setting')->group(function() {
            // Basic Setting
            Route::get('basic', 'basic')->name('basic.setting');
            Route::post('basic', 'basicUpdate');
            Route::get('system-preference', 'systemPreference')->name('basic.system.preference');
            Route::post('system', 'systemUpdate')->name('basic.system.setting');
            Route::post('logo-favicon', 'logoFaviconUpdate')->name('basic.logo.favicon.setting');

            // Author
            Route::get('author-guideline', 'guideline')->name('basic.author.guideline');
            Route::post('author/requirements-update', 'requirementsUpdate')->name('basic.author.requirements');

            // Storage Management
            Route::get('storage', 'configStorage')->name('basic.storage.config');
            Route::post('storage/update', 'updateStorage')->name('storage.update');

            // Donation
            Route::get('donation/setting', 'donationSetting')->name('basic.donation.setting');
            Route::post('donation/setting/store', 'updateDonationSetting')->name('basic.donation.setting.store');

            // Plugins Setting
            Route::get('plugin', 'plugin')->name('plugin.setting');
            Route::post('plugin/update/{id}', 'pluginUpdate')->name('plugin.setting.update');
            Route::post('plugin/status/{id}', 'pluginStatus')->name('plugin.status');

            // SEO Setting
            Route::get('seo', 'seo')->name('seo.setting');

            // Kyc Setting
            Route::get('kyc/update', 'kyc')->name('kyc.setting');
            Route::post('kyc/update', 'kycUpdate');

            // Watermark Image Uploader
            Route::post('watermark-image', 'watermarkImageUpload')->name('setting.watermark');

            // Instruction Management
            Route::post('instruction', 'updateInstruction')->name('setting.instruction');

            
        });

        // Cookie
        Route::get('cookie', 'cookie')->name('cookie.setting');
        Route::post('cookie', 'cookieUpdate');

        // Maintenance
        Route::get('maintenance', 'maintenance')->name('maintenance.setting');
        Route::post('maintenance', 'maintenanceUpdate');

        // Cache Clear
        Route::get('cache-clear', 'cacheClear')->name('cache.clear');
    });

    // Referral Management
    Route::controller('ReferralController')->group(function() {
        Route::get('referral/configuration', 'configuration')->name('basic.referral.configuration');

        Route::prefix('referral')->name('referral.')->group(function() {
            Route::post('status', 'status')->name('status');
            Route::post('store', 'store')->name('store');
        });
    });

    // Email & SMS Setting
    Route::controller('NotificationController')->prefix('notification')->name('notification.')->group(function() {
        // Template Setting
        Route::get('universal', 'universal')->name('universal');
        Route::post('universal', 'universalUpdate');
        Route::get('templates','templates')->name('templates');
        Route::get('template/edit/{id}','templateEdit')->name('template.edit');
        Route::post('template/update/{id}','templateUpdate')->name('template.update');

        // Email Setting
        Route::get('email/setting', 'email')->name('email');
        Route::post('email/setting', 'emailUpdate');
        Route::post('email/test','testEmail')->name('email.test');

        // SMS Setting
        Route::get('sms/setting', 'sms')->name('sms');
        Route::post('sms/setting', 'smsUpdate');
        Route::post('sms/test','testSMS')->name('sms.test');
    });

    // Language Setting
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('keywords', 'keywords')->name('keywords');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('translate/keyword/{id}', 'translateKeyword')->name('translate.keyword');
        Route::post('import', 'languageImport')->name('import.lang');
        Route::post('store/key/{id}', 'languageKeyStore')->name('store.key');
        Route::post('update/key/{id}', 'languageKeyUpdate')->name('update.key');
        Route::post('delete/key/{id}', 'languageKeyDelete')->name('delete.key');
    });

     // Manage Frontend
     Route::controller('SiteController')->prefix('site')->name('site.')->group(function () {
        Route::get('themes', 'themes')->name('themes');
        Route::post('themes', 'makeActive');
        Route::get('sections/{key}', 'sections')->name('sections');
        Route::post('content/{key}', 'content')->name('sections.content');
        Route::get('element/{key}/{id?}', 'element')->name('sections.element');
        Route::post('remove/{id}', 'remove')->name('remove');
    });
});
