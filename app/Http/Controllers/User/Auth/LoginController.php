<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $username;

    function __construct() {
        parent::__construct();
        $this->username = $this->findUsername();
    }

    function loginForm() {
        $pageTitle = 'Login';
        $siteData  = getSiteData('login.content', true);

        return view($this->activeTheme . 'user.auth.login', compact('pageTitle', 'siteData'));
    }

    function login() {
        $this->validateLogin(request());

        request()->session()->regenerateToken();

        if(!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];
            return back()->withToasts($toast);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if ($this->hasTooManyLoginAttempts(request())) {
            $this->fireLockoutEvent(request());

            return $this->sendLockoutResponse(request());
        }

        if ($this->attemptLogin(request())) {
            return $this->sendLoginResponse(request());
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.

        $this->incrementLoginAttempts(request());

        return $this->sendFailedLoginResponse(request());
    }

    function findUsername() {
        $login     = request()->input('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    function username() {
        return $this->username;
    }

    protected function validateLogin() {
        $this->validate(request(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    function logout() {
        $this->guard()->logout();
        request()->session()->invalidate();

        $toast[] = ['success', 'Logout success'];
        return back()->withToasts($toast);
    }

    function authenticated(Request $request, $user) {
        $user->tc = $user->ts == ManageStatus::VERIFIED ? ManageStatus::UNVERIFIED : ManageStatus::VERIFIED;
        $user->save();

        return to_route('user.author.dashboard');
    }
}
