<?php

namespace App\Http\Controllers;

class EmployeeController extends Controller {

    public function __construct()
    {
        $this->validateEmployeeAccess();
    }
}
