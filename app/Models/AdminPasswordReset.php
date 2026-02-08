<?php

namespace App\Models;

use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class AdminPasswordReset extends Model
{   
    use UniversalStatus;

    protected $table = "admin_password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
