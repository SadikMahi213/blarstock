<?php

namespace App\Http\Controllers\Reviewer;

use App\Constants\ManageStatus;
use App\Events\AssetApproveEvent;
use App\Http\Controllers\Controller;
use App\Lib\DownloadFile;
use App\Models\Category;
use App\Models\Color;
use App\Models\FileType;
use App\Models\Image;
use App\Models\ImageFile;
use App\Models\Reason;
use App\Models\Reviewer;

class AssetController extends Controller
{
    function pending() {
        return $this->assetData('Pending Assets', 'pending');
    }

    function approved() {
        return $this->assetData('Approved Assets', 'approved');
    }

    function rejected() {
        return $this->assetData('Rejected Assets', 'rejected');
    }

    function detail($id) {
        $asset = Image::find($id);

        if (!$asset) {
            $toast[] = ['error', 'Asset not found'];
            return back()->withToasts($toast);
        }

        $pageTitle         = 'Details of - ' . $asset->title;
        $predefinedReasons = Reason::active()->orderBy('title')->get();

        return view('reviewer.asset.detail', compact('pageTitle', 'asset', 'predefinedReasons'));
    }

    function update() {
        $this->validate(request(), [
            'asset_id' => 'required|integer|gt:0',
            'status'   => 'required|in:0,1,2',
            'reason'   => 'required_if:status,2'
        ],[
            'status.required'    => 'The status field is required',
            'status.in'          => 'The status must be one of the following: Pending, Approved or Rejected',
            'reason.required_if' => 'The reason field is required when the status is set to Rejected'
        ]);

        $asset = Image::find(request('asset_id'));

        if (!$asset) {
            $toast[] = ['error', 'Asset not found'];
            return back()->withToasts($toast);
        }

        if ($asset->status == ManageStatus::IMAGE_APPROVED || $asset->status == ManageStatus::IMAGE_REJECTED) {
            $toast[] = ['error', 'Invalid action'];
            return back()->withToast($toast);
        }

        $asset->status       = request('status');
        $asset->reviewer_id  = auth('reviewer')->id();
        $asset->reason       = request('reason');
        $asset->save();

        if (bs('asset_approval_notify') && $asset->status == ManageStatus::IMAGE_APPROVED) {
            event(new AssetApproveEvent($asset));
        }

        $toast[] = ['success', 'Asset update success'];
        return back()->withToasts($toast);
    }

    function downloadAssetFile($id) {
        $assetFile = ImageFile::find($id);

        if (!$assetFile) {
            $toast = ['error', 'File not found'];
            return back()->withToasts($toast);
        }

        return DownloadFile::download($assetFile);
    }

    protected function assetData($pageTitle, $scope) {
        $excludedColumns = ['type', 'thumb', 'track_id', 'upload_date', 'image_width', 'image_height', 'extensions', 'description', 'tags', 'colors', 'total_like', 'is_featured', 'attribution', 'total_view', 'reason', 'total_earning'];

        if ($scope == 'approved' || $scope == 'rejected') {
            $reviewer = auth('reviewer')->user();
            $assets = Image::$scope()->with(['imageFiles', 'category', 'fileType', 'user', 'admin', 'reviewer'])->where(fn($query) => $query->where('reviewer_id', $reviewer->id))->select(getSelectedColumns('images', $excludedColumns))->paginate(getPaginate());
            
            return view('reviewer.asset.acted', compact('pageTitle', 'assets'));
        }

        $assets = Image::$scope()->with(['imageFiles', 'category', 'fileType', 'user'])->select(getSelectedColumns('images', $excludedColumns))->latest()->paginate(getPaginate());

        return view('reviewer.asset.pending', compact('pageTitle', 'assets'));
    }
}
