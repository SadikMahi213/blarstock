<?php

namespace App\Traits;

use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait UniversalStatus
{
    public static function changeStatus($id, $column = 'status') {
        $modelName = get_class();
        $query     = $modelName::findOrFail($id);

        if ($query->$column == ManageStatus::ACTIVE) {
            $query->$column = ManageStatus::INACTIVE;
        } else {
            $query->$column = ManageStatus::ACTIVE;
        }

        $query->save();
        $message = keyToTitle($column). ' change successful';

        $toast[] = ['success', $message];
        return back()->withToasts($toast);
    }

    public function statusBadge(): Attribute {
        return new Attribute(function() {
            $html = '';

            if ($this->status == ManageStatus::ACTIVE) {
                $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
            } else {
                $html = '<span class="badge badge--warning">' . trans('Inactive') . '</span>';
            }

            return $html;
        });
    }

    //Scope
    public function scopeActive($query) {
        return $query->where('status', ManageStatus::ACTIVE);
    }
}