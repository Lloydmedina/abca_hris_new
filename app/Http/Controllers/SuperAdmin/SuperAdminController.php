<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Redirect;
// use Illuminate\Support\Facades\Input;

class SuperAdminController extends Controller
{
    public function user_access_setup(){

        $employeeTypeId = Input::get('id');
        
        $admins = array(1,2); // Admin only
        if(!in_array(session('user')->employee_type_id, $admins)) return Redirect::to('/dashboard')->send();
        
        $employee_types = DB::table('employee_type')->whereNotIn('id', $admins)->get();

        $modules = collect();
        $module_ids_for_admins = array(1,2);

        if($employeeTypeId){
            
            $modules = DB::table('modules')
                    ->whereNotIn('id', $module_ids_for_admins)
                    ->orderBy('id', 'asc');
        
            // View modules for emp user only
            if($employeeTypeId == 5){
                $module_ids_for_emp_user = [46,47,48,49,50,51,52,53,54];
                $modules = $modules->whereIn('id', $module_ids_for_emp_user);
            }
            $modules = $modules->get();
        
        }
        // $employees = DB::table('employees')
        //     ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
        //     ->select('employees.*', 'employees_rate.Position_Empl')
        //     ->get();

        return view('SuperAdmin.user_access', [
            'employee_types' => $employee_types,
            'modules' => $modules,
        ]);

    }

    public function user_privilege(){

        // $employeeTypeId = Input::get('id');
        
        $admins = array(1,2); // Admin only
        if(!in_array(session('user')->employee_type_id, $admins)) return Redirect::to('/dashboard')->send();

        $employee_types = DB::table('employee_type')->whereNotIn('id', array(1,2,5))->get();
        // $user_privilege_headers = DB::table('user_privilege_headers')->orderBy('id', 'asc')->get();
        $user_privilege_items = DB::table('user_privilege_items')
                                        ->leftJoin('user_privilege_headers', 'user_privilege_items.user_privilege_header_id', '=', 'user_privilege_headers.id')
                                        ->select('user_privilege_items.*','user_privilege_headers.header')
                                        ->groupBy('user_privilege_items.id')
                                        ->orderBY('user_privilege_items.id', 'asc')
                                        ->get();
        $sorted_items = collect();
        $index = 0;
        // Store first with a header id
        foreach($user_privilege_items as $item){ if($item->user_privilege_header_id)$sorted_items[$index] = $item; $index++;}
        // Sort by user_privilege_header_id
        $sorted_items = $sorted_items->sortBy('user_privilege_header_id');
        // Store the item without user_privilege_header_id
        foreach($user_privilege_items as $item){ if(!$item->user_privilege_header_id)$sorted_items[$index] = $item; $index++;}
        
        return view('SuperAdmin.user_privilege', [
            'employee_types' => $employee_types,
            // 'user_privilege_headers' => $user_privilege_headers,
            'user_privilege_items' => $sorted_items,
        ]);

    }

    public function user_privilege_update(Request $r){

        $employee_type_id = $r->input('employee_type_id');
        $checked = $r->input('checked') ?? [];
        $to_remove_id = DB::table('user_privilege_items')
                            ->whereRaw('FIND_IN_SET(?, employee_type_ids)', [$employee_type_id])
                            ->get();

        // REMOVE ALL {{ employee_type_id }} FIRTS
        if($to_remove_id){
            foreach ($to_remove_id as $key => $value) {

                // String
                $employee_type_ids = $value->employee_type_ids;
                // Convert to array
                $employee_type_ids = explode(",",$employee_type_ids);
                // unset selected value
                if (($key = array_search($employee_type_id, $employee_type_ids)) !== false) {
                    unset($employee_type_ids[$key]);
                }
                // Make unique array
                $employee_type_ids = array_unique($employee_type_ids);
                // Convert to string again
                $employee_type_ids = implode(",",$employee_type_ids);
                DB::table('user_privilege_items')->where('id', $value->id)->update(['employee_type_ids' => $employee_type_ids]);
            }
        }

        // INSERT IDS
        if($checked){
            foreach($checked as $key => $value){
                $upi = DB::table('user_privilege_items')->where('id', $value)->first();
                // String
                $employee_type_ids = $upi->employee_type_ids ? $upi->employee_type_ids . ',' . $employee_type_id : $employee_type_id;
                // Convert to array
                $employee_type_ids = $upi->employee_type_ids ? explode(",",$employee_type_ids) : [$employee_type_id];
                // Make unique array
                $employee_type_ids = array_unique($employee_type_ids);
                // Convert to string again
                $employee_type_ids = implode(",",$employee_type_ids);
                DB::table('user_privilege_items')->where('id', $value)->update(['employee_type_ids' => $employee_type_ids]);
            }
        }

        return Redirect::back()->with('success_message', 'User privilege successfully updated.');
        
    }
}
