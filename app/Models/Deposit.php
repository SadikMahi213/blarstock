<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Deposit extends Model
{
    use UniversalStatus, Searchable;

    protected $casts = [
        'detail'          => 'object',
        'donation_sender' => 'object'
    ];

    protected $hidden = ['detail'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function donationReceiver() {
        return $this->belongsTo(User::class, 'donation_receiver_id', 'id');
    }

    public function image() {
        return $this->belongsTo(Image::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';

            if($this->status == ManageStatus::PAYMENT_PENDING){
                $html = '<span class="badge badge--warning">'.trans('Pending').'</span>';
            }
            elseif($this->status == ManageStatus::PAYMENT_SUCCESS && $this->method_code >= 1000){
                $html = '<span class="badge badge--success">'.trans('Approved').'</span><br><span>'.diffForHumans($this->updated_at).'</span>';
            }
            elseif($this->status == ManageStatus::PAYMENT_SUCCESS && $this->method_code < 1000){
                $html = '<span class="badge badge--success">'.trans('Done').'</span>';
            }
            elseif($this->status == ManageStatus::PAYMENT_CANCEL){
                $html = '<span class="badge badge--danger">'.trans('Rejected').'</span><br><span>'.diffForHumans($this->updated_at).'</span>';
            }else{
                $html = '<span class="badge badge--dark">'.trans('Initiated').'</span>';
            }

            return $html;
        });
    }

    // Scope
    public function scopeGatewayCurrency()
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function scopeBaseCurrency()
    {
        $gateway = $this->gateway;

        return ($gateway && $gateway->crypto == ManageStatus::ACTIVE) ? 'USD' : $this->method_currency;
    }

    public function scopePending($query)
    {
        return $query->where('method_code', '>=' ,1000)->where('status', ManageStatus::PAYMENT_PENDING);
    }

    public function scopeCanceled($query)
    {
        return $query->where('method_code', '>=', 1000)->where('status', ManageStatus::PAYMENT_CANCEL);
    }

    public function scopeDone($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_SUCCESS);
    }

    public function scopeIndex($query)
    {
        return $query->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function scopeInitiate($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_INITIATE);
    }
    
    public function scopePlan($query) {
        return $query->where('plan_id', '!=', ManageStatus::EMPTY);
    }
}
