<?php

namespace App\Http\Controllers\Auth;

use App\Mail\UserRegistrationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends BaseController {

    function page(){
        $this->menu->active = 'registration';
        return view('auth.registration');
    }

    // POST
    function newUser() {
        request()->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email|regex:/^([\w+-]+\.)*[\w+-]*\w@([\w-]+\.){1,3}[\w]{2,}$/i',
            'pass' => 'required|min:6|confirmed'
        ]);

        $email = request('email');
        $pass = request('pass');

        $user = User::create([
            'email'      => $email,
            'name'       => request('name'),
            'group_role' => User::ROLE_CLIENT,
            'password'   => $pass
        ]);

        Mail::to($email)->send(new UserRegistrationMail($user, $pass));

        return redirect('/auth');
    }
}
