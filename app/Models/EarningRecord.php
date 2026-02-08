<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class EarningRecord extends Model
{
    use Searchable;

    function author() {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    function imageFile() {
        return $this->belongsTo(ImageFile::class);
    }
}
