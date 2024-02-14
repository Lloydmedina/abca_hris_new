<?php

namespace App\Http\Controllers\CompanySetup;

use App\Http\Controllers\Controller;
use Mail;
use Response;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Hr_emp_attendance as Dtr;
use DateInterval;
use DateTime;
use DatePeriod;

class CompanySetup extends Controller
{
    public function cost_center(){

      $list = DB::TABLE('cost_center')->get();

      return view('CompanySetup.cost_center',['list' => $list]);
    }

    public function add_cost_center(Request $request)
    {
      $cc_id = DB::TABLE('cost_center')->insertGetId(
        [
          'cost_center_code'=>$request->input('cost_center_code'),
          'cost_center'=>$request->input('cost_center'),
          'address'=>$request->input('address'),
          'balance'=>$request->input('balance'),
          'remarks'=>$request->input('remarks')
        ]);

      DB::TABLE('cost_center_details')->insert(
        [
          'cost_center_id'=>$cc_id,
          'trans_type'=>"DEPOSIT",
          'current_balance'=>$request->input('balance'),
          'amount'=>$request->input('balance'),
          'new_balance'=>$request->input('balance'),
          'date_save'=>date('Y-m-d'),
          'save_by_id'=>session('user')->id,
          'save_by'=>session('user')->first_name." ".session('user')->last_name
        ]);

      return redirect('/cost_center')->with('success_message','New Cost Center Added');
    }

    // Department Insert Select Delete Start 
    public function department()
    {
        $employees = DB::table('employees')->get();
        $department = DB::table('department')->get();
        $employees_rate = DB::table('employees_rate')->get();

        return view('CompanySetup.department', 
          [
            'employees' => $employees, 
            'employees_rate' => $employees_rate, 
            'department' => $department
          ]
        );
    }

    //add department
    public function add_department(Request $request)
    {
        
        $this->validate($request, [
            'DepartmentCode' => 'required',
            'Department' => 'required',
            'Location' => 'required',
            'Head_Dept' => 'required',
            'Position_Dept' => 'required',
            'AssistantHead_Dept' => 'required',
            'AssistPosition_Dept' => 'required'
      ]);

        //GET THE ORIGINAL ID FROM MD5
       $Head_Dept = $this->__get_orig_id($request->input('Head_Dept'));
       $Position_Dept = $this->__get_orig_id($request->input('Position_Dept'));
       $AssistantHead_Dept = $this->__get_orig_id($request->input('AssistantHead_Dept'));
       $AssistPosition_Dept = $this->__get_orig_id($request->input('AssistPosition_Dept'));

       $employee_1 = DB::table('employees')->select('UserID_Empl', 'Name_Empl')->where('UserID_Empl', $Head_Dept)->first();
       $employee_2 = DB::table('employees')->select('UserID_Empl', 'Name_Empl')->where('UserID_Empl', $AssistantHead_Dept)->first();

       $employee_rate_1 = DB::table('employees_rate')->select('id', 'Position_Empl')->where('id', $Position_Dept)->first();
       $employee_rate_2 = DB::table('employees_rate')->select('id', 'Position_Empl')->where('id', $AssistPosition_Dept)->first();

       $department = DB::table('department')->insertGetId(
            [
                'GroupName_Dept' => 'P0001',
                'DeptCode_Dept' => $request->input('DepartmentCode'),
                'Name_Dept' => $request->input('Department'),
                'Head_Dept' => $employee_1->Name_Empl,
                'Head_Dept_ID' => $employee_1->UserID_Empl,
                'Location_Dept' => $request->input('Location'),
                'Position_Dept' => $employee_rate_1->Position_Empl,
                'Position_Dept_ID' => $employee_rate_1->id,
                'Status_Dept' => 'ACTIVE',
                'Remarks_Dept' => $request->input('remarks'),
                'Message_Dept' => '',
                'AssistantHead_Dept' => $employee_2->Name_Empl,
                'AssistantHead_Dept_ID' => $employee_2->UserID_Empl,
                'AssistPosition_Dept' => $employee_rate_2->Position_Empl,
                'AssistPosition_Dept_ID' => $employee_rate_2->id,
                'WorkerType_Dept' => '',
                'ProjectStart' => date('Y-m-d')
            ]
        );

       return redirect('/department?added_new_dept='.md5(date('H:i:s')))->with('success_message','New department added');
    }

