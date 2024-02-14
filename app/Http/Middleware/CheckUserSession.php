<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Session;

class CheckUserSession
{

    public function handle($request, Closure $next)
    {
        $current_route = Route::current()->getName() ?? 'dashboard';
        
        if (!$request->session()->exists('logged')) {
            // user value cannot be found in session
            return redirect('/login');
        }
        elseif(session('employee')){

            $checkUser = DB::table('users')->where('username', session('user')->username)->first();

            if($checkUser->status == 'INACTIVE' || $checkUser->status == 'inactive'){
                // destroy session & logout
                Session::flush();
                return redirect('/login?user_logout='.date('m-d_H:i:s'))->with('invalid', 'Your account has been deactivated.');
            }
            elseif($current_route != "page_locked" && !Cookie::get(md5($current_route))){
                // check page/route if locked the redirec to login pin
                    $check_current_route = DB::table('employee_modules')
                    ->join('employee_page_lock', 'employee_page_lock.employee_module_id', '=' , 'employee_modules.id')
                    ->where('employee_page_lock.user_id','=',session('user')->id)
                    ->where('employee_page_lock.is_lock','=',1)
                    ->select('employee_page_lock.*', 'employee_modules.sub_route_name')
                    ->where('employee_modules.is_active', 1)
                    // ->where('employee_modules.route_name', $current_route)
                    ->where(function ($current_module)use($current_route) {
                        $current_module->where('employee_modules.route_name', $current_route)
                                        ->orWhereRaw('FIND_IN_SET(?, employee_modules.sub_route_name)', $current_route);          
                    })
                    ->first();
                
                if($check_current_route) return redirect('/page-locked?m='.$current_route);
            }
            
        }


        return $next($request);
    }

}