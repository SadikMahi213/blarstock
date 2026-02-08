<?php

namespace App\Listeners;

use App\Events\AssetApproveEvent;
use App\Models\Follow;
use App\Models\User;


class SendAssetApprovalNotification
{
    /**
     * Create the event listener.
     */

    /**
     * Handle the event.
     */
    public function handle(AssetApproveEvent $event){
        $image = $event->image;

        $followerQuery = Follow::where('following_id', $image->user_id);

        $followerQuery->chunkById(300, function($followers) use ($image) {
            $followerIds = $followers->pluck('follower_id')->toArray();

            User::whereIn('id', $followerIds)
                ->cursor()
                ->each(fn($follower) => notify($follower, 'ASSET_APPROVE', [
                    'author_name' => $image->user->author_name,
                    'username'    => $follower->username,
                    'asset_link'  => route('asset.detail', [encrypt($image->id), slug($image->title)])
                ]));
        });
    }
}