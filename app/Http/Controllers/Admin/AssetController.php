<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Validation\Rule;

class AssetController extends Controller
{

    function index() {
        return $this->assetData('All Assets');
    }

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
        $fileTypes         = FileType::orderBy('name')->get();
        $categories        = Category::orderBy('name')->get();
        $colors            = Color::orderBy('name')->get();
        $predefinedReasons = Reason::active()->orderBy('title')->get();

        return view('admin.asset.detail', compact('pageTitle', 'asset', 'fileTypes', 'categories', 'colors', 'predefinedReasons'));
    }

    function update() {
        $colors         = Color::pluck('code')->toArray();
        $fileExtensions = getFileExtension((int)request('file_type_id'), true);

        $request = request();
        $this->validate(request(), [
            'asset_id'      => 'required|integer|gt:0',
            'type'          => 'required|in:1,2',
            'category_id'   => 'required|integer|gt:0',
            'file_type_id'  => 'required|integer|gt:0',
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'resolution'    => 'required|array',
            'resolution.*'  => 'required|string|max:40|exists:resolutions,resolution',
            'tags'          => 'required|array',
            'tags.*'        => 'required|string',
            'extensions'    => 'required|array',
            'extensions.*'  => 'required|string|in:' . implode(',', $fileExtensions),
            'colors'        => 'nullable|array',
            'colors.*'      => ['required', Rule::in($colors)],
            'status'        => 'required|in:0,1,2',
            'status_file'   => 'required|array',
            'status_file.*' => 'required|in:0,1',
            'is_free'       => 'required|array',
            'is_free.*'     => 'required|in:0,1',
            'price'         => 'required|array',
            'price.*'       => 'required_if:is_free.*,0|numeric|gte:0|lte:' . bs('max_price_limit'),
            'reason'        => 'required_if:status,2',
            'file_id'       => 'required|array',
            'file_id.*'     => 'required|integer|gt:0'
        ], [
            'resolution.required'     => 'The resolution field is required.',
            'resolution.array'        => 'The resolution field must be an array.',
            'resolution.*.required'   => 'Each resolution value is required.',
            'resolution.*.string'     => 'Each resolution must be a valid string.',
            'resolution.*.max'        => 'Each resolution must not exceed 40 characters.',
            'resolution.*.exists'     => 'Each resolution must exist in the resolutions table.',

            'tags.required'           => 'The tags field is required.',
            'tags.array'              => 'The tags field must be an array.',
            'tags.*.required'         => 'Each tag is required.',
            'tags.*.string'           => 'Each tag must be a valid string.',

            'extensions.required'     => 'The extensions field is required.',
            'extensions.array'        => 'The extensions field must be an array.',
            'extensions.*.required'   => 'Each extension is required.',
            'extensions.*.string'     => 'Each extension must be a valid string.',
            'extensions.*.in'         => 'Each extension must be one of the allowed values: ' . implode(',', $fileExtensions) . '.',

            'colors.array'            => 'The colors field must be an array.',
            'colors.*.required'       => 'Each color is required.',
            'colors.*.in'             => 'Each color must be a valid value.',

            'status_file.required'    => 'The status file field is required.',
            'status_file.array'       => 'The status file field must be an array.',
            'status_file.*.required'  => 'Each status file value is required.',
            'status_file.*.in'        => 'Each status file value must be either 0 or 1.',

            
            'is_free.required'        => 'The is free field is required.',
            'is_free.array'           => 'The is free field must be an array.',
            'is_free.*.required'      => 'Each is free value is required.',
            'is_free.*.in'            => 'Each is free value must be either 0 or 1.',

            'price.required'          => 'The price field is required.',
            'price.array'             => 'The price field must be an array.',
            'price.*.required_if'     => 'The price is required when the item is not free.',
            'price.*.numeric'         => 'Each price must be a numeric value.',
            'price.*.gte'             => 'Each price must be greater than or equal to 0.',
            'price.*.lte'             => 'Each price must not exceed the maximum price limit.',
            'price.*'                 => 'The price must be 0 when the item is free.',

            'file_id.required'        => 'The file ID field is required.',
            'file_id.array'           => 'The file ID field must be an array.',
            'file_id.*.required'      => 'Each file ID is required.',
            'file_id.*.integer'       => 'Each file ID must be a valid integer.',
            'file_id.*.gt'            => 'Each file ID must be greater than 0.',

        ]);

        foreach ($request->is_free ?? [] as $index => $value) {
            $price = (float)($request->price[$index] ?? null);
        
            if (($value == ManageStatus::PREMIUM && ($price == null || $price <= 0)) || ($value == ManageStatus::FREE && $price != 0)) {
                $toast[] = ['error', $value == ManageStatus::PREMIUM ? 'The price is required and must be greater than 0 when the item is premium.' : 'The price must be 0 when the item is free.'];
                return back()->withToasts($toast)->throwResponse();
            }
        }

        $fileType = FileType::find(request('file_type_id'));

        if (!$fileType) {
            $toast[] = ['error', 'File type not found'];
            return back()->withToasts($toast);
        }

        $category = Category::find(request('category_id'));

        if (!$category) {
            $toast[] = ['error', 'Category not found'];
            return back()->withToasts($toast);
        }

        $asset = Image::with('category')->find(request('asset_id'));

        if (!$asset) {
            $toast[] = ['error', 'Asset not found'];
            return back()->withToasts($toast);
        }

        $asset->category_id  = request('category_id');
        $asset->type         = request('type');
        $asset->file_type_id = request('file_type_id');
        $asset->title        = request('title');
        $asset->description  = request('description');
        $asset->tags         = request('tags');
        $asset->extensions   = request('extensions');
        $asset->colors       = request('colors');
        $asset->status       = request('status');
        $asset->admin_id     = auth('admin')->id();
        $asset->reviewer_id  = 0;
        $asset->save();

        if ($asset->status == ManageStatus::IMAGE_REJECTED) {
            $asset->reason = request('reason');
            $asset->save();
        }

        foreach (request('resolution') ?? [] as $key => $resolution) {
            $file             = ImageFile::findOrFail(request('file_id')[$key]);
            $file->resolution = $resolution;
            $file->is_free    = request('is_free')[$key];
            $file->status     = request('status_file')[$key];
            $file->price      = request('price')[$key];

            if (request('price')[$key] == 0) {
                $file->is_free = ManageStatus::FREE;
            }
            $file->save();
        }

        if (bs('asset_approval_notify') && $asset->status == ManageStatus::IMAGE_APPROVED) {
            event(new AssetApproveEvent($asset));
        }

        $toast[] = ['success', 'Asset update success'];
        return back()->withToasts($toast);
    }

    function featured($id) {
        $asset = Image::find($id);

        if (!$asset) {
            $toast[] = ['error', 'Asset not found'];
            return back()->withToasts($toast);
        }

        if ($asset->status != ManageStatus::IMAGE_APPROVED) {
            $toast[] = ['error', 'Approval of the asset is required'];
            return back()->withToasts($toast);
        }

        $message            = $asset->is_featured ? $asset->title . ' featured success' : $asset->title . ' un-featured success';
        $asset->is_featured = $asset->is_featured ? ManageStatus::NO : ManageStatus::YES;
        $asset->save();

        $toast[] = ['success', $message];
        return back()->withToasts($toast);
    }

    function download($id) {

        $file = ImageFile::find($id);

        if (!$file) {
            $toast[] = ['error', 'File not found'];
            return back()->withToasts($toast);
        }

        return DownloadFile::download($file);
    }

    protected function assetData($pageTitle, $scope = null) {
        $assets = Image::when($scope, fn($query) => $query->$scope())->with(['imageFiles', 'category', 'fileType', 'user'])->searchable(['title', 'category:name', 'fileType:name', 'user:author_name', 'user:username'])->latest()->paginate(getPaginate());

        return view('admin.asset.index', compact('pageTitle', 'assets'));
    }
}
