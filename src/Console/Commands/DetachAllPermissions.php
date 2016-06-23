<?php

namespace Inoplate\Account\Console\Commands;

use Illuminate\Console\Command;
use Inoplate\Account\Domain\Commands;
use Inoplate\Account\Domain\Repositories;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use DB;

class DetachAllPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inoplate-account:detach-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detach all permission to given role';

    /**
     * @var Repositories\Role
     */
    protected $roleRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Bus\Dispatcher
     */
    protected $bus;

    /**
     * Create new Init instance
     * 
     * @param Repositories\Role       $roleRepository       
     * @param Bus                     $bus
     */
    public function __construct(
        Repositories\Role $roleRepository,
        Bus $bus
    ){
        parent::__construct();

        $this->roleRepository = $roleRepository;
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
