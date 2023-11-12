<?php

namespace App\Services;

use App\DTO\UserData;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    )
    {
    }

    public function store(UserData $userData)
    {
        return $this->userRepository->store($userData);
    }

    public function getUserByLogin(UserData $userData)
    {
        return $this->userRepository->getUserByLogin($userData);
    }
}
