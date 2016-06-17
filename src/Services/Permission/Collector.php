<?php

namespace Inoplate\Account\Services\Permission;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Repositories\Permission as PermissionRepository;

class Collector
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Collect permissions
     * 
     * @param  string $name
     * @param  string $description
     * @return void
     */
    public function collect($name, $description, $module = '')
    {
        $permissionId = new AccountDomainModels\PermissionId($name);

        if( !$this->permissionRepository->findById($permissionId)) {
            $description = new FoundationDomainModels\Description(['description' => $description, 'module' => $module]);
            $permission = new AccountDomainModels\Permission($permissionId, $description);

            $this->permissionRepository->save($permission);
        }
    }
}