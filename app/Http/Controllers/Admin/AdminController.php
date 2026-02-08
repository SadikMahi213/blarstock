<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Deposit;
use App\Models\Download;
use App\Models\EarningRecord;
use App\Models\FileType;
use App\Models\Image;
use App\Models\Plan;
use App\Models\Reviewer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    function dashboard() {
        $pageTitle     = 'Dashboard';
        $latestTrx     = Transaction::with('user')->latest()->limit(9)->get();
        $admin         = Admin::first();
        $passwordAlert = false;

        if (Hash::check('admin', $admin->password) || $admin->username == 'admin') {
            $passwordAlert = true;
        }

        $widget = [
            // Other Info
            'totalCategories' => Category::count(),
            'totalTrx' => Transaction::count(),
            'totalAds' => Advertisement::count(),

            // User Info
            'totalUsers'             => User::count(),
            'activeUsers'            => User::active()->count(),
            'emailUnconfirmedUsers'  => User::emailUnconfirmed()->count(),
            'mobileUnconfirmedUsers' => User::mobileUnconfirmed()->count(),

            // Author Info
            'allAuthor'      => User::authorIndex()->count(),
            'approvedAuthor' => User::approvedAuthor()->count(),
            'pendingAuthor'  => User::pendingAuthor()->count(),
            'rejectedAuthor' => User::rejectedAuthor()->count(),
            'bannedAuthor'   => User::bannedAuthor()->count(),

            // Reviewer Info
            'allReviewer'      => Reviewer::count(),
            'activeReviewer'   => Reviewer::active()->count(),
            'inactiveReviewer' => Reviewer::inactive()->count(),

            // General Info
            'totalDownload'   => Download::count(),
            'totalCollection' => Collection::count(),
            'totalFileType'   => FileType::count(),
            'totalPlan'       => Plan::count(),

            // Earning and Donation
            'totalEarning'         => EarningRecord::sum('amount'),
            'donationDone'         => Deposit::whereNot('donation_receiver_id', ManageStatus::EMPTY)->done()->sum('amount'),
            'donationPending'      => Deposit::whereNot('donation_receiver_id', ManageStatus::EMPTY)->pending()->sum('amount'),
            'pendingDonationCount' => Deposit::whereNot('donation_receiver_id', ManageStatus::EMPTY)->pending()->count(),
            'donationCharge'       => Deposit::whereNot('donation_receiver_id', ManageStatus::EMPTY)->done()->sum('charge'),

            // Payment Info
            'paymentDone'         => Deposit::whereNot('plan_id', ManageStatus::EMPTY)->done()->sum('amount'),
            'paymentPending'      => Deposit::whereNot('plan_id', ManageStatus::EMPTY)->pending()->sum('amount'),
            'pendingPaymentCount' => Deposit::whereNot('plan_id', ManageStatus::EMPTY)->pending()->count(),
            'paymentCanceled'     => Deposit::whereNot('plan_id', ManageStatus::EMPTY)->canceled()->sum('amount'),
            'cancelePaymentCount' => Deposit::whereNot('plan_id', ManageStatus::EMPTY)->canceled()->count(),
            'paymentCharge'       => Deposit::whereNot('plan_id', ManageStatus::EMPTY)->done()->sum('charge'),

            // Deposit Info
            'depositDone'          => Deposit::done()->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->sum('amount'),
            'depositPending'       => Deposit::pending()->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->sum('amount'),
            'pendingDepositCount'  => Deposit::pending()->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->count(),
            'depositCanceled'      => Deposit::canceled()->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->sum('amount'),
            'canceledDepositCount' => Deposit::canceled()->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->count(),
            'depositCharge'        => Deposit::done()->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->sum('charge'),

            // Withdraw Info
            'withdrawDone'          => Withdrawal::done()->sum('amount'),
            'withdrawPending'       => Withdrawal::pending()->sum('amount'),
            'pendingWithdrawCount'  => Withdrawal::pending()->count(),
            'withdrawCanceled'      => Withdrawal::canceled()->sum('amount'),
            'canceledWithdrawCount' => Withdrawal::canceled()->count(),
            'withdrawCharge'        => Withdrawal::done()->sum('charge'),

            // Asset Info
            'allAsset'      => Image::count(),
            'approvedAsset' => Image::approved()->count(),
            'pendingAsset'  => Image::pending()->count(),
            'rejectedAsset' => Image::rejected()->count(),
        ];

        // Monthly Deposit & Withdraw Report Graph
        $report['months'] = collect([]);
        $report['deposit_month_amount'] = collect([]);
        $report['withdraw_month_amount'] = collect([]);

        $depositsMonth = Deposit::where('created_at', '>=', Carbon::now()->subYear())
                        ->where('status', ManageStatus::PAYMENT_SUCCESS)
                        ->selectRaw("SUM( CASE WHEN status = ". ManageStatus::PAYMENT_SUCCESS. " THEN amount END) as depositAmount")
                        ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
                        ->orderBy('created_at')
                        ->groupBy('months')->get();

        $depositsMonth->map(function ($depositData) use ($report) {
            $report['months']->push($depositData->months);
            $report['deposit_month_amount']->push(getAmount($depositData->depositAmount));
        });

        $withdrawalMonth = Withdrawal::where('created_at', '>=', Carbon::now()->subYear())->where('status', ManageStatus::PAYMENT_SUCCESS)
            ->selectRaw("SUM( CASE WHEN status = ".ManageStatus::PAYMENT_SUCCESS." THEN amount END) as withdrawAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')->get();
        $withdrawalMonth->map(function ($withdrawData) use ($report){
            if (!in_array($withdrawData->months,$report['months']->toArray())) {
                $report['months']->push($withdrawData->months);
            }
            $report['withdraw_month_amount']->push(getAmount($withdrawData->withdrawAmount));
        });

        $months = $report['months'];

        for($i = 0; $i < $months->count(); ++$i) {
            $monthVal      = Carbon::parse($months[$i]);
            if(isset($months[$i+1])){
                $monthValNext = Carbon::parse($months[$i+1]);
                if($monthValNext < $monthVal){
                    $temp = $months[$i];
                    $months[$i]   = Carbon::parse($months[$i+1])->format('F-Y');
                    $months[$i+1] = Carbon::parse($temp)->format('F-Y');
                }else{
                    $months[$i]   = Carbon::parse($months[$i])->format('F-Y');
                }
            }
        }

        return view('admin.page.dashboard', compact('pageTitle', 'widget', 'latestTrx', 'depositsMonth', 'withdrawalMonth', 'months', 'passwordAlert'));
    }

    function profile() {
        $pageTitle = 'Profile';
        $admin     = auth('admin')->user();
        return view('admin.page.profile', compact('pageTitle', 'admin'));
    }

    function profileUpdate() {
        $this->validate(request(), [
            'name'     => 'required|max:40',
            'email'    => 'required|email|max:40',
            'username' => 'required|max:40',
            'contact'  => 'required|max:40',
            'address'  => 'required|max:255',
            'image'    => [File::types(['png', 'jpg', 'jpeg'])],
        ]);

        $admin = auth('admin')->user();

        if (request()->hasFile('image')) {
            try {
                $old          = $admin->image;
                $admin->image = fileUploader(request('image'), getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Image upload failed'];
                return back()->withToasts($toast);
            }
        }

        $admin->name     = request('name');
        $admin->email    = request('email');
        $admin->username = request('username');
        $admin->contact  = request('contact');
        $admin->address  = request('address');
        $admin->save();

        $toast[] = ['success', 'Profile update success'];
        return back()->withToasts($toast);
    }

    function passwordChange() {
        $passwordValidation = Password::min(6);

        if (bs('strong_pass')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate(request(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation],
        ]);

        $admin = auth('admin')->user();

        if (!Hash::check(request('current_password'), $admin->password)) {
            $toast[] = ['error', 'Current password mismatched !!'];
            return back()->withToasts($toast);
        }

        $admin->password = Hash::make(request('password'));
        $admin->save();

        $toast[] = ['success', 'Password change success'];
        return back()->withToasts($toast);
    }

    function notificationAll() {
        $notifications = AdminNotification::with('user')->orderBy('is_read')->paginate(getPaginate());
        $pageTitle     = 'Notifications';

        return view('admin.page.notification',compact('pageTitle','notifications'));
    }

    function notificationRead($id) {
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = ManageStatus::YES;
        $notification->save();

        $url = $notification->click_url;

        if ($url == '#') {
            $url = url()->previous();
        }

        return redirect($url);
    }

    function notificationReadAll() {
        AdminNotification::where('is_read', ManageStatus::NO)->update([
            'is_read'=>ManageStatus::YES
        ]);

        $toast[] = ['success', 'All notification marked as read success'];
        return back()->withToasts($toast);
    }

    function notificationRemove($id) {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();

        $toast[] = ['success', 'Notification removal success'];
        return back()->withToasts($toast);
    }

    function notificationRemoveAll(){
        AdminNotification::truncate();

        $toast[] = ['success', 'All notification remove success'];
        return back()->withToasts($toast);
    }

    function transaction() {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::searchable(['trx', 'user:username'])->filter(['trx_type', 'remark'])->dateFilter()->latest()->with('user')->paginate(getPaginate());

        return view('admin.page.transaction', compact('pageTitle', 'transactions', 'remarks'));
    }

    function fileDownload() {
        $path = request('filePath');
        $file = fileManager()->$path()->path.'/'.request('fileName');
        
        return response()->download($file);
    }

    function earningRecord() {
        $pageTitle = 'Earning Record';
        $earnings  = EarningRecord::with(['author', 'imageFile'])->searchable(['author:author_name'])->dateFilter()->latest()->paginate(getPaginate());

        return view('admin.page.earningRecord', compact('pageTitle', 'earnings'));
    }

    function downloadRecord() {
        $pageTitle = 'All Downloads';
        $downloads = Download::with(['user', 'imageFile', 'author'])->searchable(['user:username', 'author:author_name'])->dateFilter()->latest()->paginate(getPaginate());

        return view('admin.page.downloads', compact('pageTitle', 'downloads'));
    }

    function collections() {
        $pageTitle   = 'All Collections';
        $collections = Collection::with(['user', 'collectionImages'])->searchable(['user:username', 'user:author_name'])->latest()->paginate(getPaginate());

        return view('admin.page.collections', compact('pageTitle', 'collections'));
    }

    function collectionStatus($id) {
        $collection = Collection::find($id);
        if (!$collection) {
            $toast[] = ['error', 'Collection not found'];
            return back()->withToasts($toast);
        }

        return Collection::changeStatus($collection->id);
    }

    function deleteCollection($id) {
        $collection = Collection::find($id);

        if (!$collection) {
            $toast[] = ['error', 'Collection not found'];
            return back()->withToasts($toast);
        }

        $collectionTitle = $collection->title;

        $collection->collectionImages()->delete();

        $collection->delete();

        $toast[] = ['success', $collectionTitle . ' collection delete success'];
        return back()->withToasts($toast);
    }
}
