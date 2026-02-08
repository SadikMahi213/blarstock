<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Validation\Rules\File;

class AdvertisementController extends Controller
{
    function index() {
        return $this->advertisementData('All Advertisements');
    }

    function active() {
        return $this->advertisementData('Active Advertisements', 'active');
    }

    function inactive() {
        return $this->advertisementData('Inactive Advertisements', 'inactive');
    }

    function store($id = 0) {
        $imageValidation = (request('type') == '1' && !$id) ? 'required' : 'nullable';

        $this->validate(request(), [
            'type'         => 'required|integer|between:1,2',
            'redirect_url' => 'required_if:type,1|url',
            'image'        => [$imageValidation, File::types(['png', 'jpg', 'jpeg'])],
            'script'       => 'required_if:type,2'
        ]);

        if ($id) {
            $advertisement = Advertisement::find($id);

            if (!$advertisement) {
                $toast[] = ['error', 'Advertisement not found'];
                return back()->withToasts($toast);
            }

            $message = 'Advertisement update success';
        } else {
            $advertisement = new Advertisement();
            $message       = 'Advertisement add success';
        }

        if (request()->hasFile('image')) {
            try {
                $advertisement->image = fileUploader(request('image'), getFilePath('advertisements'), "735x90", $advertisement->image);

                if ($id) {
                    $advertisement->script = null;
                }
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Image upload failed'];
                return back()->withToasts($toast);
            }
        }

        if (request('type') == '2') {
            $advertisement->script = request('script');

            if ($id && $advertisement->image) {
                removeFile(getFilePath('advertisements') . '/' . $advertisement->image);
                $advertisement->image = null;
            }
        }

        $advertisement->type         = request('type');
        $advertisement->size         = '735x90';
        $advertisement->redirect_url = request('redirect_url');
        $advertisement->save();

        $toast[] = ['success', $message];
        return back()->withToasts($toast);
    }

    function status($id) {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            $toast[] = ['error', 'Advertisement not found'];
            return back()->withToasts($toast);
        }

        return Advertisement::changeStatus($advertisement->id);
    }

    function delete($id) {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            $toast[] = ['error', 'Advertisement not found'];
            return back()->withToasts($toast);
        }

        if ($advertisement->image) {
            removeFile(getFilePath('advertisements') . '/' . $advertisement->image);
        }

        $advertisement->delete();

        $toast[] = ['success', 'Advertisement delete success'];
        return back()->withToasts($toast);
    }

    protected function advertisementData($pageTitle, $scope = null) {
        $advertisements = Advertisement::when($scope, fn($query) => $query->$scope())->latest()->searchable(['size'])->paginate(getPaginate());

        return view('admin.page.advertisements', compact('pageTitle', 'advertisements'));
    }
}
