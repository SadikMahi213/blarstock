<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Models\Gateway;

class DepositController extends Controller
{
    function index() {
        return $this->depositData('All Deposits', 'index', true);
    }

    function pending() {
        return $this->depositData('Pending Deposits', 'pending');
    }

    function done() {
        return $this->depositData('Done Deposits', 'done');
    }

    function canceled() {
        return $this->depositData('Canceled Deposits', 'canceled');
    }

    function approve($id) {
        $deposit = Deposit::where('id', $id)->pending()->firstOrFail();
        PaymentController::userDataUpdate($deposit, true);

        $toast[] = ['success', 'Deposit approval success'];
        return back()->withToasts($toast);
    }

    function cancel() {
        $this->validate(request(), [
            'id' => 'required|int|gt:0',
            'admin_feedback' => 'required|max:255',
        ]);

        $deposit                 = Deposit::where('id', request('id'))->pending()->with('user')->firstOrFail();
        $deposit->status         = ManageStatus::PAYMENT_CANCEL;
        $deposit->admin_feedback = request('admin_feedback');
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name'       => $deposit->gatewayCurrency()->name,
            'method_currency'   => $deposit->method_currency,
            'method_amount'     => showAmount($deposit->final_amo),
            'amount'            => showAmount($deposit->amount),
            'charge'            => showAmount($deposit->charge),
            'rate'              => showAmount($deposit->rate),
            'trx'               => $deposit->trx,
            'rejection_message' => request('admin_feedback')
        ]);

        $toast[] = ['success', 'Deposit cancellation success'];
        return back()->withToasts($toast);
    }

    protected function depositData($pageTitle, $scope = null, $summery= false) {
        $deposits = Deposit::when($scope, fn($query) => $query->$scope(), fn($query) => $query->index())->with(['user', 'gateway'])->where('plan_id', ManageStatus::EMPTY)->where('donation_receiver_id', ManageStatus::EMPTY)->searchable(['trx', 'user:username'])->dateFilter();

        if (request('method')) {
            $method = Gateway::where('alias', request('method'))->firstOrFail();
            $deposits->where('method_code', $method->code);
        }

        if (!$summery) {
            $deposits = $deposits->latest()->paginate(getPaginate());

            return view('admin.page.deposits', compact('pageTitle', 'deposits'));
        }

        $done     = (clone $deposits)->done()->sum('amount');
        $pending  = (clone $deposits)->pending()->sum('amount');
        $canceled = (clone $deposits)->canceled()->sum('amount');
        $charge   = (clone $deposits)->sum('charge');
        $deposits = $deposits->latest()->paginate(getPaginate());

        return view('admin.page.deposits', compact('pageTitle', 'done', 'pending', 'canceled', 'charge', 'deposits'));
    }
}
