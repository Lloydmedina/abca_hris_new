<?php

namespace App\Http\Controllers\Shift;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Session;


class ShiftController extends Controller
{
    public function line_leader()
    {
        $employees = DB::table('employees')->get();

        $list = DB::table('line_leader')->get();
        $details = DB::table('line_leader_details')->get();

        return View('LineLeader.line_leader', ['list' => $list, 'details' => $details, 'employees' => $employees]);
    }

    public function employee_shift()
    {
        $employees = DB::table('employees')->get();

        $list = DB::table('line_leader')->get();
        $details = DB::table('line_leader_details')->get();

        return View('LineLeader.employee_shift', ['list' => $list, 'details' => $details, 'employees' => $employees]);
    }

    public function index()
    {
        $date_from = Input::get('date_from') ?? date('Y-m-01');
        $date_to = Input::get('date_to') ?? date('Y-m-t');
        $departmentSelected = Input::get('department') ?? 0;
        $outletSelected = Input::get('outlet') ?? 0;

        $shift = collect();

        $department = DB::table('department')->get();
        $outlets = DB::TABLE('outlet')->orderBy('outlet_id', 'desc')->get();

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) {
            return view('Shift.shift', [
                // 'shift' => $shift,
                'department' => $department,
            ])->withErrors(['Invalid date range']);
        } // invalid date inputed

        // if emp user view his/her only shift_monitoring
        // if(session('user')->employee_type_id == 5){
        //     $shift = $shift->where('emp_id', session('user')->emp_id);
        // }


