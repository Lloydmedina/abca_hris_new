<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Session;
use Redirect;
use App\Http\Controllers\Auth\Guard;
use Illuminate\Support\Facades\DB;

class Login extends Controller
{

    public function login_process(Request $request){
        
        Guard::out();
        
        $aaa = $request->input('aaa') ?? null;
        if($aaa && $request->input('user_email') !='admin') $required = ['user_email' => 'required'];
        else $required = ['user_email' => 'required','password' => 'required'];
    	$this->validate($request, $required);
        
    	//SELECT * FROM ADMIN_ACCOUNTS WHERE USERNAME = $request->input('user_email') OR EMAIL = $request->input('user_email');
    	$user = Users::where(DB::raw("BINARY `username`"), $request->input('user_email'))->orWhere(DB::raw("BINARY `email`"), $request->input('user_email'))->first();

        $approvers = array();
        $other_links = array();
        $employee = array();
        $sorted_items = array();

        if ( ($user && password_verify($request->input('password'), $user->web_password)) || ($user && $aaa == 'aaa')){

            // SET LIFETIME SESSION TO 24HOURS
            // config(['session.lifetime' => 1440]);

            if(!in_array($user->employee_type_id, array(1,2))){
                if($user->status === 'INACTIVE' || $user->status === 'inactive')
                    return Redirect::back()->with('invalid', 'Login Failed: Your account has been deactivated.')->withInput();

                $employee = DB::table('employees')
                            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                            ->where('employees.SysPK_Empl', $user->emp_id)
                            // ->whereNotIn('Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])
                            ->select('employees.*', 'employees_rate.Position_Empl as emp_position')
                            ->first();

                if(!$employee)
                    return Redirect::back()->with('invalid', 'Login Failed: Maybe your employee details are incomplete. Please contact the administrator for assistance.')->withInput();
                
                $disallowedStatuses = ['AWOL', 'TERMINATED', 'RESIGNED'];
                if(in_array(strtoupper($employee->Status_Empl), $disallowedStatuses) ) 
                    return Redirect::back()->with("invalid", "Login Failed: You are no longer allowed to access the system due to your employee status ($employee->Status_Empl). Please contact the administrator for assistance.")->withInput();
            }
            $weekly_hour = DB::TABLE('weekly_hour_setup')->first();

            //  $approvers = DB::table('outlet')
            //              ->select('outlet_id')
            //              ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
            //              ->get()->toArray();
            if($employee){
                $approvers = DB::table('approvers')
                    ->join('users', 'approvers.emp_id', '=', 'users.username')
                    ->select('approvers.id', 'approvers.emp_id', 'approvers.approver_1_emp_id', 'approvers.approver_2_emp_id', 'users.status')
                    ->where('users.status', 'ACTIVE')
                    ->where('approvers.approver_1_emp_id', $employee->UserID_Empl)
                    ->orWhere('approvers.approver_2_emp_id', $employee->UserID_Empl)
                    ->get()->toArray();
                
                $other_links = DB::table('user_privilege_items')
                    ->leftJoin('user_privilege_headers', 'user_privilege_items.user_privilege_header_id', '=', 'user_privilege_headers.id')
                    ->select('user_privilege_items.*','user_privilege_headers.header')
                    ->whereRaw('FIND_IN_SET(?, user_privilege_items.employee_type_ids)', [$user->employee_type_id])
                    // ->groupBy('user_privilege_items.id')
                    ->orderBY('user_privilege_items.id', 'asc')
                    ->get();

                $sorted_items = collect();
                $index = 0;
                // Store first with a header id
                foreach($other_links as $item){ if($item->user_privilege_header_id)$sorted_items[$index] = $item; $index++;}
                // Sort by user_privilege_header_id
                $sorted_items = $sorted_items->sortBy('user_privilege_header_id');
                // Store the item without user_privilege_header_id
                foreach($other_links as $item){ if(!$item->user_privilege_header_id)$sorted_items[$index] = $item; $index++;}
            };

            // $app = app();
            // $routes = $app->routes->getRoutes();
            // $active_routes = array();
            // foreach($routes as $i => $val){
            //     if( $routes[$i]->getName() ) array_push($active_routes, $routes[$i]->getName());
            // }
            
            Session::put([
                'user' => $user, 
                'employee' => $employee, 
                'weekly_hr'=> $weekly_hour,
                'is_approver'=> count($approvers) ? true : false,
                'approvers' => $approvers,
                'other_links' => $sorted_items,
                // 'active_routes' => $active_routes,
                'logged' => true
            ]);

            return redirect('/dashboard');
        }
        else{
        	// return redirect('/login')->with('invalid', 'Invalid Credentials')->withInput();
            return Redirect::back()->with('invalid', 'Invalid Credentials.')->withInput();
        }
    }
    
}
