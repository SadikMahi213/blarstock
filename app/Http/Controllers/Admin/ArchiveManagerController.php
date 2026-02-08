<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchiveManager;

class ArchiveManagerController extends Controller
{
    function index() {
        return $this->archiveManagerData('All Archive Managers');
    }

    function active() {
        return $this->archiveManagerData('Active Archive Managers', 'active');
    }

    function inactive() {
        return $this->archiveManagerData('Inactive Archive Managers', 'inactive');
    }

    function store($id = 0) {
        $this->validate(request(), [
            'name'      => 'required|string|max:40|unique:archive_managers,name,' . $id,
            'extension' => 'required|string|max:40|regex:/^\.[a-z0-9]+$/|unique:archive_managers,extension,' . $id
        ],[
            'extension.regex' => 'Extension must start with a dot (.) and contain only lowercase letters'
        ]);

        if ($id) {
            $archiveManager = ArchiveManager::find($id);

            if (!$archiveManager) {
                $toast[] = ['error', 'Archive manager not not found'];
                return back()->withToasts($toast);
            }

            $message = ' archive manager update success';
        } else {
            $archiveManager = new ArchiveManager();
            $message        = ' archive manager add success';
        }

        $archiveManager->name      = request('name');
        $archiveManager->extension = request('extension');
        $archiveManager->save();

        $toast[] = ['success', $archiveManager->name . $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $archiveManager = ArchiveManager::find($id);

        if (!$archiveManager) {
            $toast[] = ['error', 'Archive Manager not found'];
            return back()->withToasts($toast);
        }

        return ArchiveManager::changeStatus($archiveManager->id);
    }

    protected function archiveManagerData($pageTitle, $scope = null) {
        $archiveManagers = ArchiveManager::when($scope, fn($query) => $query->$scope())->searchable(['name', 'extension'])->latest()->paginate(getPaginate());

        return view('admin.page.archiveManagers', compact('pageTitle', 'archiveManagers'));
    }
}
