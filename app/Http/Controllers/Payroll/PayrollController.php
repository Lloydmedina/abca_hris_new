<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Redirect;

class PayrollController extends Controller
{
  public function payroll_entry()
  {


    $department = DB::table('company')->select('company_id', 'company')->get();
    $paygroup = DB::table('paygroup')->get();

    $dept_id = Input::get('Department_Empl');
    $date_from = Input::get('date_from');
    $date_to = Input::get('date_to');
    $paygroup_id = Input::get('paygroup_id');

    if (is_null($date_from)) {
      $date_from = date('Y-m-d');
    }

    if (is_null($date_to)) {
      $date_to = date('Y-m-d');
    }

    $payroll_list = array();
    // if($dept_id){
    //   $payroll_list = DB::SELECT(DB::RAW('CALL display_employee_payroll_byDepartment("'.$dept_id.'","'.$date_from.'","'.$date_to.'")'));

    // }

    if (!is_null($date_from) && !is_null($date_to)) {
      if (is_null($dept_id)) {
        $dept_id = 0;
      }
      if (is_null($paygroup_id)) {
        $paygroup_id = 0;
      }
      $payroll_list = DB::SELECT(DB::RAW('CALL display_employee_payroll(' . $dept_id . ',"' . $date_from . '","' . $date_to . '",' . $paygroup_id . ')'));
    }

    session::put(['payroll_list_print' => $payroll_list]);

    return view('Payroll.payroll_entry', [
      'department' => $department,
      'date_from' => $date_from,
      'date_to' => $date_to,
      'dept_id' => $dept_id,
      'paygroup_id' => $paygroup_id,
      'payroll_list' => $payroll_list,
      'paygroup' => $paygroup
    ]);
  }

  public function print_payroll()
  {
    return view('Payroll.print_payroll');
  }

