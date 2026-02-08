<?php

namespace App\Http\Controllers;

use App\Constants\ManageStatus;
use App\Models\Collection;
use App\Models\Follow;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    function authors() {

        $validate = Validator::make(request()->all(), [
            'search' => 'nullable|string'
        ]);

        if ($validate->fails()) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => $validate->errors()
                ]);
            }

            $toast[] = ['error', $validate->errors()];
            return back()->withToasts($toast);
        }

        $excludedColumns = ['firstname', 'lastname', 'cover_image', 'username', 'email', 'country_code', 'country_name', 'mobile', 'plan_id', 'plan_expired_date', 'author_data', 'reason', 'total_follower', 'total_following', 'total_self_download', 'total_others_download', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token'];
        
        $authors = User::active()->approvedAuthor()->select(getSelectedColumns('users', $excludedColumns))->orderBy('author_name')->withCount(['approvedImages'])->paginate(getPaginate(16));

        $defaultUserCover = getSiteData('default_user_cover.content', true);

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.authorIndex', compact('authors', 'defaultUserCover'))->render();
            
            return response([
                'success' => true,
                'html'    => $html
            ]);
        }

        $pageTitle       = 'Authors';
        $subTitle        = 'Discover our authors and their extensive resource collections';

        return view($this->activeTheme . 'authors.index', compact('pageTitle', 'subTitle', 'authors', 'defaultUserCover'));
    }

    function profile($id, $authorName) {
        try {
            $author = User::active()->find(decrypt($id));

            if (!$author) {

                if (request()->ajax()) {
                    return response([
                        'success' => false,
                        'message' => 'Author not found'
                    ]);
                }
    
                $toast[] = ['error', 'Author not found'];
                return back()->withToast($toast);
            }
        } catch (\Exception $exp) {

            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Invalid author ID'
                ]);
            }

            $toast[] = ['error', 'Invalid author ID'];
            return back()->withToasts($toast);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Invalid author'
                ]);
            }

            $toast[] = ['error', 'Invalid author'];
            return back()->withToasts($toast);
        }

        $user            = auth()->user();
        $pageTitle       = 'Author Profile';
        $excludedColumns = ['image_name', 'file_type_id', 'track_id', 'upload_date', 'image_width', 'image_height', 'extensions', 'description', 'tags', 'colors', 'total_like', 'is_featured', 'attribution', 'total_view', 'reason', 'admin_id', 'reviewer_id', 'total_earning'];
        $assets          = Image::activeCheck()->where('user_id', $author->id)
                            ->with(['user', 'category', 'imageFiles' => fn($query) => $query->active()->premium(), 'likes' => fn($query) => $query->when($user, fn($q) => $q->where('user_id', $user->id))])
                            ->select(getSelectedColumns('images', $excludedColumns))
                            ->latest()
                            ->paginate(getPaginate(16));

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.authorAssets', compact('assets', 'user'))->render();

            return response([
                'success' => true,
                'html'    => $html
            ]);
        }

        return view($this->activeTheme . 'authors.profile', compact('pageTitle', 'author', 'assets', 'user'));
    }


    function followers($id, $authorName) {
        try {
            $author = User::active()->find(decrypt($id));

            if (!$author) {
                $toast[] = ['error', 'Author not found'];
                return back()->withToasts($toast);
            }
        } catch (\Throwable $th) {
            $toast[] = ['error', 'Invalid author ID'];
            return back()->withToasts($toast);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['error', 'Invalid author'];
            return back()->withToasts($toast);
        }

        $excludedColumns = ['cover_image', 'firstname', 'lastname', 'email', 'country_code', 'plan_id', 'plan_expired_date', 'total_follower', 'total_following', 'total_self_download', 'total_others_download','country_name', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token', 'created_at', 'updated_at'];

        $pageTitle = 'Connections of ' . $author->author_name;
        
        $followers = User::whereHas('following', fn($query) => $query->where('following_id', $author->id))
                        ->active()
                        ->select(getSelectedColumns('users', $excludedColumns))
                        ->withCount(['approvedImages'])
                        ->orderByDesc(Follow::select('created_at')
                            ->whereColumn('follows.follower_id', 'users.id')
                            ->latest()
                            ->limit(1))
                        ->take(28)->get();

        $followings = User::whereHas('followers', fn($query) => $query->where('follower_id', $author->id))
                        ->active()
                        ->select(getSelectedColumns('users', $excludedColumns))
                        ->withCount(['approvedImages'])
                        ->orderByDesc(Follow::select('created_at')
                            ->whereColumn('follows.following_id', 'users.id')
                            ->latest()
                            ->limit(1))
                        ->take(28)->get();

        return view($this->activeTheme . 'authors.followers', compact('pageTitle', 'author', 'followers', 'followings'));
    }

    function loadMoreFollowers() {

        $validate = Validator::make(request()->all(), [
            'author_id' => 'required|integer|gt:0',
            'offset'    => 'required|integer|gte:28'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $author = User::active()->find(request('author_id'));

        if (!$author) {
            return response([
                'success' => false,
                'message' => 'Author not found'
            ]);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Invalid author'
            ]);
        }

        $excludedColumns = ['cover_image', 'firstname', 'lastname', 'email', 'plan_id', 'plan_expired_date', 'total_follower', 'total_following', 'total_self_download', 'total_others_download', 'country_code', 'country_name', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token', 'created_at', 'updated_at'];

        $offset = (int)request('offset');
        $limit = 21;

        $followers = User::whereHas('following', fn($query) => $query->where('following_id', $author->id))
                            ->active()
                            ->select(getSelectedColumns('users', $excludedColumns))
                            ->withCount(['approvedImages'])
                            ->orderByDesc(Follow::select('created_at')
                                ->whereColumn('follows.follower_id', 'users.id')
                                ->latest()
                                ->limit(1))
                            ->skip($offset)->take($limit)->get();

        $hasMore   = Follow::where('following_id', $author->id)->count() > ($offset + $limit);

        $html = view($this->activeTheme . 'ajax.followers', compact('followers'))->render();

        return response([
            'success'   => true,
            'html'      => $html,
            'hasMore'   => $hasMore,
            'followers' => $followers
        ]);
    }

    function loadMoreFollowings() {
        $validate = Validator::make(request()->all(), [
            'author_id' => 'required|integer|gt:0',
            'offset'    => 'required|integer|gte:28'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $author = User::active()->find(request('author_id'));

        if (!$author) {
            return response([
                'success' => false,
                'message' => 'Author not found'
            ]);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Invalid author'
            ]);
        }

        $excludedColumns = ['cover_image', 'firstname', 'lastname', 'email', 'country_code', 'country_name', 'plan_id', 'plan_expired_date', 'total_follower', 'total_following', 'total_self_download', 'total_others_download', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token', 'created_at', 'updated_at'];

        $offset = (int)request('offset');
        $limit = 21;

        $followings = User::whereHas('followers', fn($query) => $query->where('follower_id', $author->id))
                        ->active()
                        ->select(getSelectedColumns('users', $excludedColumns))
                        ->withCount(['approvedImages'])
                        ->orderByDesc(Follow::select('created_at')
                            ->whereColumn('follows.following_id', 'users.id')
                            ->latest()
                            ->limit(1))
                        ->skip($offset)->take($limit)->get();

        $hasMore = Follow::where('follower_id', $author->id)->count() > ($offset + $limit);

        $html = view($this->activeTheme . 'ajax.following', compact('followings'))->render();

        return response([
            'success'    => true,
            'html'       => $html,
            'hasMore'    => $hasMore,
            'followings' => $followings
        ]);
    }

    function collections($id, $authorName) {
        try {
            $author = User::find(decrypt($id));

            if (!$author) {

                if (request()->ajax()) {
                    return response([
                        'success' => false,
                        'message' => 'Author not found'
                    ]);
                }
    
                $toast[] = ['error', 'Author not found'];
                return back()->withToasts($toast);
            }
        } catch (\Exception $exp) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Invalid author ID'
                ]);
            }

            $toast[] = ['error', 'Invalid author ID'];
            return back()->withToasts($toast);
        }

        if ($author->status != ManageStatus::ACTIVE) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Author not active right now'
                ]);
            }

            $toast[] = ['error', 'Author not active right now'];
            return back()->withToasts($toast);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Author not in approved status'
                ]);
            }

            $toast[] = ['error', 'Author not in approved status'];
            return back()->withToasts($toast);
        }

        $collections = Collection::active()->public()->where('user_id', $author->id)->whereHas('images')->with(['images' => fn($query) => $query->select('images.id', 'images.image_name', 'images.thumb', 'images.video', 'images.status', 'images.tags')])->latest()->paginate(getPaginate(12));

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.authorCollections', compact('collections', 'author'))->render();

            return response([
                'success' => true,
                'html'    => $html
            ]);
        }

        $pageTitle = $author->author_name . ' Collections';

        return view($this->activeTheme . 'authors.collections', compact('pageTitle', 'author', 'collections'));
    }
}