    public function update_department(Request $request)
    {
    
        $this->validate($request, [
              'DepartmentCode' => 'required',
              'Department' => 'required',
              'Location' => 'required',
              'Head_Dept' => 'required',
              'Position_Dept' => 'required',
              'AssistantHead_Dept' => 'required',
              'AssistPosition_Dept' => 'required',
              'SysPK_Dept' => 'required'
        ]);

        //GET THE ORIGINAL ID FROM MD5
       $Head_Dept = $this->__get_orig_id($request->input('Head_Dept'));
       $Position_Dept = $this->__get_orig_id($request->input('Position_Dept'));
       $AssistantHead_Dept = $this->__get_orig_id($request->input('AssistantHead_Dept'));
       $AssistPosition_Dept = $this->__get_orig_id($request->input('AssistPosition_Dept'));

       $employee_1 = DB::table('employees')->select('UserID_Empl', 'Name_Empl')->where('UserID_Empl', $Head_Dept)->first();
       $employee_2 = DB::table('employees')->select('UserID_Empl', 'Name_Empl')->where('UserID_Empl', $AssistantHead_Dept)->first();

       $employee_rate_1 = DB::table('employees_rate')->select('id', 'Position_Empl')->where('id', $Position_Dept)->first();
       $employee_rate_2 = DB::table('employees_rate')->select('id', 'Position_Empl')->where('id', $AssistPosition_Dept)->first();

      $id = $this->__get_orig_id($request->input('SysPK_Dept'));

      DB::table('department')
      ->where('SysPK_Dept', $id)
      ->update([
              'DeptCode_Dept' => $request->input('DepartmentCode'),
              'Name_Dept' => $request->input('Department'),
              'Head_Dept' => $employee_1->Name_Empl,
              'Head_Dept_ID' => $employee_1->UserID_Empl,
              'Location_Dept' => $request->input('Location'),
              'Position_Dept' => $employee_rate_1->Position_Empl,
              'Position_Dept_ID' => $employee_rate_1->id,
              'Remarks_Dept' => $request->input('remarks'),
              'AssistantHead_Dept' => $employee_2->Name_Empl,
              'AssistantHead_Dept_ID' => $employee_2->UserID_Empl,
              'AssistPosition_Dept' => $employee_rate_2->Position_Empl,
              'AssistPosition_Dept_ID' => $employee_rate_2->id
            ]);
        return redirect('/department?updated_dept='.md5(date('H:i:s')))->with('success_message','Department successfully updated!');
    }

// Department Insert Select Delete End 

    public function position()
    {
    
        $employees_rate = DB::table('employees_rate')->get();
        return view('CompanySetup.position', ['employees_rate' => $employees_rate]);
    }

    public function add_position(Request $request)
    {
        $this->validate($request, [
              'Position_Code' => 'required|unique:employees_rate,Position_Code',
              'Position_Empl' => 'required|unique:employees_rate,Position_Empl',
              'DoleRate_Empl' => 'required'
        ]);

        $daily_rate = $month_rate = 0;
        if($request->input('rate_option') == 1){
          $month_rate = ($request->input('BasicSalary_Empl') > 0) ? $request->input('BasicSalary_Empl') : 0;
        }else{
          $daily_rate = ($request->input('RatePerDay_Empl') > 0) ? $request->input('RatePerDay_Empl') : 0;
        }

        $employees_rate = DB::table('employees_rate')->insertGetId(
            [
                'Position_Code' => $request->input('Position_Code'),
                'Position_Empl' => $request->input('Position_Empl'),
                'RatePerDay_Empl' => $daily_rate,
                'BasicSalary_Empl' => $month_rate,
                'DoleRate_Empl' => $request->input('DoleRate_Empl'),
                'Group_Empl' => 'P0001',
                'Department_Empl' => '001',
                'Honorarium_Empl' => 0,
                'Location_Empl' => 'MAIN OFFICE',
                'isFixedCOLA' => 0
            ]
        );

        return redirect('/position?added_pos='.md5(date('H:i:s')))->with('success_message','Position successfully added!');
    }

