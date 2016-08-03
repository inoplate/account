<?php

namespace Inoplate\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Domain\Commands\DescribeUser;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Bus\Dispatcher;
use Inoplate\Account\App\Services\User\EmailResetter;
use Inoplate\Media\Domain\Repositories\Library as LibraryRepository;
use Inoplate\Media\Domain\Commands\DescribeLibrary;
use Roseffendi\Authis\Authis;

class ProfileController extends Controller
{
    protected $auth;

    protected $authis;

    public function __construct(Guard $auth, Authis $authis)
    {
        $this->auth = $auth;
        $this->authis = $authis;
    }

    public function getIndex(UserRepository $userRepository)
    {
        $user = $this->auth->user();
        $userDomain = $userRepository->findById($user->id);

        return view('inoplate-account::profile.index', ['user' => $userDomain->toArray()]);
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

        return $this->formSuccess(route('account.admin.profile.index.get'), ['message' => trans('inoplate-account::messages.profile.updated')]);
    }

    public function putUpdateAvatar(
        Dispatcher $bus, 
        Request $request, 
        UserRepository $userRepository, 
        LibraryRepository $libraryRepository, 
        $id
    ) {
        $userId = $this->authis->check('account.admin.users.update.get') ? $id : $request->user()->id;
        $library = $libraryRepository->findByPath($request->avatar);

        $user = $userRepository->findById($userId)->toArray();
        $description = $user['description'];

        $this->validate($request, [
            'avatar' => 'required'
        ]);

        $description['avatar'] = $request->avatar;

        $bus->dispatch( new DescribeUser($userId, $user['username'], $user['email'], $description));

        if($library) {
            $library = $library->toArray();
            $libraryDescription = $library['description'];
            $libraryDescription['visibility'] = 'public';

            $bus->dispatch(new DescribeLibrary($library['id'], $libraryDescription));
        }

        return $this->formSuccess(route('account.admin.profile.index.get'), ['message' => trans('inoplate-account::messages.profile.avatar_updated')]);
    }
}