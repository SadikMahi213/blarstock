<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use Searchable;
    
    function user() {
        return $this->belongsTo(User::class);
    }

    function imageFile() {
        return $this->belongsTo(ImageFile::class);
    }

    function author() {
        return $this->belongsTo(User::class, 'author_id');
    }
}