  public function save_payroll(Request $request)
  {
    set_time_limit(3600);

    $dept_id = $request->input('p_Department_Empl');
    $date_from = $request->input('p_date_from');
    $date_to = $request->input('p_date_to');
    $paygroup_id = $request->input('p_paygroup_id');

    if (is_null($date_from)) {
      $date_from = date('Y-m-d');
    }

    if (is_null($date_to)) {
      $date_to = date('Y-m-d');
    }
    $payroll_list = "";

    if ($dept_id) {
      $department = DB::table('company')
        ->where('company_id', $dept_id)
        ->first();

      $paygroup = DB::table('paygroup')
        ->where('paygroup_id', $paygroup_id)
        ->first();

      $payroll_checker = DB::TABLE('payroll')
        ->where('dept_code', $dept_id)
        ->where('paygroup_id', $paygroup_id)
        ->where('date_from', date('Y-m-d', strtotime($date_from)))
        ->where('date_to', date('Y-m-d', strtotime($date_to)))
        ->get();

      foreach ($payroll_checker as $checker) {
        DB::TABLE('payroll')->where('payroll_id', $checker->payroll_id)->delete();
        DB::TABLE('payroll_detail_2')->where('payroll_id', $checker->payroll_id)->delete();
      }

      $description = date('m/d/Y', strtotime($date_from)) . " to " . date('m/d/Y', strtotime($date_to));
      $payroll_list = DB::SELECT(DB::RAW('CALL display_employee_payroll("' . $dept_id . '","' . $date_from . '","' . $date_to . '",' . $paygroup_id . ')'));
      $payroll_id = DB::TABLE('payroll')->insertGetId(
        [
          "date_from" => date('Y-m-d', strtotime($date_from)),
          "date_to" => date('Y-m-d', strtotime($date_to)),
          "dept_code" => $dept_id,
          "department" => $department->company,
          "description" => $department->company . " " . $paygroup->paygroup . " " . $description,
          "total_amount" => 0,
          "save_by_id" => 0,
          "save_by" => "",
          "save_date" => date('Y-m-d'),
          "paygroup_id" => $paygroup_id,
          "paygroup" => $paygroup->paygroup,
        ]
      );
      $payroll = array();
      $total_amnt = 0;

      foreach ($payroll_list as $list) {

        $total_amnt = $total_amnt + (float) str_replace(",", "", $list->NetPay);
        $payroll[] =
          [
            "payroll_id" => $payroll_id,
            "emp_id" => $list->id,
            "bank_account" => $list->AccountNo,
            "emp_name" => $list->EmployeeName,
            "department" => $list->Dept,
            "daily_rate" => (float) $list->DailyRate,
            "monthly_rate" => (float) $list->MonthlyRate,
            "semi_monthly_rate" => (float) $list->SemiMonthlyRate,
            "hourly_rate" => (float) $list->HourlyRate,
            "min_rate" => (float) $list->MinRate,
            "reg_ot_rate" => (float) $list->RegOTRate,
            "holiday_rate" => (float) $list->HolidayRate,
            "sunday_special_rate" => (float) $list->SunOtRate,
            "night_premium_rate" => (float) $list->NPRate,
            "standart_hour" => (float) $list->StandardHour,
            "absent_hour" => (float) $list->AbsentHour,
            "ut" => (float) $list->UTHour,
            "leave" => (float) $list->SL_VL,
            "actual_hour" => (float) $list->ActualHour,
            "late_min" => (float) $list->LateMin,
            "reg_ot_hours" => (float) $list->RegOTHour,
            "sunday_special_hour" => (float) $list->SpclHour,
            "sunday_special_ot_hour" => (float) $list->SpclOTHour,
            "holiday_hour" => (float) $list->HolidayHour,
            "holiday_ot_hour" => (float) $list->HolidayOTHour,
            "night_premium_hour" => (float) $list->RegularNP,
            "night_premium_holiday_hour" => (float) $list->HolidayNP,
            "night_premium_sunday_special_hour" => (float) $list->SunNP,
            "paternity_leave_hour" => (float) $list->PaternityLeave,
            "total_hour" => (float) $list->TotalManHour,
            "rice_allowance" => (float) $list->RiceAllowance,
            "clothing_allowance" => (float) $list->ClothingAllowance,
            "monetized_unused_leave_credit" => (float) $list->MonetizedUnusedLeave,
            "medical_allowance_dependents" => (float) $list->MedicalCashAllowanceDependent,
            "medical_allowance" => (float) $list->MedicalAssistance,
            "laundry_allowance" => (float) $list->LaundryAllowance,
            "deminis_benefits" => (float) $list->Deminimis,
            "cola" => (float) $list->COLA,
            "communication_allowance" => (float) $list->CommunicationAllowance,
            "basic_pay" => (float) $list->BasicPay,
            "ot_pay" => (float) $list->RegOTPay,
            "sunday_special_pay" => (float) $list->SunPay,
            "sunday_special_ot_pay" => (float) $list->SunOTPay,
            "holiday_pay" => (float) $list->HolidayPay,
            "holiday_ot_pay" => (float) $list->HolidayOTPay,
            "night_premium_pay" => (float) $list->NPPay,
            "night_premium_holiday_pay" => (float) $list->HolidayNPPay,
            "night_premium_sunday_pay" => (float) $list->SunNPPay,
            "paternity_leave_pay" => (float) $list->PaternityLeavePay,
            "adjustments" => (float) $list->AdditionalAdjustment,
            "late_deduction" => (float) $list->LateDeduction,
            "gross_pay" => (float) $list->GrossPay,
            "taxable_compensation" => (float) $list->TaxableCompensation,
            "sss" => (float) $list->SSS,
            "philhealth" => (float) $list->PhilHealth,
            "pag_ibig" => (float) $list->PAGIBIG,
            "wtax" => (float) $list->WTax,
            "under_witheld" => (float) $list->UnderWithheld,
            "other_deductions" => (float) $list->OtherDeductions,
            "total_tax" => (float) $list->TotalTax,
            "sss_loan" => (float) $list->SSSLoan,
            "pagibig_loan" => (float) $list->PAGIBIGLoan,
            "loan_adjustment" => (float) $list->DeductionAdjustment,
            "cash_advance" => (float) $list->CA,
            "total_deduction" => (float) $list->TotalDeductions,
            "net_pay_atm" => (float) $list->NetPay,
            "net_pay_should_be" => (float) $list->NetPay,
            "extra" => (float) $list->Extra,
            "sss_er" => (float) $list->SSSER,
            "philhealth_er" => (float) $list->PhilHealthER,
            "pagibig_er" => (float) $list->PAGIBIGER,
            "wtax_er" => (float) $list->WtaxER,
            "total_er" => (float) $list->TotalER,
            "service_charge" => (float) $list->ServiceFee,
            "ssswisp" => (float) $list->SSSWISP,
            "ReimbursementFee" => (float) $list->ReimbursementFee,
            "TransportationAllowance" => (float) $list->TransportationAllowance,
            "HousingAllowance" => (float) $list->HousingAllowance,
            "Allowance" => (float) $list->Allowance,
            "OtherIncome" => (float) $list->OtherIncome,
            "ATD" => (float) $list->ATD,
            "RestDay" => (float) $list->RestDay,
            "RestDayOT" => (float) $list->RestDayOT,
            "LegalHolidy" => (float) $list->LegalHolidy,
            "LegalHolidayOT" => (float) $list->LegalHolidayOT,
            "LegalHolidayRD" => (float) $list->LegalHolidayRD,
            "LegalHolidayRDOT" => (float) $list->LegalHolidayRDOT,
            "SpecialHoliday" => (float) $list->SpecialHoliday,
            "SpecialHolidayOT" => (float) $list->SpecialHolidayOT,
            "SpecialHolidayRD" => (float) $list->SpecialHolidayRD,
            "SpecialHolidayRDOT" => (float) $list->SpecialHolidayRDOT,
            "DoubleSpecialHoliday" => (float) $list->DoubleSpecialHoliday,
            "DoubleSpecialHolidayOT" => (float) $list->DoubleSpecialHolidayOT,
            "DoubleSpecialHolidayRD" => (float) $list->DoubleSpecialHolidayRD,
            "DoubleSpecialHolidayRDOT" => (float) $list->DoubleSpecialHolidayRDOT,
            "DoubleLegalHoliday" => (float) $list->DoubleLegalHoliday,
            "DoubleLegalHolidayOT" => (float) $list->DoubleLegalHolidayOT,
            "DoubleLegalHolidayRD" => (float) $list->DoubleLegalHolidayRD,
            "DoubleLegalHolidayRDOT" => (float) $list->DoubleLegalHolidayRDOT,
            "LegalSpecialHoliday" => (float) $list->LegalSpecialHoliday,
            "LegalSpecialHolidayOT" => (float) $list->LegalSpecialHolidayOT,
            "LegalSpecialHolidayRD" => (float) $list->LegalSpecialHolidayRD,
            "LegalSpecialHolidayRDOT" => (float) $list->LegalSpecialHolidayRDOT,
            "VacationLeaveWithPay" => (float) $list->VacationLeaveWithPay,
            "SickLeaveWithPay" => (float) $list->SickLeaveWithPay,
            "VacationLeaveWithoutPay" => (float) $list->VacationLeaveWithoutPay,
            "SickLeaveWithoutPay" => (float) $list->SickLeaveWithoutPay,
            "spl" => (float) $list->spl,
            "BirthDayLeave" => (float) $list->BirthDayLeave,
            "MaternityLeave" => (float) $list->MaternityLeave,
            "PaternityLeave" => (float) $list->PaternityLeave,
            "PaternityLeavePay" => (float) $list->PaternityLeavePay,
            "MaternityLeavePay" => (float) $list->MaternityLeavePay,
            "BirthdayLeavePay" => (float) $list->BirthdayLeavePay,
            "SLPay" => (float) $list->SLPay,
            "VLPay" => (float) $list->VLPay,
            "RDPay" => (float) $list->RDPay,
            "RDOTPay" => (float) $list->RDOTPay,
            "LegalHolidayOTPay" => (float) $list->LegalHolidayOTPay,
            "LegalHolidayRDPay" => (float) $list->LegalHolidayRDPay,
            "LegalHolidayRDOTPay" => (float) $list->LegalHolidayRDOTPay,
            "SpecialHolidayPay" => (float) $list->SpecialHolidayPay,
            "SpecialHolidayOTPay" => (float) $list->SpecialHolidayOTPay,
            "SpecialHolidayRDPay" => (float) $list->SpecialHolidayRDPay,
            "SpecialHolidayRDOTPay" => (float) $list->SpecialHolidayRDOTPay,
            "DoubleSpecialHolidayPay" => (float) $list->DoubleSpecialHolidayPay,
            "DoubleSpecialHolidayOTPay" => (float) $list->DoubleSpecialHolidayOTPay,
            "DoubleSpecialHolidayRDPay" => (float) $list->DoubleSpecialHolidayRDPay,
            "DoubleSpecialHolidayRDOTPay" => (float) $list->DoubleSpecialHolidayRDOTPay,
            "DoubleLegalHolidayPay" => (float) $list->DoubleLegalHolidayPay,
            "DoubleLegalHolidayOTPay" => (float) $list->DoubleLegalHolidayOTPay,
            "DoubleLegalHolidayRDPay" => (float) $list->DoubleLegalHolidayRDPay,
            "DoubleLegalHolidayRDOTPay" => (float) $list->DoubleLegalHolidayRDOTPay,
            "VLWithOutPayDeduction" => (float) $list->VLWithOutPayDeduction,
            "SLWithOutPayDeduction" => (float) $list->SLWithOutPayDeduction,
            "AbsentDeduction" => (float) $list->AbsentDeduction,
            "UTDeduction" => (float) $list->UTDeduction,
            "MOORGATE_Loan" => (float) $list->MOORGATE_Loan,
            "ACCESS_Loan" => (float) $list->ACCESS_Loan,
            "SSSWISPER" => (float) $list->SSSWISPER,
          ];
      }

      if (!empty($payroll)) {
        DB::table('payroll_detail_2')->insert($payroll);
      }

      $payroll_id = DB::TABLE('payroll')
        ->where('payroll_id', $payroll_id)
        ->update(['total_amount' => $total_amnt]);
    }

    return redirect('payroll_entry')
      ->with('success_message', 'Payroll Saved');
  }

