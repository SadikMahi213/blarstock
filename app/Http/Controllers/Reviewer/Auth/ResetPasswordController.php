<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\ReviewerPasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    function resetForm($email, $verCode) {
        $pageTitle = 'Account Recovery';
        $checkCode = ReviewerPasswordReset::where('code', $verCode)->where('email', $email)->active()->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('reviewer.password.request.form')->withToasts($toast);
        }

        return view('reviewer.auth.reset', compact('pageTitle', 'email', 'verCode'));
    }

    function resetPassword() {
        $passwordValidation = Password::min(6);

        if (bs('strong_pass')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate(request(), [
            'code'     => 'required|int',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);

        $checkCode = ReviewerPasswordReset::where('code', request('code'))->where('email', request('email'))->active()->latest()->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('reviewer.password.request.form')->withToasts($toast);
        }

        $reviewer           = Reviewer::where('email', $checkCode->email)->first();
        $reviewer->password = Hash::make(request('password'));
        $reviewer->save();

        $checkCode->status = ManageStatus::INACTIVE;
        $checkCode->save();

        $toast[] = ['success', 'Password reset successful'];
        return to_route('reviewer.login.form')->withToasts($toast);
    }
}
