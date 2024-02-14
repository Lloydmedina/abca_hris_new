<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use DateInterval;
use DatePeriod;
use DateTime;
use Mail;
use Response;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;

class LeavesController extends Controller
{

  public function index(Request $r)
  {

    // $outletApprover = array();

    //     if(session('employee')){
    //         $outletApprover = DB::table('outlet')
    //                     ->select('outlet_id')
    //                     ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
    //                     ->get()->toArray();
    //     }

    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');
    $status = $r->input('status') ?? null;

    $leaves = DB::table('leaves')
      ->join('employees', 'leaves.emp_id', '=', 'employees.SysPK_Empl')
      ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
      ->join('hr_emp_leave_type', 'leaves.leave_type_id', '=', 'hr_emp_leave_type.leave_number')
      ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
      ->select(
        DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
        'leaves.*',
        // 'employees.Name_Empl',
        'employees.picture_path',
        'employees.gender',
        'employees.SysPK_Empl',
        'employees_rate.Position_Empl',
        'hr_emp_leave_type.leave_type',
        'hr_emp_leave_type.withPay',
        'approvers.approver_1_emp_id',
        'approvers.approver_2_emp_id',
      );

    if (is_numeric($status) && $status <= 3)
      $leaves = $leaves->where('leaves.is_approved', $status);
    elseif (is_numeric($status) && $status == 4)
      $leaves = $leaves->where('leaves.is_deleted', 1);

    // ->where('leaves.is_deleted', 0);

    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
    // }// return based on searched date
    if ($date_from && $date_to) {
      $date = array($date_from, $date_to);
      $leaves = $leaves->whereBetween('leave_date_from', $date);
    }

    $leaves = $leaves->orderBy('leaves.leave_app_id', 'desc')->get();
    
    $leaves_temp = collect();
    if(count($leaves) > 0) {
      foreach($leaves as $i => $row){
        $emp = DB::table('employees')
              ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
              ->where('UserID_Empl', $row->approver_1_emp_id)
              ->orWhere('UserID_Empl', $row->approver_2_emp_id)
              ->get();
        $leaves_temp[$i]= $row;
        if($emp) $leaves_temp[$i]->approvers = $emp->toArray();
      }
    }
    $leaves = $leaves_temp;
    
    return view('Leaves.leave', [
      'leaves' => $leaves,
      'date_from' => $date_from,
      'date_to' => $date_to
    ]);
  }

  public function my_leave(Request $r){

    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');
    $ps_status = $r->input('status') ?? '4'; // default 4 - all status
    $leaves = array();

    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

    $leaves = DB::table('leaves')
      ->join('employees', 'leaves.emp_id', '=', 'employees.SysPK_Empl')
      ->join('hr_emp_leave_type', 'leaves.leave_type_id', '=', 'hr_emp_leave_type.leave_number')
      ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
      ->select('leaves.*', 'employees.picture_path', 'employees.gender', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'hr_emp_leave_type.leave_type', 'hr_emp_leave_type.withPay')
      ->where('leaves.is_deleted', 0)
      ->where('emp_id', session('user')->emp_id);

    if($ps_status !== '4') $leaves = $leaves->where('leaves.is_approved', $ps_status);
    // return based on searched date
    if ($date_from && $date_to) {
      $date = array($date_from, $date_to);
      $leaves = $leaves->whereBetween('leave_date_from', $date);
    }
    $leaves = $leaves->orderBy('leaves.leave_app_id', 'desc')->get();

    $date = array($date_from, $date_to);
    $systemLockstatus = DB::table('system_lock_status')->select('lock_date')->where('is_lock', 1)->whereBetween('lock_date', $date)->get();
    
    return view('NormalEmployees.my_leaves', [
      'leaves' => $leaves,
      'date_from' => $date_from,
      'date_to' => $date_to,
      'systemLockstatus' => $systemLockstatus
    ]);
  }

