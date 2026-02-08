<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class ImageFile extends Model
{
    use UniversalStatus, Searchable;

    function image() {
        return $this->belongsTo(Image::class);
    }

    function resolution() {
        return $this->belongsTo(Resolution::class);
    }

    public function scopeActive($query) {
        return $query->where('status', ManageStatus::ACTIVE);
    }

    public function scopePremium($query) {
        return $query->where('is_free', ManageStatus::PREMIUM);
    }

    public function scopeFree($query) {
        return $query->where('is_free', ManageStatus::FREE);
    }
}
