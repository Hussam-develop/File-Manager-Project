<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function getPaginateUsers()
    {
        return $this->userRepository->getPaginateUsers();
    }

    public function getById($id)
    {
        return $this->userRepository->getById($id);
    }

    public function getUserActions($id)
    {
        return $this->userRepository->getUserActions($id);
    }
    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }
}
