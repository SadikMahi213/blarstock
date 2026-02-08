<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reviewer extends Authenticatable
{
    use Notifiable, UniversalStatus, Searchable;

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function approvedImages() {
        return $this->images()->where('status', ManageStatus::IMAGE_APPROVED);
    }

    public function rejectedImages() {
        return $this->images()->where('status', ManageStatus::IMAGE_REJECTED);
    }

    public function scopeInactive($query) {
        return $query->where('status', ManageStatus::INACTIVE);
    }
}
