<?php

namespace App\Providers;

use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Models\Contact;
use App\Models\Deposit;
use App\Models\Donation;
use App\Models\Image;
use App\Models\SiteData;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $setting                        = bs();
        $activeTheme                    = activeTheme();
        $shareToView['setting']         = $setting;
        $shareToView['activeTheme']     = $activeTheme;
        $shareToView['activeThemeTrue'] = activeTheme(true);
        $shareToView['emptyMessage']    = 'No data found';

        view()->share($shareToView);

        view()->composer('admin.partials.topbar', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', ManageStatus::NO)->with('user')->latest()->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', ManageStatus::NO)->count(),
            ]);
        });

        view()->composer('admin.partials.sidebar', function ($view) {
            $view->with([
                'bannedUsersCount'            => User::banned()->count(),
                'emailUnconfirmedUsersCount'  => User::emailUnconfirmed()->count(),
                'mobileUnconfirmedUsersCount' => User::mobileUnconfirmed()->count(),
                'kycUnconfirmedUsersCount'    => User::kycUnconfirmed()->count(),
                'kycPendingUsersCount'        => User::kycPending()->count(),
                'pendingDepositsCount'        => Deposit::pending()->where('plan_id', ManageStatus::EMPTY)->count(),
                'pendingPaymentsCount'        => Deposit::pending()->whereNot('plan_id', ManageStatus::EMPTY)->count(),
                'pendingWithdrawCount'        => Withdrawal::pending()->count(),
                'pendingAssetCount'           => Image::pending()->count(),
                'pendingAuthorsCount'         => User::pendingAuthor()->count(),
                'unansweredContactsCount'     => Contact::where('status', ManageStatus::NO)->count(),
                'pendingDonationsCount'       => Deposit::pending()->whereNot('donation_receiver_id', ManageStatus::EMPTY)->count()
            ]);
        });

        view()->composer('reviewer.partials.sidebar', function($view) {
            $view->with([
                'bannedUsersCount'            => User::banned()->count(),
                'emailUnconfirmedUsersCount'  => User::emailUnconfirmed()->count(),
                'mobileUnconfirmedUsersCount' => User::mobileUnconfirmed()->count(),
                'kycUnconfirmedUsersCount'    => User::kycUnconfirmed()->count(),
                'kycPendingUsersCount'        => User::kycPending()->count(),
                'pendingDepositsCount'        => Deposit::pending()->where('plan_id', ManageStatus::EMPTY)->count(),
                'pendingPaymentsCount'        => Deposit::pending()->whereNot('plan_id', ManageStatus::EMPTY)->count(),
                'pendingWithdrawCount'        => Withdrawal::pending()->count(),
                'pendingAssetCount'           => Image::pending()->count(),
                'pendingAuthorsCount'         => User::pendingAuthor()->count(),
                'unansweredContactsCount'     => Contact::where('status', ManageStatus::NO)->count()
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = SiteData::where('data_key', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_info : $seo,
            ]);
        });

        if ($setting->enforce_ssl) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFour();
    }
}