  public function leave_request(Request $r){

    if(!session('is_approver')){
      if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
    } 

    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');
    $ps_status = $r->input('status') ?? '4'; // default 4 - all status
    $display_by = $r->input('display_by') ?? '10'; // default 10

    $leaves = array();
    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

    $leaves = DB::table('leaves')
      ->join('employees', 'leaves.emp_id', '=', 'employees.SysPK_Empl')
      ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
      ->join('hr_emp_leave_type', 'leaves.leave_type_id', '=', 'hr_emp_leave_type.leave_number')
      ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
      ->select(
        DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
        'leaves.*',
        'employees.picture_path',
        'employees.gender',
        // 'employees.Name_Empl',
        'employees.SysPK_Empl',
        'employees_rate.Position_Empl',
        'hr_emp_leave_type.leave_type',
        'hr_emp_leave_type.withPay',
        'approvers.approver_1_emp_id',
        'approvers.approver_2_emp_id',
      )
      ->where('leaves.is_deleted', 0);
      // ->groupBy(['leaves.leave_app_id']);

    if($ps_status !== '4') $leaves = $leaves->where('is_approved', $ps_status);

    // return based on searched date
    if ($date_from && $date_to) {
      $date = array($date_from, $date_to);
      $leaves = $leaves->whereBetween('leave_date_from', $date);
    }

    // CHECK IF THE USER IS APPROVER OF THIS EMPLOYEE
    if (session('is_approver')) {
      $emp_id_numbers = array();
      // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
      $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

      foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
      $leaves = $leaves->whereIn('employee_number', $emp_id_numbers);
    }

    // $leaves = $leaves->orderBy('leaves.leave_app_id', 'desc')->get();

    if($display_by == 'all'){
      $leaves = $leaves->orderBy('leaves.leave_app_id', 'desc')->get();
      $paginationLinks = null;
    }
    else{
        $leaves = $leaves->orderBy('leaves.leave_app_id', 'desc')->paginate($display_by);
        $paginationLinks = $leaves->appends(request()->query())->links();
    }

    $leaves_temp = collect();
    if(count($leaves) > 0) {
        foreach($leaves as $i => $row){
            $emp = DB::table('employees')
                    ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
                    ->where('UserID_Empl', $row->approver_1_emp_id)
                    ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                    ->get();
            $leaves_temp[$i]= $row;
            if($emp) $leaves_temp[$i]->approvers = $emp->toArray();
        }
    }
    $leaves = $leaves_temp;

    $date = array($date_from, $date_to);
    $systemLockstatus = DB::table('system_lock_status')->select('lock_date')->where('is_lock', 1)->whereBetween('lock_date', $date)->get();
    
    return view('Leaves.leave_request', [
        'leaves' => $leaves,
        'date_from' => $date_from,
        'date_to' => $date_to,
        "paginationLinks" => $paginationLinks,
        "systemLockstatus" => $systemLockstatus
      ]);
  }

  public function delete(Request $request){

    $id = $this->__get_orig_id($request->input('id'));

    // check application
    // json
    // if($checkApplication = $this->checkApplication(null, 'LV', 'R', $id))
    //     return response()->json(array("code" => 0, "message" => $checkApplication));

    // Check the leave first
    $leave = DB::table('leaves')->where('leave_app_id', $id)->first();
    
    if ($leave->is_approved != 1 || (session('is_approver') || in_array(session('user')->employee_type_id, [1,2]))) {
      // Do delete
      DB::table('leaves')->where('leave_app_id', $id)->update(['is_deleted' => 1, 'deleted_by' => Session('user')->id]);

      // if admin or approver & leave is approved
      if( session('is_approver') || in_array(session('user')->employee_type_id, [1,2]) && $leave->is_approved == 1){

        $emp_id = $leave->emp_id;
        $yr = date("Y", strtotime($leave->leave_date_from));

        $leave_monitoring = DB::table('leave_monitoring')
          ->where('yr', $yr)
          ->where('emp_pk', $emp_id)
          ->first();

        if (!empty($leave_monitoring)) {
          $leave_credit = $leave->total_hours;
          $vl = 0;
          $sl = 0;
          $ml = 0;
          $pl = 0;
          
          if ($leave->leave_type_id == 1) {
            $vl = $leave->total_hours;
          } elseif ($leave->leave_type_id == 2) {
            $sl = $leave->total_hours;
          }

          DB::TABLE('leave_monitoring')
            ->WHERE('pk', '=', $leave_monitoring->pk)
            ->UPDATE([
              // "first_jan" => 0,
              "second_jan" => $leave_monitoring->first_jan - $leave_credit,
              "used_vication_leave" => $leave_monitoring->used_vication_leave - $vl,
              "used_sick_leave" => $leave_monitoring->used_sick_leave - $sl,
              // "used_paternity_leave" => 0,
              // "used_maternity_leave" => 0,
              "bal_vication_leave" => $leave_monitoring->used_sick_leave + $vl,
              "bal_sick_leave" => $leave_monitoring->used_sick_leave + $sl,
              // "bal_paternity_leave" => 0,
              // "bal_maternity_leave" => 0,
              "total_leave_used" => $leave_monitoring->total_leave_used + $leave_credit
            ]);
        }

      }

      return response()->json(array("code" => 1, "message" => "Leave has been successfully deleted"));
    }
    return response()->json(array("code" => 0, "message" => "Leave already approved"));
  }

