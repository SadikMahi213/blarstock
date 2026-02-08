<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class AuthorController extends Controller
{
    function index() {
        return $this->authorData('All Authors', 'authorIndex');
    }

    function approved() {
        return $this->authorData('Approved Authors', 'approvedAuthor');
    }

    function pending() {
        return $this->authorData('Pending Authors', 'pendingAuthor');
    }

    function rejected() {
        return $this->authorData('Rejected Authors', 'rejectedAuthor');
    }

    function banned() {
        return $this->authorData('Banned Authors', 'bannedAuthor');
    }

    function status($id) {
        $reasonValidation = (request('step') == 'reject' || request('step') == 'ban') ? 'required' : 'nullable';

        $this->validate(request(), [
            'step'   => 'required|in:approve,reject,ban,allow',
            'reason' => [$reasonValidation, 'string']
        ]);

        $step = request('step');
        $action = null;

        switch ($step) {
            case 'approve':
                $action = [
                    'requiredStatus' => ManageStatus::AUTHOR_PENDING,
                    'targetStatus'   => ManageStatus::AUTHOR_APPROVED,
                    'message'        => ' author approval success'
                ];
                break;

            case 'reject':
                $action = [
                    'requiredStatus' => ManageStatus::AUTHOR_PENDING,
                    'targetStatus'   => ManageStatus::AUTHOR_REJECTED,
                    'message'        => ' author rejection success'
                ];
                break;

            case 'ban':
                $action = [
                    'requiredStatus' => ManageStatus::AUTHOR_APPROVED,
                    'targetStatus'   => ManageStatus::AUTHOR_BANNED,
                    'message'        => ' author ban success'
                ];
                break;

            case 'allow':
                $action = [
                    'requiredStatus' => ManageStatus::AUTHOR_BANNED,
                    'targetStatus'   => ManageStatus::AUTHOR_APPROVED,
                    'message'        => ' author allow success'
                ];
                break;
        }

        if (!$action) {
            $toast[] = ['error', 'Invalid action'];
            return back()->withToasts($toast);
        }

        $author = User::active()->where('author_status', $action['requiredStatus'])->find($id);

        if (!$author) {
            $toast[] = ['error', 'Author not found'];
            return back()->withToasts($toast);
        }

        $author->author_status = $action['targetStatus'];
        $author->reason        = request('reason') ?: null;

        if ($step == 'approve') {
            $author->joined_at = Carbon::now();
        }

        $author->save();

        if ($step == 'approve') {
            notify($author, 'AUTHOR_APPROVE', [
                'author_name' => $author->author_name
            ]);
        } elseif ($step == 'allow') {
            notify($author, 'AUTHOR_ALLOW', [
                'author_name' => $author->author_name
            ]);
        }

        $toast[] = ['success', $author->author_name . $action['message']];
        return back()->withToasts($toast);
    }

    protected function authorData($pageTitle, $scope) {
        $excludedColumn = ['cover_image', 'firstname', 'lastname', 'email', 'country_code', 'country_name', 'mobile', 'plan_id', 'plan_expired_date', 'reason', 'total_self_download',  'ref_by', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token'];
        $authors        = User::$scope()->select(getSelectedColumns('users', $excludedColumn))->withCount(['approvedImages'])->with(['totalEarnings'])->searchable(['username', 'author_name'])->orderByDesc('joined_at')->paginate(getPaginate());
        
        return view('admin.author.index', compact('pageTitle', 'authors'));
    }
}
