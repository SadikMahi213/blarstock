<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    function user() {
        return $this->belongsTo(User::class);
    }

    function image() {
        return $this->belongsTo(Image::class);
    }
}
