<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    function authorizeForm() {
        $user = auth()->user();

        $siteData = [];

        if (!$user->status) {
            $pageTitle = 'Banned';
            $type      = 'ban';
            $siteData  = getSiteData('user_ban.content', true);
        }elseif(!$user->ec) {
            $type          = 'email';
            $pageTitle     = 'Confirm Email';
            $toastTemplate = 'EVER_CODE';
            $siteData      = getSiteData('email_confirm.content', true);
        }elseif (!$user->sc) {
            $type          = 'sms';
            $pageTitle     = 'Confirm Mobile Number';
            $toastTemplate = 'SVER_CODE';
            $siteData      = getSiteData('mobile_confirm.content', true);
        }elseif (!$user->tc) {
            $pageTitle = '2FA Confirmation';
            $type      = '2fa';
            $siteData  = getSiteData('2fa_confirm.content', true);
        }else{
            return to_route('user.author.dashboard');
        }

        if (!$this->checkCodeValidity($user) && ($type != '2fa') && ($type != 'ban')) {
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = now();
            $user->save();

            notify($user, $toastTemplate, [
                'code' => $user->ver_code
            ],[$type]);
        }

        return view($this->activeTheme. 'user.auth.authorization.'.$type, compact('user', 'pageTitle', 'siteData'));
    }

    function sendVerifyCode($type) {
        $user = auth()->user();

        if ($this->checkCodeValidity($user)) {
            $targetTime = $user->ver_code_send_at->addMinutes(2)->timestamp;
            $delay      = $targetTime - time();
            throw ValidationException::withMessages(['resend' => 'Please try after ' . $delay . ' seconds']);
        }

        $user->ver_code         = verificationCode(6);
        $user->ver_code_send_at = now();
        $user->save();

        if ($type == 'email') {
            $type = 'email';
            $toastTemplate = 'EVER_CODE';
        } else {
            $type = 'sms';
            $toastTemplate = 'SVER_CODE';
        }

        notify($user, $toastTemplate, [
            'code' => $user->ver_code
        ],[$type]);

        $toast[] = ['success', 'Verification code send success'];
        return back()->withToasts($toast);
    }

    function emailVerification() {
        $verCode = $this->codeValidation(request());
        $user    = auth()->user();

        if ($user->ver_code == $verCode) {
            $user->ec               = ManageStatus::VERIFIED;
            $user->ver_code         = null;
            $user->ver_code_send_at = null;
            $user->save();

            return to_route('user.author.dashboard');
        }

        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    function mobileVerification() {
        $verCode = $this->codeValidation(request());
        $user    = auth()->user();

        if ($user->ver_code == $verCode) {
            $user->sc               = ManageStatus::VERIFIED;
            $user->ver_code         = null;
            $user->ver_code_send_at = null;
            $user->save();

            return to_route('user.author.dashboard');
        }

        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    function g2faVerification() {
        $verCode  = $this->codeValidation(request());
        $user     = auth()->user();
        $response = verifyG2fa($user, $verCode);

        if ($response) {
            $toast[] = ['success', 'Verification success'];
        }else{
            $toast[] = ['error', 'Wrong verification code'];
        }

        return back()->withToasts($toast);
    }

    protected function checkCodeValidity($user, $addMin = 2) {
        if (!$user->ver_code_send_at){
            return false;
        }

        if ($user->ver_code_send_at->addMinutes($addMin) < now()) {
            return false;
        }

        return true;
    }

    protected function codeValidation() {
        $this->validate(request(), [
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        return (int)(implode("", request('code')));
    }
}