  public function leave_monitoring(Request $request){

    $list = array();
    $yr = 0;
    if ($request->input('yr')) {
      $yr = date('Y', strtotime($request->input('yr')));
      $list = DB::TABLE('leave_monitoring')
        ->where('yr', $yr)
        ->get();
      if (count($list) == 0) {
        $emp = DB::TABLE('employees')->where('Status_Empl', '!=', 'RESIGNED')->ORDERBY('LastName_Empl')->GET();
        $leave_monitoring = array();
        foreach ($emp as $row) {
          $leave_monitoring[] = [
            "yr" => $yr,
            "emp_pk" => $row->SysPK_Empl,
            "emp_code" => $row->UserID_Empl,
            "emp_name" => $row->LastName_Empl . ', ' . $row->FirstName_Empl . ' ' . $row->MiddleName_Empl,
            "vication_leave" => $row->vacation_leave_credit * 8,
            "sick_leave" => $row->sick_leave_credit,
            "paternity_leave" => $row->paternity_leave_credit,
            "maternity_leave" => $row->maternity_leave_credit,
            "first_jan" => 0,
            "second_jan" => 0,
            "first_feb" => 0,
            "second_feb" => 0,
            "first_mar" => 0,
            "second_mar" => 0,
            "first_apr" => 0,
            "second_apr" => 0,
            "first_may" => 0,
            "second_may" => 0,
            "first_jun" => 0,
            "second_jun" => 0,
            "first_jul" => 0,
            "second_jul" => 0,
            "first_aug" => 0,
            "second_aug" => 0,
            "first_sep" => 0,
            "second_sep" => 0,
            "first_oct" => 0,
            "second_oct" => 0,
            "first_nov" => 0,
            "second_nov" => 0,
            "first_dec" => 0,
            "second_dec" => 0,
            "used_vication_leave" => 0,
            "used_sick_leave" => 0,
            "used_paternity_leave" => 0,
            "used_maternity_leave" => 0,
            "bal_vication_leave" => $row->vacation_leave_credit,
            "bal_sick_leave" => $row->sick_leave_credit,
            "bal_paternity_leave" => $row->paternity_leave_credit,
            "bal_maternity_leave" => $row->maternity_leave_credit,
            "total_leave_used" => $row->vacation_leave_credit + $row->sick_leave_credit + $row->paternity_leave_credit + $row->maternity_leave_credit,
          ];
        }

        DB::TABLE('leave_monitoring')->insert($leave_monitoring);
        $list = DB::TABLE('leave_monitoring')
          ->where('yr', $yr)
          ->get();
      }
    }

    return view('Leaves.leave_monitoring', ['list' => $list]);
  }

  public function leave_type(){

    $list = DB::TABLE('hr_emp_leave_type')->get();
    return view('Leaves.leave_type', ['list' => $list]);
  }

  public function add_leave_type(Request $request){

    DB::TABLE('hr_emp_leave_type')->insert(
      [
        'leave_code' => $request->input('leave_code'),
        'leave_type' => $request->input('leave_type'),
        'withPay' => $request->input('withPay'),
        'remarks' => $request->input('remarks')
      ]
    );

    return redicrect('/leave_type') -> with('success_message', 'New Leave Type Added');
  }

