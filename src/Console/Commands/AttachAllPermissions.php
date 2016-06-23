<?php

namespace Inoplate\Account\Console\Commands;

use Illuminate\Console\Command;
use Inoplate\Account\Domain\Commands;
use Inoplate\Account\Domain\Repositories;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use DB;

class AttachAllPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inoplate-account:attach-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reattach all permission to given role';

    /**
     * @var Repositories\Role
     */
    protected $roleRepository;

    /**
     * @var Repositories\Permission
     */
    protected $permissionRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Bus\Dispatcher
     */
    protected $bus;

    /**
     * Create new Init instance
     * 
     * @param Repositories\Role       $roleRepository       
     * @param Repositories\Permission $permissionRepository 
     */
    public function __construct(
        Repositories\Role $roleRepository,
        Repositories\Permission $permissionRepository,
        Bus $bus
    ){
        parent::__construct();

        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->bus = $bus;
    }

    /**
     * Handle incoming console command
     * 
     * @return void
     */
    public function handle()
    {
        $roleName = $this->ask('Role name?');
        $role = $this->roleRepository->findByName($roleName);

        if(is_null($role)) {
            $this->error('Rolename ['. $roleName .'] doesn\'t exist ');
        }

        $this->info('Clear attached permissions');
        $this->resetTable($role);

        $this->info('Reattaching permission');
        $this->attachPermissionToRole($role);
    }

    /**
     * Attach permission to role
     * 
     * @param  Inoplate\Account\Domain\Commands\Models $administrator
     * @return void
     */
    protected function attachPermissionToRole($role)
    {
        $permissions = $this->permissionRepository->all();

        foreach ($permissions as $permission) {
            $this->bus->dispatch( new Commands\AttachPermissionToRole($role->id()->value(), $permission->id()->value()) );
        }
    }

    /**
     * Reset role permission table
     * 
     * @param  Role $role
     * @return void
     */
    public function resetTable($role)
    {
        DB::table('permission_role')->where('role_id', $role->id()->value())->delete();
    }
}
