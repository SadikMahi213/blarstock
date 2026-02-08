<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use UniversalStatus, Searchable;

    public function scopeInactive($query) {
        return $query->where('status', ManageStatus::INACTIVE);
    }

    public function durationBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';

            if ($this->plan_duration == ManageStatus::DAILY_PLAN) {
                $html = '<span class="badge badge--primary">'.trans('Daily').'</span>';
            } elseif($this->plan_duration == ManageStatus::WEEKLY_PLAN) {
                $html = '<span class="badge badge--secondary">'.trans('Weekly').'</span>';
            } elseif($this->plan_duration == ManageStatus::MONTHLY_PLAN) {
                $html = '<span class="badge badge--info">'.trans('Monthly').'</span>';
            } elseif($this->plan_duration == ManageStatus::QUARTERLY_PLAN) {
                $html = '<span class="badge badge--base-two">'.trans('Quarter Annual').'</span>';
            } elseif($this->plan_duration == ManageStatus::SEMI_ANNUAL_PLAN) {
                $html = '<span class="badge badge--base">'.trans('Semi Annual').'</span>';
            } elseif($this->plan_duration == ManageStatus::ANNUAL_PLAN) {
                $html = '<span class="badge badge--success">'.trans('Annual').'</span>';
            }

            return $html;
        });
    }
}
