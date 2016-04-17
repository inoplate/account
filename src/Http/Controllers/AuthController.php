<?php

namespace Inoplate\Account\Http\Controllers;

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
     * Send response after user is authenticated
     * 
     * @param  Request $request
     * @param  User    $user
     * @return \Illuminate\Http\Response
     */
    public function authenticated(Request $request, User $user)
    {
        return redirect()->intended('/admin/dashboard');
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
            $captchaDriver = config('captcha.challenge');

            $rules[config('captcha.drivers.'.$captchaDriver.'.input')] = 'required|captcha';
        }

        $this->validate($request, $rules);

        $username = $request->input('username');
        $email = $request->input('email');
        $description['password'] = bcrypt($request->input('password'));
        $this->execute( new RegisterNewUser($username, $email, [], $description) );

        return redirect('/login');
    }
}