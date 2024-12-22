<?php

namespace App\Repositories;

use App\Models\Group;

interface GroupRepositoryInterface
{
    public function getAll();
    public function getGroupUsers(Group $group);
    public function getGroupFiles(Group $group);
    public function getById($id);
    public function create(array $data);
    public function deleteGroup($id);
    public function addUserToGroup($groupId,$userId);
    public function removeUserFromGroup($groupId,$userId);
}
