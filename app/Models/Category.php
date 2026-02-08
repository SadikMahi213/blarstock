<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use UniversalStatus, Searchable;

    function images() {
        return $this->hasMany(Image::class);
    }

    function approvedImages() {
        return $this->images()->where('status', ManageStatus::IMAGE_APPROVED);
    }

    public function scopeInactive($query) {
        return $query->where('status', ManageStatus::INACTIVE);
    }
}
