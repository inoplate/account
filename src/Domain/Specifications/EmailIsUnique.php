<?php

namespace Inoplate\Account\Domain\Specifications;

use Inoplate\Account\Domain\Models\UserId;
use Inoplate\Foundation\Domain\Contracts\Specification;
use Inoplate\Foundation\Domain\Contracts\SpecificationCandidate;
use Inoplate\Account\Domain\Repositories\User as UserRepository;

class EmailIsUnique implements Specification
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserId
     */
    protected $id;

    /**
     * Create new EmailIsUnique instance
     * 
     * @param UserRepository $userRepository
     * @param UserId|null    $id
     */
    public function __construct(UserRepository $userRepository, UserId $id = null)
    {
        $this->userRepository = $userRepository;
        $this->id = $id ?: new UserId(null);
    }

    /**
     * Determine if specification was satisfied by given candidate
     * 
     * @param  SpecificationCandidate $candidate
     * @return boolean
     */
    public function isSatisfiedBy(SpecificationCandidate $candidate)
    {
        if($user = $this->userRepository->findByEmail($candidate->value()))
        {
            if(!$user->id()->equal($this->id))
                return false;
        }

        return true;
    }
}