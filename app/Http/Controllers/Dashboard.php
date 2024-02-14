<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Dashboard extends Controller
{

    public function dashboard()
    {
    	// redirect to shift
        if (!in_array(session('user')->employee_type_id, [1,2])) return redirect()->route('my_shift');
    	
        $default = DB::select(DB::raw('SELECT COUNT(*) AS "emp_count",(SELECT COUNT(*) FROM employees e WHERE e.gender="Male" AND (e.Status_Empl="REGULAR" OR e.Status_Empl="PROBATIONARY") ) AS "male",(SELECT COUNT(*) FROM employees ee WHERE ee.gender="Female" AND (ee.Status_Empl="REGULAR" OR ee.Status_Empl="PROBATIONARY") ) AS "female" FROM employees WHERE Status_Empl="REGULAR" OR Status_Empl="PROBATIONARY"'));
        $manpower = DB::select(DB::raw('CALL get_manpower()'));
        $civil_status =DB::select(DB::raw(' CALL get_civil_status()')); 

        $total_emp = $default[0]->emp_count;
        $male = $default[0]->male;
        $female = $default[0]->female;
        $male_percentage = ($male / $total_emp) * 100;
        $female_percentage = ($female / $total_emp) * 100;

        return view('Pages.dashboard',
        	[
        		'default'=>$default,
        		'manpower'=>$manpower,
                'civil_status'=>$civil_status,
        		'male_percentage'=>round($male_percentage,2),
        		'female_percentage'=>round($female_percentage,2)
        	]);
    }

    public function update_weekly_hour(Request $request){
        

        DB::Table('weekly_hour_setup')
        ->Where('sched_id','=',1)
        ->update(
            [
                'total_hour'=>$request->input('total_hour'),
                'six_days'=>$request->input('six_days'),
                'five_days'=>$request->input('five_days'),
                'four_days'=>$request->input('four_days'),
                'three_days'=>$request->input('three_days'),
            ]);

        return redirect('/')->with('success_message','Employee Weekly Hour Setup Saved');
    }

}
