<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPurchase extends Model
{
    function user() {
        return $this->belongsTo(User::class);
    }

    function plan() {
        return $this->belongsTo(Plan::class);
    }

    function transaction() {
        return $this->belongsTo(Transaction::class, 'trx', 'trx');
    }
}
