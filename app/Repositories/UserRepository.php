<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }


    public function getById($id)
    {
        return User::findOrFail($id);
    }




}