        if ($date_from && $date_to) {
            $shift = DB::table('shift_monitoring')
                ->join('employees', 'shift_monitoring.emp_id', '=', 'employees.SysPK_Empl')
                ->join('users', 'employees.SysPK_Empl', '=', 'users.emp_id')
                ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
                ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
                ->select(
                'shift_code.is_active',
                'shift_monitoring.is_deleted',
                'shift_monitoring.id',
                'shift_monitoring.shift_date',
                'shift_monitoring.dept_id',
                'shift_monitoring.department',
                'shift_monitoring.shift_code',
                'shift_monitoring.no_hr_break',
                // 'shift_monitoring.status',
                'shift_monitoring.status AS s_m_status',
                'users.status as u_status',
                'shift_monitoring.emp_id',
                'shift_monitoring.emp_name',
                'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'shift_code.Description', 'outlet.outlet', 'outlet.outlet_id')
                ->where('users.status', '!=', 'INACTIVE')
                ->where('shift_monitoring.is_deleted', 0)
                ->orderBy('employees.Name_Empl', 'asc')
                ->orderBy('shift_monitoring.shift_date', 'asc')
                ->groupBy('shift_monitoring.shift_date', 'shift_monitoring.emp_id', 'shift_monitoring.shift_code_id')
                ->where('shift_monitoring.shift_date', '>=', $date_from)
                ->where('shift_monitoring.shift_date', '<=', $date_to);


            if (is_numeric($departmentSelected) && $departmentSelected > 0)
                $shift = $shift->where('shift_monitoring.dept_id', $departmentSelected);
            if (is_numeric($outletSelected) && $outletSelected > 0)
                $shift = $shift->where('outlet.outlet_id', $outletSelected);

            $shift = $shift->paginate(50);
        }

        return view('Shift.shift', [
            'shift' => $shift,
            'department' => $department,
            'outlets' => $outlets,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    // NORMAL EMPLOYEE SHIFT
    public function shift_old(Request $r)
    {

        // Check first
        // if(session('user')->employee_type_id != 5) die;

        $date_from = Input::get('date_from') ?? date('Y-m-01');
        $date_to = Input::get('date_to') ?? date('Y-m-t');
        $shift = collect();

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) {
            return view('NormalEmployees.shift', [
                'shift' => $shift,
            ])->withErrors(['Invalid date range']);
        }
        // invalid date inputed

        if ($date_from && $date_to) {

            $shift = DB::table('shift_monitoring')
                ->join('employees', 'shift_monitoring.emp_id', '=', 'employees.SysPK_Empl')
                ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
                ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
                ->select('shift_monitoring.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'shift_code.Description', 'outlet.outlet', 'outlet.outlet_id')
                ->where('shift_monitoring.is_deleted', 0)
                ->orderBy('shift_monitoring.id', 'desc')
                ->where('shift_monitoring.shift_date', '>=', $date_from)->where('shift_monitoring.shift_date', '<=', $date_to)
                ->where('emp_id', session('user')->emp_id)
                ->get();
        }

        return view('NormalEmployees.shift', [
            'shift' => $shift,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    public function shift(Request $r)
    {

        // Array of Month Names and Numbers
        // https://www.apharmony.com/software-sagacity/2014/07/php-one-liners-array-of-month-names-and-numbers/
        $months = array_reduce(range(1, 12), function ($rslt, $m) {
            $rslt[$m] = date('F', mktime(0, 0, 0, $m, 10));
            return $rslt; });
        // Array of years
        $years = array_combine(range(date("Y"), 2020), range(date("Y"), 2020));
        $year = Input::get('year') ?? date('Y');
        $month = Input::get('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year . '-' . $month));
        $date_to = date('Y-m-t', strtotime($year . '-' . $month));
        $shift_of_the_months = collect();
        $shifts = collect(); // with dates
        // Get the count days of the selected month-year
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // Get the name of the first day in the month
        $name_of_first_day = strtolower(date('l', strtotime($date_from)));
        // Number skip
        $number_skip = 0;

        switch ($name_of_first_day) {
            case "sunday":
                $number_skip = 0;
                break;
            case "monday":
                $number_skip = 1;
                break;
            case "tuesday":
                $number_skip = 2;
                break;
            case "wednesday":
                $number_skip = 3;
                break;
            case "thursday":
                $number_skip = 4;
                break;
            case "friday":
                $number_skip = 5;
                break;
            default:
                $number_skip = 6;
        }

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) {
            return view('NormalEmployees.shift_2', [
                // 'shifts' => $shifts,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'months' => $months,
                'years' => $years,
            ])->withErrors(['Invalid date range']);
        }
        // invalid date inputed

        // GET THE REQUESTED SCHEDULE
        $requested_sched = DB::table('emp_pass_slip')
            // ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->join('shift_code', 'emp_pass_slip.shift_code_id', '=', 'shift_code.id')
            ->select('shift_code.*', 'emp_pass_slip.*')
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 2)
            ->where('emp_pass_slip.shift_code_id', '!=', null)
            ->where(function ($q) {
                $q->where('emp_pass_slip.ps_status', 0)
                    ->orWhere('emp_pass_slip.ps_status', 2)
                    ->orWhere('emp_pass_slip.ps_status', 3);
            })
            ->where('emp_id', session('user')->emp_id)
            ->get();

        if ($date_from && $date_to) {

            $shifts = DB::table('shift_monitoring')
                ->join('employees', 'shift_monitoring.emp_id', '=', 'employees.SysPK_Empl')
                ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
                ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
                ->select('shift_code.is_active','shift_monitoring.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'shift_code.Description', 'shift_code.id as shift_code_id', 'outlet.outlet', 'outlet.outlet_id')
                ->where('shift_monitoring.is_deleted', 0)
                ->orderBy('shift_monitoring.id', 'desc')
                ->where('shift_monitoring.shift_date', '>=', $date_from)->where('shift_monitoring.shift_date', '<=', $date_to)
                ->where('emp_id', session('user')->emp_id)
                ->get();
        }

        $index = 0;
        $add_index = 35 - $total_days - $number_skip; // To complete the table

        if ($number_skip > 0) {
            for ($x = 1; $x <= $number_skip; $x++) {
                $shift_of_the_months[$index] = array('date' => [], 'shift' => []);
                $index++;
            }
        }
        // Merge
        for ($x = 1; $x <= $total_days; $x++) {
            // First val of the selected date
            $date_val = date("Y-m-d", strtotime($year . '-' . $month . '-' . $x));
            $my_shift = [];
            foreach ($shifts as $shift) {

                if ($date_val == $shift->shift_date) {
                    // insert requested sched if any
                    if (count($requested_sched))
                        foreach ($requested_sched as $rs)
                            if ($rs->emp_sched_id == $shift->id)
                                $shift->requested_sched = $rs;

                    $my_shift = $shift;
                }
            }
            $shift_of_the_months[$index] = array('date' => $date_val, 'shift' => $my_shift);
            $index++;
        }
        // dd($shift_of_the_months);
        if ($add_index > 0) {
            for ($x = 1; $x <= $add_index; $x++) {
                $shift_of_the_months[$index] = array('date' => [], 'shift' => []);
                $index++;
            }
        }

        $shift_codes = DB::table('shift_code')->where('is_active', 1)->get();

        return view('NormalEmployees.shift_2', [
            // 'shifts' => $shifts,
            'shift_codes' => $shift_codes,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'months' => $months,
            'years' => $years,
            'shift_of_the_months' => $shift_of_the_months->toArray(),
            'number_skip' => $number_skip
        ]);
    }

    // NORMAL EMPLOYEE SHIFT (OUTLET APPROVER)
    public function shift_outlet(Request $r)
    {
        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 
        // $departmentSelected = Input::get('department') ?? 0;
        // $outletSelected = Input::get('outlet') ?? 0;
        // $date_from = Input::get('date_from') ?? date('Y-m-01');
        // $date_to = Input::get('date_to') ?? date('Y-m-t');

        // Array of Month Names and Numbers
        // https://www.apharmony.com/software-sagacity/2014/07/php-one-liners-array-of-month-names-and-numbers/
        $months = array_reduce(range(1,12),function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; });
        // Array of years
        $years = array_combine(range(date("Y"), 2020), range(date("Y"), 2020));
        $year = $r->input('year') ?? date('Y');
        $month = $r->input('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year.'-'.$month));
        $date_to = date('Y-m-t', strtotime($year.'-'.$month));
        $shift = collect();
        // $outletApprover = collect();

        // $outlets = array();
        // $department = DB::table('department')->get();

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        // $outletApprover = DB::table('outlet')
        //                 ->select('outlet_id')
        //                 ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
        //                 ->get()->toArray();

        // if(count($outletApprover)){
        //     $outlet_ids = array_column($outletApprover, 'outlet_id');
        //     $outlets = DB::TABLE('outlet')->whereIn('outlet_id', $outlet_ids)->orderBy('outlet_id', 'desc')->get();
        // }
        // dd(session('approvers'));

        $emp_id_numbers = array();
        // foreach (session('approvers') as $value)
        //     $emp_id_numbers[] = $value->emp_id;
        $approvers = DB::table('approvers')
            ->where('approver_1_emp_id', session('employee')->UserID_Empl)
            // ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
            ->get()->toArray();

        foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
        
        
        // if (session('is_approver'))
        //     $outlets = DB::TABLE('outlet')->orderBy('outlet_id', 'desc')->get();
        
        if ($date_from && $date_to) {

            // $shift = DB::table('shift_monitoring')
            //     ->join('employees', 'shift_monitoring.emp_id', '=', 'employees.SysPK_Empl')
            //     ->join('users', 'employees.SysPK_Empl', '=', 'users.emp_id')
            //     ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            //     ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            //     ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
            //     ->select(
            //         DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
            //         DB::raw('COUNT(shift_monitoring.shift_date) as total_shift_date_plotted'),
            //         'shift_monitoring.is_deleted',
            //         'shift_monitoring.id',
            //         // 'shift_monitoring.shift_date',
            //         'shift_monitoring.dept_id',
            //         'shift_monitoring.department',
            //         // 'shift_monitoring.shift_code',
            //         // 'shift_monitoring.no_hr_break',
            //         // 'shift_monitoring.status AS s_m_status',
            //         'shift_monitoring.emp_id',
            //         'shift_monitoring.emp_name',
            //         // 'employees.Name_Empl',
            //         // 'users.status as u_status', 
            //         'employees.SysPK_Empl', 
            //         'employees_rate.Position_Empl', 
            //         // 'shift_code.Description', 
            //         'outlet.outlet', 
            //         'outlet.outlet_id',
            //     )
            //     ->where('users.status', '!=', 'INACTIVE')
            //     ->where('shift_monitoring.is_deleted', 0)
            //     ->where('shift_monitoring.status', 1)
            //     ->orderBy('shift_monitoring.id', 'asc')
            //     ->groupBy('shift_monitoring.emp_id')
            //     ->whereBetween('shift_monitoring.shift_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
            //     ->where(function ($query) use ($emp_id_numbers) {
            //         $query->where('shift_monitoring.emp_id', '=', session('user')->emp_id) // approver shift
            //             ->orWhereIn('employees.UserID_Empl', $emp_id_numbers); // approvers emp
            //     })
            //     ->get();


            // if (is_numeric($departmentSelected) && $departmentSelected > 0)
            //     $shift = $shift->where('shift_monitoring.dept_id', $departmentSelected);
            // if (is_numeric($outletSelected) && $outletSelected > 0)
            //     $shift = $shift->where('outlet.outlet_id', $outletSelected);

            // $shift = $shift->paginate(50);
            // $shift = $shift->get();


            $ids = implode(",",$emp_id_numbers);
            $from = date('Y-m-d', strtotime($date_from));
            $to = date('Y-m-d', strtotime($date_to));
            
            $shift = DB::select("SELECT 
                employees.company_id,
                company.company
                , employees.Department_Empl AS department
                , outlet.outlet
                , employees.UserID_Empl
                , employees.Status_Empl
                , employees.SysPK_Empl AS emp_id
                , CONCAT(employees.FirstName_Empl,' ',employees.LastName_Empl) AS Name_Empl
                , employees_rate.Position_Empl
                , employees.BasicSalary_Empls
                , employees.rate_type
                , (SELECT Name_Empl FROM employees WHERE employees.UserID_Empl = approvers.approver_1_emp_id) 'approver1'
                , (SELECT Name_Empl FROM employees WHERE employees.UserID_Empl = approvers.approver_2_emp_id) 'approver2'
                , (SELECT COUNT(shift_date) FROM shift_monitoring WHERE shift_date BETWEEN '$from' AND '$to' 
                AND emp_id = employees.SysPK_Empl AND shift_monitoring.is_deleted = 0
                AND STATUS = 1
                ) 'total_shift_date_plotted' 
            FROM  employees
                LEFT JOIN company 
                    ON (employees.company_id = company.company_id)
                LEFT JOIN outlet 
                    ON (employees.outlet_id = outlet.outlet_id)
                LEFT JOIN employees_rate 
                    ON (employees.Position_Empl = employees_rate.id)
                LEFT JOIN approvers
                    ON (approvers.emp_id = employees.UserID_Empl)
                WHERE employees.UserID_Empl IN ($ids)
                AND employees.Status_Empl NOT IN ('RESIGNED', 'AWOL', 'TERMINATED')
                GROUP BY emp_id
            ORDER BY total_shift_date_plotted,company, department, outlet, employees.Name_Empl");
        }

        $shift = collect($shift);

        return view('NormalEmployees.shift_outlet_approver', [
            'shift' => $shift,
            'months' => $months,
            'years' => $years,
            // 'outletApprover' => $outletApprover,
            // 'department' => $department,
            // 'outlets' => $outlets,
            // 'date_from' => $date_from,
            // 'date_to' => $date_to,
        ]);
    }
    

    public function update_staff_shift_v2(Request $request)
    {
        $emp_id = $request->input('emp_id');
        $status = $request->input('status') ?? 1;
        $year = $request->input('year') ?? date('Y');
        $month = $request->input('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year.'-'.$month));
        $date_to = date('Y-m-t', strtotime($year.'-'.$month));
        // Array of Month Names and Numbers
        // https://www.apharmony.com/software-sagacity/2014/07/php-one-liners-array-of-month-names-and-numbers/
        $months = array_reduce(range(1,12),function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; });
        $years = array_combine(range(date("Y"), 2020), range(date("Y"), 2020));
        // Array of years

        // dd($emp_id);
        if(!$emp_id) return Redirect::back()->withErrors(['Something went wrong.'])->withInput();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $shift_codes = DB::table('shift_code')->where('is_active', 1)->get();

        // check the employee if belongs to approver 1
        $isEditableByApprover1 = DB::table('approvers')
            ->join('employees', 'approvers.emp_id', '=', 'employees.UserID_Empl')
            ->select('approvers.*', 'employees.Name_Empl')
            ->where('approvers.approver_1_emp_id', session('employee')->UserID_Empl)
            ->where('employees.SysPK_Empl', $emp_id)
            ->first();
        
        $shifts = DB::table('shift_monitoring')
                ->where('emp_id', '=', $emp_id)
                ->whereBetween('shift_date', [$date_from, $date_to])
                ->where('is_deleted', '=', 0);
                

        if($status != 0)
            $shifts = $shifts->where('status', '=', 1);

        // get the total shifts
        $totalShifts = $shifts->orderBy('shift_date')->count();

        $shifts = $shifts->orderBy('shift_date')->paginate(50);
        $paginationLinks = $shifts->appends(request()->query())->links();

        // check duplicte entries
        $duplicateEntries = DB::table('shift_monitoring')
            ->where('emp_id', $emp_id)
            ->whereBetween('shift_date', [$date_from, $date_to])
            ->where('status', 1)
            ->where('is_deleted', '=', 0)
            ->select('id', 'emp_id', 'shift_date', 'shift_code_id', 'is_deleted', 'status')
            ->groupBy('emp_id', 'shift_date', 'shift_code_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();
        
        return view('NormalEmployees.update_staff_shift_v2', [
            'shifts' => $shifts,
            'months' => $months,
            'month' => $month,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'years' => $years,
            'year' => $year,
            'emp_id' => $emp_id,
            'status' => $status,
            'isEditableByApprover1' => $isEditableByApprover1,
            'shift_codes' => $shift_codes,
            'duplicateEntries' => $duplicateEntries,
            'paginationLinks' => $paginationLinks,
            'totalShifts' => $totalShifts,
        ]);

    }

    public function fix_staff_shift(Request $r)
    {
        $emp_id = $r->input('emp_id');
        $date_from = $r->input('date_from');
        $date_to = $r->input('date_to');

        $duplicateEntries = DB::table('shift_monitoring')
            ->where('emp_id', $emp_id)
            ->whereBetween('shift_date', [$date_from, $date_to])
            ->where('status', 1)
            ->where('is_deleted', '=', 0)
            ->select('id', 'emp_id', 'shift_date', 'shift_code_id', 'is_deleted', 'status')
            ->groupBy('emp_id', 'shift_date', 'shift_code_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if($duplicateEntries){
            foreach ($duplicateEntries as $key => $val) {
                DB::table('shift_monitoring')
                    ->where('id', '!=', $val->id)
                    ->where('emp_id', $emp_id)
                    ->where('shift_date', $val->shift_date)
                    ->where('shift_code_id', $val->shift_code_id)
                    ->update(['is_deleted' => 1]);
            }
        }

        return redirect()->back()->with('success_message', 'The employee shift schedule was successfully fixed.');

    }


    public function shift_entry_v2(Request $request)
    {

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        }

        // Check date if valid
        if (strtotime($request->input('date_from')) > strtotime($request->input('date_to'))) {
            return Redirect::back()->withErrors(['The selected date from must be less than or equal to the date to.'])->withInput();
        }


        $employees = collect();

        $date_from = $request->input('date_from') ?? null;
        $date_to = $request->input('date_to') ?? null;
        $company_id = $request->input('company_id') ?? "all";
        $dept_id = $request->input('dept_id') ?? "all";

        $departments = DB::table('department')->get();
        $companies = DB::table('company')->get();
        $shift_codes = DB::table('shift_code')->where('is_active', 1)->get();

        if($date_from && $date_to){
            // Get the employees under approver 1
            $employees = DB::table('employees')
                        ->join('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
                        ->select('employees.*', 'approvers.approver_1_emp_id', 'approvers.emp_id')
                        ->where('approvers.approver_1_emp_id', session('employee')->UserID_Empl)
                        ->whereNotIn('employees.Status_Empl', ['RESIGNED', 'AWOL', 'TERMINATED']);

            if($company_id != "all") $employees = $employees->where('employees.company_id', $company_id);
            if($dept_id != "all") $employees = $employees->where('employees.dept_id', $dept_id);

            $employees = $employees->get();
        }
        
        return view('Shift.shift_entry_v2',
            [
                'employees' => $employees,
                'departments' => $departments,
                'companies' => $companies,
                'shift_codes' => $shift_codes,
                'date_from' => $date_from,
                'date_to' => $date_to,
            ]
        );
        
    }

    public function shift_entry(Request $request)
    {
        // redirect to the new shift entry
        // return Redirect::to(url('/shift-entry-v2'));

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 

        $department = collect();

        // $outletApprover = array();
        if (session('employee')) {
            // $outletApprover = DB::table('outlet')
            //             ->select('outlet_id')
            //             ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
            //             ->get()->toArray();

            if (!session('is_approver'))
                return view("errors.404");
        }

        $emp_id_numbers = array();
        $dept_ids = array();

        if (session('is_approver')) { // get the departments of approvers and thier emloyees only
            // foreach (session('approvers') as $value)
            //     $emp_id_numbers[] = $value->emp_id;
            
            $approvers = DB::table('approvers')
            ->where('approver_1_emp_id', session('employee')->UserID_Empl)
            ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
            ->get()->toArray();

            foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;

            $depts = DB::table('employees')
                ->select('dept_id')
                ->where('SysPK_Empl', '=', session('user')->emp_id) // approver shift
                ->orWhereIn('UserID_Empl', $emp_id_numbers) // approvers emp
                ->get();

            if ($depts) foreach ($depts as $value)
                    $dept_ids[] = $value->dept_id;
            if ($dept_ids)
                $department = DB::table('department')->whereIn('SysPK_Dept', $dept_ids)->get();

        } else
            $department = DB::table('department')->get();

        $outlets = DB::table('outlet')->orderBy('outlet_id', 'desc')->get();
        $companies = DB::table('company')->orderBy('company_id', 'desc')->get();

        $employees = DB::table('employees')->get();
        $shift_code = DB::table('shift_code')->where('is_active', 1)->get();
        $list = array();
        $df = $request->date_from ? date("Y-m-d", strtotime($request->date_from)) : date("Y-m-d");
        $datePlus6days = $request->date_to ? date("Y-m-d", strtotime($request->date_to)) : date("Y-m-d", strtotime("$df +6 day"));

        // FOR TESTING PURPOSES ONLY
        $list = DB::table('shift_monitoring')
            ->select('shift_monitoring.*', 'shift_code.id as shift_code_id', 'shift_code.shift_code', 'shift_code.Description', 'shift_code.time_start', 'shift_code.time_end', 'shift_code.total_hrs', 'shift_code.default_break_hrs', 'shift_code.default_break_hrs')
            ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
            ->where('shift_date', ">=", date("Y-m-d", strtotime($df)))
            ->where('shift_date', "<=", date("Y-m-d", strtotime($datePlus6days)));

        // get the new records only
        if (Session::has('new_shift_created')) {
            if (session('new_shift_created') == true && session('shift_last_id_before_insert') > 0) {
                $list = $list->where('shift_monitoring.id', '>', session('shift_last_id_before_insert'))
                    ->where('created_by', session('user')->id);
            }
        }


        // filtering for approvers emp only
        if ($dept_ids)
            $list = $list->whereIn('dept_id', $dept_ids);

        $list = $list->get();

        // emp_id_department
        // dd($list->unique('department')->toArray());
        $empIdDepartment = array();
        foreach ($list->unique('emp_id') as $l) {
            $empIdDepartment[] = $l->emp_id . '' . $l->department;
        }

        // Store the shift entry in array
        $dataListShift = array();
        foreach ($list as $l) {
            $checker = $l->emp_id . '' . $l->department;
            foreach ($empIdDepartment as $row1) {
                if ($row1 == $checker) {
                    $dataListShift[$row1][] = $l;
                }
            }
        }

        $empStatus = DB::table('employees')->select('Status_Empl')->groupBy('Status_Empl')->orderBy('Status_Empl', 'desc')->get();

        // Convert array to cellection
        $dataListShift = collect($dataListShift);
        return view(
            'Shift.shift_entry',
            [
                'empStatus' => $empStatus,
                'employees' => $employees,
                'department' => $department,
                'shift_code' => $shift_code,
                'outlets' => $outlets,
                'companies' => $companies,
                'datePlus6days' => $datePlus6days,
                'dataListShift' => $dataListShift,
                // 'outletApprover' => $outletApprover,
            ]
        );

    }

    public function save_shift(Request $request)
    {

        $this->validate($request, [
            'employee_id' => 'required|array',
            'employee_number' => 'required|array',
            'remarks' => 'required|array',
            'time_from' => 'required',
            'time_to' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'no_of_days' => 'required'
        ]);

        $emp_shift = array();

        // Check date if valid
        if (strtotime($request->input('date_from')) > strtotime($request->input('date_to'))) {
            return Redirect::back()->withErrors(['The selected date from must be less than or equal to the date to.'])->withInput();
        }

        // Compute the total no. of days between date from and date to
        $date_from = date_create($request->input('date_from'));
        $date_to = date_create($request->input('date_to'));
        $total_days = date_diff($date_from, $date_to);
        $no_of_days = $total_days->format("%a");
        $time_from = date('H:i:s', strtotime($request->input('time_from')));
        $time_to = date('H:i:s', strtotime($request->input('time_to')));


        // Store employees shift
        for ($i = 0; $i < count($request->input('employee_id')); $i++) {
            $emp_shift[] = array(
                "emp_id" => $request->input('employee_id')[$i],
                "employee_number" => $request->input('employee_number')[$i],
                "remarks" => $request->input('remarks')[$i],
                "no_of_days" => $no_of_days,
                "time_from" => $time_from,
                "time_to" => $time_to,
                "date_from" => $date_from,
                "date_to" => $date_to,
                "no_hr_break" => $request->input('no_hr_break'),
                "line_lead_id" => session('user')->id,
                'created_by' => session('user')->id,
                "created_at" => date('Y-m-d H:i:s')
            );
        }

        DB::table('shift_monitoring')->insert($emp_shift);

        return redirect('/shift-monitoring?')->with('success_message', 'Employees shift successfully created!');

    }

    public function delete(Request $request)
    {

        $id = $this->__get_orig_id($request->input('id'));
        if ($id) {
            DB::table('shift_monitoring')->where('id', $id)
                ->update([
                    'is_deleted' => 1,
                    'deleted_by' => Session('user')->id,
                    'updated_at' => now()
                ]);
            return response()->json(array("code" => 1, "message" => "Shift successfully deleted"));
        }
    }

    public function upload_emp_shift(Request $request)
    {

        if ($request->hasFile('shift')) {
            $employees = DB::table('employees')
                ->whereRAW('(Status_Empl="REGULAR") OR  (Status_Empl="PROBATIONARY")')
                ->get();
            $data = Excel::toArray(new UsersImport, request()->file('shift'));

            foreach ($data[0] as $shift) {
                echo $shift[0];
                die();
            }
        }
        return redirect('/shift-monitoring?')->with('success_message', 'Employees shift successfully created!');

    }

    public function shift_code()
    {

        $list = DB::table('shift_code')->orderBy('time_start', 'asc')->get();
        
        return view('Shift.shift_code_entry', ['list' => $list]);
    }

    public function save_shift_code(Request $request)
    {

        $this->validate($request, [
            'shift_code' => 'required',
            'Description' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'total_hrs' => 'required',
            'default_break_hrs' => 'required',
        ]);

        $shiftCodeExists = DB::table('shift_code')->where('shift_code', strtoupper($request->input('shift_code')))->first();

        if ($shiftCodeExists)
            return redirect()->back()->withErrors([strtoupper($request->input('shift_code')).' shift code already exists!']); 

        $timeSchedExists = DB::table('shift_code')
                            ->where('time_start', '=', $request->input('time_start'))
                            ->where('time_end', '=', $request->input('time_end'))
                            ->where('default_break_hrs', '=', $request->input('default_break_hrs'))
                            ->first();
        
        if ($timeSchedExists) {
            $errM = "The time schedule with the specified time start of ".date('H:i:s', strtotime($request->input('time_start')))." and time end of ".date('H:i:s', strtotime($request->input('time_end')))." with a break of ".$request->input('default_break_hrs')." hour(s), has already been used by shift code ".strtoupper($timeSchedExists->shift_code);
            return redirect()->back()->withErrors([$errM]);
        }
        

        DB::table('shift_code')->insert(
            [
                "shift_code" => $request->input('shift_code'),
                "Description" => $request->input('Description'),
                "time_start" => $request->input('time_start'),
                "time_end" => $request->input('time_end'),
                "total_hrs" => $request->input('total_hrs'),
                "flex_time" => $request->input('flex_time'),
                "default_break_hrs" => $request->input('default_break_hrs'),
                "Remarks" => $request->input('Remarks'),
                'is_grave_shift' => $request->input('is_grave_shift', 0)
            ]
        );

        return redirect('/shift-code?')->with('success_message', 'Shift code successfully created!');
    }

    public function update_shift_code(Request $r){

        $this->validate($r, [
          'shift_code' => 'required',
          'Description' => 'required',
          'time_start' => 'required',
          'time_end' => 'required',
          'total_hrs' => 'required',
          'default_break_hrs' => 'required',
        ]);
  
        if(!$r->input('id'))
          return redirect()->back()->withErrors(['Something went wrong!']);

        $shiftCodeExists = DB::table('shift_code')->where('id', '!=', $r->input('id'))->where('shift_code', strtoupper($r->input('shift_code')))->first();

        if($shiftCodeExists)
            return redirect()->back()->withErrors([strtoupper($r->input('shift_code')).' shift code already exists!']);

        $timeSchedExists = DB::table('shift_code')->where('id', '!=', $r->input('id'))
            ->where('time_start', '=', $r->input('time_start'))
            ->where('time_end', '=', $r->input('time_end'))
            ->where('default_break_hrs', '=', $r->input('default_break_hrs'))
            ->first();

        if ($timeSchedExists) {
            $errM = "The time schedule with the specified time start of ".date('H:i:s', strtotime($r->input('time_start')))." and time end of ".date('H:i:s', strtotime($r->input('time_end')))." with a break of ".$r->input('default_break_hrs')." hour(s), has already been used by shift code ".strtoupper($timeSchedExists->shift_code);
            return redirect()->back()->withErrors([$errM]);
        }

        $shift_code = DB::table('shift_code')
              ->where('id', $r->input('id'))
              ->update([
                  'shift_code' => $r->input('shift_code'),
                  'Description' => $r->input('Description'),
                  'time_start' => $r->input('time_start'),
                  'time_end' => $r->input('time_end'),
                  'total_hrs' => $r->input('total_hrs'),
                  'flex_time' => $r->input('flex_time'),
                  'default_break_hrs' => $r->input('default_break_hrs'),
                  'Remarks' => $r->input('Remarks'),
                  'is_grave_shift' => $r->input('is_grave_shift', 0)
                ]);
        if($shift_code)
            return redirect()->back()->with('success_message','Shift code successfully updated!');
        else
            return redirect()->back()->withErrors(['Something went wrong!']);     
    }

    
    public function delete_shift_code($id = null){
        // dd("No more deletion for now.");
        if($id)
            DB::table('shift_code')->where('id', $id)->update(['is_active' => 0]);
        else
            return redirect()->back()->withErrors(['Something went wrong!']);

        return redirect()->back()->with('success_message','The shift code has been deleted.');
    }

    public function restore_shift_code($id = null){
        if($id)
            DB::table('shift_code')->where('id', $id)->update(['is_active' => 1]);
        else
            return redirect()->back()->withErrors(['Something went wrong!']);

        return redirect()->back()->with('success_message','The shift code has been restored.');
    }

    public static function excelToPHPDate($excelDate = null, $format = "Y-m-d") {
        
        $returnValue = $excelDate;
        if ($excelDate) {
            $UNIX_DATE = ($excelDate - 25569) * 86400;
            $returnValue = gmdate($format, $UNIX_DATE);
        }
        return $returnValue;
    }

    function searchForShiftCode(string $shiftCode, array $array, $objectOrIndexReturn = null)
    {
        // dd($array);
        foreach ($array as $key => $val) {

            if ($val->shift_code == $shiftCode) {

                if($val->is_active === 0)
                    return "<span class='text-danger'>This shift code has been deleted. Please report to your admin.</span>";
                
                if ($objectOrIndexReturn) 
                    return $val->$objectOrIndexReturn ?? "Object not found";
                
                return $val;
            }
        }
        return null;
    }

    function searchForDept(string $dept_id, array $array, $objectOrIndexReturn = null)
    {
        // dd($array);
        foreach ($array as $key => $val) {

            if ($val->SysPK_Dept == $dept_id) {

                if ($objectOrIndexReturn) {

                    return $val->$objectOrIndexReturn ?? "Object not found";
                }
                return $val;
            }
        }
        return null;
    }

    public function view_emp_shift_uploads(Request $request)
    {

        $request->validate([
            'file_import' => 'required'
        ]);

        try {
            $shiftDuplicateIds = array();
            $empShift = array();
            $empShiftView = array(); // data for view
            $empployee_id_checker = array();

            $shiftCodes = DB::table('shift_code')->get()->toArray();
            $departments = DB::table('department')->get()->toArray();

            // $test = $this->searchForShiftCode("RD", $shiftCodes->toArray(),'Description');
            // dd($test);
            // dd($shiftCodes);
            // $path = $request->file('file_import')->getRealPath();
            // $data = Excel::load($path)->get();
            $data = Excel::toArray([], $request->file('file_import'))[0]; // [0] gte the first row of an excel file
            $date_index_start = 0;
            $day_index_start = 1;
            $emp_index_start = 2;

            $shiftDates_ = $data[$date_index_start];
            $shiftDay_ = $data[$day_index_start];

            $shiftDates = array(
                $shiftDay_[2] => $this->excelToPHPDate($shiftDates_[2]),
                $shiftDay_[3] => $this->excelToPHPDate($shiftDates_[3]),
                $shiftDay_[4] => $this->excelToPHPDate($shiftDates_[4]),
                $shiftDay_[5] => $this->excelToPHPDate($shiftDates_[5]),
                $shiftDay_[6] => $this->excelToPHPDate($shiftDates_[6]),
                $shiftDay_[7] => $this->excelToPHPDate($shiftDates_[7]),
                $shiftDay_[8] => $this->excelToPHPDate($shiftDates_[8]),
            );

            $emp_index = $emp_index_start; // start from index 2

            $emp_id_numbers = array();
            if(session('is_approver')){
                // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
                $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

                foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
            }

            foreach ($data as $i => $row) {

                if ($i >= $emp_index_start) {

                    // Check if the employee is under this approver, or if they are an admin, then set it to true.
                    $is_admin_or_approver = false;
                    if(session('is_approver'))
                        $is_admin_or_approver = in_array($row[0], $emp_id_numbers) ? true : false;
                    elseif(in_array(session('user')->employee_type_id, [1,2]))
                        $is_admin_or_approver = true;
                    
                    // dd($row);
                    // Sample output
                    // 0 => "00000" // emp id number
                    // 1 => "EMPLOYEE NAME"
                    // 2 => "RD"
                    // 3 => "LL"
                    // 4 => "LLL"
                    // 5 => "LL"
                    // 6 => "LL"
                    // 7 => "LL"
                    // 8 => "RD"
                    if ($row[0] && $is_admin_or_approver) {
                        
                        $clean_emp_no = explode(".", trim($row[0]));
                        
                        $empployee_id_checker[] = $clean_emp_no[0];
                        $empShiftView[] = array(
                            'emp_id_numer' => $clean_emp_no[0] ?? "No Employee ID",
                            'emp_name' => $row[1] ?? "No Employee Name",
                            'emp_shift_code_1' => trim($row[2]),
                            'emp_shift_code_2' => trim($row[3]),
                            'emp_shift_code_3' => trim($row[4]),
                            'emp_shift_code_4' => trim($row[5]),
                            'emp_shift_code_5' => trim($row[6]),
                            'emp_shift_code_6' => trim($row[7]),
                            'emp_shift_code_7' => trim($row[8]),
                            'emp_shift_code_desc_1' => $this->searchForShiftCode(strtoupper(trim($row[2])), $shiftCodes, 'Description'),
                            'emp_shift_code_desc_2' => $this->searchForShiftCode(strtoupper(trim($row[3])), $shiftCodes, 'Description'),
                            'emp_shift_code_desc_3' => $this->searchForShiftCode(strtoupper(trim($row[4])), $shiftCodes, 'Description'),
                            'emp_shift_code_desc_4' => $this->searchForShiftCode(strtoupper(trim($row[5])), $shiftCodes, 'Description'),
                            'emp_shift_code_desc_5' => $this->searchForShiftCode(strtoupper(trim($row[6])), $shiftCodes, 'Description'),
                            'emp_shift_code_desc_6' => $this->searchForShiftCode(strtoupper(trim($row[7])), $shiftCodes, 'Description'),
                            'emp_shift_code_desc_7' => $this->searchForShiftCode(strtoupper(trim($row[8])), $shiftCodes, 'Description'),
                        );

                        $employee = DB::table('employees')->where('UserID_Empl', $clean_emp_no[0])->first();
                        if ($employee) {
                            foreach ($row as $i => $val) {

                                if ($i == 9)
                                    break;
                                if ($i >= 2) {

                                    // Check the shift code if already in the database
                                    if (!$this->searchForShiftCode(strtoupper($val), $shiftCodes)) {
                                        continue;
                                        // return '<center>The "'.$val.'" shift code was not found in the database. <br/>Create a shift code for this shift.</center>';
                                        // die();
                                    }

                                    $shift_code_id_store = $this->searchForShiftCode(strtoupper($val), $shiftCodes, 'id');
                                    $no_hr_break_store = $this->searchForShiftCode(strtoupper($val), $shiftCodes, 'default_break_hrs');
                                    $shift_date_store = date("Y-m-d", strtotime($this->excelToPHPDate($shiftDates_[$i])));
                                    $line_lead_id_store = $this->searchForDept($employee->dept_id, $departments, 'Head_Dept_ID');

                                    $checkerData = DB::table('shift_monitoring')->where('emp_id', $employee->SysPK_Empl)->where('shift_date', $shift_date_store)->where('is_deleted', 0)->first();
                                    // $checkerData = DB::table('shift_monitoring')->where('emp_id', $employee->SysPK_Empl)->where('shift_code_id', $shift_code_id_store)->where('shift_date', $shift_date_store)->where('is_deleted', 0)->first();

                                    if ($checkerData){
                                        if($checkerData->allow_update == 0) continue; // skip this part 🔒
                                        
                                        $shiftDuplicateIds[] = $checkerData->id; // store the shift id when found in the record
                                    }

                                    $empShift[] = array(
                                        "emp_id" => $employee->SysPK_Empl,
                                        "emp_name" => $employee->Name_Empl,
                                        "remarks" => "",
                                        "shift_code_id" => $shift_code_id_store,
                                        "shift_code" => $val,
                                        "no_hr_break" => $no_hr_break_store,
                                        "shift_date" => $shift_date_store,
                                        "line_lead_id" => $line_lead_id_store,
                                        "dept_id" => $employee->dept_id,
                                        "department" => $employee->Department_Empl,
                                        'created_by' => session('user')->id,
                                    );
                                }
                            }
                        }

                    }

                }

                $emp_index++;
            }

            if(count($empployee_id_checker) > 0){
                $duplicateValues = $this->findDuplicates($empployee_id_checker);
                if (count($duplicateValues) > 0) {
                    $html = "Error: Duplicate Employee Number Detected: " . implode(', ', $duplicateValues);
                    $html = '<div class="text-danger text-center">'.$html.' <br/><p>Description: The system has encountered an issue while processing your request. It appears that there is a duplicate Employee Number within the file, which is not allowed.</p>Resolution: Please review the entered Employee Numbers and ensure that each one is distinct. If you believe this message was received in error, kindly contact the system administrator for further assistance. It is essential to resolve this issue promptly to maintain data integrity and avoid any potential confusion or errors in employee management.<p></p></div>';
                    $response = [
                        'success' => false,
                        'html' => $html,
                    ];
                    return response()->json($response, 201);
                }
            }
            
            // dd($empShiftView);
            // dd($empShift);
            // set session $empShift
            Session::put('empShift', $empShift);
            Session::put('shiftDuplicateIds', $shiftDuplicateIds);

            $html = view('Shift.import_shift_view', [
                'empShiftView' => $empShiftView,
                'shiftDates' => $shiftDates,
            ])->render();

            $response = [
                'success' => true,
                'html' => $html,
            ];

        } catch (\Throwable $e) {
            
            $html = '<div class="text-center text-danger">Please double-check the Excel file format and the employee details if you are the approver of them.</div>';
            $response = [
                'success' => false,
                'html' => $html,
            ];
        }

        return response()->json($response, 201);
    }

    public function findDuplicates($array) {
        $countedValues = array_count_values($array);
        $duplicates = array();
        
        foreach ($countedValues as $value => $count) {
            if ($count > 1) {
                $duplicates[] = $value;
            }
        }
        
        return $duplicates;
    }

    public function save_emp_shift_uploads(Request $r)
    {

        $r->validate([
            'save_import_shift' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        $date_from = $r->input('date_from');
        $date_to = $r->input('date_to');

        if ($r->input('save_import_shift')) {

            if (Session::has('shift_last_id_before_insert') && session('shift_last_id_before_insert') !== null)
                $shift_last_id_before_insert = session('shift_last_id_before_insert');
            else
                $shift_last_id_before_insert = null;

            // get the last record
            if ($shift_last_id_before_insert === null) {

                $shift_last_id_before_insert = DB::table('shift_monitoring')->orderBy('id', 'DESC')->first();
                if ($shift_last_id_before_insert)
                    $shift_last_id_before_insert = $shift_last_id_before_insert->id;
                else
                    $shift_last_id_before_insert = 0;
            }

            Session::put([
                'new_shift_created' => true,
                'shift_last_id_before_insert' => $shift_last_id_before_insert
            ]);

            $empShift = Session::get('empShift');
            $shiftDuplicateIds = Session::get('shiftDuplicateIds');

            if ($empShift)
                $insert_shift = DB::table('shift_monitoring')->insert($empShift);
            if ($insert_shift && count($shiftDuplicateIds))
                DB::table('shift_monitoring')->whereIn('id', $shiftDuplicateIds)->update(['is_deleted' => 1, 'deleted_by' => 0, 'updated_at' => now()]); // deleted_by system

            Session::forget('empShift');
            Session::forget('shiftDuplicateIds');

            return redirect('/shift-entry?date_from=' . $date_from . '&date_to=' . $date_to)
                ->with([
                    'success_message' => 'Employees shift has been successfully uploaded.',
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                ]);
        }
        return redirect()->back()->withErrors(['Something went wrong!']);


    }

    public function save_emp_shift(Request $request)
    {

        $this->validate($request, [
            'checked' => 'required|array',
        ]);

        $shift_code = DB::table('shift_code')->where('id', $request->input('shift_code'))->first();
        $date_from = $request->input('date_from_s');
        $date_to = $request->input('date_to_s');
        $emp_shift = array();
        $shiftDuplicateIds = array();

        $employees = DB::table('employees')
            ->selectRaw('employees.SysPK_Empl, employees.Name_Empl, employees.Department_Empl,employees.UserID_Empl,"' . $shift_code->shift_code . '" AS ShiftCode,"' . $shift_code->id . '" AS ShiftId')
            ->whereIn('SysPK_Empl', $request->input('checked'))
            ->get();
        $department = DB::table('department')->where('SysPK_Dept', $request->input('Department_Empl'))->first();
        $date1 = date_create($date_from);
        $date2 = date_create($date_to);
        $diff = date_diff($date1, $date2);

        for ($cnt = 0; $cnt <= $diff->days; $cnt++) {
            $plus_days = strval($cnt) . " days";
            $shift_date = date("Y-m-d", strtotime($plus_days, strtotime($date_from)));

            // Store employees shift
            foreach ($employees as $emp) {
                $checkerData = DB::table('shift_monitoring')->where('emp_id', $emp->SysPK_Empl)->where('shift_date', date("Y-m-d", strtotime($shift_date)))->where('is_deleted', 0)->first();
                // $checkerData = DB::table('shift_monitoring')->where('emp_id', $emp->SysPK_Empl)->where('shift_code_id', $shift_code->id)->where('shift_date', date("Y-m-d", strtotime($shift_date)))->where('is_deleted', 0)->first();
                
                if ($checkerData){
                    if($checkerData->allow_update == 0) continue; // skip this part 🔒

                    $shiftDuplicateIds[] = $checkerData->id; // store the shift id when found in the record
                }
                    

                $emp_shift[] = array(
                    "emp_id" => $emp->SysPK_Empl,
                    "emp_name" => $emp->Name_Empl,
                    "remarks" => "",
                    "shift_code_id" => $shift_code->id,
                    "shift_code" => $shift_code->shift_code,
                    "no_hr_break" => $shift_code->default_break_hrs,
                    "shift_date" => date("Y-m-d", strtotime($shift_date)),
                    "line_lead_id" => $department->Head_Dept_ID,
                    "dept_id" => $department->SysPK_Dept,
                    "department" => $department->Name_Dept,
                    'created_by' => session('user')->id,
                    "created_at" => date('Y-m-d H:i:s')
                );
            }
        }

        if (Session::has('shift_last_id_before_insert') && session('shift_last_id_before_insert') !== null)
            $shift_last_id_before_insert = session('shift_last_id_before_insert');
        else
            $shift_last_id_before_insert = null;

        if ($emp_shift) {
            // get the last record
            if ($shift_last_id_before_insert === null) {
                $shift_last_id_before_insert = DB::table('shift_monitoring')->orderBy('id', 'DESC')->first();
                if ($shift_last_id_before_insert)
                    $shift_last_id_before_insert = $shift_last_id_before_insert->id;
                else
                    $shift_last_id_before_insert = 0;
            }

            $insert_shift = DB::table('shift_monitoring')->insert($emp_shift);
            if ($insert_shift && count($shiftDuplicateIds))
                DB::table('shift_monitoring')->whereIn('id', $shiftDuplicateIds)->update(['is_deleted' => 1, 'deleted_by' => 0, 'updated_at' => now()]); // deleted_by system
        }

        Session::put([
            'new_shift_created' => true,
            'shift_last_id_before_insert' => $shift_last_id_before_insert
        ]);

        return redirect('/shift-entry?date_from=' . $date_from . '&date_to=' . $date_to)
            ->with(
                [
                    'success_message' => 'Employees shift successfully created!',
                    // 'list' => $list,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'no_of_days' => ($diff->d + 1)
                ]
            );
    }

    public function save_emp_shift_v2(Request $request)
    {

        set_time_limit(0);

        $this->validate($request, [
            'checked' => 'required|array',
        ]);

        // // Check date if valid
        // if (strtotime($request->input('date_from')) < strtotime(date('Y-m-d'))) {
        //     return Redirect::back()->withErrors(['Past date.'])->withInput();
        // }

        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $emp_shift = array();
        $shiftDuplicateIds = array();

        $emp_ids = $request->input('checked') ?? [];


        foreach ($emp_ids as $key => $emp_id) {
            
            $employee = DB::table('employees')
                ->select('employees.SysPK_Empl', 'employees.Name_Empl', 'employees.Department_Empl', 'employees.UserID_Empl', 'employees.dept_id')
                ->where('SysPK_Empl', $emp_id)
                ->first();

            $shift_code_ids = $request->input('shift_code')[$emp_id] ?? [];

            foreach ($shift_code_ids as $key => $shift_code_id) {

                $plus_days = strval($key) . " days";
                $shift_date = date("Y-m-d", strtotime($plus_days, strtotime($date_from)));
                $shift_code = DB::table('shift_code')->where('id', $shift_code_id)->first();
                $department = DB::table('department')->where('SysPK_Dept', $employee->dept_id)->first();

                $checkerData = DB::table('shift_monitoring')->where('emp_id', $emp_id)->where('shift_date', date("Y-m-d", strtotime($shift_date)))->where('is_deleted', 0)->first();
                
                if ($checkerData){
                    if($checkerData->allow_update == 0) continue; // skip this part 🔒
                    $shiftDuplicateIds[] = $checkerData->id; // store the shift id when found in the record
                }
                    
                $emp_shift[] = array(
                    "emp_id" => $emp_id,
                    "emp_name" => $employee->Name_Empl,
                    "remarks" => "",
                    "shift_code_id" => $shift_code_id,
                    "shift_code" => $shift_code->shift_code,
                    "no_hr_break" => $shift_code->default_break_hrs,
                    "shift_date" => date("Y-m-d", strtotime($shift_date)),
                    "line_lead_id" => $department->Head_Dept_ID,
                    "dept_id" => $department->SysPK_Dept,
                    "department" => $department->Name_Dept,
                    'created_by' => session('user')->id,
                    "created_at" => date('Y-m-d H:i:s')
                );
                
            }
        
        }

        if ($emp_shift) {
            $insert_shift = DB::table('shift_monitoring')->insert($emp_shift);
            if ($insert_shift && count($shiftDuplicateIds))
                DB::table('shift_monitoring')->whereIn('id', $shiftDuplicateIds)->update(['is_deleted' => 1, 'deleted_by' => 0, 'updated_at' => now()]); // deleted_by system
        }

        return redirect('/staff-shift?date_from=' . $date_from . '&date_to=' . $date_to)
            ->with(
                [
                    'success_message' => 'Employees shift successfully created!',
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                ]
            );
    }

    public function clear_shift_session()
    {
        Session::forget('new_shift_created');
        Session::forget('shift_last_id_before_insert');
        return redirect('/shift-entry');
    }

    public function get_emp_shift(Request $request)
    {

        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $emp_id = $request->input('emp_id');

        $shift_code = DB::table('shift_code')->where('is_active', 1)->get();

        $list = DB::table('shift_monitoring')
            ->join('employees', 'shift_monitoring.emp_id', '=', 'employees.SysPK_Empl')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
            ->select('shift_monitoring.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'shift_code.Description', 'outlet.outlet', 'outlet.outlet_id')
            ->where('shift_monitoring.is_deleted', 0)
            ->where('emp_id', "=", $emp_id)
            ->orderBy('employees.Name_Empl', 'asc')
            ->orderBy('shift_monitoring.shift_date', 'asc')
            ->groupBy('shift_monitoring.shift_date', 'shift_monitoring.emp_id', 'shift_monitoring.shift_code_id');

        if ($date_from && $date_to) {
            $list = $list->where('shift_date', ">=", date("Y-m-d", strtotime($date_from)))
                ->where('shift_date', "<=", date("Y-m-d", strtotime($date_to)));
        }
        $list = $list->get();

        return response()->json(
            [
                $list,
                $shift_code
            ]
        );
    }

    public function get_emp_shift_2(Request $request)
    {
        $emp_id = $request->input('emp_id');
        $year = $request->input('year') ?? date('Y');
        $month = $request->input('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year.'-'.$month));
        $date_to = date('Y-m-t', strtotime($year.'-'.$month));

        $shift_code = DB::table('shift_code')->where('is_active', 1)->get();

        $list = DB::table('shift_monitoring')
            ->join('employees', 'shift_monitoring.emp_id', '=', 'employees.SysPK_Empl')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
            ->select('shift_monitoring.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'shift_code.Description', 'outlet.outlet', 'outlet.outlet_id')
            ->where('shift_monitoring.is_deleted', 0)
            ->where('emp_id', "=", $emp_id)
            ->orderBy('employees.Name_Empl', 'asc')
            ->orderBy('shift_monitoring.shift_date', 'asc')
            // ->groupBy('shift_monitoring.shift_date', 'shift_monitoring.emp_id', 'shift_monitoring.shift_code_id')
            ->whereBetween('shift_monitoring.shift_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
            ->get();

        return response()->json(
            [
                $list,
                $shift_code
            ]
        );
    }

    public function update_emp_shift(Request $request)
    {

        $from_page = $request->input('from_page') ?? ""; // identifier 
        $date_from = $request->input('d_from');
        $date_to = $request->input('d_to');
        $date1 = date_create($date_from);
        $date2 = date_create($date_to);
        $diff = date_diff($date1, $date2);

        $row_cnt = count($request->input('u_shift_id'));
        for ($cnt = 0; $cnt < $row_cnt; $cnt++) {

            $shift_code1 = DB::table('shift_code')->where('id', $request->input('u_shift_code')[$cnt])->first();
            DB::table('shift_monitoring')
                ->where('id', $request->input('u_shift_id')[$cnt])
                ->where('allow_update', 1)
                ->update(
                    [
                        'shift_code_id' => $request->input('u_shift_code')[$cnt],
                        'shift_code' => $shift_code1->shift_code,
                        'no_hr_break' => $request->input('u_break_hr')[$cnt],
                        'remarks' => $request->input('u_remarks')[$cnt],
                        'status' => 0,
                        'updated_at' => now()
                        // set 0 by default
                    ]
                );
        }

        // Update status
        if (!is_null($request->input('u_status')) && is_array($request->input('u_status'))) {
            foreach ($request->input('u_status') as $data) {
                DB::table('shift_monitoring')
                    ->where('id', $data)
                    ->where('allow_update', 1)
                    ->update([
                            'status' => 1,
                            'updated_at' => now()
                        ]);
            }
        }

        if ($from_page == 'shift-monitoring') {
            return redirect()->back()->with('success_message', 'Employees shift successfully updated!');
        }
        $list = DB::TABLE('shift_monitoring')
            ->where('shift_date', ">=", date("Y-m-d", strtotime($date_from)))
            ->where('shift_date', "<=", date("Y-m-d", strtotime($date_to)))
            ->get();
        return redirect("/shift-entry?date_from=$date_from&date_to=$date_to")
            ->with(
                [
                    'success_message' => 'Employees shift successfully updated!',
                    'list' => $list,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'no_of_days' => ($diff->d + 1)
                ]
            );
    }

    public function update_emp_shift_v2(Request $request)
    {

        set_time_limit(0);

        $row_cnt = count($request->input('shift_code'));
        
        for ($cnt = 0; $cnt < $row_cnt; $cnt++) {

            $shift_code1 = DB::table('shift_code')->where('id', $request->input('shift_code')[$cnt])->first();
            DB::table('shift_monitoring')
                ->where('id', $request->input('u_shift_id')[$cnt])
                ->where('allow_update','=',1)
                ->update(
                    [
                        'shift_code_id' => $request->input('shift_code')[$cnt],
                        'shift_code' => $shift_code1->shift_code,
                        'status' => 0,
                        'updated_at' => now()
                        // set 0 by default
                    ]
                );
        }

        // Update status
        if (!is_null($request->input('status')) && is_array($request->input('status'))) {
            foreach ($request->input('status') as $data) {
                DB::table('shift_monitoring')
                    ->where('id', $data)
                    ->where('allow_update','=',1)
                    ->update([
                            'status' => 1,
                            'updated_at' => now()
                        ]);
            }
        }

        return Redirect::back()->with('success_message', "The employee's shift successfully updated.");

    }

    public function file_change_schedule_request(Request $r){

        $this->validate($r, [
            'date_from' => 'required',
            'date_to' => 'required',
            'remarks' => 'required',
            'shift_id' => 'required',
            'shift_code' => 'required',
        ]);

        $date_from = $r->input('date_from');
        $date_to = $r->input('date_to');
        $shift_id = $r->input('shift_id');
        $shift_code_id = $r->input('shift_code');
        $remarks = $r->input('remarks');

        $changeSched = DB::table('emp_pass_slip')->insert([
            'emp_id' => session('user')->emp_id,
            'emp_name' => session('employee')->Name_Empl,
            'ps_code' => 2,
            'ps_date_from' => date('Y-m-d', strtotime($date_from)),
            'ps_date_to' => date('Y-m-d', strtotime($date_to)),
            'shift_code_id' => $shift_code_id,
            'time_type' => 3,
            'remarks' => $remarks,
            'ps_status' => 0,
            'total_time' => 0,
            'emp_sched_id' => $shift_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($changeSched)
            return Redirect::back()->with('success_message', 'The shift schedule change request has been successfully submitted.');
        else
            return Redirect::back()->with('success_message', 'Something went wrong!');
    }

    public function approver_change_schedule(Request $r){

        $this->validate($r, [
            'shift_code' => 'required',
            'remarks' => 'required',
        ]);

        $shift_monitoring_id = $r->input('shift_monitoring_id');
        $shift_code_id = $r->input('shift_code');
        $remarks = $r->input('remarks');

        if(!$shift_monitoring_id) return Redirect::back()->withErrors(["Something went wrong!"])->withInput();

        $changeSched = DB::table('shift_monitoring')
            ->where('id', $shift_monitoring_id)
            ->where('allow_update', 1)
            ->where('is_deleted', 0)
            ->update(
                ['shift_code_id' => $shift_code_id, 'status' => 1, 'remarks' => $remarks, 'updated_at' => now()]
            );

        if ($changeSched)
            return Redirect::back()->with('success_message', 'The shift schedule has been successfully changed.');
        else
            return Redirect::back()->with('success_message', 'Something went wrong!');
    }

}