<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getPaginateUsers()
    {
        return User::paginate(8);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function getUserActions($id)
    {
        $user = $this->getById($id);
        return $user->actions()->paginate(8);
    }
}
