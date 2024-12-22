<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact(['users']));
    }

    public function userActions($id)
    {

        $user = User::find($id);
        $userActions =$user->actions()->paginate(8);
        return view('users.user-actions', compact(['userActions', 'user']));
    }
}
