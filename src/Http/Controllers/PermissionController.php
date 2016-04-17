<?php

namespace Inoplate\Account\Http\Controllers;

use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Repositories\Permission as PermissionRepository;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use Inoplate\Account\Domain\Commands;
use Roseffendi\Dales\Dales;
use Roseffendi\Authis\Authis;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $roleRepository;

    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository, RoleRepository $roleRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
    }

    public function getIndex()
    {
        $roles = $this->roleRepository->all();
        $permissions = $this->permissionRepository->all();

        return view('inoplate-account::permissions.index', compact('roles', 'permissions'));
    }

    public function putUpdate(Request $request, Bus $bus, $roleId, $permissionId)
    {
        if($request->attached) {
            return $this->attachPermissionToRole($bus, $roleId, $permissionId);
        }else {
            return $this->detachPermissionFromRole($bus, $roleId, $permissionId);
        }
    }

    protected function attachPermissionToRole(Bus $bus, $roleId, $permissionId) {
        $bus->dispatch( new Commands\AttachPermissionToRole($roleId, $permissionId));

        return $this->formSuccess(route('account.admin.permissions.index.get'), ['message' => trans('inoplate-account::messages.permissions.attached')]);
    }

    protected function detachPermissionFromRole(Bus $bus, $roleId, $permissionId) {
        $bus->dispatch( new Commands\DetachPermissionFromRole($roleId, $permissionId));

        return $this->formSuccess(route('account.admin.permissions.index.get'), ['message' => trans('inoplate-account::messages.permissions.detached')]);
    }
}