  public function leave_entry(){
    
    $genderType = session('employee') ? session('employee')->gender : null;

    if($genderType == null)
      $leave_type = DB::table('hr_emp_leave_type')->get();
    elseif(strtoupper($genderType) == "MALE")
      $leave_type = DB::table('hr_emp_leave_type')->where('leave_number', '!=', 3)->get();
    else
      $leave_type = DB::table('hr_emp_leave_type')->where('leave_number', '!=', 6)->get();

    $employees = DB::table('employees')
      ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
      ->select('employees.*', 'employees_rate.Position_Empl')
      ->where('employees.UserID_Empl', '!=', '')
      ->where('employees.UserID_Empl', '!=', null)
      ->get();

    $checkBdayLeaveMessage = '';
    $checkBdayLeave = null;

    if(session('employee')){

      $birthMonth = date('m', strtotime(session('employee')->BirthDate_Empl));
      $currentMonth = date('m');
      $timestamp = strtotime ("-1 month",strtotime (session('employee')->BirthDate_Empl));
      $prevMonth  =  date("m",$timestamp);

     

      if($birthMonth != $currentMonth and $currentMonth != $prevMonth){
        $checkBdayLeave = true;
        $checkBdayLeaveMessage = 'Birthday Leave can only be filed on your Birth month.';
      }
      else{

        $checkBdayLeave = DB::table('hr_emp_attendance')
          ->whereMonth('dtr_date', '=', date('m'))
          ->whereYear('dtr_date', '=', date('Y'))
          ->where('employee_number', '=', session('user')->username)
          ->where('bday_duty', '=', '1')
          ->first();
      
        if($checkBdayLeave){
          $checkBdayLeaveMessage = 'You have birthday duty scheduled for the current year.';
        }
        else{
          $checkBdayLeave = DB::table('leaves')
            ->where('employee_number', session('user')->username)
            ->where('leave_type_id', '=', '5')
            ->whereMonth('leave_date_from', '=', date('m'))
            ->whereYear('leave_date_from', '=', date('Y'))
            ->where('is_deleted', '=', '0')
            ->first();

            if($checkBdayLeave){
              $checkBdayLeaveMessage = 'You have an existing birthday leave application.';
            }
        }

      }
    }

    $isEmployeeDateHireEqualOrMorethan180Days = DB::table('employees')
      ->where('UserID_Empl', '=', session('user')->username)
      ->whereDate('DateHired_Empl', '<=', now()->subDays(180))->first();
    
    return view('Leaves.leave_entry', [
      'leave_type' => $leave_type, 
      'employees' => $employees,
      'checkBdayLeave' => $checkBdayLeave,
      'checkBdayLeaveMessage' => $checkBdayLeaveMessage,
      'isEmployeeDateHireEqualOrMorethan180Days' => $isEmployeeDateHireEqualOrMorethan180Days,
    ]);
  }

  public function calculateFiledLeaveDays($startDate, $endDate) {
    $oneDay = 24 * 60 * 60; // Number of seconds in a day
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);

    $timeDifference = abs($endTimestamp - $startTimestamp);
    $totalDays = floor($timeDifference / $oneDay) + 1; // Including both start and end days

