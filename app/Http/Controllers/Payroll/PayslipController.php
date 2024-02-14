<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PayslipController extends Controller
{
    public function payslip(){

        $SSS_emp_contrib = $PHILHEALTH_emp_contrib = $PAGIBIG_emp_contrib = $WTAX_emp_contrib = $WISP_emp_contrib = array();

        $payroll_id = Input::get('payroll') ?? 0;
        $departmentSelected = Input::get('department') ?? 0;
        $department = DB::table('department')->get();
        $payslip = collect();
        $display_by = Input::get('display_by') ?? '10'; // default 10
        
        $payrollDate = DB::table('payroll')
                        ->leftJoin('payroll_detail_2', 'payroll.payroll_id', '=', 'payroll_detail_2.payroll_id')
                        ->select('payroll.*');

        if(!in_array(session('user')->employee_type_id, [1,2])) // NORMAL EMPLOYEE
            $payrollDate = $payrollDate->where('payroll_detail_2.emp_id','=',session('user')->emp_id);
        else
            $payrollDate = $payrollDate->groupBy('payroll_id'); // ADMIN

        $payrollDate = $payrollDate->orderBy('payroll_id', 'desc')->get();
        
        $payslip = DB::table('payroll')
                        ->join('payroll_detail_2', 'payroll.payroll_id', '=', 'payroll_detail_2.payroll_id')
                        ->join('department', 'payroll_detail_2.department', '=', 'department.Name_Dept') // remove this line when already have dept_id in payroll
                        ->join('employees', 'payroll_detail_2.emp_id', '=', 'employees.SysPK_Empl')
                        ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                        ->leftJoin('atd', function($join)
                              {
                                //(ca.date_from <= _dateTo) AND  (ca.date_to >= _dateTo)
                                $join->on('atd.emp_id', '=', 'employees.SysPK_Empl');
                                $join->on('atd.date_to', '=', 'payroll.date_to');
                              })
                        ->select(
                            'payroll.*',
                            'payroll_detail_2.*',
                            'atd.remarks as atdremarks',
                            'employees.Name_Empl' ,
                            'employees.FirstName_Empl',
                            'employees.LastName_Empl',
                            'employees.SysPK_Empl',
                            'employees_rate.Position_Empl',
                            'department.Name_Dept',
                            'employees.SSS_Empl',
                            'employees.TIN_Empl',
                            'employees.philhealth_no',
                            'employees.pagibig_no',
                            'employees.rate_type',
                            'payroll.department AS company')
                        ->orderBy('payroll.payroll_id', 'desc');
        
        // FOR ADMIN ONLY
        if( in_array(session('user')->employee_type_id, [1,2]) ){
            if(is_numeric($departmentSelected) && $departmentSelected > 0) $payslip = $payslip->where('department.SysPK_Dept', $departmentSelected);
        }
        elseif(!in_array(session('user')->employee_type_id, [1,2]))// NORMAL EMPLOYEE
        {
            if($payrollDate && $payroll_id == 0){
                if(count($payrollDate) > 0){
                    $payroll_id = $payrollDate[0]->payroll_id;
                }
            }
            $payslip = $payslip->where('payroll_detail_2.emp_id', session('user')->emp_id)->orderByDesc('payroll_detail_id');
        } 
        if($payroll_id > 0)
            $payslip = $payslip->where('payroll.payroll_id', $payroll_id);
        
        if($display_by == 'all'){
            $payslip = $payslip->get();
            $paginationLinks = null;
        }
        else{
            $payslip = $payslip->paginate($display_by);
            $paginationLinks = $payslip->appends(request()->query())->links();
        }

        // NORMAL EMPLOYEE
        if (!in_array(session('user')->employee_type_id, [1,2])){

            $ps = $payslip->first();

            // DB::SELECT(DB::RAW('CALL display_employee_payroll(' . $dept_id . ',"' . $date_from . '","' . $date_to . '",' . $paygroup_id . ')'));
            
            $date_from = $ps->date_from;
            $date_to = $ps->date_to;

            $SSS_emp_contrib = DB::SELECT(DB::RAW('CALL SSS_emp_contrib(' . session('user')->emp_id . ',"' . $date_from . '","' . $date_to . '")'));
            $PHILHEALTH_emp_contrib = DB::SELECT(DB::RAW('CALL PHILHEALTH_emp_contrib(' . session('user')->emp_id . ',"' . $date_from . '","' . $date_to . '")'));
            $PAGIBIG_emp_contrib = DB::SELECT(DB::RAW('CALL PAGIBIG_emp_contrib(' . session('user')->emp_id . ',"' . $date_from . '","' . $date_to . '")'));
            $WTAX_emp_contrib = DB::SELECT(DB::RAW('CALL WTAX_emp_contrib(' . session('user')->emp_id . ',"' . $date_from . '","' . $date_to . '")'));
            $WISP_emp_contrib = DB::SELECT(DB::RAW('CALL WISP_emp_contrib(' . session('user')->emp_id . ',"' . $date_from . '","' . $date_to . '")'));
            
            return view('NormalEmployees.payslip',[
                        'payslip' => $payslip,
                        'payrollDate' => $payrollDate,
                        "paginationLinks" => $paginationLinks,
                        "SSS_emp_contrib" => $SSS_emp_contrib,
                        "PHILHEALTH_emp_contrib" => $PHILHEALTH_emp_contrib,
                        "PAGIBIG_emp_contrib" => $PAGIBIG_emp_contrib,
                        "WTAX_emp_contrib" => $WTAX_emp_contrib,
                        "WISP_emp_contrib" => $WISP_emp_contrib,

            ]);
        } 
        
        return view('Payroll.payslip',[
            'payslip' => $payslip,
            'payrollDate' => $payrollDate,
            'department' => $department,
            "paginationLinks" => $paginationLinks
        ]);

    }

}
