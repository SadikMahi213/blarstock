<?php

namespace App\Lib;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class StorageManager
{
    public $server;

    protected $driver;

    protected $file;

    public $path;

    public $size;

    protected $isImage;

    public $thumb;

    public $old;

    public $filename;
    /**
     * Create a new class instance.
     */
    public function __construct($server, $file = null)
    {
        $this->file = $file;

        if ($file) {
            $imageExtensions = ['png', 'jpg', 'jpeg', 'JPG', 'PNG', 'JPEG'];

            $this->isImage = in_array($file->getClientOriginalExtension(), $imageExtensions) ? true : false;
        }

        $this->setConfiguration($server);

        $this->driver = Storage::disk($server);
    }

    private function setConfiguration($server) {
        $setting      = bs();
        $this->server = $server;
        $method       = $server . 'Configuration';
        $this->$method($setting);
    }

    public function upload() {
        $path = $this->makeDirectory();

        if (!$path) throw new \Exception('File creation failed');

        $fileName = $this->getFileName();
        $this->filename = $fileName;

        if ($this->isImage == true) {
            $this->uploadImage();
        } else {
            $this->uploadFile();
        }
    }

    public function uploadImage($image = null, $filename = null, $isThumb = false) {
        if ($filename) {
            $this->filename = $filename;
        }

        if ($isThumb) {
            $this->thumb = true;
            $separator   = '/thumb_';
        } else {
            $this->thumb = false;
            $separator   = '/';
        }

        if ($this->old) {
            $this->removeFile();
        }

        $image = $image->stream();
        $this->driver->put($this->path . $separator . $this->filename, $image->_toString());
    }

    protected function uploadFile() {
        if ($this->old) {
            $this->removeFile();
        }

        $this->driver->put($this->path . '/' . $this->filename, fopen($this->file, 'r+'));
    }

    public function removeFile($path = null) {
        if (str_contains($this->old, '/')) {
            $files     = explode('/', $this->old );
            $this->old = end($files);
        }

        if ($this->thumb) {
            $path = $this->path . '/thumb_' . $this->old;
        }

        if (!$path) $path = $this->path . '/' . $this->old;

        if ($this->driver->exists($path)) {
            $this->driver->delete($path);
        }
    }

    protected function getFileName() {
        return uniqid() . time() . '.' . $this->file->getClientOriginalExtension();
    }

    protected function makeDirectory() {
        $test = $this->driver->exists($this->path);

        if ($test) {
            return $this->path;
        }

        return $this->driver->makeDirectory($this->path);
    }

    private function ftpConfiguration($setting) {
        Config::set('filesystems.disks.ftp', [
            'driver'   => 'ftp',
            'host'     => $setting?->ftp?->host ?? '',
            'username' => $setting?->ftp?->username ?? '',
            'password' => $setting?->ftp?->password ?? '',
            'port'     => (int)$setting?->ftp?->port ?? '',
            'root'     => $setting?->ftp?->root_path ?? '',
        ]);
    }

    private function wasabiConfiguration($setting) {
        Config::set('filesystems.disks.wasabi', [
            'driver'   => $setting->wasabi->driver,
            'key'      => $setting->wasabi->key,
            'secret'   => $setting->wasabi->secret,
            'region'   => $setting->wasabi->region,
            'bucket'   => $setting->wasabi->bucket,
            'endpoint' => $setting->wasabi->endpoint
        ]);
    }

    private function doConfiguration($setting) {
        Config::set('filesystems.disks.do', [
            'driver'   => $setting->digital_ocean->driver,
            'key'      => $setting->digital_ocean->key,
            'secret'   => $setting->digital_ocean->secret,
            'region'   => $setting->digital_ocean->region,
            'bucket'   => $setting->digital_ocean->bucket,
            'endpoint' => $setting->digital_ocean->endpoint
        ]);
    }

    private function vultrConfiguration($setting) {
        Config::set('filesystems.disks.do', [
            'driver'   => $setting->vultr->driver,
            'key'      => $setting->vultr->key,
            'secret'   => $setting->vultr->secret,
            'region'   => $setting->vultr->region,
            'bucket'   => $setting->vultr->bucket,
            'endpoint' => $setting->vultr->endpoint
        ]);
    }
}
