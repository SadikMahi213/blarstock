<?php

namespace App\Http\Controllers;

use App\Constants\ManageStatus;
use App\Models\Collection;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function profile($id, $userName) {
        try {
            $user = User::find(decrypt($id));

            if (!$user) {
                if (request()->ajax()) {
                    return response([
                        'success' => false,
                        'message' => 'User not found'
                    ]);
                }
    
                $toast[] = ['error', 'User not found'];
                return back()->withToasts($toast);
            }
        } catch (\Exception $exp) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Invalid user ID'
                ]);
            }

            $toast[] = ['error', 'Invalid user ID'];
            return back()->withToasts($toast);
        }

        if ($user->status != ManageStatus::ACTIVE) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'User is not active right now'
                ]);
            }

            $toast[] = ['error', 'User is not active right now'];
            return back()->withToasts($toast);
        }

        $collections = Collection::active()->public()->where('user_id', $user->id)->whereHas('images')->with(['images' => fn($query) => $query->select('images.id', 'images.image_name', 'images.thumb', 'images.video', 'images.status', 'images.tags')])->latest()->paginate(getPaginate(12));

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.memberUserCollections', compact('collections', 'user'))->render();

            return response([
                'success' => true,
                'html'    => $html
            ]);
        }

        $pageTitle = $user->fullname . ' Collections';

        return view($this->activeTheme . 'memberUser.collections', compact('pageTitle', 'user', 'collections'));
    }

    function following($id, $userName) {
        try {
            $user = User::find(decrypt($id));

            if (!$user) {
                $toast[] = ['error', 'User not found'];
                return back()->withToasts($toast);
            }
        } catch (\Throwable $th) {
            $toast[] = ['error', 'Invalid user ID'];
            return back()->withToasts($toast);
        }

        if ($user->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'User not active right now'];
            return back()->withToasts($toast);
        }

        $excludedColumn = ['cover_image', 'firstname', 'lastname', 'email', 'country_code', 'country_name', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token', 'created_at', 'updated_at'];

        $pageTitle  = 'Connections of ' . $user->username;
        $followings = User::whereHas('followers', fn($query) => $query->where('follower_id', $user->id))
                        ->active()
                        ->select(getSelectedColumns('users', $excludedColumn))
                        ->withCount(['approvedImages'])
                        ->orderByDesc(Follow::select('created_at')
                            ->whereColumn('follows.following_id', 'users.id')
                            ->latest()
                            ->limit(1))
                        ->take(28)->get();

        return view($this->activeTheme . 'memberUser.following', compact('pageTitle', 'user', 'followings'));
    }

    function loadMoreFollowings() {
        $validate = Validator::make(request()->all(), [
            'user_id' => 'required|integer|gt:0',
            'offset'  => 'required|integer|gte:56'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $user = User::find(request('user_id'));

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        if ($user->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'User not active right now'
            ]);
        }

        $excludedColumns = ['cover_image', 'firstname', 'lastname', 'email', 'country_code', 'country_name', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token', 'created_at', 'updated_at'];

        $offset = (int)request('offset');
        $limit  = 42;

        $followings = User::whereHas('followers', fn($query) => $query->where('follower_id', $user->id))
                        ->active()
                        ->select(getSelectedColumns('users', $excludedColumns))
                        ->withCount(['approvedImages'])
                        ->orderByDesc(Follow::select('created_at')
                            ->whereColumn('follows.following_id', 'users.id')
                            ->latest()
                            ->limit(1))
                        ->skip($offset)->take($limit)->get();
        
        $hasMore = Follow::where('follower_id', $user->id)->count() > ($offset + $limit);

        $html = view($this->activeTheme . 'ajax.following', compact('followings'))->render();

        return response([
            'success'    => true,
            'html'       => $html,
            'hasMore'    => $hasMore,
            'followings' => $followings
        ]);
    }
}
