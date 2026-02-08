<?php

namespace App\Events;

use App\Models\ImageFile;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DownloadCompleted
{
    use Dispatchable, SerializesModels;


    public $file;
    public $user;
    public $downloadId;
    /**
     * Create a new event instance.
     */
    public function __construct(ImageFile $file, User $user, int $downloadId) {
        $this->file       = $file;
        $this->user       = $user;
        $this->downloadId = $downloadId;
    }
}
