<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    function resetForm($verCode = null) {
        $pageTitle = 'Account Recovery';
        $siteData  = getSiteData('password_reset.content', true);
        $email     = session('fpass_email');

        if (PasswordReset::where('code', $verCode)->where('email', $email)->count() != 1) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('user.password.request.form')->withToasts($toast);
        }

        return view($this->activeTheme . 'user.auth.password.reset')->with(
            ['code' => $verCode, 'email' => $email, 'pageTitle' => $pageTitle, 'siteData' => $siteData]
        );
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

        $email     = request('email');
        $checkCode = PasswordReset::where('code', request('code'))->where('email', $email)->orderBy('created_at', 'desc')->first();

        if (!$checkCode) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('user.password.request.form')->withToasts($toast);
        }

        $user           = User::where('email', $email)->first();
        $user->password = Hash::make(request('password'));
        $user->save();

        $userIpInfo      = getIpInfo();
        $userBrowserInfo = osBrowser();

        notify($user, 'PASS_RESET_DONE', [
            'operating_system' => $userBrowserInfo['os_platform'],
            'browser'          => $userBrowserInfo['browser'],
            'ip'               => $userIpInfo['ip'],
            'time'             => $userIpInfo['time']
        ],['email']);

        $toast[] = ['success', 'Password reset successfully'];
        return to_route('user.login.form')->withToasts($toast);
    }
}
