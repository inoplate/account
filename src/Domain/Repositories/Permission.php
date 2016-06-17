<?php

namespace Inoplate\Account\Domain\Repositories;

interface Permission
{
    /**
     * Retrieve all permissions
     * 
     * @return array
     */
    public function all();

    /**
     * Retrieve permission by id
     * 
     * @param  mixed $id
     * @return Inoplate\Account\Domain\Models\Permission
     */
    public function findById($id);
}