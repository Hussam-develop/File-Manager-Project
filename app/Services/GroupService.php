<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use App\Repositories\GroupRepository;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GroupService
{
    public function __construct(protected GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getAllGroups()
    {
        if (!auth()->user()->isAdmin) {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            return $user->groups()->orderBy('group_id', 'asc')->paginate(6);
        }
        return $this->groupRepository->getAll();
    }

    public function getGroupUsers(Group $group)
    {
        return $this->groupRepository->getGroupUsers($group);
    }

    public function getGroupFiles(Group $group)
    {
        return $this->groupRepository->getGroupFiles($group);
    }

    public function createGroup(Request $request)
    {
        $data = ['name' => $request->name, 'admin_id' => auth()->id()];
        return $this->groupRepository->create($data);
    }

    public function getById($id)
    {
        return $this->groupRepository->getById($id);
    }

    public function deleteGroup($id)
    {
        return $this->groupRepository->deleteGroup($id);
    }

    public function removeUserFromGroup($groupId, $userId)
    {
        $this->groupRepository->removeUserFromGroup($groupId, $userId);
    }

    public function addUserToGroup($groupId, $userId)
    {
        $group = $this->groupRepository->getById($groupId);
        if (!$group->users()->where('user_id', $userId)->exists()) {
            $this->groupRepository->addUserToGroup($group->id, $userId);

            return true; // if user added successfully  return true .
        }
        return false;

          }
}
