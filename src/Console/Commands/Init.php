<?php

namespace Inoplate\Account\Console\Commands;

use Illuminate\Console\Command;
use Inoplate\Account\Domain\Commands;
use Inoplate\Account\Domain\Repositories;
use Inoplate\Foundation\App\Services\Bus\Dispatcher as Bus;
use DB;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inoplate-account:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Account administrator initialization';

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
        $this->truncateTable();

        $this->initRole();

        $administrator = $this->roleRepository->findByName('Administrator');
        $this->attachPermissionToRole($administrator);
        $this->initUser($administrator);
    }

    /**
     * Truncating table before initialization
     * 
     * @return void
     */
    protected function truncateTable()
    {
        $this->info('Truncating users table');
        DB::table('users')->truncate();

        $this->info('Truncating roles table');
        DB::table('roles')->truncate();

        $this->info('Truncating password_resets table');
        DB::table('password_resets')->truncate();

        $this->info('Truncating role_user table');
        DB::table('role_user')->truncate();

        $this->info('Truncating permission_role table');
        DB::table('permission_role')->truncate();
    }

    /**
     * Init role
     * 
     * @return void
     */
    protected function initRole()
    {
        $this->bus->dispatch( new Commands\CreateNewRole('Administrator', ['slug' => 'administrator']) );
        $this->bus->dispatch( new Commands\CreateNewRole('Spectator', ['slug' => 'spectator', 'is_default' => true]) );
    }

    /**
     * Attach permission to role
     * 
     * @param  Inoplate\Account\Domain\Commands\Models $administrator
     * @return void
     */
    protected function attachPermissionToRole($administrator)
    {
        $permissions = $this->permissionRepository->all();

        foreach ($permissions as $permission) {
            $this->bus->dispatch( new Commands\AttachPermissionToRole($administrator->id()->value(), $permission->id()->value()) );
        }
    }

    /**
     * Init user
     * 
     * @param  Inoplate\Account\Domain\Commands\Models $administrator
     * @return void
     */
    protected function initUser($administrator)
    {
        $email = $this->ask('Email?', 'admin@admin.com');
        $username = $this->ask('Username?', 'admin');
        $name = $this->ask('Name?', 'Administrator');
        $password = $this->secret('Password?');

        $this->bus->dispatch( new Commands\RegisterNewUser($username, $email, [$administrator->id()->value()], ['password' => bcrypt($password), 'name' => $name, 'active' => true]) );
    }

}