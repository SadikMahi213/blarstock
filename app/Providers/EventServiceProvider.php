<?php

namespace App\Providers;

use App\Events\AssetApproveEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\DownloadCompleted;
use App\Listeners\ProcessDownloadEarnings;
use App\Listeners\SendAssetApprovalNotification;

class EventServiceProvider extends ServiceProvider {
    
    protected $listen = [
        DownloadCompleted::class => [
            ProcessDownloadEarnings::class,
        ],

        AssetApproveEvent::class => [
            SendAssetApprovalNotification::class
        ]
    ];
}
