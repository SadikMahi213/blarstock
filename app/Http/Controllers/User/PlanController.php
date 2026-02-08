<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Transaction;
use Carbon\Carbon;

class PlanController extends Controller
{
    function purchase() {

        $this->validate(request(), [
            'plan_id'      => 'required|integer|gt:0',
            'payment_type' => 'required|in:wallet,direct'
        ], [
            'payment_type.in' => 'Invalid payment type'
        ]);

        $plan = Plan::active()->find(request('plan_id'));

        if (!$plan) {
            $toast[] = ['error', 'Plan not found'];
            return back()->withToasts($toast);
        }

        $user = auth()->user();

        if (($user->plan_id == $plan->id) && ($user->plan_expired_date && Carbon::parse($user->plan_expired_date)->isFuture())) {
            $toast[] = ['error', 'This plan has already been purchased'];
            return back()->withToasts($toast);
        }

        if (request('payment_type') == 'wallet') {

            if ($plan->price > $user->balance) {
                $toast[] = ['error', 'Insufficient balance'];
                return back()->withToasts($toast);
            }

            $this->action($plan);

            $toast[] = ['success', 'Plan purchased success'];
            return back()->withToasts($toast);
        } else {
            return to_route('user.deposit.payment', ['plan_id' => $plan->id]);
        }


    }

    protected function action($plan) {
        $user    = auth()->user();
        $trx     = getTrx();
        $setting = bs();

        $user->balance -= $plan->price;
        $user->plan_id  = $plan->id;
        $user->plan_expired_date = addPlanDuration($plan->plan_duration);
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $plan->price;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->details      = $plan->name . ' plan purchased by ' . $user->username;
        $transaction->remark       = 'plan_purchase';
        $transaction->save();

        if ($setting->referral && $user->ref_by) {
            levelCommission($user, $plan->price, 'plan_purchase');
        }
    }

    function existingPlanCheck() {
        $user = auth()->user();
        $plan = $user->plan;

        if ($plan && ($plan->status == ManageStatus::ACTIVE) && ($user->plan_expired_date && Carbon::parse($user->plan_expired_date)->isFuture())) {
            return response([
                'success'      => true,
                'planTitle'    => $plan->title,
                'existingTime' => $user->plan_expired_date
            ]);
        }
        
        return response([
            'success' => false
        ]);
    }
}
