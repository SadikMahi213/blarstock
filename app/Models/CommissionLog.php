<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionLog extends Model
{
    public function fromUser() {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }

    public function toUser() {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }
}