    return $totalDays;
}

  public function save(Request $request){

    // STORAGE FOR EMPLOYEES OVERTIME
    $emp_leave = array();

    // for half day
    if ($request->input('is_half_day') == 1) {

      $this->validate($request, [
        'employee_id' => 'required|array',
        'leave_type_id' => 'required',
        'employee_number' => 'required|array',
        'remarks' => 'required|array',
        'date_selected' => 'required',
        'time_from' => 'required',
        'time_to' => 'required',
      ]);

      // check application
      $date_selected['from'] = $request->input('date_selected');
      $date_selected['to'] = $request->input('date_selected');
      if($return = $this->checkApplication($date_selected, 'LV', 'I', null))
        return Redirect::back()->withErrors([$return])->withInput();

      // Check date if valid
      // if (strtotime($request->input('date_selected')) < strtotime(date('Y-m-d'))) {
      //   return Redirect::back()->withErrors(['Selected date must be less than or equals to current date!'])->withInput();
      // }

      // if (strtotime($request->input('time_from')) > strtotime($request->input('time_to'))) {
      //   return Redirect::back()->withErrors(['Selected Time From must be less than or equals to Time To!'])->withInput();
      // }


      // Store employees leave
      for ($i = 0; $i < count($request->input('employee_id')); $i++) {
        $emp_leave[] = array(
          "emp_id" => $request->input('employee_id')[$i],
          "employee_number" => $request->input('employee_number')[$i],
          "remarks" => $request->input('remarks')[$i],
          "leave_date_from" => date('Y-m-d', strtotime($request->input('date_selected'))),
          "leave_date_to" => date('Y-m-d', strtotime($request->input('date_selected'))),
          "time_from" => date('H:i:s', strtotime($request->input('time_from'))),
          "time_to" => date('H:i:s', strtotime($request->input('time_to'))),
          "total_hours" => $request->input('total_hours'),
          "is_half_day" => 1,
          "submitted_by" => Session('user')->id,
          "leave_type_id" => $request->input('leave_type_id'),
          "created_at" => date('Y-m-d H:i:s'),
          "no_of_days" => null
        );
      }

    } 
    else 
    {

      if($request->input('leave_type_id') == 5){

        $this->validate($request, [
          'employee_id' => 'required|array',
          'leave_type_id' => 'required',
          'employee_number' => 'required|array',
          'remarks' => 'required|array',
          'date_selected_from' => 'required',
        ]);

      }
      else{

        $this->validate($request, [
          'employee_id' => 'required|array',
          'leave_type_id' => 'required',
          'employee_number' => 'required|array',
          'remarks' => 'required|array',
          'date_selected_from' => 'required',
          'date_selected_to' => 'required'
        ]);

      }

      $date_from = $request->input('date_selected_from');
      $date_to = $request->input('date_selected_to');
      $leave_type = $request->input('leave_type_id');

      if($leave_type == 5){
        $birthMonth = date('m', strtotime(session('employee')->BirthDate_Empl));
        $leaveMonth = date('m', strtotime($date_from));

        // check if same month
        if($leaveMonth != $birthMonth){
          return redirect()->back()->withErrors(['Your birthday leave application does not match your birth month.'])->withInput();
        }
        
        // set to same date for bday leave
        $date_to = $date_from;
      }

     $employee = DB::table('employees')->where('SysPK_Empl', $request->input('employee_id'))->first();

     $leaveCredit = 0;
     $errorMessage = "";


    if ($leave_type == 1) 
    {
        $leaveCredit = $employee->vacation_leave_credit;
        $errorMessage = 'Your Vacation Leave With Pay Credit is not enough.';
    } 
    elseif ($leave_type == 2) 
    {
        $leaveCredit = $employee->sick_leave_credit;
        $errorMessage = 'Your Sick Leave With Pay Credit is not enough.';
    }

    if ($leaveCredit <= 0 && ($leave_type == 1 || $leave_type == 2)) {
        // Redirect back with an error message if leave credit is not enough
        return redirect()->back()->withErrors([$errorMessage])->withInput();
    }




      // check application
      $date_selected['from'] = $date_from;
      $date_selected['to'] = $date_to;
      if($return = $this->checkApplication($date_selected, 'LV', 'I', null))
        return Redirect::back()->withErrors([$return])->withInput();

      // // Check date if valid
      // if ($request->input('leave_type_id') != 2) {
      //   if (strtotime($request->input('date_selected_from')) < strtotime(date('Y-m-d'))) {
      //     return Redirect::back()->withErrors(['The selected Date from: must be less than or equal to current date.'])->withInput();
      //   }
      // }

      // // Check date if valid
      // if (strtotime($request->input('date_selected_from')) > strtotime($request->input('date_selected_to'))) {
      //   return Redirect::back()->withErrors(['The selected Date from: must be less than or equal to Date to:.'])->withInput();
      // }

      // Compute the total no. of days between date from and date to
      
      // $no_of_days = $request->input('no_of_days');

      

      // $no_of_days = $this->calculateFiledLeaveDays($date_from, $date_to);
      $startDate = new DateTime($date_from);
      $endDate = new DateTime($date_to);

      // Store employees leave
      for ($i = 0; $i < count($request->input('employee_id')); $i++) {
        if($startDate == $endDate){
          $emp_leave[] = array(
            "emp_id" => $request->input('employee_id')[$i],
            "employee_number" => $request->input('employee_number')[$i],
            "remarks" => $request->input('remarks')[$i],
            "leave_date_from" => date('Y-m-d', strtotime($date_from)),
            "leave_date_to" => date('Y-m-d', strtotime($date_to)),
            "no_of_days" => 1,
            "is_half_day" => 0,
            "submitted_by" => Session('user')->id,
            "leave_type_id" => $leave_type,
            "time_from" => null,
            "time_to" => null,
            "total_hours" => 0,
            "created_at" => date('Y-m-d H:i:s'),
          );
        }else{
          $dateInterval = new DateInterval('P1D');
          $dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day'));
          foreach ($dateRange as $date) {
            $emp_leave[] = array(
              "emp_id" => $request->input('employee_id')[$i],
              "employee_number" => $request->input('employee_number')[$i],
              "remarks" => $request->input('remarks')[$i],
              "leave_date_from" => date('Y-m-d', strtotime($date->format('Y-m-d'))),
              "leave_date_to" => date('Y-m-d', strtotime($date->format('Y-m-d'))),
              "no_of_days" => 1,
              "is_half_day" => 0,
              "time_from" => null,
              "time_to" => null,
              "total_hours" => 0,
              "submitted_by" => Session('user')->id,
              "leave_type_id" => $leave_type,
              "created_at" => date('Y-m-d H:i:s'),
            );
          }
        }
      }
    }
    
    $leaveDB = false;
    $errorMsg = 'Something went wrong!';
    foreach ($emp_leave as $key => $value) {

      $exists = DB::table('leaves') // Check if leave record exists in the database
        ->where('emp_id', $value['emp_id'])
        ->where('leave_date_from', $value['leave_date_from'])
        ->where('leave_type_id', $value['leave_type_id'])
        ->where('is_deleted', 0)
        ->first();

      if($exists){ // If record exists
        if($exists->is_approved == 1 || $exists->is_approved == 3){ // Check if the leave is approved or partially approved
          // If approved or partially approved, skip processing for this entry
          $errorMsg = 'You have a leave application file that already exists and has been either approved or partially approved.';
          continue;
        }
        // If not approved, soft-delete the existing leave record
        // Update 'is_deleted' to 1 for the existing leave record
        DB::table('leaves')->where('leave_app_id', $exists->leave_app_id)->update(['is_deleted' => 1]);
      } 
        
      // Insert a new leave record into the database
      DB::table('leaves')->insert([
        'emp_id' => $value['emp_id'],
        'employee_number' => $value['employee_number'],
        'remarks' => $value['remarks'],
        'leave_date_from' => $value['leave_date_from'],
        'leave_date_to' => $value['leave_date_to'],
        'no_of_days' => $value['no_of_days'],
        'submitted_by' => $value['submitted_by'],
        'leave_type_id' => $value['leave_type_id'],
        'created_at' => $value['created_at'],
        "is_half_day" => $value['is_half_day'],
        "time_from" => $value['time_from'],
        "time_to" => $value['time_to'],
        "total_hours" => $value['total_hours'],
      ]);

      $leaveDB = true;
      
    }

    if ($leaveDB)
      return redirect('/my-leave')->with('success_message', 'Leave was successfully submitted.');
    else
      return redirect()->back()->withErrors([$errorMsg]);
  }

  public function file_leave_to_update(Request $r){

    $id = $this->__get_orig_id($r->input('id'));
    $emp_id = session('employee')->SysPK_Empl ?? null;

    $leave = DB::table('leaves')->where('leave_app_id', $id)->where('emp_id', $emp_id)->where('is_deleted', 0)->first();
    if(!$leave) return redirect('/file-leave');

    $leave_type = DB::table('hr_emp_leave_type')->get();

    // check application
    $checkApplication = $this->checkApplication(null, 'LV', 'U', $id);
    
    return view('Leaves.file_leave_to_update', [
      'leave_type' => $leave_type,
      'leave' => $leave,
      'checkApplication' => $checkApplication
    ]);
  }

  public function file_leave_to_update_approver(Request $r){

    $emp_id = $r->input('emp_id') ?? null;
    $id = $this->__get_orig_id($r->input('id'));

    // CHECK IF THE USER IS APPROVER OF THIS EMPLOYEE
    if(session('is_approver') && $emp_id){
        $emp_id_numbers = array();
        // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
        $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

        foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
        $employee = DB::table('employees')->where('SysPK_Empl',$emp_id)->first();
        if($employee){
            if(!in_array($employee->UserID_Empl, $emp_id_numbers)) return redirect('/leave-request')->withErrors(['Something went wrong!']);
        }
        else return redirect('/leave-request')->withErrors(['Something went wrong! Employee not found.']);
    }
    else return redirect('/leave-request')->withErrors(['Something went wrong!']);

    $leave = DB::table('leaves')->where('leave_app_id', $id)->where('emp_id', $emp_id)->where('is_deleted', 0)->first();
    if(!$leave) return redirect('/leave-request');

    $leave_type = DB::table('hr_emp_leave_type')->get();

    // check application
    // $checkApplication = $this->checkApplication(null, 'LV', 'U', $id);

    return view('Leaves.file_leave_to_update', [
      'leave_type' => $leave_type,
      'leave' => $leave,
      'employee' => $employee,
      'approver_update' => 1,
      // 'checkApplication' => $checkApplication
      'checkApplication' => null
    ]);

  }

  public function file_leave_update(Request $request){

    $id = $request->input('id') ?? null;
    if(!$id) return Redirect::back()->withErrors(["Something went wrong!"])->withInput();
  
    $approver_update = $request->input('approver_update') ?? null;

    // Run validation
    if(!$approver_update){
      // check application
      $date_selected['from'] = $request->input('date_selected');
      $date_selected['to'] = $request->input('date_selected');
      if($return = $this->checkApplication($date_selected, 'LV', 'U', $id))
        return Redirect::back()->withErrors([$return])->withInput();
    }

    // STORAGE FOR EMPLOYEES OVERTIME
    $emp_leave = array();

    // Defaut for emplyee file leave
    $emp_leave_def = array (
                      "rejected_by" => null,
                      "approved_by" => "",
                      "is_approved" => 0,
                      "app1_approved_on" => null,
                      "app2_approved_on" => null,
                    );

    // for half day
    if ($request->input('is_half_day') == 1) {

      $this->validate($request, [
        'leave_type_id' => 'required',
        'remarks' => 'required',
        'date_selected' => 'required',
        'time_from' => 'required',
        'time_to' => 'required',
      ]);

      // Run validation
      if(!$approver_update){
        // if (strtotime($request->input('time_from')) > strtotime($request->input('time_to'))) {
        //   return Redirect::back()->withErrors(['Selected Time From must be less than or equals to Time To!'])->withInput();
        // }
      }

      // // check application
      // $date_selected['from'] = $request->input('date_selected');
      // $date_selected['to'] = $request->input('date_selected');
      // if($return = $this->checkApplication($date_selected, 'LV', 'U', $id))
      //   return Redirect::back()->withErrors([$return])->withInput();
      
      // Store employees leave
      $emp_leave = array(
        "remarks" => $request->input('remarks'),
        "leave_date_from" => date('Y-m-d', strtotime($request->input('date_selected'))),
        "leave_date_to" => date('Y-m-d', strtotime($request->input('date_selected'))),
        "time_from" => date('H:i:s', strtotime($request->input('time_from'))),
        "time_to" => date('H:i:s', strtotime($request->input('time_to'))),
        "total_hours" => $request->input('total_hours'),
        "is_half_day" => 1,
        "leave_type_id" => $request->input('leave_type_id'),
        "updated_at" => date('Y-m-d H:i:s'),
        "no_of_days" => null
      );
      
    } 
    else {

      $this->validate($request, [
        'leave_type_id' => 'required',
        'remarks' => 'required',
        'date_selected_from' => 'required',
        'date_selected_to' => 'required'
      ]);


      if(!$approver_update){
        // Check date if valid
        // if (strtotime($request->input('date_selected_from')) > strtotime($request->input('date_selected_to'))) {
        //   return Redirect::back()->withErrors(['The selected Date from: must be less than or equal to Date to:.'])->withInput();
        // }
      }

      // Compute the total no. of days between date from and date to
      $date_from = $request->input('date_selected_from');
      $date_to = $request->input('date_selected_to');
      // $no_of_days = $request->input('no_of_days');

      // check application
      $date_selected['from'] = $request->input('date_selected_from');
      $date_selected['to'] = $request->input('date_selected_to');
      if($return = $this->checkApplication($date_selected, 'LV', 'U', $id))
        return Redirect::back()->withErrors([$return])->withInput();

      $no_of_days = $this->calculateFiledLeaveDays($date_from, $date_to);

      // Store employees leave
      $emp_leave = array(
        "remarks" => $request->input('remarks'),
        "leave_date_from" => date('Y-m-d', strtotime($date_from)),
        "leave_date_to" => date('Y-m-d', strtotime($date_to)),
        "no_of_days" => $no_of_days,
        "total_hours" => null,
        "leave_type_id" => $request->input('leave_type_id'),
        "updated_at" => date('Y-m-d H:i:s'),
        "is_half_day" => 0,
        "time_from" => '00:00:00',
        "time_to" => '00:00:00'
      );

    }

    if(!$approver_update){
      $emp_leave = array_merge($emp_leave, $emp_leave_def);
    }

    $route = 'my_leave';

    if($approver_update == 1){
      $route = 'leave_request';
    }

    DB::table('leaves')->where('leave_app_id', $id)->update($emp_leave);

    return redirect()->back()->with('success_message', 'Leave was successfully updated.');
  }

  // 0 = pending
  // 1 = approved
  // 2 = rejected
  // 3 = partially approved
  public function approve(Request $request){

    $id = $this->__get_orig_id($request->input('id'));

    // check application
    // json
    // if($checkApplication = $this->checkApplication(null, 'LV', 'U', $id))
    //     return response()->json(array("code" => 0, "message" => $checkApplication));

    $leave = DB::table('leaves')->where('leave_app_id', $id)->first();
    
    $approvers = explode(",",$leave->approved_by);
        
    // Check first if the approver already approve the request
    if($leave->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && ($leave->is_approved == 1 || $leave->is_approved == 3)){
        return response()->json(array("code" => 0, "message" => "You already approved this request."));
    }
    // Check if the is_approved is 0 then update to 1
    elseif($leave->is_approved == 0) {
      DB::table('leaves')->where('leave_app_id', $id)
        ->update([
          'is_approved' => 3,
          'rejected_by' => null,
          'approved_by' => session('employee')->UserID_Empl,
          'app1_approved_on' => date('Y-m-d H:i:s')
        ]);
      return response()->json(array("code" => 3, "message" => "Leave partially approved"));
    } elseif ($leave->is_approved == 3) {


      $emp_id = $leave->emp_id;
      $yr = date("Y", strtotime($leave->leave_date_from));

      $employee = DB::table('employees')->where('SysPK_Empl', $emp_id)->first();

      $leave_monitoring = DB::table('leave_monitoring')
        ->where('yr', $yr)
        ->where('emp_pk', $emp_id)
        ->first();

      if (!empty($leave_monitoring)) {
        $leave_credit = $leave->total_hours;
        $vl = 0;
        $sl = 0;
        $ml = 0;
        $pl = 0;

        if ($leave->leave_type_id == 1) {
          $vl = $leave->total_hours;
          $VLCredit = $employee->vacation_leave_credit;
          $VLDays = $leave->no_of_days;


          
          $VLCredit = $VLCredit -  $VLDays;

          DB::TABLE('employees')
            ->WHERE('SysPK_Empl', '=', $emp_id )
            ->UPDATE([
            "vacation_leave_credit" => $VLCredit
          ]);

        } elseif ($leave->leave_type_id == 2) {
          $sl = $leave->total_hours;

          $SLCredit = $employee->sick_leave_credit;
          $SLDays = $leave->no_of_days;


          
          $SLCredit = $SLCredit -  $SLDays;

          DB::TABLE('employees')
            ->WHERE('SysPK_Empl', '=', $emp_id )
            ->UPDATE([
            "sick_leave_credit" => $SLCredit
          ]);

        } elseif ($leave->leave_type_id == 3) {
          $ml = $leave->total_hours;

          $MLCredit = $employee->maternity_leave_credit;
          $MLDays = $leave->no_of_days;


          
          $MLCredit = $MLCredit -  $MLDays;

          DB::TABLE('employees')
            ->WHERE('SysPK_Empl', '=', $emp_id )
            ->UPDATE([
            "maternity_leave_credit" => $MLCredit
          ]);

        } elseif ($leave->leave_type_id == 6) {
          $pl = $leave->total_hours;

          $PLCredit = $employee->paternity_leave_credit;
          $PLDays = $leave->no_of_days;


          
          $PLCredit = $PLCredit -  $PLDays;

          DB::TABLE('employees')
            ->WHERE('SysPK_Empl', '=', $emp_id )
            ->UPDATE([
            "paternity_leave_credit" => $PLCredit
          ]);

        }

        DB::TABLE('leave_monitoring')
          ->WHERE('pk', '=', $leave_monitoring->pk)
          ->UPDATE([
            "first_jan" => 0,
            "second_jan" => $leave_monitoring->first_jan + $leave_credit,
            "used_vication_leave" => $leave_monitoring->used_vication_leave + $vl,
            "used_sick_leave" => $leave_monitoring->used_sick_leave + $sl,
            "used_paternity_leave" => 0,
            "used_maternity_leave" => 0,
            "bal_vication_leave" => $leave_monitoring->used_sick_leave - $vl,
            "bal_sick_leave" => $leave_monitoring->used_sick_leave - $sl,
            "bal_paternity_leave" => 0,
            "bal_maternity_leave" => 0,
            "total_leave_used" => $leave_monitoring->total_leave_used - $leave_credit
          ]);
      }
      DB::table('leaves')->where('leave_app_id', $id)
        ->update([
          'is_approved' => 1,
          'rejected_by' => null,
          'approved_by' => $leave->approved_by . ',' . session('employee')->UserID_Empl,
          'app2_approved_on' => date('Y-m-d H:i:s')
        ]);
      return response()->json(array("code" => 1, "message" => "Leave successfully approved"));
    } elseif ($leave->is_approved == 2) {
      return response()->json(array("code" => 2, "message" => "Leave already rejected"));
    }
  }

  public function reject(Request $r){

    if (session('is_approver')) {
      
      $id = $this->__get_orig_id($r->input('id'));

      // check application
      // json
      // if($checkApplication = $this->checkApplication(null, 'LV', 'U', $id))
      //     return response()->json(array("code" => 0, "message" => $checkApplication));

      $reason_remarks = $r->input('reason_remarks');

      // get the employee id first from leave table
      $leave = DB::table('leaves')->where('leave_app_id', $id)->first();
      // get the approvers from approvers table
      $approver = DB::table('approvers')->where('emp_id', $leave->employee_number)->first();
      // check if what type of approver
      $current_approver = 'app1_approved_on';
      if($approver->approver_1_emp_id == session('employee')->UserID_Empl)
        $current_approver = 'app1_approved_on';
      elseif($approver->approver_2_emp_id == session('employee')->UserID_Empl)
        $current_approver = 'app2_approved_on';

      $reject = DB::table('leaves')->where('leave_app_id', $id)
        ->update([
          'is_approved' => 2,
          'reason_remarks' => $reason_remarks,
          'rejected_by' => session('employee')->UserID_Empl,
          $current_approver => date('Y-m-d H:i:s')
        ]);
      if ($reject)
        return response()->json(array("code" => 1, "message" => "Leave rejected"));
    } else
        return response()->json(array("code" => 0, "message" => "Something went wrong!"));
  }

}