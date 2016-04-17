<?php

namespace Inoplate\Account\Providers;

use Inoplate\Foundation\Providers\CommandServiceProvider as ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Commands to register
     * 
     * @var array
     */
    protected $commands = [
        'Inoplate\Account\Domain\Commands\RegisterNewUser' => 
            'Inoplate\Account\App\Handlers\Command\RegisterNewUserHandler@handle',
        'Inoplate\Account\Domain\Commands\DescribeUser' => 
            'Inoplate\Account\App\Handlers\Command\DescribeUserHandler@handle',
        'Inoplate\Account\Domain\Commands\GrantRoleToUser' => 
            'Inoplate\Account\App\Handlers\Command\GrantRoleToUserHandler@handle',
        'Inoplate\Account\Domain\Commands\RevokeRoleFromUser' => 
            'Inoplate\Account\App\Handlers\Command\RevokeRoleFromUserHandler@handle',
        'Inoplate\Account\Domain\Commands\CreateNewRole' => 
            'Inoplate\Account\App\Handlers\Command\CreateNewRoleHandler@handle',
        'Inoplate\Account\Domain\Commands\AttachPermissionToRole' => 
            'Inoplate\Account\App\Handlers\Command\AttachPermissionToRoleHandler@handle',
            'Inoplate\Account\Domain\Commands\DetachPermissionFromRole' => 
            'Inoplate\Account\App\Handlers\Command\DetachPermissionFromRoleHandler@handle',
        'Inoplate\Account\Domain\Commands\UnregisterUser' => 
            'Inoplate\Account\App\Handlers\Command\UnregisterUserHandler@handle',
        'Inoplate\Account\Domain\Commands\CreateRole' => 
            'Inoplate\Account\App\Handlers\Command\CreateRoleHandler@handle',
        'Inoplate\Account\Domain\Commands\DescribeRole' => 
            'Inoplate\Account\App\Handlers\Command\DescribeRoleHandler@handle',
        'Inoplate\Account\Domain\Commands\DeleteRole' => 
            'Inoplate\Account\App\Handlers\Command\DeleteRoleHandler@handle',
    ];
}