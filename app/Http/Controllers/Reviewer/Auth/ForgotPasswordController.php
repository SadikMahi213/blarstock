<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\ReviewerPasswordReset;

class ForgotPasswordController extends Controller
{
    function requestForm() {
        $pageTitle = 'Forgot Password';

        return view('reviewer.auth.passRequest', compact('pageTitle'));
    }

    function sendResetCode() {
        $this->validate(request(), [
            'email' => 'required|email|exists:reviewers,email'
        ]);

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];
            return back()->withToasts($toast);
        }

        $email    = request('email');
        $reviewer = Reviewer::where('email', $email)->first();

        if (!$reviewer) {
            return back()->withErrors(['Email not available']);
        }

        $verCode               = verificationCode(6);
        $passReset             = new ReviewerPasswordReset();
        $passReset->email      = $email;
        $passReset->code       = $verCode;
        $passReset->created_at = now();
        $passReset->save();

        $reviewerIpInfo      = getIpInfo();
        $reviewerBrowserInfo = osBrowser();

        // ______________________password reset code notify___________________

        session()->put('pass_res_email', $email);

        $toast[] = ['success', 'Well, we found you as a registered one'];
        return to_route('reviewer.password.code.verification.form')->withToasts($toast);
    }

    function verificationForm() {
        $pageTitle = 'Code Verification';
        $email     = session()->get('pass_res_email');

        if (!$email) {
            $toast[] = ['error', 'Oops! session expired'];
            return to_route('reviewer.password.request.form')->withToasts($toast);
        }

        return view('reviewer.auth.codeVerification', compact('pageTitle', 'email'));
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

        $email   = request('email');
        $verCode = (int) (implode("", request('code')));

        if (ReviewerPasswordReset::where('code', $verCode)->where('email', $email)->count() != 1) {
            $toast[] = ['error', 'Invalid verification code'];
            return to_route('reviewer.password.request.form')->withToasts($toast);
        }

        $toast[] = ['success', 'Code matched. You can reset your password'];
        return to_route('reviewer.password.reset.form', [$email, $verCode])->withToasts($toast);
    }
}
