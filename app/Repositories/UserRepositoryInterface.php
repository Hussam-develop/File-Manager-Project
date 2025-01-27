<?php

namespace App\Repositories;

use App\Models\Group;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function getById($id);
    public function getUserActions($id);
    public function getPaginateUsers();
}
