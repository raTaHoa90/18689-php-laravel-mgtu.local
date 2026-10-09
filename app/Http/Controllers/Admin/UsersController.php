<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

class UsersController extends BaseController {

    function table(){
        $this->menu->active = 'users';

        return view('admin.users.table', [
            'roles' => User::ROLES,
            'users' => User::orderBy('id')->paginate(10)
        ]);
    }

    function user(User $user){

    }

    /******** POST *********/

    function createUser(){

    }

}