    public function update_position(Request $request)
    {
        $this->validate($request, [
          'Position_Code' => 'required',
          'Position_Empl' => 'required',
          'DoleRate_Empl' => 'required'
        ]);
        
        $daily_rate = $month_rate = 0;
        if($request->input('rate_option') == 1){
          $month_rate = ($request->input('BasicSalary_Empl') > 0) ? $request->input('BasicSalary_Empl') : 0;
        }else{
          $daily_rate = ($request->input('RatePerDay_Empl') > 0) ? $request->input('RatePerDay_Empl') : 0;
        }
        
        $id = $this->__get_orig_id($request->input('id'));

        DB::table('employees_rate')->where('id', $id)
        ->update([
          'Position_Code' => $request->input('Position_Code'),
          'Position_Empl' => $request->input('Position_Empl'),
          'RatePerDay_Empl' => $daily_rate,
          'BasicSalary_Empl' => $month_rate,
          'DoleRate_Empl' => $request->input('DoleRate_Empl'),
        ]);

        return redirect('/position?updated_pos='.md5(date('H:i:s')))->with('success_message','Position successfully updated!');
    }

    public function employee_level()
    {
        $list = DB::TABLE('emp_level')->get();
        return view('CompanySetup.employee_level',['list'=>$list]);
    }

    public function add_employee_level(Request $request)
    {
        $employee_level = DB::table('emp_level')->insertGetId(
            [
                'emp_lvl' => $request->input('emp_lvl'),
                'transpo_allowance' => $request->input('transpo_allowance'),
                'mobile_allowance' => $request->input('mobile_allowance'),
                'out_station_allowance' => $request->input('out_station_allowance'),
                'meal_allowance' => $request->input('meal_allowance'),
            ]
        );

        return redirect('/employee_level')->with('success_message','Employee Level successfully added!');
    }

    public function company()
    {
        $list = DB::TABLE('company')->get();
        return view('CompanySetup.company',['list'=>$list]);
    }

    public function add_company(Request $request)
    {
        $company = DB::table('company')->insertGetId(
            [
                'company' => $request->input('company'),
                'remarks' => $request->input('remarks'),
            ]
        );

        return redirect('/company')->with('success_message','Company successfully added!');
    }

    public function company_group()
    {
        $list = DB::TABLE('company_group')->get();
        return view('CompanySetup.company_group',['list'=>$list]);
    }

    public function add_company_group(Request $request)
    {
        $company = DB::table('company_group')->insertGetId(
            [
                'company_group' => $request->input('company_group'),
                'remarks' => $request->input('remarks'),
            ]
        );

        return redirect('/company_group')->with('success_message','Company successfully added!');
    }

    public function outlet()
    {
        $outlets = DB::TABLE('outlet')->orderBy('outlet_id', 'desc')->get();
        $employees = DB::TABLE('employees')->orderBy('SysPK_Empl', 'desc')->get();
        $company_group = DB::TABLE('company_group')->get();

        return view('CompanySetup.outlet',[
          'outlets'=>$outlets,
          'company_group'=>$company_group,
          'employees'=>$employees
        ]);
    }

    public function add_outlet(Request $request){
        $this->validate($request, [
          'outlet' => 'required',
        ]);

        $outlet = DB::table('outlet')->insertGetId(
            [
                'outlet' => $request->input('outlet'),
                'remarks' => $request->input('remarks'),
            ]
        );

        return redirect('/outlet')->with('success_message','Outlet successfully added!');
    }

