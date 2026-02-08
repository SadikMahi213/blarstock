<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;

class ColorController extends Controller
{
    function index() {
        return $this->colorData('All Colors');
    }

    function active() {
        return $this->colorData('Active Colors', 'active');
    }

    function inactive() {
        return $this->colorData('Inactive Colors', 'inactive');
    }

    function store($id = 0) {
        $this->validate(request(), [
            'name' => 'required|string|max:40|unique:colors,name,' . $id,
            'code' => 'required|string|max:40|unique:colors,code,' . $id
        ]);

        if ($id) {
            $color = Color::find($id);

            if (!$color) {
                $toast[] = ['error', 'Color not found'];
                return back()->withToasts($toast);
            }

            $message = ' color update success';
        } else {
            $color   = new Color();
            $message = ' color add success';
        }

        $color->name = request('name');
        $color->code = request('code');
        $color->save();

        $toast[] = ['success', $color->name . $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $color = Color::find($id);

        if (!$color) {
            $toast[] = ['error', 'Color not found'];
            return back()->withToasts($toast);
        }

        return Color::changeStatus($color->id);
    }

    function delete($id) {
        $color = Color::find($id);

        if (!$color) {
            $toast[] = ['error', 'Color not found'];
            return back()->withToasts($toast);
        }

        $color->delete();

        $toast[] = ['success', 'Color delete success'];
        return back()->withToasts($toast);
    }

    protected function colorData($pageTitle, $scope = null) {
        $colors = Color::when($scope, fn($query) => $query->$scope())->searchable(['name', 'code'])->latest()->paginate(getPaginate());

        return view('admin.page.colors', compact('pageTitle', 'colors'));
    }
}
