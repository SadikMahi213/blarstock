<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;

class WithdrawController extends Controller
{
    function index() {
        return $this->withdrawalData('All Withdrawals', 'index', 'true');
    }

    function pending() {
        return $this->withdrawalData('Pending Withdrawals', 'pending');
    }

    function done() {
        return $this->withdrawalData('Done Withdrawals', 'done');
    }

    function canceled() {
        return $this->withdrawalData('Canceled Withdrawals', 'canceled');
    }

    function approve() {
        $this->validate(request(), ['id' => 'required|int|gt:0']);
        $withdraw                 = Withdrawal::where('id', request('id'))->pending()->with('user')->firstOrFail();
        $withdraw->status         = ManageStatus::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = request('admin_feedback');
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'admin_details'   => request('admin_feedback')
        ]);

        $toast[] = ['success', 'Withdrawal approval success'];
        return back()->withToasts($toast);
    }

    function cancel() {
        $this->validate(request(), [
            'id' => 'required|int|gt:0',
            'admin_feedback' => 'required|max:255',
        ]);
        
        $withdraw                 = Withdrawal::where('id', request('id'))->pending()->with('user')->firstOrFail();
        $withdraw->status         = ManageStatus::PAYMENT_CANCEL;
        $withdraw->admin_feedback = request('admin_feedback');
        $withdraw->save();

        $user = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = showAmount($withdraw->amount) . ' ' . bs('cur_text') . ' Refunded from withdrawal cancellation';
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw_reject';
        $transaction->save();

        notify($user, 'WITHDRAW_REJECT', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
            'admin_details'   => request('admin_feedback')
        ]);

        $toast[] = ['success', 'Withdrawal cancellation success'];
        return back()->withToasts($toast);
    }

    protected function withdrawalData($pageTitle, $scope = null, $summery = false) {
        $withdrawals = Withdrawal::when($scope, fn($query) => $query->$scope(), fn($query) => $query->index())->with(['method', 'user'])->searchable(['trx', 'user:username'])->dateFilter();

        if (request('method')) {
            $withdrawals->where('method_id', request('method'));
        }

        if (!$summery) {
            $withdrawals = $withdrawals->latest()->paginate(getPaginate());

            return view('admin.page.withdrawals', compact('pageTitle', 'withdrawals'));
        }

        $done        = (clone $withdrawals)->done()->sum('amount');
        $pending     = (clone $withdrawals)->pending()->sum('amount');
        $canceled    = (clone $withdrawals)->canceled()->sum('amount');
        $charge      = (clone $withdrawals)->sum('charge');
        $withdrawals = $withdrawals->latest()->paginate(getPaginate());

        return view('admin.page.withdrawals', compact('pageTitle', 'done', 'pending', 'canceled', 'charge', 'withdrawals'));
    }
}
