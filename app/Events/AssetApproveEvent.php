<?php

namespace App\Events;

use App\Models\Image;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssetApproveEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }
    
}
