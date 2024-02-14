<?php

namespace App\Http\Controllers\DiciplineManagement;

use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DiciplineManagementController extends Controller
{
  
  public function view_code_of_conduct()
  {
    return view('DiciplineManagement.view_code_of_conduct');
  }

  public function incident_report()
  {
    $employees = DB::table('employees')->get();
    $department = DB::table('department')->get();
    $incident_report = DB::table('incident_report')->get();

    return view('DiciplineManagement.incident_report_notification', 
      [
        'employees' => $employees, 
        'department' => $department,
        'incident_report' => $incident_report
      ]
    );
  }

  public function save(Request $request)
  {
    $input_data = $request->all();
    
    unset($input_data["_token"]);

    // Get employee name
    $emp_id = $input_data["emp_id"];
    $employee = DB::table('employees')->where("SysPK_Empl", $emp_id)->first();
    $input_data["emp_name"] = $employee->Name_Empl;

    // Get Mngr name
    $emp_mngr_id = $input_data["emp_mngr_id"];
    $mgr = DB::table('employees')->where("SysPK_Empl", $emp_mngr_id)->first();
    $input_data["emp_mngr_name"] = $mgr->Name_Empl;

    $dept_id = $input_data["dept_id"];
    $department = DB::table('department')->where("SysPK_Dept", $dept_id)->first();
    $input_data["department"] = $department->Name_Dept;

    $count_ir = DB::table('incident_report')->orderBy('ir_id', 'desc')->whereYear('date_prepared', date('Y'))->first();

    $total_ir_seq = 1;

    if(count($count_ir) > 0){
      $total_ir_seq = $count_ir->ir_id + 1;// without leading zeros
    }

    $count_digit = strlen($total_ir_seq); // count how many digits
    $leading_zeroes = 4; // Total leading zeroes
    $zeroes_to_used = $leading_zeroes - $count_digit; // check zeroes to used base on how many digits in total
    $get_zeroes = '';
    // Determine how many zeroes to used base on the last digits
    for ($i=0; $i < $zeroes_to_used; $i++) { 
        $get_zeroes .= '0';
    }
    
    $input_data["ir_no"] = "IR".date('Y').$get_zeroes.$total_ir_seq; // with leading zeroes

    DB::table('incident_report')->insert($input_data);

    return redirect("/incident_report?")->with('success_message', 'Incident report successfully created!');

  }

  public function disciplinary_action_form()
  {
    $incident_report = DB::table('incident_report')->get();
    $disciplinary_action = DB::table('disciplinary_action')->get();

    return view('DiciplineManagement.disciplinary_action_form', ["incident_report" => $incident_report, 'disciplinary_action' => $disciplinary_action]);
  }

  public function disciplinary_action_save(Request $request)
  {
    $input_data = $request->all();
    
    unset($input_data["_token"]);

    DB::table('disciplinary_action')->insert($input_data);

    return redirect("/disciplinary-action-form?")->with('success_message', 'Disciplinary action form successfully created!');
  }

  public function delete_da()
  {
    $id = $this->__get_orig_id(Input::get('id'));

    DB::table('disciplinary_action')->where('id', $id)->delete();

    return redirect('/disciplinary-action-form?')->with('success_message','Disciplinary action form has been deleted');
  }

}
