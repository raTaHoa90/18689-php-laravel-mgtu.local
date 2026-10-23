<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Menu\AuthMenu;

class BaseController extends Controller {

    function __construct() {
        $this->menu = new AuthMenu();
        view()->share('menu', $this->menu);
    }
}
