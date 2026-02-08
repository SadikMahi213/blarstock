<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use Searchable, UniversalStatus;

    public function collectionImages() {
        return $this->hasMany(CollectionImage::class);
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'collection_images')->where('status', ManageStatus::IMAGE_APPROVED)->whereHas('category', fn($q) => $q->where('status', ManageStatus::ACTIVE));
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopePublic($query) {
        return $query->where('visibility', ManageStatus::ACTIVE);
    }
}
