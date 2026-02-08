<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Validation\Rules\File;

class CategoryController extends Controller
{
    function index() {
        return $this->categoryData('All Categories');
    }

    function active() {
        return $this->categoryData('Active Categories', 'active');
    }

    function inactive() {
        return $this->categoryData('Inactive Categories', 'inactive');
    }

    function store($id = 0) {
        $imageValidation = $id ? 'nullable' : 'required';

        $this->validate(request(), [
            'name'  => 'required|string|unique:categories,name,' . $id,
            'slug'  => 'required|string|unique:categories,slug,' . $id,
            'image' => [$imageValidation, File::types(['png', 'jpg', 'jpeg'])]
        ]);

        if ($id) {
            $category = Category::find($id);

            if (!$category) {
                $toast[] = ['error', 'Category not found'];
                return back()->withToasts($toast);
            }

            $message = ' category update success';
        } else {
            $category = new Category();
            $message  = ' category add success';
        }

        if (request()->hasFile('image')) {
            try {
                $category->image = fileUploader(request('image'), getFilePath('categories'), getFileSize('categories'), $category->image);
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Image upload fail'];
                return back()->withToasts($toast);
            }
        }

        $category->name = request('name');
        $category->slug = request('slug');
        $category->save();

        $toast[] = ['success', $category->name . $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $category = Category::find($id);

        if (!$category) {
            $toast[] = ['error', 'Category not found'];
            return back()->withToasts($toast);
        }

        return Category::changeStatus($category->id);
    }

    protected function categoryData($pageTitle, $scope = null) {
        $categories = Category::when($scope, fn($query) => $query->$scope())->withCount(['approvedImages'])->searchable(['name', 'slug'])->latest()->paginate(getPaginate());

        return view('admin.page.categories', compact('pageTitle', 'categories'));
    }
}
