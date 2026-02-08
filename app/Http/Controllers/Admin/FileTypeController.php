<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileType;
use Illuminate\Validation\Rules\File;

class FileTypeController extends Controller
{
    function index() {
        return $this->fileTypeData('All File Types');
    }

    function active() {
        return $this->fileTypeData('Active File Types', 'active');
    }

    function inactive() {
        return $this->fileTypeData('Inactive File Types', 'inactive');
    }

    function store($id = 0) {
        $imageValidation = $id ? 'nullable' : 'required';

        $this->validate(request(), [
            'name'                       => 'required|string|max:40|unique:file_types,name,' . $id,
            'slug'                       => 'required|string|max:40|unique:file_types,slug,' . $id,
            'icon'                       => 'required|string|max:240',
            'type'                       => 'required|in:1,2',
            'supported_file_extension'   => 'required|array|min:1',
            'supported_file_extension.*' => 'required|string',
            'image'                      => [$imageValidation, File::types(['png', 'jpg', 'jpeg'])]    
        ]);

        if ($id) {
            $fileType = FileType::find($id);

            if (!$fileType) {
                $toast[] = ['error', 'File type not found'];
                return back()->withToasts($toast);
            }

            $message = ' file type update success';
        } else {
            $fileType = new FileType();
            $message  = ' file type add success';
        }

        if (request()->hasFile('image')) {
            try {
                $fileType->image = fileUploader(request('image'), getFilePath('fileTypes'), null, $fileType->image);
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Image upload fail'];
                return back()->withToasts($toast);
            }
        }

        $fileType->name                     = request('name');
        $fileType->slug                     = request('slug');
        $fileType->icon                     = request('icon');
        $fileType->type                     = request('type');
        $fileType->supported_file_extension = request('supported_file_extension');
        $fileType->save();

        $toast[] = ['success', $fileType->name . $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $fileType = FileType::find($id);

        if (!$fileType) {
            $toast[] = ['error', 'File type not found'];
            return back()->withToasts($toast);
        }

        return FileType::changeStatus($fileType->id);
    }

    protected function fileTypeData($pageTitle, $scope = null) {
        $fileTypes = FileType::when($scope, fn($query) => $query->$scope())->withCount(['approvedImages'])->searchable(['name', 'slug'])->latest()->paginate(getPaginate());

        return view('admin.page.fileTypes', compact('pageTitle', 'fileTypes'));
    }
}
