<?php

namespace App\Http\Controllers;

class ManagerController extends Controller {

    public function __construct()
    {
        $this->validateManagerAccess();
    }
}