  public function payroll_list(Request $request)
  {
    $yr = date('Y');
    $month = date('m');
    $payroll_list = array();
    $payroll_master = DB::TABLE('payroll')
      ->whereRaw('YEAR(date_from) = "' . $yr . '"')
      ->get();
    if ($request->input("payroll_list")) {
      $yr = $request->input('yr');
      $payroll_master = DB::TABLE('payroll')
        ->where('payroll_id', $request->input("payroll_list"))->get();
      if (count($payroll_master) > 0) {
        $payroll_list = DB::TABLE('payroll_detail_2')
          ->join('employees', 'payroll_detail_2.emp_id', 'employees.SysPK_Empl')
          ->select('payroll_detail_2.*', 'employees.UserID_Empl')
          ->where('payroll_detail_2.payroll_id', $payroll_master[0]->payroll_id)
          ->get();
      }
    }

    $payroll_month = [
      ["value"=>1, "month"=>"January"],
      ["value"=>2, "month"=>"February"],
      ["value"=>3, "month"=>"March"],
      ["value"=>4, "month"=>"April"],
      ["value"=>5, "month"=>"May"],
      ["value"=>6, "month"=>"June"],
      ["value"=>7, "month"=>"July"],
      ["value"=>8, "month"=>"August"],
      ["value"=>9, "month"=>"September"],
      ["value"=>10, "month"=>"October"],
      ["value"=>11, "month"=>"November"],
      ["value"=>12, "month"=>"December"]
    ];

    return view(
      'Payroll.payroll_list',
      [
        'yr' => $yr,
        'payroll_list' => $payroll_list,
        'payroll_master' => $payroll_master,
        'month' => $month,
        'payroll_month'=>$payroll_month
      ]
    );
  }

