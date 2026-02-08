<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;

class PaymentController extends Controller {
    
    function deposit() {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
                                $gate->active();
                            })->with('method')->orderby('method_code')->get();

        $pageTitle = 'Deposit Methods';
        $siteData  = getSiteData('deposit.content', true);

        return view($this->activeTheme . 'user.deposit.create', compact('gatewayCurrency', 'pageTitle', 'siteData'));
    }

    function depositInsert() {
        $this->validate(request(), [
            'amount'   => 'required|numeric|gt:0',
            'gateway'  => 'required',
            'currency' => 'required',
            'type'     => 'required|in:payment,deposit',
            'plan_id'  => 'required_if:type,payment|integer|gt:0'
        ]);

        if (request('plan_id')) {
            $plan = Plan::active()->find(request('plan_id'));

            if (!$plan) {
                $toast[] = ['error', 'Plan not found'];
                return to_route('plan')->withToasts($toast);
            }

            $price = $plan->price;

            if ($price != request('amount')) {
                $toast[] = ['error', 'Amount must be equal to plan\'s ' . request('period') . ' price'];
                return to_route('plan')->withToasts($toast);
            }
        }
  
        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
                    $gate->active();
                })->where('method_code', request('gateway'))->where('currency', request('currency'))->first();


        if (!$gate) {
            $toast[] = ['error', 'Invalid gateway'];
            return back()->withToasts($toast);
        }

        if ($gate->min_amount > request('amount') || $gate->max_amount < request('amount')) {
            $toast[] = ['error', request('plan_id') ? 'Please follow the payment limit' : 'Please follow deposit limit'];
            return back()->withToasts($toast);
        }

        $charge    = $gate->fixed_charge + (request('amount') * $gate->percent_charge / 100);
        $payable   = request('amount') + $charge;
        $final_amo = $payable * $gate->rate;

        $deposit                  = new Deposit();
        $deposit->user_id         = $user->id;
        $deposit->plan_id         = request('plan_id') ?? 0;
        $deposit->method_code     = $gate->method_code;
        $deposit->method_currency = strtoupper($gate->currency);
        $deposit->amount          = request('amount');
        $deposit->charge          = $charge;
        $deposit->rate            = $gate->rate;
        $deposit->final_amo       = $final_amo;
        $deposit->btc_amo         = 0;
        $deposit->btc_wallet      = "";
        $deposit->trx             = getTrx();
        $deposit->save();

        session()->put('Track', $deposit->trx);
        return to_route('user.deposit.confirm');
    }

    function depositConfirm() {   
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->initiate()->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            if ($deposit->donation_receiver_id) {
                return to_route('donation.manual.confirm');
            }

            return to_route('user.deposit.manual.confirm');
        }
    
        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);
        
        if (isset($data->error)) {
            $toast[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withToasts($toast);
        }
        
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }
        // for Stripe V3
        if(isset($data->session)){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }
        
        $pageTitle = $deposit->donation_receiver_id ? 'Donation Confirm' : ($deposit->plan_id ? 'Payment Confirm' : 'Deposit Confirm');

        return view($this->activeTheme . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    static function userDataUpdate($deposit, $isManual = null) {
        if ($deposit->status == ManageStatus::PAYMENT_INITIATE || $deposit->status == ManageStatus::PAYMENT_PENDING) {
            if ($deposit->donation_receiver_id) {

                $donationReceiver = User::findOrFail($deposit->donation_receiver_id);

                $deposit->status = ManageStatus::PAYMENT_SUCCESS;
                $deposit->save();
                
                $donationReceiver->balance += $deposit->amount;
                $donationReceiver->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $donationReceiver->id;
                $transaction->amount       = $deposit->amount;
                $transaction->post_balance = $donationReceiver->balance;
                $transaction->charge       = $deposit->charge;
                $transaction->trx_type     = '+';
                $transaction->details      = 'Received donation from ' . $deposit?->donation_sender?->name ?? '';
                $transaction->trx          = $deposit->trx;
                $transaction->remark       = 'donation';
                $transaction->save();

                notify($donationReceiver, 'DONATION_RECEIVE', [
                    'author_name'     => $donationReceiver->author_name,
                    'donation_amount' => $deposit->amount,
                    'donor_name'      => $donationReceiver->username,
                    'donor_email'     => $donationReceiver->email,
                    'asset_link'      => route('asset.detail', [encrypt($deposit->image_id), slug($deposit->image->title)])
                ]);
            } else {
                $deposit->status = ManageStatus::PAYMENT_SUCCESS;
                $deposit->save();

                $user           = User::find($deposit->user_id);
                $user->balance += $deposit->amount;
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id      = $deposit->user_id;
                $transaction->amount       = $deposit->amount;
                $transaction->post_balance = $user->balance;
                $transaction->charge       = $deposit->charge;
                $transaction->trx_type     = '+';
                $transaction->details      = 'Deposit Via ' . $deposit->gatewayCurrency()->name;
                $transaction->trx          = $deposit->trx;
                $transaction->remark       = 'deposit';
                $transaction->save();

                if ($deposit->plan_id) {
                    $plan = Plan::active()->find($deposit->plan_id);

                    if (!$plan) {
                        $toast[] = ['error', 'Plan not found'];
                        return to_route('plan')->withToasts($toast);
                    }

                    $user->balance           -= $deposit->balance;
                    $user->plan_id            = $plan->id;
                    $user->plan_expired_date  = addPlanDuration($plan->plan_duration);
                    $user->save();

                    // Transaction Log
                    $transaction               = new Transaction();
                    $transaction->user_id      = $deposit->user_id;
                    $transaction->amount       = $deposit->amount;
                    $transaction->post_balance = $user->balance;
                    $transaction->charge       = 0;
                    $transaction->trx_type     = '-';
                    $transaction->details      = 'Secure payment for the ' . $plan->name . ' plan activation';
                    $transaction->trx          = $deposit->trx;
                    $transaction->remark       = 'payment';
                    $transaction->save();

                    if (bs('referral') && $user->ref_by) {
                        levelCommission($user, $plan->price, 'plan_purchase');
                    }

                    session()->put('plan_purchase', ['trx' => $deposit->trx, 'plan_id' => $plan->id]);

                    notify($user, $isManual ? 'PLAN_PAYMENT_APPROVE' : 'PLAN_PAYMENT_COMPLETE', [
                        'plan_name'       => $plan->title,
                        'method_name'     => $deposit->gatewayCurrency()->name,
                        'method_currency' => $deposit->method_currency,
                        'method_amount'   => showAmount($deposit->final_amo),
                        'amount'          => showAmount($deposit->amount),
                        'charge'          => showAmount($deposit->charge),
                        'rate'            => showAmount($deposit->rate),
                        'trx'             => $deposit->trx,
                        'post_balance'    => showAmount($user->balance)
                    ]);
                }

                if (!$isManual) {
                    $adminNotification            = new AdminNotification();
                    $adminNotification->user_id   = $user->id;
                    $adminNotification->title     = ($deposit->plan_id ? 'Plan payment via ' : 'Deposit successful via ') . $deposit->gatewayCurrency()->name;
                    $adminNotification->click_url = $deposit->plan_id ? urlPath('admin.payment.done') : urlPath('admin.deposit.done');
                    $adminNotification->save();
                }

                if ($deposit->plan_id == ManageStatus::EMPTY || $deposit->donation_receiver_id == ManageStatus::EMPTY) {
                    notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                        'method_name'     => $deposit->gatewayCurrency()->name,
                        'method_currency' => $deposit->method_currency,
                        'method_amount'   => showAmount($deposit->final_amo),
                        'amount'          => showAmount($deposit->amount),
                        'charge'          => showAmount($deposit->charge),
                        'rate'            => showAmount($deposit->rate),
                        'trx'             => $deposit->trx,
                        'post_balance'    => showAmount($user->balance)
                    ]);
                }
            }
        }
    }

    function manualDepositConfirm() {
        $track   = session()->get('Track');
        $deposit = Deposit::with('gateway')->initiate()->where('trx', $track)->first();

        if (!$deposit) {
            abort(404);
        }

        if ($deposit->method_code > 999) {
            $pageTitle = $deposit->donation_receiver_id ? 'Donation Confirm' : ($deposit->plan_id ? 'Payment Confirm' : 'Deposit Confirm');
            $method    = $deposit->gatewayCurrency();
            $gateway   = $method->method;

            return view($this->activeTheme . 'user.payment.manual', compact('deposit', 'pageTitle', 'method', 'gateway'));
        }

        abort(404);
    }

    function manualDepositUpdate() {
        $track   = session()->get('Track');
        $deposit = Deposit::with('gateway')->initiate()->where('trx', $track)->first();

        if (!$deposit) {
            abort(404);
        }

        $gatewayCurrency = $deposit->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        request()->validate($validationRule);
        $userData = $formProcessor->processFormData(request(), $formData);

        $deposit->detail = $userData;
        $deposit->status = ManageStatus::PAYMENT_PENDING;
        $deposit->save();

        $notifyBody = [
            'method_name'     => $deposit->gatewayCurrency()->name,
            'method_currency' => $deposit->method_currency,
            'method_amount'   => showAmount($deposit->final_amo),
            'amount'          => showAmount($deposit->amount),
            'charge'          => showAmount($deposit->charge),
            'rate'            => showAmount($deposit->rate),
            'trx'             => $deposit->trx
        ];

        if ($deposit->plan_id) {
            $plan = Plan::active()->find($deposit->plan_id);
            
            if (!$plan) {
                $toast[] = ['error', 'Plan not found'];
                return to_route('plan')->withToasts($toast);
            }

            $notifier   = $deposit->user;
            $notifyType = 'PLAN_PAYMENT_REQUEST';
            $clickUrl   = urlPath('admin.payment.pending');
            $title      = 'Plan payment request from ' . $deposit->user->username;

            session()->put('plan_purchase', ['trx' => $deposit->trx, 'plan_id' => $plan->id]);

        } else if ($deposit->donation_receiver_id) {
            $notifier   = $deposit->donationReceiver; 
            $notifyType = 'DONATION_REQUEST';
            $clickUrl   = urlPath('admin.donation.pending');
            $title      = 'Donation request from ' . $deposit?->donation_sender?->name ?? '';
        
        } else {
            $notifier   = $deposit->user;
            $notifyType = 'DEPOSIT_REQUEST';
            $clickUrl   = urlPath('admin.deposit.pending');
            $title      = 'Deposit request from ' . $deposit->user->username;
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $deposit->user_id;
        $adminNotification->title     = $title;
        $adminNotification->click_url = $clickUrl;
        $adminNotification->save();

        notify($notifier, $notifyType, $notifyBody);

        $message = $deposit->donation_receiver_id ? 'Your donation has been taken' : ($deposit->plan_id ? 'You have payment request has been taken' : 'You have deposit request has been taken');
        $toRoute = $deposit->donation_receiver_id ? to_route('asset.detail', [encrypt($deposit->image_id), slug($deposit->image->title)]) :($deposit->plan_id ? to_route('user.payment.history') : to_route('user.deposit.history'));

        $toast[] = ['success', $message];
        return $toRoute->withToasts($toast);
    }

    function payment() {
        $plan = Plan::active()->find(request('plan_id'));

        if (!$plan) {
            $toast[] = ['error', 'Plan not found'];
            return to_route('plan')->withToasts($toast);
        }

        $pageTitle       = 'Payment Methods';
        $gatewayCurrency = GatewayCurrency::whereHas('method', fn($gate) => $gate->active())->with('method')->orderBy('method_code')->get();
        $amount          = $plan->price;
        $siteData        = getSiteData('plan_payment.content', true);

        return view($this->activeTheme . 'user.deposit.planPayment', compact('pageTitle', 'plan', 'amount', 'gatewayCurrency', 'siteData'));
    }
}
