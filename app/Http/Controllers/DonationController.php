<?php

namespace App\Http\Controllers;

use App\Constants\ManageStatus;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Image;
use App\Models\ImageFile;
use App\Models\Transaction;
use App\Models\User;

class DonationController extends Controller
{
    function insert($assetId) {
        if (!bs('donation')) {
            $toast[] = ['error', 'Donation not enabled'];
            return back()->withToasts($toast);
        }

        $asset = Image::find($assetId);

        if (!$asset) {
            $toast[] = ['error', 'Asset not found'];
            return back()->withToasts($toast);
        }

        if ($asset->status != ManageStatus::IMAGE_APPROVED) {
            $toast[] = ['error', $asset->title . ' not approved asset'];
            return back()->withToasts($toast);
        }

        $authUser = auth()->user();

        if ($authUser && $asset->user_id == $authUser->id) {
            $toast[] = ['error', 'Invalid action'];
            return back()->withToasts($toast);
        }

        $fileExistence = ImageFile::active()->where('image_id', $asset->id)->exists();

        if (!$fileExistence) {
            $toast[] = ['error', 'No active file found for the requested asset'];
            return back()->withToasts($toast);
        }

        $donationValidation = $authUser ? 'nullable' : 'required';

        $this->validate(request(),[
            'gateway'  => 'required',
            'quantity' => 'required|integer|gt:0',
            'name'     => $donationValidation . '|string|max:240',
            'email'    => $donationValidation . '|email',
            'mobile'   => $donationValidation
        ]);

        if (request('gateway') == 'balance' && !$authUser) {
            $toast[] = ['error', 'Invalid action'];
            return back()->withToasts($toast);
        }

        $gateway = null;

        $donationAmount = request('quantity') * @bs('donation_setting')?->amount;

        $donationSender = [
            'name'   => $authUser->fullname ?? request('name'),
            'email'  => $authUser->email ?? request('email'),
            'mobile' => $authUser->mobileNumber ?? request('mobile')
        ];

        if (request('gateway') == 'balance') {
            if ($authUser->balance < $donationAmount) {
                $toast[] = ['error', 'Insufficient balance'];
                return back()->withToasts($toast);
            }

            return $this->donationViaWalletBalance( $donationAmount, $donationSender, $asset);
        } else {
            $gateway = GatewayCurrency::whereHas('method', fn($gate) => $gate->where('status', ManageStatus::ACTIVE))->where('method_code', request('gateway'))->where('currency', request('currency'))->first();

            if (!$gateway) {
                $toast[] = ['error', 'Invalid gateway'];
                return back()->withToasts($toast);
            }

            return $this->donationViaGateway($donationAmount, $donationSender, $asset, $gateway);
        }
    }

    protected function donationViaWalletBalance($donationAmount, $donationSender, $asset) {
        $receiver = User::findOrFail($asset->user_id);
        $sender   = auth()->user();
        $trx      = getTrx();

        $sender->balance -= $donationAmount;
        $sender->save();
        
        $receiver->balance += $donationAmount;
        $receiver->save();

        $deposit                       = new Deposit();
        $deposit->user_id              = $sender->id;
        $deposit->donation_receiver_id = $receiver->id;
        $deposit->image_id             = $asset->id;
        $deposit->donation_sender      = $donationSender;
        $deposit->amount               = $donationAmount;
        $deposit->final_amo            = $donationAmount;
        $deposit->trx                  = $trx;
        $deposit->payment_info         = 'Wallet balance';
        $deposit->method_currency      = bs('site_cur');
        $deposit->status               = ManageStatus::PAYMENT_SUCCESS;
        $deposit->save();

        $transactions = [
            [
                'user_id'      => $sender->id,
                'amount'       => $donationAmount,
                'post_balance' => getAmount($sender->balance),
                'charge'       => 0,
                'trx_type'     => '-',
                'details'      => 'Send donation to ' . $receiver->author_name,
                'trx'          => $trx,
                'remark'       => 'donation',
                'created_at'   => now(),
                'updated_at'   => now()
            ],
            [
                'user_id'      => $receiver->id,
                'amount'       => $donationAmount,
                'post_balance' => $receiver->balance,
                'charge'       => 0,
                'trx_type'     => '+',
                'details'      => 'Received donation from ' . $sender->username,
                'remark'       => 'donation',
                'trx'          => $trx,
                'created_at'   => now(),
                'updated_at'   => now()
            ]
        ];

        Transaction::insert($transactions);

        notify($receiver, 'DONATION_RECEIVE', [
            'author_name'     => $receiver->author_name,
            'donation_amount' => $donationAmount,
            'donor_name'      => $sender->username,
            'donor_email'     => $sender->email,
            'asset_link'      => route('asset.detail', [encrypt($asset->id), slug($asset->title)])
        ]);

        $toast[] = ['success', 'Donation to ' . $receiver->author_name . ' success'];
        return back()->withToasts($toast)->throwResponse();
    }

    protected function donationViaGateway($donationAmount, $donationSender, $asset, $gateway) {
        $charge      = $gateway->fixed_charge + ($donationAmount * $gateway->percent_charge / 100);
        $payable     = $donationAmount + $charge;
        $finalAmount = $payable * $gateway->rate;

        $deposit                       = new Deposit();
        $deposit->user_id              = auth()->check() ? auth()->id() : 0;
        $deposit->image_id             = $asset->id;
        $deposit->donation_receiver_id = $asset->user_id;
        $deposit->donation_sender      = $donationSender;
        $deposit->payment_info         = $gateway->name;
        $deposit->method_code          = $gateway->method_code;
        $deposit->method_currency      = strtoupper($gateway->currency);
        $deposit->amount               = $donationAmount;
        $deposit->charge               = $charge;
        $deposit->rate                 = $gateway->rate;
        $deposit->final_amo            = $finalAmount;
        $deposit->btc_amo              = 0;
        $deposit->btc_wallet           = "";
        $deposit->trx                  = getTrx();
        $deposit->save();

        session()->put('Track', $deposit->trx);

        return to_route('donation.confirm');
    }
}
