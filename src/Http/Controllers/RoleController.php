<?php

namespace Inoplate\Account\Http\Controllers;

use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use Inoplate\Account\Domain\Commands;
use Roseffendi\Dales\Dales;
use Roseffendi\Authis\Authis;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $authis;

    protected $roleRepository;

    public function __construct(Authis $authis, RoleRepository $roleRepository)
    {
        $this->authis = $authis;
        $this->roleRepository = $roleRepository;
    }

    public function getIndex()
    {
        $actions['active'] = [];
        $actions['trashed'] = [];

        if($this->authis->check('account.admin.roles.create.get')) {
            $actions['active'][] = 'create';
            $actions['trashed'][] = 'create';
        }

        if($this->authis->check('account.admin.roles.delete')) {
            $actions['active'][] = 'delete';
            $actions['trashed'][] = 'delete';
        }

        return view('inoplate-account::role.index', compact('actions'));
    }

    public function getDatatables(Dales $dales, $trashed = false)
    {
        if($trashed) {
            return $this->getTrashedDatatables($dales);
        }else {
            return $this->getActiveDatatables($dales);
        }
    }

    public function getCreate(RoleRepository $roleRepository)
    {
        return $this->getResponse('inoplate-account::role.create');
    }

    public function postCreate(Request $request, Bus $bus)
    {
        $this->validate($request, [
            'name' => 'bail|required|max:255|unique:roles',
            'slug' => 'bail|required|alpha_dash|max:255|unique:roles',
        ]);

        $bus->dispatch( new Commands\CreateNewRole($request->name, ['slug' => $request->slug]) );

        $role = $this->roleRepository->findByName(new FoundationDomainModels\Name($request->name))->toArray();

        return $this->formSuccess(route('account.admin.roles.index.get'), ['message' => trans('inoplate-account::messages.role.created'), 'role' => $role]);
    }

    public function getUpdate($id)
    {
        $role = $this->roleRepository->findById(new AccountDomainModels\RoleId($id));

        if(is_null($role)) {
            abort(404);
        }else {
            $role = $role->toArray();
        }

        return $this->getResponse('inoplate-account::role.update', compact('role'));
    }

    public function putUpdate(Request $request, Bus $bus, $id)
    {
        $role = $this->roleRepository->findById(new AccountDomainModels\RoleId($id));

        if(is_null($role)) {
            abort(404);
        }else {
            $role = $role->toArray();
        }

        $this->validate($request, [
            'name' => 'bail|required|max:255|unique:roles,name,'.$id,
            'slug' => 'bail|required|alpha_dash|max:255|unique:roles,slug,'.$id,
        ]);

        $bus->dispatch( new Commands\DescribeRole($id, $request->name, ['slug' => $request->slug]));

        $role = $this->roleRepository->findById(new AccountDomainModels\RoleId($id))->toArray();

        return $this->formSuccess(route('account.admin.roles.index.get'), ['message' => trans('inoplate-account::messages.role.updated'), 'role' => $role]);
    }

    public function delete(Bus $bus, $ids)
    {
        $ids = explode(',', $ids);

        foreach ($ids as $id) {
            $bus->dispatch( new Commands\DeleteRole($id));
        }

        return $this->formSuccess(route('account.admin.roles.index.get'), ['message' => trans('inoplate-account::messages.role.deleted')]);
    }

    public function putRestore($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $this->roleRepository->getModel()
                                 ->onlyTrashed()
                                 ->find($id)
                                 ->restore();
        }

        return $this->formSuccess(route('account.admin.roles.index.get'), ['message' => trans('inoplate-account::messages.role.restored')]);
    }

    public function deleteForceDelete($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $this->roleRepository->getModel()
                                ->onlyTrashed()
                                ->find($id)
                                ->forceDelete();
        }

        return $this->formSuccess(route('account.admin.roles.index.get'), ['message' => trans('inoplate-account::messages.role.permanently-deleted')]);
    }

    protected function getActiveDatatables(Dales $dales)
    {
        return $dales->setDTDataProvider($this->roleRepository)
                     ->addColumn('actions', function($data) {
                        $actions = [];

                        if($this->authis->check('account.admin.roles.update.get'))
                            $actions[] = 'update';

                        if($this->authis->check('account.admin.roles.delete'))
                            $actions[] = 'delete';

                        return $actions;
                     })
                     ->render();
    }

    protected function getTrashedDatatables(Dales $dales)
    {
        return $dales->setDTDataProvider($this->roleRepository, ['deleted'])
                     ->render();
    }
}