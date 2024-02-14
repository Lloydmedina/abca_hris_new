<?php

namespace App\Http\Controllers\Absent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Input;

class AbsenteeismController extends Controller
{

    public function index(){

        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');
        
        $absenteeism = DB::table('absenteeism')
            ->join('employees', 'absenteeism.employee_id', '=', 'employees.SysPK_Empl')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('absenteeism.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl')
            ->where('absenteeism.is_deleted', 0);

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) {

            return view('Absenteeism.absenteeism', ['absenteeism' => $absenteeism])->withErrors(['Invalid date range']);

        }// invalid date inputed
        elseif(strtotime($date_from) > strtotime(date('Y-m-d')) || strtotime($date_to) > strtotime(date('Y-m-d'))){

            return view('Absenteeism.absenteeism', ['absenteeism' => $absenteeism])->withErrors(['Date must be less than or equals to future date']);
            
        }// return based on searched date
        elseif($date_from && $date_to){

            $date = array($date_from, $date_to);

            $absenteeism = $absenteeism->whereBetween('dtr_date', $date);
        }

        $absenteeism = $absenteeism->get();

        return view('Absenteeism.absenteeism', ['absenteeism' => $absenteeism]);
    }
    
    public function add_absent_view(){

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.*', 'employees_rate.Position_Empl')
            ->get();

        return view('Absenteeism.add_absenteeism', ['employees' => $employees]);

    }

    public function get(Request $request){

        $this->validate($request, [
            'checked' => 'required|array',
        ]);

        $shift_code = DB::table('shift_code')->where('id', $request->input('shift_code'))->first();

        $employees = DB::table('employees')
            ->selectRaw('employees.SysPK_Empl, employees.Name_Empl, employees.Department_Empl,employees.UserID_Empl,"'. $shift_code->shift_code . '" AS ShiftCode,"'.$shift_code->id.'" AS ShiftId')
            ->whereIn('SysPK_Empl', $request->input('checked'))
            ->get();

        return response()->json($employees);
    }

    public function save(Request $request){
        
        $this->validate($request, [
            'employee_id' => 'required|array',
            'employee_number' => 'required|array',
            'remarks' => 'required|array',
            'date_selected' => 'required',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);
        
        // Check date if valid
        if(strtotime($request->input('date_selected')) > strtotime(date('Y-m-d'))){
            return Redirect::back()->withErrors(['Selected date must be less than or equals to current date!'])->withInput();
        }

        // STORAGE FOR EMPLOYEES OVERTIME
        $emp_ab = array();
        // Store employees overtime
        for ($i=0; $i < count($request->input('employee_id')); $i++) { 
            $emp_ab[] = array(
                    "employee_id" => $request->input('employee_id')[$i], 
                    "employee_number" => $request->input('employee_number')[$i],
                    "remarks" => $request->input('remarks')[$i],
                    "dtr_date" => date('Y-m-d', strtotime($request->input('date_selected'))),
                    "Time_Start" => date('H:i:s', strtotime($request->input('time_from'))),
                    "Time_End" => date('H:i:s', strtotime($request->input('time_to'))),
                    "AB_Hours" => $request->input('total_hours')
            );
        }
        
        DB::table('absenteeism')->insert($emp_ab);

        return redirect('/absenteeism?')->with('success_message', 'Employees absenteeism successfully created!');

    }

    public function delete(Request $request){

        $id = $this->__get_orig_id($request->input('id'));

        DB::table('absenteeism')->where('AB_ID', $id)->update(['is_deleted' => 1]);
        
        return response()->json(array("code" => 1, "message" => "Absenteeism successfully deleted"));

    }

}
