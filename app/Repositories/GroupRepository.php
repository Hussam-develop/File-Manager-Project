<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository implements GroupRepositoryInterface
{
    public function getAll()
    {
        return Group::paginate(8);
    }


    public function getGroupUsers(Group $group){
        return $group->users()->paginate(8);
    }


    public function getGroupFiles(Group $group){
        return $group->files()->paginate(8);
    }


    public function getById($id)
    {
        return Group::findOrFail($id);
    }


    public function create(array $data)
    {
         $group =Group::create($data);
         $group->users()->attach([
            'user_id' => auth()->user()->id,
        ]);

    }


    public function removeUserFromGroup($groupId,$userId){
        $group =$this->getById($groupId);
        $group->users()->detach($userId);
    }


    public function addUserToGroup($groupId,$userId){
        $group=$this->getById($groupId);
        $group->users()->attach($userId);
    }

    public function deleteGroup($id)
    {
        $group = $this->getById($id);
        $group->users()->detach(); // إزالة المستخدمين المرتبطين
        return $group->delete();
    }
}
