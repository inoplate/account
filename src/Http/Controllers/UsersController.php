<?php

namespace Inoplate\Account\Http\Controllers;

use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use Inoplate\Account\Domain\Commands;
use Roseffendi\Dales\Dales;
use Roseffendi\Authis\Authis;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $authis;

    protected $userRepository;

    public function __construct(Authis $authis, UserRepository $userRepository)
    {
        $this->authis = $authis;
        $this->userRepository = $userRepository;
    }

    public function getIndex(RoleRepository $roleRepository)
    {
        $actions['active'] = [];
        $actions['trashed'] = [];

        if($this->authis->check('account.admin.users.register.get')) {
            $actions['active'][] = 'register';
            $actions['trashed'][] = 'register';
        }

        if($this->authis->check('account.admin.users.delete')) {
            $actions['active'][] = 'delete';
            $actions['trashed'][] = 'delete';
        }

        $roles = $roleRepository->all();

        return view('inoplate-account::users.index', compact('actions', 'roles'));
    }

    public function getDatatables(Dales $dales, $trashed = false)
    {
        if($trashed) {
            return $this->getTrashedUsersDatatables($dales);
        }else {
            return $this->getActiveUsersDatatables($dales);
        }
    }

    public function getRegister(RoleRepository $roleRepository)
    {
        $roles = $roleRepository->all();

        return $this->getResponse('inoplate-account::users.register', compact('roles'));
    }

    public function postRegister(Request $request, Bus $bus)
    {
        $this->validate($request, [
            'email' => 'bail|required|email|max:255|unique:users',
            'username' => 'bail|required|alpha_dash|max:255|unique:users',
            'name' => 'required|max:255',
            'roles' => 'required',
            'status' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $desc = ['password' => bcrypt($request->password), 'name' => $request->name, 'active' => $request->status ? true : false];

        $bus->dispatch( new Commands\RegisterNewUser($request->username, $request->email, $request->roles, $desc) );

        $user = $this->userRepository->findByUsername(new AccountDomainModels\Username($request->username))->toArray();

        return $this->formSuccess(route('account.admin.users.update.get', ['id' => $user['id']]), ['message' => trans('inoplate-account::messages.users.registered'), 'user' => $user]);
    }

    public function getUpdate(RoleRepository $roleRepository, $id)
    {
        $user = $this->userRepository->findById(new AccountDomainModels\UserId($id));
        $roles = $roleRepository->all();

        if(is_null($user)) {
            abort(404);
        }else {
            $user = $user->toArray();
        }

        return $this->getResponse('inoplate-account::users.update', compact('user', 'roles'));
    }

    public function putUpdate(Request $request, Bus $bus, $id)
    {
        $user = $this->userRepository->findById(new AccountDomainModels\UserId($id));

        if(is_null($user)) {
            abort(404);
        }else {
            $user = $user->toArray();
        }

        $this->validate($request, [
            'email' => 'bail|required|email|max:255|unique:users,email,'.$id,
            'username' => 'bail|required|alpha_dash|max:255|unique:users,username,'.$id,
            'name' => 'required|max:255',
            'roles' => 'required',
            'status' => 'required',
            'password' => 'sometimes|min:6|confirmed',
        ]);

        $desc['name'] = $request->name;
        $desc['active'] = $request->status ? true : false;

        if($request->has('password')) {
            $desc['password'] = bcrypt($request->password);
        }

        $this->grantRoleToUser($bus, $id, $user['roles'], $request->roles);
        $this->revokeRoleFromUser($bus, $id, $user['roles'], $request->roles);

        $bus->dispatch( new Commands\DescribeUser($id, $request->username, $request->email, $desc));

        $user = $this->userRepository->findById(new AccountDomainModels\UserId($id))->toArray();

        return $this->formSuccess(route('account.admin.users.index.get'), ['message' => trans('inoplate-account::messages.users.updated'), 'user' => $user]);
    }

    public function delete(Bus $bus, $ids)
    {
        $ids = explode(',', $ids);

        foreach ($ids as $id) {
            $bus->dispatch( new Commands\UnregisterUser($id));
        }

        return $this->formSuccess(route('account.admin.users.index.get'), ['message' => trans('inoplate-account::messages.users.unregistered')]);
    }

    public function putRestore($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $this->userRepository->createModel()
                                 ->onlyTrashed()
                                 ->find($id)
                                 ->restore();
        }

        return $this->formSuccess(route('account.admin.users.index.get'), ['message' => trans('inoplate-account::messages.users.restored')]);
    }

    public function deleteForceDelete($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $this->userRepository->createModel()
                                ->onlyTrashed()
                                ->find($id)
                                ->forceDelete();
        }

        return $this->formSuccess(route('account.admin.users.index.get'), ['message' => trans('inoplate-account::messages.users.permanently-deleted')]);
    }

    protected function getActiveUsersDatatables(Dales $dales)
    {
        return $dales->setDTDataProvider($this->userRepository)
                     ->addColumn('roles', function($data){
                        return collect($data['roles'])->lists('name');
                     })
                     ->addColumn('actions', function($data) {
                        $actions = [];

                        if($this->authis->check('account.admin.users.update.get'))
                            $actions[] = 'update';

                        if($this->authis->check('account.admin.users.delete'))
                            $actions[] = 'delete';

                        return $actions;
                     })
                     ->render();
    }

    protected function getTrashedUsersDatatables(Dales $dales)
    {
        return $dales->setDTDataProvider($this->userRepository, ['deleted'])
                     ->addColumn('roles', function($data){
                        return collect($data['roles'])->lists('name');
                     })
                     ->addColumn('actions', function($data) {
                        $actions = [];

                        if($this->authis->check('account.admin.users.update.get'))
                            $actions[] = 'update';

                        if($this->authis->check('account.admin.users.delete'))
                            $actions[] = 'delete';

                        return $actions;
                     })
                     ->render();
    }

    protected function grantRoleToUser(Bus $bus, $userId, array $grantedRoles, array $plannedGrantedRoles)
    {
        foreach ($plannedGrantedRoles as $key => $role) {
            $key = array_search($role, array_column($grantedRoles, 'id'));
            if($key === false) {
                $bus->dispatch( new Commands\GrantRoleToUser($userId, $role) );
            }
        }
    }

    protected function revokeRoleFromUser(Bus $bus, $userId, array $grantedRoles, array $plannedGrantedRoles)
    {
        foreach ($grantedRoles as $key => $role) {
            if( !in_array($role['id'], $plannedGrantedRoles)) {
                $bus->dispatch( new Commands\RevokeRoleFromUser($userId, $role['id']) );
            }
        }
    }
}