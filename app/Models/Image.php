<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use UniversalStatus, Searchable;

    protected $casts = [
        'tags'       => 'array',
        'extensions' => 'array',
        'colors'     => 'array'
    ];

    protected $appends = ['image_url', 'status_badge'];

    public function getImageUrlAttribute()
    {
        return $this->getImageUrl();
    }

    function user() {
        return $this->belongsTo(User::class);
    }

    function admin() {
        return $this->belongsTo(Admin::class);
    }

    function reviewer() {
        return $this->belongsTo(Reviewer::class);
    }

    function fileType() {
        return $this->belongsTo(FileType::class);
    }

    function category() {
        return $this->belongsTo(Category::class);
    }

    function imageFiles() {
        return $this->hasMany(ImageFile::class);
    }

    function likes() {
        return $this->hasMany(Like::class);
    }

    function collectionImages() {
        return $this->hasMany(CollectionImage::class);
    }

    function downloads() {
        return $this->hasManyThrough(Download::class, ImageFile::class);
    }

    public function scopePending($query) {
        return $query->where('status', ManageStatus::IMAGE_PENDING);
    }

    public function scopeApproved($query) {
        return $query->where('status', ManageStatus::IMAGE_APPROVED);
    }

    public function scopeRejected($query) {
        return $query->where('status', ManageStatus::IMAGE_REJECTED);
    }

    public function scopeActiveCheck($query) {
        return $query->where('status', ManageStatus::IMAGE_APPROVED)->whereHas('category', fn($q) => $q->where('status', ManageStatus::ACTIVE))->whereHas('fileType', fn($q) => $q->active())->whereHas('user', fn($q) => $q->where('status', ManageStatus::ACTIVE)->where('author_status', ManageStatus::AUTHOR_APPROVED));
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            function() {
                $html = '';

                if ($this->status == ManageStatus::IMAGE_APPROVED) {
                    $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
                } elseif ($this->status == ManageStatus::IMAGE_PENDING) {
                    $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
                } else {
                    $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
                }

                return $html;
            }
        );
    }

    /**
     * Get primary image file for gallery display
     *
     * @return ImageFile|null
     */
    public function getPrimaryFile()
    {
        return $this->imageFiles()->first();
    }

    /**
     * Get image URL for gallery display
     *
     * @param string $size
     * @return string
     */
    public function getImageUrl($version = 'preview')
    {
        // Use existing helper functions
        if ($this->image_name) {
            return imageUrl(getFilePath('stockImage'), $this->image_name, $version);
        }
        
        return ''; // Return empty string for placeholder
    }

    /**
     * Get thumbnail URL for gallery display
     *
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->getImageUrl('thumbnail');
    }

    /**
     * Scope for gallery-approved images only
     *
     * @param $query
     * @return mixed
     */
    public function scopeGalleryApproved($query)
    {
        return $query->where('status', ManageStatus::IMAGE_APPROVED)
                     ->whereHas('category', fn($q) => $q->where('status', ManageStatus::ACTIVE))
                     ->whereHas('fileType', fn($q) => $q->active())
                     ->whereHas('user', fn($q) => $q->where('author_status', ManageStatus::AUTHOR_APPROVED)
                                               ->where('status', ManageStatus::ACTIVE));
    }
}
