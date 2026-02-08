<?php

use Illuminate\Support\Facades\Route;

Route::controller('WebsiteController')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    // Contact
    Route::get('contact', 'contact')->name('contact');
    Route::post('contact', 'contactStore');

    // Cookie
    Route::get('cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    // Language
    Route::get('change-language/{lang?}', 'changeLanguage')->name('lang');

    // Policy Details
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    // Plan Management
    Route::get('pricing', 'plan')->name('plan'); 

    // Asset Details
    Route::get('asset/details/{id}/{title}', 'assetDetail')->name('asset.detail');

    // Assets Search
    Route::get('all/assets', 'allAssets')->name('all.assets');


    // Collections
    Route::get('collection/index', 'collections')->name('collection.index');
    Route::get('collection-detail/{collectionId}/{title}', 'collectionDetail')->name('collection.detail');

    Route::controller('AuthorController')->prefix('author')->name('author.')->group(function() {
        Route::get('index', 'authors')->name('index');
        Route::get('profile/{id}/{authorName}', 'profile')->name('profile');
        Route::get('collections/{id}/{authorName}', 'collections')->name('collections');
        Route::get('followers/{id}/{authorName}', 'followers')->name('followers');
        Route::get('followers/load', 'loadMoreFollowers')->name('load.more.followers');
        Route::get('followings/load', 'loadMoreFollowings')->name('load.more.followings');
    });

    Route::controller('UserController')->prefix('member/user')->name('member.user.')->group(function() {
        Route::get('profile/{id}/{name}', 'profile')->name('profile');
        Route::get('following/{id}/{name}', 'following')->name('following');
        Route::get('followings/load', 'loadMoreFollowings')->name('load.more.followings');
    });

    Route::get('add/click', 'addClick')->name('add.click');

    Route::prefix('donation')->name('donation.')->group(function() {
        Route::controller('DonationController')->group(function() {
            Route::post('insert/{assetId}', 'insert')->name('insert');
        });

        Route::controller('Gateway\PaymentController')->group(function() {
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
