<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{
    function index() {
        return $this->reviewerData('All Reviewers');
    }

    function active() {
        return $this->reviewerData('Active Reviewers', 'active');
    }

    function inactive() {
        return $this->reviewerData('Inactive Reviewers', 'inactive');
    }

    function store($id = 0) {
        $passwordValidation = $id ? 'nullable' : 'required|min:6';

        $this->validate(request(), [
            'name'     => 'required|string|max:40',
            'username' => 'required|string|max:40|unique:reviewers,username,' . $id,
            'email'    => 'required|email|unique:reviewers,email,' . $id,
            'password' => $passwordValidation
        ]);

        if ($id) {
            $reviewer = Reviewer::find($id);

            if (!$reviewer) {
                $toast[] = ['error', 'Reviewer not found'];
                return back()->withToasts($toast);
            }

            $message = ' reviewer update success';
        } else {
            $reviewer = new Reviewer();
            $message  = ' reviewer add success';
        }

        $reviewer->name     = request('name');
        $reviewer->username = request('username');
        $reviewer->email    = request('email');

        if (request('password')) {
            $reviewer->password = Hash::make(request('password'));
        }
        
        $reviewer->save();

        if (!$id) {
            notify($reviewer, 'REVIEWER_APPROVE', [
                'name'     => request('name'),
                'email'    => request('email'),
                'username' => request('username'),
                'password' => request('password')
            ]);
        } else {
            notify($reviewer, 'REVIEWER_UPDATE', [
                'name'     => request('name'),
                'email'    => request('email'),
                'username' => request('username'),
                'password' => request('password') ? request('password')  : 'Not Changed'
            ]);
        }

        $toast[] = ['success', $reviewer->username . $message];
        return back()->withToasts($toast);
    }

    function login($id) {
        $reviewer = Reviewer::find($id);

        if (!$reviewer) {
            $toast[] = ['error', 'Reviewer not found'];
            return back()->withToasts($toast);
        }

        if ($reviewer->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'Reviewer not currently active'];
            return back()->withToasts($toast);
        }

        Auth::guard('reviewer')->loginUsingId($reviewer->id);

        return to_route('reviewer.dashboard');
    }

    function status($id) {
        $reviewer = Reviewer::find($id);

        if (!$reviewer) {
            $toast[] = ['error', 'Reviewer not found'];
            return back()->withToasts($toast);
        }

        return Reviewer::changeStatus($reviewer->id);
    }

    protected function reviewerData($pageTitle, $scope = null) {
        $reviewers = Reviewer::when($scope, fn($query) => $query->$scope())->withCount(['approvedImages', 'rejectedImages'])->latest()->searchable(['name', 'username', 'email'])->paginate(getPaginate());

        return view('admin.page.reviewers', compact('pageTitle', 'reviewers'));
    }
}
