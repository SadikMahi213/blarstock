<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SocialProfile;

class SocialProfileController extends Controller
{
    function profile() {
        $pageTitle      = 'Social Profile';
        $user           = auth()->user();
        $socialAccounts = SocialProfile::where('user_id', $user->id)->latest()->paginate(getPaginate());

        return view($this->activeTheme . 'user.page.socialProfile', compact('pageTitle', 'user', 'socialAccounts'));
    }

    function store($id = 0) {
        $this->validate(request(), [
            'name' => 'required|string|max:240',
            'icon' => 'required|string|max:255',
            'url'  => 'required|url' 
        ]);

        if ($id) {
            $socialProfileAccount = SocialProfile::find($id);

            if (!$socialProfileAccount) {
                $toast[] = ['error', 'Account not found'];
                return back()->withToasts($toast);
            }

            if ($socialProfileAccount->user_id != auth()->id()) {
                $toast[] = ['error', 'Invalid action'];
                return back()->withToasts($toast);
            }

            $message = 'Social account update success';
        } else {
            $socialProfileAccount = new SocialProfile();
            $message              = 'Social account add success';
        }

        $socialProfileAccount->user_id = auth()->id();
        $socialProfileAccount->name    = request('name');
        $socialProfileAccount->icon    = request('icon');
        $socialProfileAccount->url     = request('url');
        $socialProfileAccount->save();

        $toast[] = ['success', $message];
        return back()->withToasts($toast);
    }

    function delete($id) {
        $socialProfileAccount = SocialProfile::find($id);

        if (!$socialProfileAccount) {
            $toast[] = ['error', 'Account not found'];
            return back()->withToasts($toast);
        }

        if ($socialProfileAccount->user_id != auth()->id()) {
            $toast[] = ['error', 'Invalid action'];
            return back()->withToasts($toast);
        }

        $socialProfileAccount->delete();

        $toast[] = ['success', 'Social profile account delete success'];
        return back()->withToasts($toast);
    }

    function status($id) {
        $socialProfileAccount = SocialProfile::find($id);

        if (!$socialProfileAccount) {
            $toast[] = ['error', 'Account not found'];
            return back()->withToasts($toast);
        }

        if ($socialProfileAccount->user_id != auth()->id()) {
            $toast[] = ['error', 'Invalid action'];
            return back()->withToasts($toast);
        }

        return SocialProfile::changeStatus($socialProfileAccount->id);
    }
}
