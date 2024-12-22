<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreGroupRequest;
use App\Services\GroupService;
use App\Services\UserService;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected GroupService $groupService, protected UserService $userService)
    {
        $this->groupService = $groupService;
    }

    /**
     * get all groups
     */
    public function index()
    {
        $groups = $this->groupService->getAllGroups();
        return view('groups.index', compact([
            'groups',
        ]));
    }

    /**
     * create group
     */
    public function store(Request $request)
    {
        $this->groupService->createGroup($request);
        flash()->success('Your group has been created.');
        return redirect()->route('admin.dashboard.groups.index');
    }
    //
    // get users from group
    //
    public function groupUsers($id)
    {
        $group = $this->groupService->getById($id);
        $users = $this->userService->getAllUsers();
        $group_Users = $this->groupService->getGroupUsers($group);
        return view('groups.group-users', compact([
            'group',
            'group_Users',
            'users'
        ]));
    }
    //
    // get files from group
    //
    public function groupFiles($id)
    {
        $group = $this->groupService->getById($id);
        $files = $this->groupService->getGroupFiles($group);
        return view('files.files', compact([
            'files',
            'group'
        ]));
    }
    //
    // add user to group
    //
    public function addUserTogroup(Request $request, $groupId)
    {

        $result = $this->groupService->addUserToGroup($groupId, $request->user_id);
        if (!$result) {
            Session::flash('warning', 'User already exists in this group.');
            return redirect()->back();
        }
        Session::flash('success', 'User Added Succcessfully To group');
        return redirect()->back();
    }
    //
    // remove user frim group
    //
    public function removeUserFromGroup(Request $request, $groupId)
    {
        $this->groupService->removeUserFromGroup($groupId, $request->user_id);

        return redirect()->back();
    }
    //
    // delete group
    //
    public function destroy(Request $request, $id)
    {
        $this->groupService->deleteGroup($id);
        Session::flash('success', 'Group destroyed successfully');
        return redirect()->back();
    }
}
