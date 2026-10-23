<?php

namespace App\Http\Menu;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MainMenuRight extends BaseMenu {
    function __construct() {

        $this->AddMenu('Корзина')->icon('fa-shopping-cart')->link('/carts')->id('carts')->span('countProduct');

        $user = Auth::user();
        /** @var User $user */
        if($user && $user->can('access-to-admin-panel', User::class))
            $this->AddMenu('Управление сайтом')->link('/admin')->icon('fa-cogs');

        if($user){
            $this->AddMenu('Мои заказы')->link('/lk')->icon('fa-cogs')->id('lk');
            $this->AddMenu('Выход')->icon('fa-sign-out')->link('/logout');

        } else
            $this->AddMenu('Вход')->icon('fa-user')->link('/auth');
    }
}
