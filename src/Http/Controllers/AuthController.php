<?php

namespace Inoplate\Account\Http\Controllers;

use Auth;
use Inoplate\Account\User;
use Inoplate\Account\Domain\Commands\RegisterNewUser;
use Inoplate\Foundation\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var string
     */
    protected $username = 'identifier';

    /**
     * @var string
     */
    protected $redirectPath = '/admin/dashboard';

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if(!config('inoplate.account.allow_register')) {
            abort(404);
        }

        return view('inoplate-account::auth.register');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('inoplate-account::auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if(filter_var($credentials[$this->loginUsername()], FILTER_VALIDATE_EMAIL) !== false) {
            $credentials['email'] = $credentials[$this->loginUsername()];
        }else {
            $credentials['username'] = $credentials[$this->loginUsername()];
        }

        unset($credentials[$this->loginUsername()]);

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $rules = [
            'username' => 'required|alpha_dash|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];

        if(config('inoplate.account.enable_captcha')) {
            $captchaDriver = config('inoplate.captcha.challenge');

            $rules[config('inoplate.captcha.drivers.'.$captchaDriver.'.input')] = 'required|captcha';
        }

        $this->validate($request, $rules);

        $username = $request->input('username');
        $email = $request->input('email');
        $description['password'] = bcrypt($request->input('password'));
        $this->execute( new RegisterNewUser($username, $email, [], $description) );

        return redirect('/login')->with('success', 'Silahkan cek email anda untuk aktivasi');
    }
}