  public function alphalist(Request $request)
  {
    $yr = date('Y');
    $paryoll_month = "";
    $payroll_list = array();

    if ($request->input("payroll_list")) {
      $payroll_master = DB::TABLE('payroll')->where('description', $request->input("payroll_list"))->get();
      if (count($payroll_master) > 0) {
        $payroll_list = DB::TABLE('payroll_detail_2')->where('payroll_id', $payroll_master[0]->payroll_id)->get();
      }
    }

    $list_7_5 = DB::SELECT(DB::RAW('CALL get_alpha_list_1604CF_7_5()'));

    return view(
      'Payroll.alpha_list',
      [
        'yr' => $yr,
        'payroll_list' => $payroll_list,
        'list_7_5' => $list_7_5
      ]
    );
  }

  public function taxdue_list(Request $request)
  {
    $yr = date('Y');
    $list = array();

    if ($request->input("yr")) {
      $list = DB::SELECT(DB::RAW('CALL get_emp_taxdue_yearly("' . $request->input("yr") . '")'));
    }

    return view(
      'Payroll.taxdue_list',
      [
        'yr' => $yr,
        'list' => $list
      ]
    );
  }

  public function payroll_deduction()
  {


    return view('Payroll.deduction_entry');
  }

  public function upload_payroll_deduction(Request $request)
  {

    DB::table('payroll_deduction')
      ->where('date_from', '=', date('Y-m-d', strtotime($request->input('date_from'))))
      ->where('date_to', '=', date('Y-m-d', strtotime($request->input('date_to'))))
      ->delete();

    $data = Excel::toArray(new UsersImport, request()->file('dtr'));
    $date_from = date('Y-m-d', strtotime($request->input('date_from')));
    $date_to = date('Y-m-d', strtotime($request->input('date_to')));
    $employee = DB::Table('employees')->get()->toArray();
    $deduction = array();

    //start search COOP Deduction
    foreach ($data[0] as $coop) {
      if ($coop[1] == "Name") {
        continue;
      }

      if (is_null($coop[5]) || $coop[5] == 0 || $coop[5] == "") {
        continue;
      } else {
        $deduction[] = [
          "date_from" => $date_from,
          "date_to" => $date_to,
          "emp_no" => $coop[0],
          "emp_name" => $coop[1],
          "coop" => str_replace(",", "", $coop[2]),
          "sss_loan" => 0,
          "pagibig_loan" => 0,
          "cash_advance" => 0,
        ];
      }
    }
    //end search COOP Deduction

    //start search Gov. Deduction
    foreach ($data[1] as $gov) {
      $emp_exist = array_keys(array_column($deduction, "emp_no"), $gov[0]);
      $emp_array_cnt = 0;
      $emp_array_cnt = count($emp_exist);

      if ($gov[1] == "Name") {
        continue;
      }

      if (
        (is_null($gov[2]) || $gov[2] == 0 || $gov[2] == "") &&
        (is_null($gov[3]) || $gov[3] == 0 || $gov[3] == "")
      ) {
        continue;
      } elseif ($emp_array_cnt != 0) {
        foreach ($emp_exist as $index) {
          $deduction[$index]['sss_loan'] = str_replace(",", "", $gov[2]);
          $deduction[$index]['pagibig_loan'] = str_replace(",", "", $gov[3]);
        }
      } else {
        $deduction[] = [
          "date_from" => $date_from,
          "date_to" => $date_to,
          "emp_no" => $gov[0],
          "emp_name" => $gov[1],
          "coop" => 0,
          "sss_loan" => str_replace(",", "", $gov[2]),
          "pagibig_loan" => str_replace(",", "", $gov[3]),
          "cash_advance" => 0,
        ];
      }
    }
    //end search Gov. Deduction

    //start search Cash Advance
    foreach ($data[2] as $ca) {
      $emp_exist = array_keys(array_column($deduction, "emp_no"), $ca[0]);
      $emp_array_cnt = 0;
      $emp_array_cnt = count($emp_exist);

      if ($ca[1] == "Name") {
        continue;
      }

      if ((is_null($ca[2]) || $ca[2] == 0 || $ca[2] == "")) {
        continue;
      } elseif ($emp_array_cnt != 0) {
        foreach ($emp_exist as $index) {
          $deduction[$index]['cash_advance'] = str_replace(",", "", $ca[2]);
        }
      } else {
        $deduction[] = [
          "date_from" => $date_from,
          "date_to" => $date_to,
          "emp_no" => $ca[0],
          "emp_name" => $ca[1],
          "coop" => 0,
          "sss_loan" => 0,
          "pagibig_loan" => 0,
          "cash_advance" => str_replace(",", "", $ca[2]),
        ];
      }
    }
    //end search Cash Advance 

    if (!empty($deduction)) {
      DB::table('payroll_deduction')->insert($deduction);
    }

    return redirect('/payroll_deduction')
      ->with('success_message', 'Imported file!');
  }

