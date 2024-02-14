<?php

namespace App\Http\Controllers\Loan;

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

class LoanController extends Controller
{

  public function sss_loan(Request $r)
  {

    $employees = DB::table('employees')->where('UserID_Empl', '!=', null)->where('UserID_Empl', '!=', '')->whereNotIn('employees.Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])->get();
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');

    $loan = DB::table('emp_sss_loan')
      ->join('employees', 'emp_sss_loan.emp_id', '=', 'employees.SysPK_Empl')
      ->select('emp_sss_loan.*', 'employees.Name_Empl')
      ->selectRaw('(SELECT IF( ISNULL(s.remaining_balance),0, s.remaining_balance)  FROM emp_sss_loan_details s where s.sss_loan_id =emp_sss_loan.sss_loan_id order by date_deducted desc limit 1) as "remaining_balance"')
      ->selectRaw('(SELECT SUM(s.current_deduction)  FROM emp_sss_loan_details s where s.sss_loan_id =emp_sss_loan.sss_loan_id limit 1) as "total_deductions"')
      ->where('emp_sss_loan.is_deleted', 0)->get();

    return view(
      'Loan.sss_loan',
      [
        'employees' => $employees,
        'loan' => $loan
      ]
    );
  }

  public function add_sss_loan(Request $request)
  {

    $employees = DB::TABLE('employees')->where('UserID_Empl', $request->input('emp_code'))->first();

    DB::TABLE('emp_sss_loan')->insert(
      [
        'sss_loan_date' => date('Y-m-d', strtotime($request->input('sss_loan_date'))),
        'emp_id' => $employees->SysPK_Empl,
        'emp_name' => $employees->Name_Empl,
        'loan_amount' => $request->input('loan_amount'),
        'amount_deduction' => $request->input('amount_deduction'),
        'date_from' => date('Y-m-d', strtotime($request->input('date_from'))),
        'date_to' => date('Y-m-d', strtotime($request->input('date_to'))),
        'remarks' => $request->input('remarks'),
        'issued_by' => session('user')->emp_id
      ]
    );

    return redirect('/sss_loan')->with('success_message', 'SSS loan successfully added.');
  }

  public function update_sss_loan(Request $request)
  {

    DB::table('emp_sss_loan')
      ->where('sss_loan_id', $request->input('sss_loan_id'))
      ->update(
        [
          'loan_amount' => $request->input('loan_amount'),
          'amount_deduction' => $request->input('amount_deduction'),
          'date_from' => $request->input('date_from'),
          'date_to' => $request->input('date_to'),
          'remarks' => $request->input('remarks')
        ]
      );

    return redirect('/sss_loan')->with('success_message', 'SSS loan successfully updated.');
  }

  public function delete_sss_loan()
  {
    $sss_loan_id = Input::get('id');

    if (!$sss_loan_id) {
      return redirect('/sss_loan')->with('invalid', 'Oops! Something went wrong');
    }

    DB::table('emp_sss_loan')
      ->where('sss_loan_id', $sss_loan_id)
      ->update(
        [
          'is_deleted' => 1,
          'deleted_by' => session('user')->emp_id
        ]
      );

    return redirect('/sss_loan')->with('success_message', 'SSS loan successfully deleted.');
  }

  public function pagibig_loan()
  {

    $employees = DB::table('employees')->where('UserID_Empl', '!=', null)->where('UserID_Empl', '!=', '')->whereNotIn('employees.Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])->get();
    $loan = DB::TABLE('emp_pagibig_loan')->join('employees', 'emp_pagibig_loan.emp_id', '=', 'employees.SysPK_Empl')
      ->select('emp_pagibig_loan.*', 'employees.Name_Empl')
      ->selectRaw('(SELECT IF( ISNULL(s.remaining_balance),0, s.remaining_balance)  FROM emp_pagibig_loan_details s where s.pagibig_loan_id =emp_pagibig_loan.pagibig_loan_id order by date_deducted desc limit 1) as "remaining_balance"')
      ->selectRaw('(SELECT SUM(s.current_deduction)  FROM emp_pagibig_loan_details s where s.pagibig_loan_id =emp_pagibig_loan.pagibig_loan_id limit 1) as "total_deductions"')
      ->where('emp_pagibig_loan.is_deleted', 0)->get();

    return view(
      'Loan.pagibig_loan',
      [
        'employees' => $employees,
        'loan' => $loan
      ]
    );
  }

  public function add_pagibig_loan(Request $request)
  {

    $employees = DB::TABLE('employees')->where('UserID_Empl', $request->input('emp_code'))->first();


    DB::TABLE('emp_pagibig_loan')->insert(
      [
        'pagibig_loan_date' => date('Y-m-d', strtotime($request->input('pagibig_loan_date'))),
        'emp_id' => $employees->SysPK_Empl,
        'emp_name' => $employees->Name_Empl,
        'loan_amount' => $request->input('loan_amount'),
        'amount_deduction' => $request->input('amount_deduction'),
        'date_from' => date('Y-m-d', strtotime($request->input('date_from'))),
        'date_to' => date('Y-m-d', strtotime($request->input('date_to'))),
        'remarks' => $request->input('remarks'),
        'issued_by' => session('user')->emp_id
      ]
    );

    return redirect('/pagibig_loan')
      ->with('success_message', 'PAG-IBIG loan successfully added.');
  }

  public function update_pagibig_loan(Request $request)
  {
    DB::table('emp_pagibig_loan')
      ->where('pagibig_loan_id', $request->input('pagibig_loan_id'))
      ->update(
        [
          'loan_amount' => $request->input('loan_amount'),
          'amount_deduction' => $request->input('amount_deduction'),
          'date_from' => $request->input('date_from'),
          'date_to' => $request->input('date_to'),
          'remarks' => $request->input('remarks')
        ]
      );

    return redirect('/pagibig_loan')->with('success_message', 'PAG-IBIG loan successfully updated.');
  }

  public function delete_pagibig_loan()
  {

    $pagibig_loan_id = Input::get('id');

    if (!$pagibig_loan_id) {
      return redirect('/pagibig_loan')->with('invalid', 'Oops! Something went wrong');
    }

    DB::table('emp_pagibig_loan')
      ->where('pagibig_loan_id', $pagibig_loan_id)
      ->update(
        [
          'is_deleted' => 1,
          'deleted_by' => session('user')->emp_id
        ]
      );

    return redirect('/pagibig_loan')->with('success_message', 'PAG-IBIG loan successfully deleted.');
  }

  public function moorgate_loan(Request $r)
  {
    $employees = DB::table('employees')->where('UserID_Empl', '!=', null)->where('UserID_Empl', '!=', '')->whereNotIn('employees.Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])->get();
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');
    
    $loan = DB::table('emp_moorgate_loan')
      ->join('employees', 'emp_moorgate_loan.emp_id', '=', 'employees.SysPK_Empl')
      ->join('employees as issued', 'emp_moorgate_loan.issued_by','=','issued.SysPK_Empl')
      ->select('emp_moorgate_loan.*', 'employees.Name_Empl', 'issued.Name_Empl as issued_by')
      ->selectRaw('(SELECT IF( ISNULL(s.remaining_balance),0, s.remaining_balance) FROM emp_moorgate_loan_details s where s.loan_id =emp_moorgate_loan.loan_id order by date_deducted desc limit 1) as "remaining_balance"')
      ->selectRaw('(SELECT SUM(s.current_deduction)  FROM emp_moorgate_loan_details s where s.loan_id =emp_moorgate_loan.loan_id limit 1) as "total_deductions"')
      ->whereBetween('emp_moorgate_loan.loan_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
      ->where('emp_moorgate_loan.is_deleted', 0)
      ->get();

    return view(
      'Loan.moorgate_loan',
      [
        'employees' => $employees,
        'loan' => $loan,
        'date_from'=>$date_from,
        'date_to'=>$date_to
      ]
    );
  }

  public function add_moorgate_loan(Request $request)
  {

    $employees = DB::TABLE('employees')->where('UserID_Empl', $request->input('emp_code'))->first();

    DB::TABLE('emp_moorgate_loan')->insert(
      [
        'loan_date' => date('Y-m-d', strtotime($request->input('loan_date'))),
        'emp_id' => $employees->SysPK_Empl,
        'emp_name' => $employees->Name_Empl,
        'loan_amount' => $request->input('loan_amount'),
        'amount_deduction' => $request->input('amount_deduction'),
        'date_from' => date('Y-m-d', strtotime($request->input('date_from'))),
        'date_to' => date('Y-m-d', strtotime($request->input('date_to'))),
        'remarks' => $request->input('remarks'),
        'issued_by' => session('user')->emp_id
      ]
    );

    return redirect()->back()->with('success_message', 'Moorgate loan successfully added.');
  }

  public function update_moorgate_loan(Request $request)
  {
    $loan_id = $request->input('loan_id');

    if (!$loan_id) {
      return redirect()->back()->with('invalid', 'Oops! Something went wrong');
    }
    
    DB::table('emp_moorgate_loan')
      ->where('loan_id', $loan_id)
      ->update(
        [
          'loan_date' => $request->input('loan_date'),
          'loan_amount' => $request->input('loan_amount'),
          'amount_deduction' => $request->input('amount_deduction'),
          'date_from' => $request->input('date_from'),
          'date_to' => $request->input('date_to'),
          'remarks' => $request->input('remarks')
        ]
      );

    return redirect()->back()->with('success_message', 'Moorgate loan successfully updated.');
  }

  public function delete_moorgate_loan()
  {
    $loan_id = Input::get('id');

    if (!$loan_id) {
      return redirect()->back()->with('invalid', 'Oops! Something went wrong');
    }

    DB::table('emp_moorgate_loan')
      ->where('loan_id', $loan_id)
      ->update(
        [
          'is_deleted' => 1,
          'deleted_by' => session('user')->emp_id
        ]
      );

    return redirect()->back()->with('success_message', 'Moorgate loan successfully deleted.');
  }

  // Access Loan
  public function access_loan(Request $r)
  {
    $employees = DB::table('employees')->where('UserID_Empl', '!=', null)->where('UserID_Empl', '!=', '')->whereNotIn('employees.Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])->get();
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');
    
    $loan = DB::table('emp_access_loan')
      ->join('employees', 'emp_access_loan.emp_id', '=', 'employees.SysPK_Empl')
      ->join('employees as issued', 'emp_access_loan.issued_by','=','issued.SysPK_Empl')
      ->select('emp_access_loan.*', 'employees.Name_Empl', 'issued.Name_Empl as issued_by')
      ->selectRaw('(SELECT IF( ISNULL(s.remaining_balance),0, s.remaining_balance) FROM emp_access_loan_details s where s.loan_id = emp_access_loan.loan_id order by date_deducted desc limit 1) as "remaining_balance"')
      ->selectRaw('(SELECT SUM(s.current_deduction)  FROM emp_access_loan_details s where s.loan_id = emp_access_loan.loan_id limit 1) as "total_deductions"')
      ->whereBetween('emp_access_loan.loan_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
      ->where('emp_access_loan.is_deleted', 0)
      ->whereNotIn('employees.Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])
      ->get();

    return view(
      'Loan.access_loan',
      [
        'employees' => $employees,
        'loan' => $loan,
        'date_from'=>$date_from,
        'date_to'=>$date_to
      ]
    );
  }

  public function add_access_loan(Request $request)
  {

    $employees = DB::TABLE('employees')->where('UserID_Empl', $request->input('emp_code'))->first();

    DB::TABLE('emp_access_loan')->insert(
      [
        'loan_date' => date('Y-m-d', strtotime($request->input('loan_date'))),
        'emp_id' => $employees->SysPK_Empl,
        'emp_name' => $employees->Name_Empl,
        'loan_amount' => $request->input('loan_amount'),
        'amount_deduction' => $request->input('amount_deduction'),
        'date_from' => date('Y-m-d', strtotime($request->input('date_from'))),
        'date_to' => date('Y-m-d', strtotime($request->input('date_to'))),
        'remarks' => $request->input('remarks'),
        'issued_by' => session('user')->emp_id
      ]
    );

    return redirect()->back()->with('success_message', 'Access loan successfully added.');
  }

  public function update_access_loan(Request $request)
  {
    $loan_id = $request->input('loan_id');

    if (!$loan_id) {
      return redirect()->back()->with('invalid', 'Oops! Something went wrong');
    }
    
    DB::table('emp_access_loan')
      ->where('loan_id', $loan_id)
      ->update(
        [
          'loan_date' => $request->input('loan_date'),
          'loan_amount' => $request->input('loan_amount'),
          'amount_deduction' => $request->input('amount_deduction'),
          'date_from' => $request->input('date_from'),
          'date_to' => $request->input('date_to'),
          'remarks' => $request->input('remarks')
        ]
      );

      return redirect()->back()->with('success_message', 'Access loan successfully updated.');
  }

  public function delete_access_loan()
  {
    $loan_id = Input::get('id');

    if (!$loan_id) {
      return redirect()->back()->with('invalid', 'Oops! Something went wrong');
    }

    DB::table('emp_access_loan')
      ->where('loan_id', $loan_id)
      ->update(
        [
          'is_deleted' => 1,
          'deleted_by' => session('user')->emp_id
        ]
      );

    return redirect()->back()->with('success_message', 'Access loan successfully deleted.');
  }

  
  public function get_deduction_history(Request $request)
  {

    $deductions = null;

    if ($request->input('type') == 'sss') {

      $deductions = DB::table('emp_sss_loan_details')
        ->join('emp_sss_loan', 'emp_sss_loan_details.sss_loan_id', '=', 'emp_sss_loan.sss_loan_id')
        ->select('emp_sss_loan_details.*', 'emp_sss_loan.emp_name')
        ->where('emp_sss_loan_details.sss_loan_id', $request->input('sss_loan_id'))
        ->get();
    } elseif ($request->input('type') == 'pagibig') {

      $deductions = DB::table('emp_pagibig_loan_details')
        ->join('emp_pagibig_loan', 'emp_pagibig_loan_details.pagibig_loan_id', '=', 'emp_pagibig_loan.pagibig_loan_id')
        ->select('emp_pagibig_loan_details.*', 'emp_pagibig_loan.emp_name')
        ->where('emp_pagibig_loan_details.pagibig_loan_id', $request->input('pagibig_loan_id'))
        ->get();
    }

    return response()->json($deductions);
  }

  public function add_deduction_loan(Request $request)
  {

    if ($request->input('type') == 'sss') {

      DB::table('emp_sss_loan_details')->insert([
        'date_deducted' => $request->input('date_deducted'),
        'sss_loan_id' => $request->input('sss_loan_id'),
        'current_deduction' => $request->input('current_deduction')
      ]);

      return redirect('/sss_loan')->with('success_message', 'SSS Loan deducted');

    } elseif ($request->input('type') == 'pagibig') {

      DB::table('emp_pagibig_loan_details')->insert([
        'date_deducted' => $request->input('date_deducted'),
        'pagibig_loan_id' => $request->input('pagibig_loan_id'),
        'current_deduction' => $request->input('current_deduction')
      ]);

      return redirect('/pagibig_loan')->with('success_message', 'PAG-IBIG Loan deducted');

    }
  }

}