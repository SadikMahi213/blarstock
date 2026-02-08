<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Events\DownloadCompleted;
use App\Http\Controllers\Controller;
use App\Lib\DownloadFile;
use App\Models\Download;
use App\Models\Image;
use App\Models\ImageFile;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class DownloadController extends Controller {
    function download($id) {
        try {
            $file = ImageFile::with('image')->find(decrypt($id));

            if (!$file) {
                $toast[] = ['error', 'File not found'];
                return back()->withToasts($toast);
            }
        } catch (\Exception $e) {
            $toast[] = ['error', 'Invalid file ID'];
            return back()->withToasts($toast);
        }

        if ($file->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'File not active right now'];
            return back()->withToasts($toast);
        }

        $asset = Image::activeCheck()->find($file->image_id);

        if (!$asset) {
            $toast[] = ['error', 'Something went wrong'];
            return back()->withToasts($toast);
        }

        $user = auth()->user();

        if ($file->is_free == ManageStatus::PREMIUM && $file->image->user_id != $user->id) {
            $this->premiumDownloadProcess($file);
        }

        if ($file->image->user_id != $user->id) {
            $download                = new Download();
            $download->image_file_id = $file->id;
            $download->user_id       = $user->id;
            $download->author_id     = $file->image->user_id;
            $download->premium       = $file->is_free;
            $download->save();

            event(new DownloadCompleted($file, $user, $download->id));
        }

        return DownloadFile::download($file);
    }

    private function premiumDownloadProcess($file) {
        $user = auth()->user();

        if ($user->plan_id && ($user->plan_expired_date && Carbon::parse($user->plan_expired_date)->isFuture())) {
            return $this->purchaseProcessByPlan($file, $user);
        }

        return $this->purchaseProcessByBalance($file, $user);
    }


    private function purchaseProcessByPlan($file, $user) {
        $downloads = Download::where('user_id', $user->id)->where('premium', ManageStatus::YES);

        $toadyDownload   = (clone $downloads)->whereDate('created_at', Carbon::now())->count();
        $monthlyDownload = (clone $downloads)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();


        if ((($user->plan->daily_limit != ManageStatus::UNLIMITED_DOWNLOAD) && ($user->plan->daily_limit <= $toadyDownload)) || ($user->plan->monthly_limit != ManageStatus::EMPTY && ($user->plan->monthly_limit != ManageStatus::UNLIMITED_DOWNLOAD) && ($user->plan->monthly_limit <= $monthlyDownload))) {
            $this->purchaseProcessByBalance($file, $user);
        }        
    }

    private function purchaseProcessByBalance($file, $user) {
        $price = $file->price;

        if ($user->balance < $price) {
            throw ValidationException::withMessages(['balance_over' => 'Insufficient Balance']);
        }

        $user->balance -= $price;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $price;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Download charge for ' . $file->image->title;
        $transaction->trx          = getTrx();
        $transaction->remark       = 'Download Charge';
        $transaction->save();
    }


    function index() {
        $pageTitle = 'Download History';
        $downloads = Download::where('user_id', auth()->id())->with(['imageFile', 'author'])->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.page.downloads', compact('pageTitle', 'downloads'));
    }

}
