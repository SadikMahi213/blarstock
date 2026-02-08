<?php

namespace App\Models;

use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    use UniversalStatus, UniversalStatus;

    public function user() {
        return $this->belongsTo(User::class);
    }

}
