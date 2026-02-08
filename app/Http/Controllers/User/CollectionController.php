<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionImage;
use App\Models\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CollectionController extends Controller
{
    function index() {
        $pageTitle   = 'All Collections';
        $collections = Collection::where('user_id', auth()->id())->withCount(['images'])->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.page.collections', compact('pageTitle', 'collections'));
    }

    function store($id = 0) {
        $this->validate(request(), [
            'title'       => 'required|string|max:40',
            'description' => 'nullable|string|max:255',
            'visibility'  => 'required|in:0,1'
        ]);

        if ($id) {
            $collection = Collection::where('user_id', auth()->id())->find($id);

            if (!$collection) {
                $toast[] = ['error', 'Collection not found'];
                return back()->withToasts($toast);
            }

            $message = ' collection update success';
        } else {
            $collection = new Collection();
            $message    = ' collection add success';
        }

        $collection->user_id     = auth()->id();
        $collection->title       = request('title');
        $collection->description = request('description');
        $collection->visibility  = request('visibility');
        $collection->save();

        $toast[] = ['success', $collection->title . $message];
        return back()->withToasts($toast);
    }

    function delete($id) {
        $collection = Collection::where('user_id', auth()->id())->find($id);

        if (!$collection) {
            $toast[] = ['error', 'Collection not found'];
            return back()->withToasts($toast);
        }

        $collectionTitle = $collection->title;

        $collection->collectionImages()->delete();

        $collection->delete();

        $toast[] = ['success', $collectionTitle . ' collection delete success'];
        return back()->withToasts($toast);
    }

    function addCollection() {
        $validate = Validator::make(request()->all(), [
            'title'   => 'required|string|max:40',
            'user_id' => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $user = auth()->user();

        if ($user->id != request('user_id')) {
            return response([
                'success' => false,
                'message' => 'Invalid action'
            ]);
        }

        $collection             = new Collection();
        $collection->user_id    = $user->id;
        $collection->title      = request('title');
        $collection->visibility = ManageStatus::YES;
        $collection->save();

        return response([
            'success'    => true,
            'message'    => $collection->title . ' collection add success',
            'collection' => $collection
        ]);
    }

    function addImage() {
        $user                    = auth()->user();
        $myExistingCollectionIds = Collection::active()->where('user_id', $user->id)->pluck('id')->toArray();

        $validate = Validator::make(request()->all(), [
            'asset_id'         => 'required|integer|gt:0',
            'user_id'          => 'required|integer|gt:0',
            'collection_ids'   => 'required|array',
            'collection_ids.*' => ['required', 'integer', 'gt:0', Rule::in($myExistingCollectionIds)]
        ], [
            'collection_ids.required'   => 'Please select at least one collection',
            'collection_ids.array'      => 'Invalid format! The selected collections must be an array',
            'collection_ids.*.required' => 'Each selected collections must be valid ID',
            'collection_ids.*.integer'  => 'Invalid collection ID format. Please select valid collection',
            'collection_ids.*.gt'       => 'Collection ID must be greater that 0',
            'collection_ids.*.in'       => 'One or more selected collections are invalid or do not belong to you'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        if ($user->id != request('user_id')) {
            return response([
                'success' => false,
                'message' => 'Invalid action'
            ]);
        }

        $asset = Image::find(request('asset_id'));

        if (!$asset) {
            return response([
                'success' => false,
                'message' => 'Asset not found'
            ]);
        }

        if ($asset->status != ManageStatus::IMAGE_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Asset is not in approved status'
            ]);
        }

        if ($asset->category->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'Category of the asset is not active right now'
            ]);
        }

        if ($asset->fileType->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'File type of the asset is not active right now'
            ]);
        }

        $existingCollectionIdsFromCollectionImage = $user->collectionImages()->where('image_id', $asset->id)->pluck('collection_id')->toArray();

        $newCollections = array_diff(request('collection_ids'), $existingCollectionIdsFromCollectionImage);

        if (!empty($newCollections)) {
            $newCollectionImageEntries = array_map(fn($collectionId) => [
                'collection_id' => $collectionId,
                'image_id'      => $asset->id,
                'created_at'    => now(),
                'updated_at'    => now()
            ], $newCollections);
    
            CollectionImage::insert($newCollectionImageEntries);
        }

        $user->collectionImages()->where('image_id', $asset->id)->whereNotIn('collection_id', request('collection_ids'))->delete();

        return response([
            'success' => true,
            'message' => 'Collection enrich success'
        ]);
    }

    function modalView() {
        $validate = Validator::make(request()->all(), [
            'asset_id' => 'required|integer|gt:0',
            'user_id'  => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $asset = Image::find(request('asset_id'));

        if (!$asset) {
            return response([
                'success' => false,
                'message' => 'Asset not found'
            ]);
        }

        if ($asset->status != ManageStatus::IMAGE_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Asset is not in approved status'
            ]);
        }

        if ($asset->category->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'Category of the asset is not active right now'
            ]);
        }

        $user = auth()->user();

        if ($user->id != request('user_id')) {
            return response([
                'success' => false,
                'message' => 'Unauthorized action'
            ]);
        }

        $collections = Collection::active()->where('user_id', $user->id)->with(['collectionImages'])->get();

        $html = view($this->activeTheme . 'ajax.collectionModalContent', compact('collections', 'asset', 'user'))->render();

        return response([
            'success' => true,
            'html'    => $html
        ]);
    }
}
