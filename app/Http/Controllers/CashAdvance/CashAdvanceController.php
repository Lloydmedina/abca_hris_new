<?php

namespace App\Http\Controllers\CashAdvance;

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
use Illuminate\Support\Arr;

class CashAdvanceController extends Controller
{

  public function cash_advance(){
    
    $departmentSelected = Input::get('department') ?? 0;
    $outletSelected = Input::get('outlet') ?? 0;
    $date_from = Input::get('date_from') ?? date('Y-m-01');
    $date_to = Input::get('date_to') ?? date('Y-m-t');

    $department = DB::table('department')->get();
    $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();

    $employees = DB::table('employees')->get();
    $ca = array();

    if (strtotime($date_from) > strtotime($date_to)) {
      return view('CashAdvance.cash_advance', [
        'employees'=>$employees,
        'ca'=>$ca,
        'department'=> $department,
        'outlets'=> $outlets,
      ])->withErrors(['Invalid date range']);
    }

    $ca = DB::table('emp_cash_advance')
              ->whereBetween('emp_cash_advance.date_from', [ date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
              ->where('is_deleted', 0);
              if(is_numeric($departmentSelected) && $departmentSelected > 0) $ca = $ca->where('dept_id', $departmentSelected);
              if(is_numeric($outletSelected) && $outletSelected > 0) $ca = $ca->where('outlet_id', $outletSelected);
              $ca = $ca->get();

    return view('CashAdvance.cash_advance',[
        'employees'=>$employees,
        'ca'=>$ca,
        'department' => $department,
        'outlets' => $outlets,
        'date_from' => $date_from,
        'date_to' => $date_to,
    ]);
  }

  public function add_cash_advance(Request $request){
    
    $employee = DB::table('employees')->where('UserID_Empl',$request->input('emp_code'))->first();
    if($employee){
      DB::TABLE('emp_cash_advance')->insert(
        [
          'ca_date'=>date('Y-m-d',strtotime( $request->input('ca_date') ) ),
          'emp_id'=>$employee->SysPK_Empl,
          'emp_name'=>$employee->Name_Empl,
          'dept_id'=>$employee->dept_id,
          'outlet_id'=>$employee->outlet_id,
          'ca_amount'=>$request->input('ca_amount'),
          'amount_deduction'=>$request->input('amount_deduction'),
          'date_from'=>date('Y-m-d',strtotime( $request->input('date_from') ) ),
          'date_to'=>date('Y-m-d',strtotime( $request->input('date_to') ) ),
          'remarks'=>$request->input('remarks')
        ]);
  
      return redirect('/cash_advance')->with('success_message','Cash Advance added');
    }
  }
  
  public function delete(Request $request){
    
    $id = $this->__get_orig_id($request->input('id'));
    if($id){
        DB::table('emp_cash_advance')->where('ca_id', $id)
            ->update([
                'is_deleted' => 1,
                'deleted_by' => Session('user')->id
            ]);
        return response()->json(array("code" => 1, "message" => "Cash advance successfully deleted"));
    }
  }


  public function atd(){
    
    $departmentSelected = Input::get('department') ?? 0;
    $outletSelected = Input::get('outlet') ?? 0;
    $date_from = Input::get('date_from') ?? date('Y-m-01');
    $date_to = Input::get('date_to') ?? date('Y-m-t');

    $department = DB::table('department')->get();
    $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();

    $employees = DB::table('employees')->orderBy('LastName_Empl', 'asc')->get();
    $atd = array();

    if (strtotime($date_from) > strtotime($date_to)) {
      return view('ATD.atd', [
        'employees'=>$employees,
        'ca'=>$atd,
        'department'=> $department,
        'outlets'=> $outlets,
      ])->withErrors(['Invalid date range']);
    }

    $atd = DB::table('atd')
              ->whereBetween('atd.date_from', [ date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
              ->where('is_deleted', 0);
              if(is_numeric($departmentSelected) && $departmentSelected > 0) $atd = $atd->where('dept_id', $departmentSelected);
              if(is_numeric($outletSelected) && $outletSelected > 0) $atd = $atd->where('outlet_id', $outletSelected);
    $atd = $atd->get();

    return view('ATD.atd',[
        'employees'=>$employees,
        'atd'=>$atd,
        'department' => $department,
        'outlets' => $outlets,
        'date_from' => $date_from,
        'date_to' => $date_to,
    ]);
  }

  public function add_atd(Request $request){
    
    $employee = DB::table('employees')->where('UserID_Empl',$request->input('emp_code'))->first();
    if($employee){
      DB::TABLE('atd')->insert(
        [
          'atd_date'=>date('Y-m-d',strtotime( $request->input('atd_date') ) ),
          'emp_id'=>$employee->SysPK_Empl,
          'emp_name'=>$employee->Name_Empl,
          'dept_id'=>$employee->dept_id,
          'outlet_id'=>$employee->outlet_id,
          'atd_amount'=>$request->input('atd_amount'),
          'amount_deduction'=>$request->input('amount_deduction'),
          'date_from'=>date('Y-m-d',strtotime( $request->input('date_from') ) ),
          'date_to'=>date('Y-m-d',strtotime( $request->input('date_to') ) ),
          'remarks'=>$request->input('remarks')
        ]);
  
      return redirect('/atd')->with('success_message','ATD successfully added');
    }
  }

  public function update_atd(Request $request){
    
    $employee = DB::table('employees')->where('SysPK_Empl',$request->input('emp_id'))->first();
    $id = $this->__get_orig_id($request->input('atd_id'));
    if($employee && $id){
      DB::TABLE('atd')->where('atd_id', $id)
        ->update(
        [
          'atd_date' => date('Y-m-d',strtotime( $request->input('atd_date') ) ),
          'emp_id' => $employee->SysPK_Empl,
          'emp_name' => $employee->Name_Empl,
          'dept_id' => $employee->dept_id,
          'outlet_id' => $employee->outlet_id,
          'atd_amount' => $request->input('atd_amount'),
          'amount_deduction' => $request->input('amount_deduction'),
          'date_from' => date('Y-m-d',strtotime( $request->input('date_from') ) ),
          'date_to' => date('Y-m-d',strtotime( $request->input('date_to') ) ),
          'remarks' => $request->input('remarks')
        ]);
  
      return redirect('/atd')->with('success_message','ATD successfully updated');
    }
    else{
      return redirect('/atd')->withErrors(['Something went wrong!']);
    }
  }

  public function delete_atd(Request $request){
    
    $id = $this->__get_orig_id($request->input('id'));
    if($id){
        DB::table('atd')->where('atd_id', $id)
            ->update([
                'is_deleted' => 1,
                'deleted_by' => Session('user')->id
            ]);
        return response()->json(array("code" => 1, "message" => "ATD successfully deleted"));
    }
  }
}
