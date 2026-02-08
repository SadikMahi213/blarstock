<?php

namespace App\Listeners;

use App\Events\DownloadCompleted;
use App\Models\Download;
use App\Models\EarningRecord;
use App\Models\Transaction;

class ProcessDownloadEarnings {
    
    public function handle(DownloadCompleted $event) {

        $download = Download::where('id', $event->downloadId)->whereNull('processed_at')->lockForUpdate()->first();

        if (!$download) {
            return;
        }

        $file       = $event->file;
        $user       = $event->user;
        $author     = $file->image->user;

        $file->total_downloads += 1;
        $file->save();

        $file->image->total_download += 1;
        $file->image->save();

        $author->total_others_download += 1;
        $author->save();
        
        $user->total_self_download += 1;
        $user->save();

        if (!$file->is_free) {
            $setting = bs();
            $amount  = $file->price * $setting->authors_commission / 100;

            $author->balance += $amount;
            $author->save();

            $file->image->total_earning += $amount;
            $file->image->save();

            $earn                = new EarningRecord();
            $earn->author_id     = $author->id;
            $earn->image_file_id = $file->id;
            $earn->amount        = $amount;
            $earn->earning_date  = now()->format('Y-m-d');
            $earn->save();
            
            
            $transaction               = new Transaction();
            $transaction->user_id      = $author->id;
            $transaction->amount       = $amount;
            $transaction->post_balance = $author->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Earning accrued from the download of ' . $file->image->title;
            $transaction->trx          = getTrx();
            $transaction->remark       = 'Earning Log';
            $transaction->save();
        }

        $download->processed_at = now();
        $download->save();
    }
}
