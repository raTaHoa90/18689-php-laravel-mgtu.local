<?php

namespace App\Http\Menu;

class AuthMenu extends BaseMenu {
    function __construct()
    {
        $this->AddMenu('На главную')->link('/')->icon('fa-home');
        $this->AddMenu('Авторизация')->link('/auth')->icon('fa-sign-out')->id('auth');
        $this->AddMenu('Регистрация')->link('/registration')->icon('fa-user-plus')->id('registration');
    }
}
