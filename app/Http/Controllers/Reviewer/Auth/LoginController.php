<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public $redirectTo = 'reviewer';

    function loginForm() {
        $pageTitle = 'Reviewer Login';

        return view('reviewer.auth.login', compact('pageTitle'));
    }

    protected function guard() {
        return auth()->guard('reviewer');
    }

    function username() {
        return $this->findUsername();
    }

    function login() {
        $this->validateLogin(request());
        request()->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];
            return back()->withToasts($toast);
        }

        $reviewer = Reviewer::where(fn($query) => $query->where('username', request('username'))->orWhere('email', request('username')))->first();

        if (!$reviewer) {
            $toast[] = ['error', 'Reviewer not found'];
            return back()->withToasts($toast);
        }

        if ($reviewer->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'Your account is currently inactive'];
            return back()->withToasts($toast);
        }

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts(request())) {
            $this->fireLockoutEvent(request());

            return $this->sendLockoutResponse(request());
        }

        if ($this->attemptLogin(request())) {
            return $this->sendLoginResponse(request());
        }

        $this->incrementLoginAttempts(request());

        return $this->sendFailedLoginResponse(request());
    }

    function findUsername() {
        $login     = request('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    function logout() {
        $this->guard()->logout();
        request()->session()->invalidate();

        return $this->loggedOut(request()) ?: redirect($this->redirectTo);
    }
}
