<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class GatewayCurrency extends Model
{
    use UniversalStatus;

    protected $hidden = [
        'gateway_parameter'
    ];

    protected $casts = ['status' => 'boolean'];

    public function method()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function scopeBaseSymbol()
    {
        return $this->method->crypto == ManageStatus::ACTIVE ? '$' : $this->symbol;
    }
}
