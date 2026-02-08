<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    
    function index() {
        return $this->userData('All Users');
    }

    function active() {
        return $this->userData('Active Users', 'active');
    }

    function banned() {
        return $this->userData('Banned Users', 'banned');
    }

    function kycPending() {
        return $this->userData('KYC Pending Users', 'kycPending');
    }

    function kycUnConfirmed() {
        return $this->userData('KYC Unconfirmed Users', 'kycUnconfirmed');
    }

    function emailUnConfirmed() {
        return $this->userData('Email Unconfirmed Users', 'emailUnconfirmed');
    }

    function mobileUnConfirmed() {
        return $this->userData('Mobile Unconfirmed Users', 'mobileUnconfirmed');
    }

    function details($id) {
        $user              = User::findOrFail($id);
        $pageTitle         = 'Details - ' .$user->username;
        $totalDeposit      = Deposit::where('user_id', $user->id)->done()->sum('amount');
        $totalWithdrawal   = Withdrawal::where('user_id', $user->id)->done()->sum('amount');
        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        $countries         = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('admin.user.detail', compact('pageTitle', 'user', 'totalDeposit', 'totalWithdrawal', 'totalTransactions', 'countries'));
    }

    function update($id) {
        $user         = User::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array)$countryData;
        $countries    = implode(',', array_keys($countryArray));
        $countryCode  = request('country');
        $country      = $countryData->$countryCode->country;
        $dialCode     = $countryData->$countryCode->dial_code;

        $this->validate(request(), [
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile'    => 'required|string|max:40|unique:users,mobile,' . $user->id,
            'country'   => 'required|in:' . $countries,
        ]);

        $user->mobile       = $dialCode.request('mobile');
        $user->country_name = $country;
        $user->country_code = $countryCode;
        $user->firstname    = request('firstname');
        $user->lastname     = request('lastname');
        $user->email        = request('email');
        $user->ec           = request('ec') ? ManageStatus::VERIFIED : ManageStatus::UNVERIFIED;
        $user->sc           = request('sc') ? ManageStatus::VERIFIED : ManageStatus::UNVERIFIED;
        $user->ts           = request('ts') ? ManageStatus::ACTIVE   : ManageStatus::INACTIVE;
        $user->address      = [
                                'city'    => request('city'),
                                'state'   => request('state'),
                                'zip'     => request('zip'),
                                'country' => $country ?? null,
                            ];
        if (!request('kc')) {
            $user->kc = ManageStatus::UNVERIFIED;

            if ($user->kyc_data) {
                foreach ($user->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify').'/'.$kycData->value);
                    }
                }
            }

            $user->kyc_data = null;
        }else{
            $user->kc = ManageStatus::VERIFIED;
        }
        $user->save();

        $toast[] = ['success', 'User details updated success'];
        return back()->withToasts($toast);
    }

    function login($id) {
        Auth::loginUsingId($id);
        return to_route('user.author.dashboard');
    }

    function balanceUpdate( $id) {
        $this->validate(request(), [
            'amount' => 'required|numeric|gt:0',
            'act'    => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $user   = User::findOrFail($id);
        $amount = request('amount');
        $trx    = getTrx();

        $transaction = new Transaction();

        if (request('act') == 'add') {
            $user->balance        += $amount;
            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';
            $notifyTemplate        = 'BAL_ADD';

            $toast[] = ['success', bs('cur_sym') . $amount . ' add success'];

        } else {
            if ($amount > $user->balance) {
                $toast[] = ['error', $user->username . ' doesn\'t have sufficient balance.'];
                return back()->withToasts($toast);
            }

            $user->balance        -= $amount;
            $transaction->trx_type = '-';
            $transaction->remark   = 'balance_subtract';
            $notifyTemplate        = 'BAL_SUB';

            $toast[] = ['success', bs('cur_sym') . $amount . ' subtract success'];
        }

        $user->save();

        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx          =  $trx;
        $transaction->details      = request('remark');
        $transaction->save();

        notify($user, $notifyTemplate, [
            'trx'          => $trx,
            'amount'       => showAmount($amount),
            'remark'       => request('remark'),
            'post_balance' => showAmount($user->balance)
        ]);

        return back()->withToasts($toast);
    }

    function status($id) {
        $user = User::findOrFail($id);

        if ($user->status == ManageStatus::ACTIVE) {
            $this->validate(request(), [
                'ban_reason'=>'required|string|max:255'
            ]);

            $user->status = ManageStatus::INACTIVE;
            $user->ban_reason = request('ban_reason');
            $toast[] = ['success','User ban success'];
        }else{
            $user->status = ManageStatus::ACTIVE;
            $user->ban_reason = null;
            $toast[] = ['success','User unbanned success'];
        }

        $user->save();

        return back()->withToasts($toast);
    }

    function kycApprove($id) {
        $user     = User::findOrFail($id);
        $user->kc = ManageStatus::VERIFIED;
        $user->save();

        notify($user, 'KYC_APPROVE', []);

        $toast[] = ['success', 'KYC approval success'];
        return back()->withToasts($toast);
    }

    function kycCancel($id) {
        $user           = User::findOrFail($id);

        foreach ($user->kyc_data as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify').'/'.$kycData->value);
            }
        }

        $user->kc       = ManageStatus::UNVERIFIED;
        $user->kyc_data = null;
        $user->save();

        notify($user, 'KYC_REJECT', []);

        $toast[] = ['success', 'KYC cancellation success'];
        return back()->withToasts($toast);
    }

    protected function userData($pageTitle, $scope = null) {
        $users = User::when($scope, fn($query) => $query->$scope())->searchable(['username', 'email'])->dateFilter()->latest()->paginate(getPaginate());

        return view('admin.user.index', compact('pageTitle', 'users'));
    }
}
