<?php

namespace App\Constants;

class FileDetails {
    function fileDetails() {
        $data['logoFavicon'] = [
            'path' => 'assets/universal/images/logoFavicon',
        ];

        $data['favicon'] = [
            'size' => '128x128',
        ];

        $data['seo'] = [
            'path' => 'assets/universal/images/seo',
            'size' => '1180x600',
        ];

        $data['adminProfile'] = [
            'path' => 'assets/admin/images/profile',
            'size' => '200x200',
        ];

        $data['reviewerProfile'] = [
            'path' => 'assets/reviewer/images/profile',
            'size' => '200x200',
        ];

        $data['userProfile'] = [
            'path' => 'assets/user/images/profile',
            'size' => '200x200',
        ];

        $data['userCover'] = [
            'path' => 'assets/user/images/cover',
            'size' => '1920x500'
        ];

        $data['plugin'] = [
            'path' => 'assets/admin/images/plugin'
        ];
        
        $data['verify'] = [
            'path'      =>'assets/verify'
        ];

        $data['advertisements'] = [
            'path' => 'assets/universal/images/advertisements'
        ];

        $data['categories'] = [
            'path' => 'assets/universal/images/categories',
            'size' => '150x150'
        ];

        $data['fileTypes'] = [
            'path' => 'assets/universal/images/fileTypes'
        ];

        $data['plans'] = [
            'path' => 'assets/universal/images/plans',
            'size' => '100x100'
        ];

        $data['watermarkImage'] = [
            'path' => 'assets/universal/images/watermark',
            'size' => '800x800'
        ];

        $data['instructionManual'] = [
            'path' => 'assets/universal/instruction'
        ];

        $data['stockImage'] = [
            'path' => 'assets/universal/stock/images'
        ];

        $data['stockVideo'] = [
            'path' => 'assets/universal/stock/videos'
        ];

        $data['stockFile'] = [
            'path' => 'assets/universal/stock/files'
        ];

        return $data;
    }
}
