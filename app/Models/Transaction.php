<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use Searchable;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUserDashboard($query) {
        return $query->select('trx', 'amount', 'trx_type', 'created_at');
    }
}
