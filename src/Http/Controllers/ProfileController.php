<?php

namespace Inoplate\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Inoplate\Account\Domain\Commands\DescribeUser;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Bus\Dispatcher;
use Inoplate\Account\App\Services\User\EmailResetter;

class ProfileController extends Controller
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function getIndex()
    {
        $user = $this->auth->user();

        return view('inoplate-account::profile.index', compact('user'));
    }

    public function putUpdate(Dispatcher $bus, Request $request, EmailResetter $emailResetter)
    {
        $user = $this->auth->user();

        $this->validate($request, [
            'email' => 'bail|required|email|max:255|unique:users,email,'.$user->id,
            'username' => 'bail|required|max:255|unique:users,username,'.$user->id,
            'name' => 'required|max:255',
            'password' => 'sometimes|min:6|confirmed',
        ]);

        $desc['name'] = $request->name;

        if($request->password) {
            $desc['password'] = bcrypt($request->password);
        }

        if($request->email !== $user->email) {
            $emailResetter->reset($user->id, $request->email);
        }

        // Keep email with current email, user need to reconfirm it
        $bus->dispatch( new DescribeUser($user->id, $request->username, $user->email, $desc));

        return $this->formSuccess(route('account.admin.profile.index.get'), ['message' => trans('inoplate-account::message.profile.updated')]);
    }
}