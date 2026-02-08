<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reason;

class ReasonController extends Controller
{
    function index() {
        return $this->reasonData('All Reasons');
    }

    function active() {
        return $this->reasonData('Active Reasons', 'active');
    }

    function inactive() {
        return $this->reasonData('Inactive Reasons', 'inactive');
    }

    function store($id = 0) {
        $this->validate(request(), [
            'title'       => 'required|string|max:255|unique:reasons,title,' . $id,
            'description' => 'required|string'
        ]);

        if ($id) {
            $reason = Reason::find($id);

            if (!$reason) {
                $toast[] = ['error', 'Reason not found'];
                return back()->withToasts($toast);
            }

            $message = ' reason update success';
        } else {
            $reason  = new Reason();
            $message = ' reason add success';
        }

        $reason->title       = request('title');
        $reason->description = request('description');
        $reason->save();

        $toast[] = ['success', $reason->title . $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $reason = Reason::find($id);

        if (!$reason) {
            $toast[] = ['error', 'Reason not found'];
            return back()->withToasts($toast);
        }

        return Reason::changeStatus($reason->id);
    }

    function delete($id) {
        $reason = Reason::find($id);

        if (!$reason) {
            $toast[] = ['error', 'Reason not found'];
            return back()->withToasts($toast);
        }

        $reason->delete();

        $toast[] = ['success', 'Reason delete success'];
        return back()->withToasts($toast);
    }

    protected function reasonData($pageTitle, $scope = null) {
        $reasons = Reason::when($scope, fn($query) => $query->$scope())->searchable(['title'])->latest()->paginate(getPaginate());

        return view('admin.page.reasons', compact('pageTitle', 'reasons'));
    }
}
