<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController as GatewayPaymentController;
use App\Models\Deposit;
use App\Models\Gateway;

class PaymentController extends Controller
{
    function index() {
        return $this->paymentData('All Payments', 'index', true);
    }

    function pending() {
        return $this->paymentData('Pending Payments', 'pending');
    }

    function done() {
        return $this->paymentData('Done Payments', 'done');
    }

    function canceled() {
        return $this->paymentData('Canceled Payments', 'canceled');
    }

    function approve($id) {
        $payment = Deposit::whereNot('plan_id', ManageStatus::EMPTY)->find($id);

        if (!$payment) {
            $toast[] = ['error', 'Payment not found'];
            return back()->withToasts($toast);
        }

        GatewayPaymentController::userDataUpdate($payment, true);

        $toast[] = ['success', 'Payment approval success'];
        return back()->withToasts($toast);
    }

    function cancel() {
        $this->validate(request(), [
            'id'             => 'required|integer|gt:0',
            'admin_feedback' => 'required|max:255'
        ]);

        $payment = Deposit::pending()->whereNot('plan_id', ManageStatus::EMPTY)->with('user')->find(request('id'));

        if (!$payment) {
            $toast[] = ['error', 'Payment not found'];
            return back()->withToasts($toast); 
        }

        $payment->status         = ManageStatus::PAYMENT_CANCEL;
        $payment->admin_feedback = request('admin_feedback');
        $payment->save(); 

        // Notify should use here 
        notify($payment->user, 'PAYMENT_REJECT', [
            'method_name'       => $payment->gatewayCurrency()->name,
            'method_currency'   => $payment->method_currency,
            'method_amount'     => showAmount($payment->final_amo),
            'amount'            => showAmount($payment->amount),
            'charge'            => showAmount($payment->charge),
            'rate'              => showAmount($payment->rate),
            'trx'               => $payment->trx,
            'rejection_message' => request('admin_feedback')
        ]);

        $toast[] = ['success', 'Payment cancellation success'];
        return back()->withToasts($toast);
    }

    protected function paymentData($pageTitle, $scope = null, $summery = false) {
        $payments = Deposit::when($scope, fn($query) => $query->$scope(), fn($query) => $query->index())->whereNot('plan_id', ManageStatus::EMPTY)->with(['user', 'gateway'])->searchable(['trx', 'user:username'])->dateFilter();

        // By Payment Method
        if (request('method')) {
            $method = Gateway::where('alias', request('method'))->firstOrFail();
            $payments->where('method_code', $method->code);
        }

        if (!$summery) {
            $payments = $payments->latest()->paginate(getPaginate());

            return view('admin.payment.index', compact('pageTitle', 'payments'));
        } 

        $done     = (clone $payments)->done()->sum('amount');
        $pending  = (clone $payments)->pending()->sum('amount');
        $canceled = (clone $payments)->canceled()->sum('amount');
        $charge   = (clone $payments)->sum('charge');
        $payments = $payments->latest()->paginate(getPaginate());

        return view('admin.page.payments', compact('pageTitle', 'payments', 'pending', 'done', 'canceled', 'charge'));
    }    
}
