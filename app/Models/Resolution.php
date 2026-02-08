<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    use Searchable, UniversalStatus;

    public function scopeInactive($query) {
        return $query->where('status', ManageStatus::INACTIVE);
    }
}
