<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resolution;

class ResolutionController extends Controller
{
    function index() {
        return $this->resolutionData('All Resolutions');
    }

    function active() {
        return $this->resolutionData('Active Resolutions', 'active');
    }

    function inactive() {
        return $this->resolutionData('Inactive Resolutions', 'inactive');
    }

    function store($id = 0) {

        request()->merge(['resolution' => trim(request('resolution'))]);

        $this->validate(request(), [
            'resolution' => 'required|max:40|regex:/^\d+x\d+$/|unique:resolutions,resolution,' . $id
        ]);

        if ($id) {
            $resolution = Resolution::find($id);

            if (!$resolution) {
                $toast[] = ['error', 'Resolution not found'];
                return back()->withToasts($toast);
            }

            $message = 'Resolution update success';
        } else {
            $resolution = new Resolution();
            $message    = 'New resolution add success';
        }

        $resolution->resolution = request('resolution');
        $resolution->save();

        $toast[] = ['success', $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $resolution = Resolution::find($id);

        if (!$resolution) {
            $toast[] = ['error', 'Resolution not found'];
            return back()->withToasts($toast);
        }

        return Resolution::changeStatus($resolution->id);
    }

    function resolutionData($pageTitle, $scope = null) {
        $resolutions = Resolution::when($scope, fn($query) => $query->$scope())->searchable(['resolution'])->latest()->paginate(getPaginate());

        return view('admin.page.resolutions', compact('pageTitle', 'resolutions'));
    }
}
