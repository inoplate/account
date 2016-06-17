<?php

namespace Inoplate\Account\Domain\Specifications;

use Inoplate\Account\Domain\Models\RoleId;
use Inoplate\Foundation\Domain\Contracts\Specification;
use Inoplate\Foundation\Domain\Contracts\SpecificationCandidate;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;

class RolenameIsUnique implements Specification
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @var RoleId
     */
    protected $id;

    /**
     * Create new RolenameIsUnique instance
     * 
     * @param RoleRepository $userRepository
     * @param RoleId|null    $id
     */
    public function __construct(RoleRepository $roleRepository, RoleId $id = null)
    {
        $this->roleRepository = $roleRepository;
        $this->id = $id ?: new RoleId(null);
    }

    /**
     * Determine if specification was satisfied by given candidate
     * 
     * @param  SpecificationCandidate $candidate
     * @return boolean
     */
    public function isSatisfiedBy(SpecificationCandidate $candidate)
    {
        if($user = $this->roleRepository->findByName($candidate->value()))
        {
            if(!$user->id()->equal($this->id))
                return false;
        }

        return true;
    }
}