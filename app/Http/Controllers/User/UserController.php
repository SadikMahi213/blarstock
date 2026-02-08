<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\CommissionLog;
use App\Models\Deposit;
use App\Models\EarningRecord;
use App\Models\Follow;
use App\Models\Form;
use App\Models\Image;
use App\Models\Like;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    function home() {
        $pageTitle        = 'Dashboard';
        $dashboardContent = getSiteData('user_dashboard.content', true);
        $user             = auth()->user();
        $transactions     = Transaction::userDashboard()->where('user_id', $user->id)->latest()->limit(5)->get();
        $earnings         = EarningRecord::where('author_id', $user->id)->whereDate('earning_date', '>=', Carbon::now()->subDays(30))
                            ->selectRaw('DATE(earning_date) as date, SUM(amount) as total')
                            ->groupBy('date')
                            ->orderBy('date', 'asc')
                            ->get()
                            ->keyBy('date');

        $formattedData = [];

        for($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $formattedData[] = $earnings[$date]->total ?? 0;
        }

        $kycContent = getSiteData('kyc.content', true);

        return view($this->activeTheme . 'user.page.dashboard', compact('pageTitle', 'transactions', 'dashboardContent', 'user', 'formattedData', 'kycContent'));
    }

    function kycForm() {
        $siteData  = getSiteData('kyc.content', true);
        $pageTitle = $siteData->data_info->Verification_required_heading;
        $subTitle  = $siteData->data_info->Verification_required_details;
        $form      = Form::where('act','kyc')->first();
        $user      = auth()->user();

        if ($user->kc == ManageStatus::PENDING) {
            $toast[] = ['warning', 'Your identity verification is being processed'];
            return back()->withToasts($toast);
        }

        if ($user->kc == ManageStatus::VERIFIED) {
            $toast[] = ['success', 'Your identity verification is being succeed'];
            return back()->withToasts($toast);
        }

        return view($this->activeTheme . 'user.kyc.form', compact('pageTitle', 'subTitle', 'form'));
    }

    function kycSubmit() {
        $form           = Form::where('act','kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        request()->validate($validationRule);

        $userData       = $formProcessor->processFormData(request(), $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kc       = ManageStatus::PENDING;
        $user->save();

        $toast[] = ['success', 'Your identity verification information has been received'];
        return to_route('user.author.dashboard')->withToasts($toast);
    }

    function kycData() {
        $siteData  = getSiteData('kyc.content', true);
        $pageTitle = $siteData->data_info->Verification_pending_heading;
        $subTitle  = $siteData->data_info->Verification_pending_details;
        $user      = auth()->user();

        return view($this->activeTheme . 'user.kyc.info', compact('pageTitle', 'subTitle', 'user'));
    }

    function profile() {
        $pageTitle = 'Profile Update';
        $siteData  = getSiteData('profile_setting.content', true);
        $user      = auth()->user();

        return view($this->activeTheme. 'user.page.profile', compact('pageTitle', 'user', 'siteData'));
    }

    function profileUpdate() {
        
        $this->validate(request(), [
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
        ],[
            'firstname.required' => 'First name field is required',
            'lastname.required'  => 'Last name field is required'
        ]);

        $user            = auth()->user();
        $user->firstname = request('firstname');
        $user->lastname  = request('lastname');

        $user->address = [
            'state' => request('state'),
            'zip'   => request('zip'),
            'city'  => request('city'),
            'address' => request('address'),
        ];

        $user->save();

        $toast[] = ['success', 'Profile updated success'];
        return back()->withToasts($toast);
    }

    function profileImageUpload() {
        $validate = Validator::make(request()->all(), [
            'profile_image' => ['required', File::types(['png', 'jpg', 'jpeg'])]
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $user = auth()->user();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        if (request()->hasFile('profile_image')) {
            try {
                $user->image = fileUploader(request('profile_image'), getFilePath('userProfile'), getFileSize('userProfile'), $user->image);
                $user->save();

                return response([
                    'success' => true,
                    'message' => 'Profile image upload success',
                    'image_url' => asset(getFilePath('userProfile') . '/' . $user->image)
                ]);
            } catch (\Exception $exp) {
                return response([
                    'success' => false,
                    'message' => 'Profile image upload fail'
                ]);
            }
        }

        return response([
            'success' => false,
            'message' => 'File not found'
        ]);
    }

function coverImageUpload() {
        $validate = Validator::make(request()->all(), [
            'cover_image' => ['required', File::types(['png', 'jpg', 'jpeg'])]
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $user = auth()->user();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        if (request()->hasFile('cover_image')) {
            try {
                $coverImageFile = request('cover_image');
                
                $fileName = fileUploader($coverImageFile, getFilePath('userCover'), null, $user->cover_image);
                $user->cover_image = $fileName;

                $thumbFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_thumb.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $thumbFullPath = getFilePath('userCover') . '/' . $thumbFileName;

                if ($user->cover_image_thumb) {
                    removeFile(getFilePath('userCover') . '/' . $user->cover_image_thumb);
                }

                $manager = new ImageManager(new Driver());
                $thumb   = $manager->read($coverImageFile);

                $originalWidth = $thumb->width();
                $thumbWidth    = round($originalWidth * 0.236);

                $originalHeight = $thumb->height();
                $thumbHeight = round($originalHeight * 0.236);

                $thumb->resize($thumbWidth, $thumbHeight, function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $thumb->save($thumbFullPath);

                $user->cover_image_thumb = $thumbFileName;
                $user->save();


                return response([
                    'success'   => true,
                    'message'   => 'Cover image upload success',
                    'image_url' => asset(getFilePath('userCover') . '/' . $user->cover_image)
                ]);
            } catch (\Exception $exp) {
                return response([
                    'success' => false,
                    'message' => 'Cover image upload fail'
                ]);
            }
        }

        return response([
            'success' => false,
            'message' => 'File not found'
        ]);
    }

    function password() {
        $pageTitle = 'Password Change';
        $siteData  = getSiteData('change_password.content', true);

        return view($this->activeTheme . 'user.page.password', compact('pageTitle', 'siteData'));
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

        $user = auth()->user();

        if (!Hash::check(request('current_password'), $user->password)) {
            $toast[] = ['error', 'Current password mismatched !!'];
            return back()->withToasts($toast);
        }

        $user->password = Hash::make(request('password'));
        $user->save();

        $toast[] = ['success', 'Password change success'];
        return back()->withToasts($toast);
    }

    function show2faForm() {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . bs('site_name'), $secret);
        $pageTitle = 'Two Factor Setting';

        return view($this->activeTheme . 'user.page.twoFactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    function enable2fa() {
        $user = auth()->user();

        $this->validate(request(), [
            'key'    => 'required',
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        $verCode  = (int)(implode("", request('code')));
        $response = verifyG2fa($user, $verCode, request('key'));

        if ($response) {
            $user->tsc = request('key');
            $user->ts  = ManageStatus::YES;
            $user->save();

            $toast[] = ['success', 'Google authenticator activation success'];
            return back()->withToasts($toast);
        } else {
            $toast[] = ['error', 'Wrong verification code'];
            return back()->withToasts($toast);
        }
    }

    function disable2fa() {
        $this->validate(request(), [
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        $verCode  = (int)(implode("", request('code')));
        $user     = auth()->user();
        $response = verifyG2fa($user, $verCode);

        if ($response) {
            $user->tsc = null;
            $user->ts = ManageStatus::NO;
            $user->save();

            $toast[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $toast[] = ['error', 'Wrong verification code'];
        }
        return back()->withToasts($toast);
    }

    function depositHistory() {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->index()->where('plan_id', ManageStatus::EMPTY)->with('gateway')->latest()->paginate(getPaginate());

        return view($this->activeTheme.'user.deposit.index', compact('pageTitle', 'deposits'));
    }

    function paymentHistory() {
        $pageTitle = 'Payment History';
        $payments  = auth()->user()->deposits()->searchable(['trx'])->index()->plan()->with('gateway')->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.deposit.planIndex', compact('pageTitle', 'payments'));
    }

    public function transactions() {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type','remark'])->orderBy('id','desc')->paginate(10);

        return view($this->activeTheme.'user.page.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    function fileDownload() {
        $path = request('filePath');
        $file = fileManager()->$path()->path.'/'.request('fileName');
        
        return response()->download($file);
    }

    function like() {

        $validate = Validator::make(request()->all(), [
            'asset_id' => 'required|integer|gt:0',
            'user_id'  => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $user = User::active()->find(request('user_id'));

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $authUser = auth()->user();

        if ($authUser->id != $user->id) {
            return response([
                'success' => false,
                'message' => 'Invalid action'
            ]);
        }

        $asset = Image::find(request('asset_id'));

        if (!$asset) {
            return response([
                'success' => false,
                'message' => 'Asset not found'
            ]);
        }

        if ($asset->status != ManageStatus::IMAGE_APPROVED) {
            return response([
                'success' => false,
                'message' => $asset->title . ' not in approved status'
            ]);
        }

        if ($asset->category->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'Category of the asset is not active right now'
            ]);
        }

        if ($asset->fileType->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'File type of the asset is not active right now'
            ]);
        }

        $like = Like::where('user_id', $user->id)->where('image_id', $asset->id)->first();

        if ($like) {
            $like->delete();
            $asset->total_like -= 1;
            $asset->save();

            return response([
                'warning' => true,
                'message' => $asset->title . ' unlike success'
            ]);
        }

        $like           = new Like();
        $like->user_id  = $user->id;
        $like->image_id = $asset->id;
        $like->save();

        $asset->total_like += 1;
        $asset->save();

        return response([
            'success' => true,
            'message' => $asset->title . ' like success'
        ]);
    }

    function follow() {
        $validate = Validator::make(request()->all(), [
            'user_id'   => 'required|integer|gt:0',
            'author_id' => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $author = User::active()->find(request('author_id'));

        if (!$author) {
            return response([
                'success' => false,
                'message' => 'Author not exist'
            ]);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Invalid author'
            ]);
        }

        $user = auth()->user();

        if ($user->id != request('user_id')) {
            return response([
                'success' => false,
                'message' => 'Permission access denied'
            ]);
        }

        if ($author->id == $user->id) {
            return response([
                'success' => false,
                'message' => 'Invalid action'
            ]);
        }

        $follow = Follow::where('follower_id', $user->id)->where('following_id', $author->id)->first();

        if ($follow) {
            $follow->delete();

            $author->total_follower -= 1;
            $author->save();

            $user->total_following -= 1;
            $user->save();

            return response([
                'warning' => true,
                'message' => 'Successfully removed ' . $author->author_name . ' from your following list'
            ]);
        }

        $follow               = new Follow();
        $follow->follower_id  = $user->id;
        $follow->following_id = $author->id;
        $follow->save();

        $author->total_follower += 1;
        $author->save();

        $user->total_following += 1;
        $user->save();

        return response([
            'success' => true,
            'message' => $author->author_name . ' follow success'
        ]);
    }

    function donations() {

        $user = auth()->user();
        
        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['error', 'Approved authors allowed only'];
            return to_route('user.author.dashboard')->withToasts($toast);
        }

        $pageTitle = 'Donation Logs';

        $excludedColumns = ['user_id', 'plan_id', 'image_id', 'payment_info', 'method_code', 'charge', 'rate', 'final_amo', 'detail', 'btc_amo', 'btc_wallet', 'trx', 'payment_try', 'from_api', 'admin_feedback'];
        $donations       = Deposit::where('donation_receiver_id', $user->id)->select(getSelectedColumns('deposits', $excludedColumns))->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.author.donations', compact('pageTitle', 'donations'));
    }

    function earnings() {

        $user = auth()->user();

        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['error', 'Approved authors allowed only'];
            return to_route('user.author.dashboard')->withToasts($toast);
        }

        $pageTitle = 'Earning Logs';
        $earnings  = EarningRecord::where('author_id', $user->id)->with(['imageFile'])->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.author.earnings', compact('pageTitle', 'earnings'));
    }

    function referralLink() {
        $pageTitle = 'Referred Users';
        $user      = auth()->user();
        $link      = route('home') . '?reference=' . $user->username;
        $maxLevel  = bs('max_referral_level');

        return view($this->activeTheme . 'user.referral.referralLink', compact('pageTitle', 'user', 'link', 'maxLevel'));
    }

    function referralLog() {
        $pageTitle       = 'Referral Log';
        $excludedColumns = ['level', 'post_balance', 'amount', 'details', 'type', 'percent', 'trx'];
        $referrals       = CommissionLog::where('to_id', auth()->id())->select(getSelectedColumns('commission_logs', $excludedColumns))->with(['fromUser'])->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.referral.referralLog', compact('pageTitle', 'referrals'));
    }
}
