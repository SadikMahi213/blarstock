<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use Searchable;

    protected $casts = [
        'sender' => 'object'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function image() {
        return $this->belongsTo(Image::class);
    }

    public function deposit() {
        return $this->hasOne(Deposit::class);
    }

    public function scopePending($query) {
        return $query->where('status', ManageStatus::DONATION_PENDING);
    }

    public function scopeApproved($query) {
        return $query->where('status', ManageStatus::DONATION_APPROVED);
    }

    public function scopeRejected($query) {
        return $query->where('status', ManageStatus::DONATION_REJECTED);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            function() {
                $html = '';

                if ($this->status == ManageStatus::DONATION_APPROVED) {
                    $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
                
                } elseif ($this->status == ManageStatus::DONATION_PENDING) {
                    $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
                
                } elseif ($this->status == ManageStatus::DONATION_REJECTED) {
                    $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
                }

                return $html;
            }
        );
    }
}
