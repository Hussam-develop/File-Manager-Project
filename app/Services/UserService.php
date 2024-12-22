<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers() {
        return $this->userRepository->getAll();
    }

    public function getById($id) {
        return $this->userRepository->getById($id);

    }

   

}
