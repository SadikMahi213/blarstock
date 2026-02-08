<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class ReviewerController extends Controller
{
    function dashboard() {
        $reviewer  = auth('reviewer')->user();
        $pageTitle = 'Reviewer Dashboard';

        $passwordAlert = false;

        if (Hash::check('admin', $reviewer->password) || $reviewer->username == 'reviewer') {
            $passwordAlert = true;
        }

        $assetCount = [
            'total'            => Image::count(),
            'pending'          => Image::pending()->count(),
            'approvedByMyself' => Image::approved()->where('reviewer_id', $reviewer->id)->count(),
            'rejectedByMyself' => Image::rejected()->where('reviewer_id', $reviewer->id)->count()
        ];

        $months = collect();

        for($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonth($i)->format('F-Y'));
        }

        $approvedRaw = Image::where('reviewer_id', $reviewer->id)
                        ->approved()
                        ->where('created_at', '>=', Carbon::now()->subYear())
                        ->selectRaw("COUNT(id) as total, DATE_FORMAT(created_at, '%M-%Y')as month")
                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M-%Y')"))
                        ->pluck('total', 'month');

        $rejectedRaw = Image::where('reviewer_id', $reviewer->id)
                        ->rejected()
                        ->where('created_at', '>=', Carbon::now()->subYear())
                        ->selectRaw("COUNT(id) as total, DATE_FORMAT(created_at, '%M-%Y')as month")
                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M-%Y')"))
                        ->pluck('total', 'month');

        $report['months']   = collect();
        $report['approved'] = collect();
        $report['rejected'] = collect();

        foreach ($months as $month) {
            $report['months']->push($month);
            $report['approved']->push($approvedRaw[$month] ?? 0);
            $report['rejected']->push($rejectedRaw[$month] ?? 0);
        }

        return view('reviewer.page.dashboard', compact('pageTitle', 'reviewer', 'passwordAlert', 'assetCount', 'months', 'report'));
    }

    function profile() {
        $pageTitle = 'Profile';
        $reviewer  = auth('reviewer')->user();

        return view('reviewer.page.profile', compact('pageTitle', 'reviewer'));
    }

    function profileUpdate() {
        $this->validate(request(), [
            'name'    => 'required|max:40',
            'contact' => 'required|string',
            'address' => 'required|string',
            'image'   => ['nullable', File::types(['png', 'jpg', 'jpeg'])]
        ]);

        $reviewer = auth('reviewer')->user();

        if (request()->hasFile('image')) {
            try {
                $reviewer->image = fileUploader(request('image'), getFilePath('reviewerProfile'), getFileSize('reviewerProfile'), $reviewer->image);
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Image upload failed'];
                return back()->withToasts($toast);
            }
        }

        $reviewer->name    = request('name');
        $reviewer->contact = request('contact');
        $reviewer->address = request('address');
        $reviewer->save();

        $toast[] = ['success', 'Profile update success'];
        return back()->withToasts($toast);
    }

    function passwordChange() {
        $passwordValidation = Password::min(6);

        if (bs('strong_pass')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate(request(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation]
        ]);

        $reviewer = auth('reviewer')->user();

        if (!Hash::check(request('current_password'), $reviewer->password)) {
            $toast[] = ['error', 'Current password mismatched'];
            return back()->withToasts($toast);
        }

        $reviewer->password = Hash::make(request('password'));
        $reviewer->save();

        $toast[] = ['success', 'Password change success'];
        return back()->withToasts($toast);
    }
}
