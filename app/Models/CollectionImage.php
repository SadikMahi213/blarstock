<?php

namespace App\Models;

use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Model;

class CollectionImage extends Model
{
    public function image() {
        return $this->belongsTo(Image::class);
    }

    public function activeCheck() {
        return $this->image()->where('status', ManageStatus::IMAGE_APPROVED)->whereHas('category', fn($q) => $q->where('status', ManageStatus::ACTIVE));
    }
}