    public function update_outlet(Request $request){
      $this->validate($request, [
        'outlet' => 'required',
      ]);

      if(!$request->input('outlet_id')){
        return redirect('/outlet')->withErrors(['Something went wrong!']);
      }

      DB::table('outlet')
            ->where('outlet_id', $request->input('outlet_id'))
            ->update([
                'outlet' => $request->input('outlet'),
                'company_group_id' => $request->input('company_group_id'),
                'remarks' => $request->input('remarks')
              ]);
      return redirect('/outlet')->with('success_message','Outlet successfully updated!');
    }

    public function delete_outlet($outlet_id = null){
  
      if($outlet_id) DB::table('outlet')->where('outlet_id', $outlet_id)->delete();
      else die();
      return redirect('/outlet')->with('success_message','Outlet Deleted');
    }

    public function outlet_approver(Request $request){

      $outlet_id = $request->input('outlet_id');
      $employees = [];
      
      if($outlet_id){
        $outlet = DB::table('outlet')->where('outlet_id', $outlet_id)->first();
        $approver_emp_ids = $outlet->approver_emp_id ? explode(",",$outlet->approver_emp_id) : [];
        if($approver_emp_ids) $employees = DB::table('employees')->whereIn('SysPK_Empl', $approver_emp_ids)->get();
      }

      return view('CompanySetup.outlet_approver_table',[
        'employees'=>$employees
      ]);

    }

    public function add_outlet_approver(Request $request){

      $outlet_id = $request->input('outlet_id');
      $emp_id = $request->input('emp_id');
      $employees = [];
      $approver_emp_ids = null;

      if($outlet_id){
        $outlet = DB::table('outlet')->where('outlet_id', $outlet_id)->first();
        // String
        $approver_emp_ids = $outlet->approver_emp_id ? $outlet->approver_emp_id . ',' . $emp_id : $emp_id;
        // Convert to array
        $approver_emp_ids = $outlet->approver_emp_id ? explode(",",$approver_emp_ids) : [$emp_id];
        $approver_emp_ids2 = $approver_emp_ids;
        // Make unique array
        $approver_emp_ids = array_unique($approver_emp_ids);
        // Convert to string again
        $approver_emp_ids = implode(",",$approver_emp_ids);

        DB::table('outlet')->where('outlet_id', $outlet_id)->update(['approver_emp_id' => $approver_emp_ids]);

        $approver_emp_ids = $outlet->approver_emp_id ? explode(",",$outlet->approver_emp_id) : [];
        if($approver_emp_ids2) $employees = DB::table('employees')->whereIn('SysPK_Empl', $approver_emp_ids2)->get();
      }

      return view('CompanySetup.outlet_approver_table',[
        'employees'=>$employees
      ]);

    }

    public function remove_outlet_approver(Request $request){

      $outlet_id = $request->input('outlet_id');
      $emp_id = $request->input('emp_id');
      $employees = [];
      $approver_emp_ids = null;

      if($outlet_id){
        $outlet = DB::table('outlet')->where('outlet_id', $outlet_id)->first();
        // String
        $approver_emp_ids = $outlet->approver_emp_id;
        // Convert to array
        $approver_emp_ids = explode(",",$approver_emp_ids);
        // unset selected value
        if (($key = array_search($emp_id, $approver_emp_ids)) !== false) {
          unset($approver_emp_ids[$key]);
        }

        $approver_emp_ids2 = $approver_emp_ids;
        // Make unique array
        $approver_emp_ids = array_unique($approver_emp_ids);
        // Convert to string again
        $approver_emp_ids = implode(",",$approver_emp_ids);

        DB::table('outlet')->where('outlet_id', $outlet_id)->update(['approver_emp_id' => $approver_emp_ids]);

        $approver_emp_ids = $outlet->approver_emp_id ? explode(",",$outlet->approver_emp_id) : [];
        if($approver_emp_ids2) $employees = DB::table('employees')->whereIn('SysPK_Empl', $approver_emp_ids2)->get();
      }

      return view('CompanySetup.outlet_approver_table',[
        'employees'=>$employees
      ]);

    }
}
