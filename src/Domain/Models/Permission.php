<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models as FoundationModels;
use Inoplate\Foundation\Domain\Contracts\Describeable;

class Permission extends FoundationModels\Entity implements Describeable
{
    use FoundationModels\Describeable;

    /**
     * Create new Permission instance
     * 
     * @param PermissionId  $id
     * @param Description   $description
     */
    public function __construct(PermissionId $id, FoundationModels\Description $description)
    {
        $this->id = $id;
        $this->description = $description;
    }
}