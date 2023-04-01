<?php

namespace App\Http\Controllers;

use App\Models\AccessTier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function validateAccessTier($accessTierIds) {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) use ($accessTierIds) {
            if (!in_array(app('user_tier_id'), $accessTierIds)){

                return redirect('/app');
            }

            return $next($request);
        });

    }

    public function validateAdminAccess(){
        $this->validateAccessTier(app('admin_access'));
    }

    public function validateManagerAccess(){
        $this->validateAccessTier(app('manager_access'));
    }

    public function validateEmployeeAccess(){
        $this->validateAccessTier(app('employee_access'));
    }

}