  public function service_charge()
  {

    $departmentSelected = Input::get('department') ?? 0;
    $outletSelected = Input::get('outlet') ?? 0;
    $date_from = Input::get('date_from') ?? date('Y-m-01');
    $date_to = Input::get('date_to') ?? date('Y-m-t');

    $department = DB::table('department')->get();
    $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();

    $employees = DB::table('employees')->orderBy('LastName_Empl', 'asc')->get();
    $list = array();

    if (strtotime($date_from) > strtotime($date_to)) {
      return view('Payroll.service_fee_other', [
        'employees' => $employees,
        'list' => $list,
        'department' => $department,
        'outlets' => $outlets,
      ])->withErrors(['Invalid date range']);
    }

    $list = DB::table('payroll_others')
      ->join('employees', 'payroll_others.emp_code', '=', 'employees.UserID_Empl')
      ->select('payroll_others.*', 'employees.outlet_id', 'employees.dept_id')
      ->whereBetween('payroll_others.payroll_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
      ->where('payroll_others.service_fee', '>', 0)
      ->where('payroll_others.deduction', 0)
      ->where('payroll_others.other_income', 0)
      ->where('payroll_others.is_deleted', 0);
    if (is_numeric($departmentSelected) && $departmentSelected > 0)
      $list = $list->where('employees.dept_id', $departmentSelected);
    if (is_numeric($outletSelected) && $outletSelected > 0)
      $list = $list->where('employees.outlet_id', $outletSelected);
    $list = $list->orderBy('payroll_others.payroll_other_id', 'desc')->get();

    return view('Payroll.service_fee_other', [
      'employees' => $employees,
      'list' => $list,
      'department' => $department,
      'outlets' => $outlets,
      'date_from' => $date_from,
      'date_to' => $date_to,
    ]);
  }

  public function other_income()
  {

    $departmentSelected = Input::get('department') ?? 0;
    $outletSelected = Input::get('outlet') ?? 0;
    $date_from = Input::get('date_from') ?? date('Y-m-01');
    $date_to = Input::get('date_to') ?? date('Y-m-t');

    $department = DB::table('department')->get();
    $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();

    $employees = DB::table('employees')->orderBy('LastName_Empl', 'asc')->get();
    $list = array();

    if (strtotime($date_from) > strtotime($date_to)) {
      return view('Payroll.other_income', [
        'employees' => $employees,
        'list' => $list,
        'department' => $department,
        'outlets' => $outlets,
      ])->withErrors(['Invalid date range']);
    }

    $list = DB::table('payroll_others')
      ->join('employees', 'payroll_others.emp_code', '=', 'employees.UserID_Empl')
      ->select('payroll_others.*', 'employees.outlet_id', 'employees.dept_id')
      ->whereBetween('payroll_others.payroll_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
      ->where('payroll_others.other_income', '>', 0)
      ->where('payroll_others.deduction', 0)
      ->where('payroll_others.service_fee', 0)
      ->where('payroll_others.is_deleted', 0);
    if (is_numeric($departmentSelected) && $departmentSelected > 0)
      $list = $list->where('employees.dept_id', $departmentSelected);
    if (is_numeric($outletSelected) && $outletSelected > 0)
      $list = $list->where('employees.outlet_id', $outletSelected);
    $list = $list->orderBy('payroll_others.payroll_other_id', 'desc')->get();

    return view('Payroll.other_income', [
      'employees' => $employees,
      'list' => $list,
      'department' => $department,
      'outlets' => $outlets,
      'date_from' => $date_from,
      'date_to' => $date_to,
    ]);
  }

  public function adjustments()
  {

    $departmentSelected = Input::get('department') ?? 0;
    $outletSelected = Input::get('outlet') ?? 0;
    $date_from = Input::get('date_from') ?? date('Y-m-01');
    $date_to = Input::get('date_to') ?? date('Y-m-t');

    $department = DB::table('department')->get();
    $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();

    $employees = DB::table('employees')->orderBy('LastName_Empl', 'asc')->get();
    $list = array();

    if (strtotime($date_from) > strtotime($date_to)) {
      return view('Payroll.adjustments', [
        'employees' => $employees,
        'list' => $list,
        'department' => $department,
        'outlets' => $outlets,
      ])->withErrors(['Invalid date range']);
    }

    $list = DB::table('payroll_others')
      ->join('employees', 'payroll_others.emp_code', '=', 'employees.UserID_Empl')
      ->select('payroll_others.*', 'employees.outlet_id', 'employees.dept_id')
      ->whereBetween('payroll_others.payroll_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
      ->where('payroll_others.adjustments', '!=', 0)
      ->where('payroll_others.deduction', 0)
      ->where('payroll_others.service_fee', 0)
      ->where('payroll_others.is_deleted', 0);
    if (is_numeric($departmentSelected) && $departmentSelected > 0)
      $list = $list->where('employees.dept_id', $departmentSelected);
    if (is_numeric($outletSelected) && $outletSelected > 0)
      $list = $list->where('employees.outlet_id', $outletSelected);
    $list = $list->orderBy('payroll_others.payroll_other_id', 'desc')->get();

    return view('Payroll.adjustments', [
      'employees' => $employees,
      'list' => $list,
      'department' => $department,
      'outlets' => $outlets,
      'date_from' => $date_from,
      'date_to' => $date_to,
    ]);
  }

  public function deduction()
  {

    $departmentSelected = Input::get('department') ?? 0;
    $outletSelected = Input::get('outlet') ?? 0;
    $date_from = Input::get('date_from') ?? date('Y-m-01');
    $date_to = Input::get('date_to') ?? date('Y-m-t');

    $department = DB::table('department')->get();
    $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();

    $employees = DB::table('employees')->orderBy('LastName_Empl', 'asc')->get();
    $list = array();

    if (strtotime($date_from) > strtotime($date_to)) {
      return view('Payroll.deduction', [
        'employees' => $employees,
        'list' => $list,
        'department' => $department,
        'outlets' => $outlets,
      ])->withErrors(['Invalid date range']);
    }

    $list = DB::table('payroll_others')
      ->join('employees', 'payroll_others.emp_code', '=', 'employees.UserID_Empl')
      ->select('payroll_others.*', 'employees.outlet_id', 'employees.dept_id')
      ->whereBetween('payroll_others.payroll_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
      ->where('payroll_others.deduction', '>', 0)
      ->where('payroll_others.other_income', 0)
      ->where('payroll_others.service_fee', 0)
      ->where('payroll_others.is_deleted', 0);
    if (is_numeric($departmentSelected) && $departmentSelected > 0)
      $list = $list->where('employees.dept_id', $departmentSelected);
    if (is_numeric($outletSelected) && $outletSelected > 0)
      $list = $list->where('employees.outlet_id', $outletSelected);
    $list = $list->orderBy('payroll_others.payroll_other_id', 'desc')->get();

    return view('Payroll.deduction', [
      'employees' => $employees,
      'list' => $list,
      'department' => $department,
      'outlets' => $outlets,
      'date_from' => $date_from,
      'date_to' => $date_to,
    ]);
  }

  public function add_service_fee_other_deduction(Request $request)
  {
    $service_type = $request->input('service_type');
    $service_fee = $request->input('service_fee');
    $other_income = $request->input('other_income');
    $deduction = $request->input('deduction');
    $adjustments = $request->input('adjustments');
    $remarks = $request->input('remarks');
    $payroll_date = $request->input('payroll_date');
    $emp_code = $request->input('emp_code');
    $amt_eff = 0;
    $date_from = $request->input('payroll_date');
    $date_to = $request->input('payroll_date');

    if($request->input('amt_eff')){
      $amt_eff = $request->input('amt_eff');
    }
    if($request->input('date_from')){
      $date_from = $request->input('date_from');
    }
    if($request->input('date_to')){
      $date_to = $request->input('date_to');
    }

    if ($service_type == 'service_charge') {

      $this->validate($request, [
        'service_fee' => 'required|numeric',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      $redirect_to = '/service-charge';

      if ($service_fee <= 0)
        return Redirect::back()->withErrors(['Service charge must be greater than 0.'])->withInput();

      // $checking = DB::table('payroll_others')
      //               ->where('emp_code', $emp_code)
      //               ->where('service_fee', '>', 0)
      //               ->where('payroll_date', date('Y-m-d', strtotime($payroll_date)))
      //               ->first();
      // if($checking)
      //   return Redirect::back()->withErrors(["Service charge has already been submitted for ".$emp_code." - ".date('M d, Y', strtotime($payroll_date)).". Please delete any previous entries to avoid confusion."])->withInput();

      $success_message = 'Service charge successfully added';
      $other_income = 0;
      $deduction = 0;
      $adjustments = 0;
    } elseif ($service_type == 'other_income') {

      $this->validate($request, [
        'other_income' => 'required',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      $redirect_to = '/other-income';

      if ($other_income <= 0)
        return Redirect::back()->withErrors(['Other income must be greater than 0.'])->withInput();

      // $checking = DB::table('payroll_others')
      //               ->where('emp_code', $emp_code)
      //               ->where('other_income', '>', 0)
      //               ->where('payroll_date', date('Y-m-d', strtotime($payroll_date)))
      //               ->first();
      // if($checking)
      //   return Redirect::back()->withErrors(["Other income has already been submitted for ".$emp_code." - ".date('M d, Y', strtotime($payroll_date)).". Please delete any previous entries to avoid confusion."])->withInput();

      $success_message = 'Other income & deduction successfully added';
      $service_fee = 0;
      $deduction = 0;
      $adjustments = 0;
    } elseif ($service_type == 'deduction') {

      $this->validate($request, [
        'deduction' => 'required',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      $redirect_to = '/deduction';

      if ($deduction <= 0)
        return Redirect::back()->withErrors(['Deduction must be greater than 0.'])->withInput();

      // $checking = DB::table('payroll_others')
      //               ->where('emp_code', $emp_code)
      //               ->where('deduction', '>', 0)
      //               ->where('payroll_date', date('Y-m-d', strtotime($payroll_date)))
      //               ->first();
      // if($checking)
      //   return Redirect::back()->withErrors(["Deduction has already been submitted for ".$emp_code." - ".date('M d, Y', strtotime($payroll_date)).". Please delete any previous entries to avoid confusion."])->withInput();

      $success_message = 'Deduction & deduction successfully added';
      $service_fee = 0;
      $other_income = 0;
      $adjustments = 0;
    } elseif ($service_type == 'adjustments') {

      $this->validate($request, [
        'adjustments' => 'required',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      $redirect_to = '/adjustments';

      // if ($adjustments <= 0)
      //   return Redirect::back()->withErrors(['Other income must be greater than 0.'])->withInput();

      $success_message = 'Adjustment successfully added';
      $service_fee = 0;
      $deduction = 0;
      $other_income = 0;
    } else
      return Redirect::back()->withErrors(['Something went wrong!'])->withInput();


    $employee = DB::table('employees')->where('UserID_Empl', $emp_code)->first();

    $payroll_others = DB::table('payroll_others')->insert([
      'payroll_date' => date('Y-m-d', strtotime($payroll_date)),
      'emp_code' => $employee->UserID_Empl,
      'emp_name' => $employee->Name_Empl,
      'service_fee' => $service_fee,
      'other_income' => $other_income,
      'deduction' => $deduction,
      'adjustments' => $adjustments,
      'amt_eff'=>$amt_eff,
      'dept_id' => $employee->dept_id,
      'remarks' => $remarks,
      'created_by' => session('user')->id,
      'created_at' => date('Y-m-d H:i:s'),
      'date_from' => date('Y-m-d', strtotime($date_from)),
      'date_to' => date('Y-m-d', strtotime($date_to)),
    ]);
    if ($payroll_others)
      return redirect($redirect_to)->with('success_message', $success_message);
    else
      return Redirect::back()->withErrors(['Something went wrong!']);
  }

  public function update_service_fee_other_deduction(Request $request){

    $service_type = $request->input('service_type');
    $service_fee = $request->input('service_fee');
    $other_income = $request->input('other_income');
    $deduction = $request->input('deduction');
    $adjustments = $request->input('adjustments');
    $remarks = $request->input('remarks');
    $payroll_date = $request->input('payroll_date');
    $emp_code = $request->input('emp_code');

    $payroll_other_id = $this->__get_orig_id($request->input('payroll_other_id'));
    
    if ($service_type == 'service_charge') {

      $this->validate($request, [
        'service_fee' => 'required|numeric',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      if ($service_fee <= 0)
        return Redirect::back()->withErrors(['Service charge must be greater than 0.'])->withInput();

      $success_message = 'Service charge successfully updated';
      $other_income = 0;
      $deduction = 0;
      $adjustments = 0;
    }
    else if($service_type == 'other_income'){

      $this->validate($request, [
        'other_income' => 'required',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      if ($other_income <= 0)
        return Redirect::back()->withErrors(['Other income must be greater than 0.'])->withInput();

      $success_message = 'Other income & deduction successfully updated';
      $service_fee = 0;
      $deduction = 0;
      $adjustments = 0;
    }
    else if($service_type == 'deduction'){

      $this->validate($request, [
        'deduction' => 'required',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      if ($deduction <= 0)
        return Redirect::back()->withErrors(['Deduction must be greater than 0.'])->withInput();

      $success_message = 'Deduction & deduction successfully updated';
      $service_fee = 0;
      $other_income = 0;
      $adjustments = 0;
    }
    else if($service_type == 'adjustments'){

      $this->validate($request, [
        'adjustments' => 'required',
        'remarks' => 'required',
        'payroll_date' => 'required',
      ]);

      // if ($adjustments <= 0)
      //   return Redirect::back()->withErrors(['Other income must be greater than 0.'])->withInput();

      $success_message = 'Adjustment successfully updated';
      $service_fee = 0;
      $deduction = 0;
      $other_income = 0;
    }
    else
      return Redirect::back()->withErrors(['Something went wrong!'])->withInput();

    $employee = DB::table('employees')->where('UserID_Empl', $emp_code)->first();
    
    $payroll_others = DB::table('payroll_others')->where('payroll_other_id', $payroll_other_id)
      ->update([
      'payroll_date' => date('Y-m-d', strtotime($payroll_date)),
      'emp_code' => $employee->UserID_Empl,
      'emp_name' => $employee->Name_Empl,
      'service_fee' => $service_fee,
      'other_income' => $other_income,
      'deduction' => $deduction,
      'adjustments' => $adjustments,
      'dept_id' => $employee->dept_id,
      'remarks' => $remarks,
      'created_by' => session('user')->id,
      'created_at' => date('Y-m-d H:i:s')
    ]);
    if ($payroll_others)
      return Redirect::back()->with('success_message', $success_message);
    else
      return Redirect::back()->withErrors(['Something went wrong!']);
  }

  public function upload_service_fee_other(Request $request)
  {
    // Guard::in();
    $request->validate([
      'file_import' => 'required'
    ]);
    $empOther = array();
    try {
      $data = Excel::toArray([], $request->file('file_import'))[0];

      $cnt = 0;
      foreach ($data as $i => $row) {
        $cnt = $cnt + 1;
        if ($cnt == 1) {
          continue;
        }

        $employee = DB::table('employees')->where('UserID_Empl', $row[0])->first();
        $emp_name = "";
        $emp_code = "";
        try {
          $emp_name = $employee->UserID_Empl;
          $emp_code = $employee->Name_Empl;
        } catch (Exception $e) {
          continue;
        }

        $dateConvertionError = 0;
        try {
          $UNIX_DATE = ($row[2] - 25569) * 86400;
          $UNIX_DATE = date('Y-m-d', $UNIX_DATE);
        } catch (Exception $e) {
          $UNIX_DATE = "1970-01-01";
          $dateConvertionError = 1;
        }

        if ($dateConvertionError == 1) {
          try {
            $UNIX_DATE = date('Y-m-d', strtotime($row[2]));
          } catch (Exception $e) {
            $UNIX_DATE = "1970-01-01";
          }
        }

        $empOther[] = array(
          'payroll_date' => $UNIX_DATE,
          'emp_code' => $emp_name,
          'emp_name' => $emp_code,
          'service_fee' => $row[3],
          'other_income' => $row[5],
          'deduction' => $row[4],
          'adjustments' => $row[6],
          'dept_id' => $employee->dept_id,
          'remarks' => $row[7]
        );
      }
    } catch (Exception $e) {
      return redirect('/service-charge')
        ->withErrors(['Something Went Wrong']);
    }

    DB::TABLE('payroll_others')->insert($empOther);

    return redirect('/service-charge')
      ->with('success_message', 'Service Fee and Other Deduction added');
  }

  public function delete_service_charge_other_deduction(Request $request)
  {

    $id = $this->__get_orig_id($request->input('id'));
    $service_type = $request->input('service_type') ?? '';

    if ($id) {
      DB::table('payroll_others')->where('payroll_other_id', $id)
        ->update([
          'is_deleted' => 1,
          'deleted_by' => Session('user')->id
        ]);
      return response()->json(array("code" => 1, "message" => $service_type . " successfully deleted"));
    }
  }
}
