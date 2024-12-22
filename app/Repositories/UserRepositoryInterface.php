<?php

namespace App\Repositories;

use App\Models\Group;

interface UserRepositoryInterface
{
    public function getAll();
    public function getById($id);
}
