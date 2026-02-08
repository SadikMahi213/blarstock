<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use UniversalStatus, Searchable;

    protected $casts = [
        'supported_file_extension' => 'array'
    ];

    function images() {
        return $this->hasMany(Image::class);
    }

    function approvedImages() {
        return $this->images()->where('status', ManageStatus::IMAGE_APPROVED);
    }

    function categories() {
        return $this->belongsToMany(Category::class, 'images', 'file_type_id', 'category_id')
            ->where('images.status', ManageStatus::ACTIVE)
            ->where('categories.status', ManageStatus::ACTIVE)
            ->withCount('images as category_image')
            ->orderByDesc('category_image')
            ->distinct();
    }

    public function scopeInactive($query) {
        return $query->where('status', ManageStatus::INACTIVE);
    }

    function scopeApprovedImageCount($query) {
        return $query->withCount(['images' => fn($image) => $image->approved()]);
    }
}
