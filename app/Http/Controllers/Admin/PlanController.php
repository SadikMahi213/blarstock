<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Validation\Rules\File;

class PlanController extends Controller
{
    function index() {
        return $this->planData('All Plans');
    }

    function active() {
        return $this->planData('Active Plans', 'active');
    }

    function inactive() {
        return $this->planData('Inactive Plans', 'inactive');
    }

    function store($id = 0) {
        $imageValidation = $id ? 'nullable' : 'required';

        $this->validate(request(), [
            'name'          => 'required|string|max:40|unique:plans,name,' . $id,
            'title'         => 'required|string|max:255|unique:plans,title,' . $id,
            'plan_duration' => 'required|in:1,2,3,4,5,6',
            'price'         => 'required|numeric|gt:0',
            'daily_limit'   => 'required|integer|gte:-1',
            'monthly_limit' => 'required_if:plan_duration,3,4,5,6|integer|gte:daily_limit',
            'image'         => [$imageValidation, File::types(['png', 'jpg', 'jpeg'])]
        ]);



        if ($id) {
            $plan = Plan::find($id);

            if (!$plan) {
                $toast[] = ['error', 'Plan not found'];
                return back()->withToasts($toast);
            }

            $message = ' plan update success';
        } else {
            $plan    = new Plan();
            $message = ' plan add success';
        }

        if (request()->hasFile('image')) {
            try {
                $plan->image = fileUploader(request('image'), getFilePath('plans'), getFileSize('plans'), $plan->image);
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Image upload failed'];
                return back()->withToasts($toast);
            }
        }

        $plan->name          = request('name');
        $plan->title         = request('title');
        $plan->plan_duration = request('plan_duration');
        $plan->price         = request('price');
        $plan->daily_limit   = request('daily_limit');
        $plan->monthly_limit = request('monthly_limit') ? request('monthly_limit') : 0;
        $plan->save();

        $toast[] = ['success', $plan->name . $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $plan = Plan::find($id);

        if (!$plan) {
            $toast[] = ['error', 'Plan not found'];
            return back()->withToasts($toast);
        }

        return Plan::changeStatus($plan->id);
    }

    protected function planData($pageTitle, $scope = null) {
        $plans = Plan::when($scope, fn($query) => $query->$scope())->searchable(['name', 'title'])->latest()->paginate(getPaginate());

        return view('admin.page.plans', compact('pageTitle', 'plans'));
    }
}
