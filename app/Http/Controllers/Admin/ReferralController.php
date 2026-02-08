<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\ReferralSetting;

class ReferralController extends Controller
{
    function configuration() {
        $pageTitle = 'Referral Configuration';
        $levels    = ReferralSetting::get();

        return view('admin.page.referral', compact('pageTitle', 'levels'));
    }

    function store() {
        $this->validate(request(), [
           'commission_type' => 'required|string|max:40',
            'percent'        => 'required|array',
            'percent.*'      => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/'
        ], [
            'percent.required'    => 'Minumum one percent field is required',
            'percent.*.required'  => 'Minmum one percent value is required',
            'percent.*.integer'   => 'Provide integer number as percentage',
            'percent.*.min'       => 'Percentage must be greater than 0'
        ]);

        ReferralSetting::where('commission_type', request('commission_type'))->truncate();

        $referralSettings = array_map(fn($percent, $index) => [
            'level'           => $index + 1,
            'percent'         => $percent,
            'commission_type' => request('commission_type'),
            'created_at'      => now(),
            'updated_at'      => now()
        ], request('percent'), array_keys(request('percent')));

        try {
            ReferralSetting::insert($referralSettings);
        } catch (\Exception $exp) {
            $toast[] = ['error', 'Referral configuration update fail'];
            return back()->withToasts($toast);
        }

        $toast[] = ['success', 'Referral configuration update success'];
        return back()->withToasts($toast);
    }

    function status() {
        $setting = bs();

        if ($setting->referral == ManageStatus::ACTIVE) {
            $setting->referral = ManageStatus::INACTIVE;
            $message = 'Referral inactive success';
        } else {
            $setting->referral = ManageStatus::ACTIVE;
            $message = 'Referral active success';
        }

        $setting->save();

        $toast[] = ['success', $message];
        return back()->withToasts($toast);
    }
}
