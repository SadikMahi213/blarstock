<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteData extends Model
{
    protected $casts = [
        'data_info' => 'object'
    ];
}
