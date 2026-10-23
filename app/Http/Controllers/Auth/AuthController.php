<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController {

    function authPage(){
        $this->menu->active = 'auth';
        return view('auth.auth');
    }

    function logout(){
        Auth::logout();
        return redirect('/');
    }

    // POST

    function login(){
        request()->validate([
            'login' => 'required|min:3|email',
            'pass' => 'required|min:6'
        ]);

        if(Auth::attempt(['email' => request('login'), 'password' => request('pass')])) {
            request()->session()->regenerate();

            $user = Auth::user();
            /** @var User $user */
            if($user->group_role == User::ROLE_CLIENT)
                return redirect('/lk');
            return redirect('/admin');
        }

        return back()->withErrors([
            'email' =>  __('auth.failed') //'указанный пользователь не существует или неправвильно введен пароль'
        ])->onlyInput('login');
        //->withInput(request()->all());
    }
}
