<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{   
    function resetForm($email, $verCode) {
        $pageTitle = 'Account Recovery';
        $checkCode = AdminPasswordReset::where('code', $verCode)->where('email', $email)->active()->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('admin.password.request.form')->withToasts($toast);
        }

        return view('admin.auth.reset', compact('pageTitle', 'email', 'verCode'));
    }

    function resetPassword() {
        $passwordValidation = Password::min(6);

        if (bs('strong_pass')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate(request(), [
            'code'     => 'required|int',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', $passwordValidation],
        ]);

        $checkCode = AdminPasswordReset::where('code', request('code'))->where('email', request('email'))->active()->latest()->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('admin.password.request.form')->withToasts($toast);
        }

        $admin = Admin::where('email', $checkCode->email)->first();
        $admin->password = Hash::make(request('password'));
        $admin->save();

        $checkCode->status = ManageStatus::INACTIVE;
        $checkCode->save();

        $toast[] = ['success', 'Password reset successfully'];
        return to_route('admin.login.form')->withToasts($toast);
    }
}
