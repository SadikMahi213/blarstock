<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPasswordReset;

class ForgotPasswordController extends Controller
{
    function requestForm() {
        $pageTitle = 'Forgot Password';
        return view('admin.auth.passRequest', compact('pageTitle'));
    }

    function sendResetCode() {
        $this->validate(request(), [
            'email' => 'required|email|exists:admins,email',
        ]);

        if(!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];
            return back()->withToasts($toast);
        }

        $email = request('email');
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return back()->withErrors(['Email Not Available']);
        }

        $verCode                = verificationCode(6);
        $passReset              = new AdminPasswordReset();
        $passReset->email       = $email;
        $passReset->code        = $verCode;
        $passReset->created_at  = now();
        $passReset->save();

        $adminIpInfo      = getIpInfo();
        $adminBrowserInfo = osBrowser();

        notify($admin, 'PASS_RESET_CODE', [
            'code'             => $verCode,
            'operating_system' => $adminBrowserInfo['os_platform'],
            'browser'          => $adminBrowserInfo['browser'],
            'ip'               => $adminIpInfo['ip'],
            'time'             => $adminIpInfo['time']
        ],['email']);

        session()->put('pass_res_email', $email);

        $toast[] = ['success', 'Well, we found you as a registered one'];
        return to_route('admin.password.code.verification.form')->withToasts($toast);
    }

    function verificationForm() {
        $pageTitle = 'Code Verification';
        $email     = session()->get('pass_res_email');

        if (!$email) {
            $toast[] = ['error','Oops! session expired'];
            return to_route('admin.password.request.form')->withToasts($toast);
        }

        return view('admin.auth.codeVerification', compact('pageTitle', 'email'));
    }

    function verificationCode() {
        $this->validate(request(), [
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
            'email'  => 'required|email'
        ], [
            'code.*.required' => 'All code field is required',
            'code.*.integer'  => 'All code should be integer',
        ]);

        $email     = request('email');
        $verCode   = (int)(implode("", request('code')));
        $toast[]   = ['success','Code matched. You can reset your password'];

        return to_route('admin.password.reset.form', [$email, $verCode])->withToasts($toast);
    }
}
