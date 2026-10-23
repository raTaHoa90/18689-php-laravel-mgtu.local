<?php

namespace App\Http\Controllers;


class LKController extends BaseController {

    function page(){
        $this->rightMenu->active = 'lk';
        return view('lk.table');
    }
}
