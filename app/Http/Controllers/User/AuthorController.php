<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AuthorController extends Controller
{
    function form() {
        $pageTitle = 'Author Form';
        $siteData  = getSiteData('author_form.content', true);
        $form      = Form::where('act', 'author')->first();
        $user      = auth()->user();
        $redirect = $this->authorStateRedirect($user->author_status);

        if ($redirect) return $redirect;

        return view($this->activeTheme . 'user.author.form', compact('pageTitle' , 'siteData', 'form', 'user'));
    }

     function store() {
        $user     = auth()->user();
        $redirect = $this->authorStateRedirect($user->author_status);

        if ($redirect) return $redirect;

        $form           = Form::where('act', 'author')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $validation = [
            'author_name' => 'required|string|max:255|unique:users,author_name'
        ];

        $validation = array_merge($validation, $validationRule);

        request()->validate($validation);

        $authorData = $formProcessor->processFormData(request(), $formData);

        $user->author_name   = request('author_name');
        $user->author_data   = $authorData;
        $user->author_status = ManageStatus::AUTHOR_PENDING;
        $user->joined_at     = Carbon::now();
        $user->save();

        $toast[] = ['success', 'Author data submit success'];
        return to_route('user.author.info')->withToasts($toast);
    }

    function info() {
        $user = auth()->user();
        
        if (!$user->author_status) {
            $toast[] = ['warning', 'Author status don\'t exist'];
            return to_route('user.author.form')->withToasts($toast);
        }

        $pageTitle = 'Author Information';
        
        return view($this->activeTheme . 'user.author.info', compact('pageTitle', 'user'));
    }

    function manualDownload() {
        $setting = bs();

        if (!$setting->instruction_manual) {
            $toast[] = ['warning', 'Instruction manual does not exist'];
            return back()->withToasts($toast);
        }

        $filePath = getFilePath('instructionManual') . '/' . $setting->instruction_manual;

        if (!file_exists($filePath)) {
            $toast[] = ['error', 'Instruction manual not found'];
            return back()->withToasts($toast);
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName  = $setting->site_name . '-manual.' . $extension;

        $headers = [
            'Cache-Control' => 'no-store, no-cache'
        ];

        return response()->download($filePath, $fileName, $headers);
    }

    function profileImageUpdate() {
        $validate = Validator::make(request()->all(), [
            'profile_image' => ['required', File::types(['png', 'jpg', 'jpeg'])]
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $author = auth()->user();

        if (!$author) {
            return response([
                'success' => false,
                'message' => 'Author not Authenticated'
            ]);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Invalid Authors'
            ]);
        }

        if (request()->hasFile('profile_image')) {
            try {
                $author->image = fileUploader(request('profile_image'), getFilePath('userProfile'), getFileSize('userProfile'), $author->image);
                $author->save();

                return response([
                    'success'   => true,
                    'image_url' => asset(getFilePath('userProfile') . '/' . $author->image),
                    'message'   => 'profile image update success'
                ]);
            } catch (\Exception $exp) {
                return response([
                    'success' => false,
                    'message' => 'Profile image update fail'
                ]);
            }
        }

        return response([
            'success' => false,
            'message' => 'File not found'
        ]);
    }

    function coverImageUpdate() {
        $validate = Validator::make(request()->all(), [
            'cover_image' => ['required', File::types(['png', 'jpg', 'jpeg'])]
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $author = auth()->user();

        if (!$author) {
            return response([
                'success' => false,
                'message' => 'Author not found'
            ]);
        }

        if ($author->author_status != ManageStatus::AUTHOR_APPROVED) {
            return response([
                'success' => false,
                'message' => 'Invalid Author'
            ]);
        }

        if (request()->hasFile('cover_image')) {
            try {
                $coverImageFile = request('cover_image');
                
                $fileName = fileUploader($coverImageFile, getFilePath('userCover'), null, $author->cover_image);
                $author->cover_image = $fileName;

                $thumbFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_thumb.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $thumbFullPath = getFilePath('userCover') . '/' . $thumbFileName;

                if ($author->cover_image_thumb) {
                    removeFile(getFilePath('userCover') . '/' . $author->cover_image_thumb);
                }

                $manager = new ImageManager(new Driver());
                $thumb   = $manager->read($coverImageFile);

                $originalWidth = $thumb->width();
                $thumbWidth    = round($originalWidth * 0.236);

                $originalHeight = $thumb->height();
                $thumbHeight = round($originalHeight * 0.236);

                $thumb->resize($thumbWidth, $thumbHeight, function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $thumb->save($thumbFullPath);

                $author->cover_image_thumb = $thumbFileName;
                $author->save();

                return response([
                    'success'   => true,
                    'image_url' => asset(getFilePath('userCover') . '/' . $author->cover_image),
                    'message'   => 'Cover image update success'
                ]);
            } catch (\Exception $exp) {
                return response([
                    'success' => false,
                    'message' => 'Cover image update fail'
                ]);
            }
        }

        return response([
            'success' => false,
            'message' => 'File not found'
        ]);
    }

     protected function authorStateRedirect($authorStatus) {
        $state = null;

        switch ($authorStatus) {
            case ManageStatus::AUTHOR_PENDING:
                $state = ['sign' => 'warning', 'route' => 'user.author.info', 'message' => 'Your author verification is being processed'];
                break;

            case ManageStatus::AUTHOR_APPROVED:
                $state = ['sign' => 'warning', 'route' => 'user.author.dashboard', 'message' => 'Your author verification is being succeed'];
                break;

            case ManageStatus::AUTHOR_BANNED:
                $state = ['sign' => 'warning', 'route' => 'user.author.dashboard', 'message' => 'Your membership is banned from author access'];
                break;
        }

        if ($state) {
            $toast[] = [$state['sign'], $state['message']];
            return to_route($state['route'])->withToasts($toast);
        }

        return null;
    }
}
