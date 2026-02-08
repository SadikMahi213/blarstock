<?php

namespace App\Lib;

class DownloadFile {
    
    public static function download($file, $type = 'file') {
        $filePathMethod = $type == 'video' ? 'videoFileUrl' : 'fileUrl';
        $fileKey        = $type == 'video' ? 'video' : 'file';

        if ($file->$fileKey == null) {
            abort($fileKey == 'video' ? 400 : 404, "The requested {$fileKey} is no longer available for download");
        }

        $setting   = bs();
        $filePath  = $filePathMethod($file->$fileKey);
        $parseUrl  = parse_url($filePath);
        $extension = getExtension($parseUrl['path'] ?? $filePath);
        $fileName  = $setting->site_name . '_' . $file->track_id . '_' . $file->resolution . '.' . $extension;
        $fileName  = str_replace('/', '-', $fileName);

        if ($setting->storage_type == 1) {
            $headers = [
                'Content-Type'  => 'application/octet-stream',
                'Cache-Control' => 'no-store, no-cache'
            ];

            file_get_contents($filePath);

            return response()->download($filePath, $fileName, $headers);
        } else {
            header('Content-type: application/octet-stream');
            header('Content-disposition: attachment; filename=' . $fileName);

            while (ob_get_level()) {
                ob_get_clean();
            }

            readfile($filePath);
        }
    }
}
