<?php

namespace App\Models;

use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class ReviewerPasswordReset extends Model
{
    use UniversalStatus;

    protected $table      = "reviewer_password_resets";
    protected $guarded    = ['id'];
    public    $timestamps = false;
}
