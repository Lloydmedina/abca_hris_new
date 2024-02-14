<?php

namespace App\Http\Controllers\Overtime;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Input;

class OvertimeController extends Controller
{

    public function index(Request $r){

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        
        // Initial query
        $overtime = DB::table('approved_ot')
            ->join('employees', 'approved_ot.employee_id', '=', 'employees.SysPK_Empl')
            ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('approved_ot.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'approvers.approver_1_emp_id',
            'approvers.approver_2_emp_id')
            ->where('approved_ot.is_deleted', 0);
            // ->orderBy('approved_ot.stat', 'desc')
            // ->orderBy('approved_ot.dtr_date', 'desc')
            // ->orderBy('employees.LastName_Empl', 'asc')
            // ->orderBy('employees.FirstName_Empl', 'asc');
            
        // For normal user
        if(session('user')->employee_type_id == 5)
            $overtime = $overtime->where('employee_id', session('user')->emp_id);
        
        // // invalid date inputed
        // if (strtotime($date_from) > strtotime($date_to)) {

        //     return view('Overtime.overtime', ['overtime' => $overtime])->withErrors(['Invalid date range']);

        // }// invalid date inputed
        // elseif(strtotime($date_from) > strtotime(date('Y-m-d')) || strtotime($date_to) > strtotime(date('Y-m-d'))){

        //     return view('Overtime.overtime', ['overtime' => $overtime])->withErrors(['Date must be less than or equals to future date']);
            
        // }// return based on searched date
        // else
        
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        if($date_from && $date_to){

            $date = array($date_from, $date_to);
            $overtime = $overtime->whereBetween('dtr_date', $date);
        }

        $overtime = $overtime->orderBy('OT_ID', 'desc')->get();

        $overtime_temp = collect();
        if(count($overtime) > 0) {
            foreach($overtime as $i => $row){
                $emp = DB::table('employees')
                        ->select('Name_Empl as approver_name', 'UserID_Empl as approver_emp_id')
                        ->where('UserID_Empl', $row->approver_1_emp_id)
                        ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                        ->get();
                $overtime_temp[$i]= $row;
                if($emp) $overtime_temp[$i]->approvers = $emp->toArray();
            }
        }
        $overtime = $overtime_temp;
        
        return view('Overtime.overtime', [
            'overtime' => $overtime,
            'date_from' => $date_from,
                'date_to' => $date_to
        ]);
    }

    public function my_overtime(Request $r){
        
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status

        $overtime = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        // Initial query
        $overtime = DB::table('approved_ot')
            ->join('employees', 'approved_ot.employee_id', '=', 'employees.SysPK_Empl')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->leftJoin('system_lock_status', 'approved_ot.dtr_date', '=', 'system_lock_status.lock_date')
            ->select('approved_ot.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'employees_rate.Position_Empl', 'system_lock_status.lock_date', 'system_lock_status.is_lock')
            ->where('approved_ot.is_deleted', 0)
            ->where('employee_id', session('user')->emp_id);
        
        if($ps_status !== '4') $overtime = $overtime->where('approved_ot.is_approved', $ps_status); 
        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $overtime = $overtime->whereBetween('dtr_date', $date);
        }
        $overtime = $overtime->orderBy('approved_ot.OT_ID', 'desc')->get();

        return view('NormalEmployees.my_overtime', [
                'overtime' => $overtime,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]);
    }

    public function overtime_request(Request $r){

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 
        
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status
        $display_by = $r->input('display_by') ?? '10'; // default 10

        $overtime = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
        
        // Initial query
        $overtime = DB::table('approved_ot')
            ->join('employees', 'approved_ot.employee_id', '=', 'employees.SysPK_Empl')
            ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->leftJoin('system_lock_status', 'approved_ot.dtr_date', '=', 'system_lock_status.lock_date')
            ->select(
                DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
                'approved_ot.*', 
                'employees.picture_path',
                'employees.gender', 
                // 'employees.Name_Empl', 
                'employees.SysPK_Empl', 
                'employees_rate.Position_Empl',
                'approvers.approver_1_emp_id',
                'approvers.approver_2_emp_id',
                'system_lock_status.lock_date', 'system_lock_status.is_lock'
                )
            ->where('approved_ot.is_deleted', 0)
            ->groupBy('approved_ot.OT_ID');
        
        if($ps_status !== '4') $overtime = $overtime->where('is_approved', $ps_status);

        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $overtime = $overtime->whereBetween('dtr_date', $date);
        }

        // CHECK IF THE USER IS APPROVER OF THIS EMPLOYEE
        if(session('is_approver')){
            $emp_id_numbers = array();
            // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
            $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

            foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
            $overtime = $overtime->whereIn('employee_number', $emp_id_numbers);
        }

        // $overtime = $overtime->orderBy('approved_ot.OT_ID', 'desc')->get();

        if($display_by == 'all'){
            $overtime = $overtime->orderBy('approved_ot.OT_ID', 'desc')->get();
            $paginationLinks = null;
        }
        else{
            $overtime = $overtime->orderBy('approved_ot.OT_ID', 'desc')->paginate($display_by);
            $paginationLinks = $overtime->appends(request()->query())->links();
        }

        $overtime_temp = collect();
        if(count($overtime) > 0) {
            foreach($overtime as $i => $row){
                $emp = DB::table('employees')
                        ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
                        ->where('UserID_Empl', $row->approver_1_emp_id)
                        ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                        ->get();
                $overtime_temp[$i]= $row;
                if($emp) $overtime_temp[$i]->approvers = $emp->toArray();
            }
        }
        
        $overtime = $overtime_temp;
        
        return view('Overtime.overtime_request', [
                'overtime' => $overtime,
                'date_from' => $date_from,
                'date_to' => $date_to,
                "paginationLinks" => $paginationLinks
            ]);
    }

    public function add_overtime_view(){

        $outletApprover = array();
        
        if(session('employee')){
            $outletApprover = DB::table('outlet')
                        ->select('outlet_id')
                        ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
                        ->get()->toArray();
        
            if(!count($outletApprover)) return view("errors.404");
        }

        $dept="";
        if (session('user')->employee_type_id == 6) {
               $emp =  DB::table('employees')
                        ->where('SysPK_Empl','=',session('user')->emp_id)
                        ->first();
            $dept = $emp->Department_Empl;
        }

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.*', 'employees_rate.Position_Empl')
            ->where('employees.Department_Empl','like','%'.$dept.'%')
            ->get();

        return view('Overtime.add_overtime', [
            'employees' => $employees,
        ]);

    }

    public function file_overtime(){
        
        return view('NormalEmployees.file_overtime');
    }

    // Get employee
    public function get(Request $request){

        $this->validate($request, [
            'checked' => 'required|array',
        ]);

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.SysPK_Empl', 'employees.Name_Empl', 'employees.Department_Empl', 'employees_rate.Position_Empl','employees.UserID_Empl')
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
        $emp_ot = array();
        // Store employees overtime
        for ($i=0; $i < count($request->input('employee_id')); $i++) { 
            $emp_ot[] = array(
                    "employee_id" => $request->input('employee_id')[$i], 
                    "employee_number" => $request->input('employee_number')[$i],
                    "remarks" => $request->input('remarks')[$i],
                    "dtr_date" => date('Y-m-d', strtotime($request->input('date_selected'))),
                    "Time_Start" => date('H:i:s', strtotime($request->input('time_from'))),
                    "Time_End" => date('H:i:s', strtotime($request->input('time_to'))),
                    "OT_Hours" => $request->input('total_hours')//temporary
            );
        }
        
        DB::table('approved_ot')->insert($emp_ot);

        return redirect('/overtime?')->with('success_message', 'Employees overtime successfully created!');

    }

    public function delete(Request $request){

        $id = $this->__get_orig_id($request->input('id'));

        // check application
        // json
        // if($checkApplication = $this->checkApplication(null, 'OT', 'R', $id))
        //     return response()->json(array("code" => 0, "message" => $checkApplication));

        DB::table('approved_ot')
            ->where('OT_ID', $id)
            ->update([
                'is_deleted' => 1,
                "deleted_by" => session('user')->id,
            ]);
        
        return response()->json(array("code" => 1, "message" => "Overtime has been successfully deleted."));

    }

    public function approved_ot(Request $request){
        

        $row_cnt = count($request->input('ot_id'));
        $update = array();
        for ($i=0; $i < $row_cnt ; $i++) { 
            DB::TABLE('approved_ot')
                ->WHERE('OT_ID','=',$request->input('ot_id')[$i])
                ->UPDATE(
                    [
                        'stat'=>'APPROVED'
                    ]);
        }

        return redirect('/overtime?')->with('success_message', 'Employees overtime successfully approved!');
    }

    public function file_overtime_request(Request $r){
        
        $this->validate($r, [
            'employee_id' => 'required',
            'employee_number' => 'required',
            'remarks' => 'required',
            'date_selected' => 'required',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);

        $employee_id = $r->input('employee_id');
        $date_selected = $r->input('date_selected');
        $time_from = $r->input('time_from');
        $time_to = $r->input('time_to');
        $total_hours = $r->input('total_hours');

        // check application
        if($return = $this->checkApplication($date_selected, 'OT', 'I', null))
            return Redirect::back()->withErrors([$return])->withInput();

        // $time1 = strtotime($time_from);
        // $time2 = strtotime($time_to);
        // $difference = round(abs($time2 - $time1) / 3600,2);
        
        // Validate the selected date
        if(strtotime($r->input('date_selected')) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['Selected date must be less than or equals to current date!'])->withInput();
        
        if(DB::table('approved_ot')->where('is_approved', '!=', 2)->where('employee_id',$employee_id)->where('is_deleted',0)->where('dtr_date', date('Y-m-d', strtotime($date_selected)))->first()){
            return Redirect::back()->withErrors(["Overtime has already been submitted for ".date('M d, Y', strtotime($date_selected)).". Please delete any previous entries to avoid confusion."])->withInput();
        }

        $total_hours = $this->calculateTotalOT($time_from, $time_to);

        // STORAGE FOR EMPLOYEES OVERTIME
        $emp_ot = array(
            "employee_id" => $employee_id, 
            "employee_number" => $r->input('employee_number'),
            "remarks" => $r->input('remarks'),
            "dtr_date" => date('Y-m-d', strtotime($date_selected)),
            "Time_Start" => date('H:i:s', strtotime($time_from)),
            "Time_End" => date('H:i:s', strtotime($time_to)),
            "OT_Hours" => $total_hours,
            "submitted_by" => Session('user')->id,
            "created_at" => date('Y-m-d H:i:s')
        );
        
        $overtime = DB::table('approved_ot')->insert($emp_ot);
        if($overtime)
            return redirect('/my-overtime')->with('success_message', 'Your overtime request has been filed!');
        else
            return Redirect::back()->withErrors(["Something went wrong!"])->withInput();
    }

    public function file_overtime_to_update(Request $r){

        $emp_id = session('employee')->SysPK_Empl ?? null;
        $id = $this->__get_orig_id($r->input('id'));

        $overtime = DB::table('approved_ot')
                    ->where('OT_ID', $id)
                    ->where('employee_id', $emp_id)
                    ->where('is_deleted', 0)
                    ->first();
        
        if(!$overtime) return redirect('/file-overtime');

        // check application
        $checkApplication = $this->checkApplication(null, 'OT', 'U', $id);

        return view('NormalEmployees.file_overtime_to_update', [
            'overtime' => $overtime,
            'checkApplication' => $checkApplication
        ]);

    }

    public function file_overtime_to_update_approver(Request $r){

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
                if(!in_array($employee->UserID_Empl, $emp_id_numbers)) return redirect('/overtime-request')->withErrors(['Something went wrong!']);
            }
            else return redirect('/overtime-request')->withErrors(['Something went wrong! Employee not found.']);
        }
        else return redirect('/overtime-request')->withErrors(['Something went wrong!']);

        $overtime = DB::table('approved_ot')->where('OT_ID', $id)->where('employee_id', $emp_id)->where('is_deleted', 0)->first();
        if(!$overtime) return redirect('/overtime-request');

        // check application
        // $checkApplication = $this->checkApplication(null, 'OT', 'U', $id);

        return view('NormalEmployees.file_overtime_to_update', [
            'overtime' => $overtime,
            'employee' => $employee,
            // 'checkApplication' => $checkApplication,
            'checkApplication' => null,
            'approver_update' => 1
        ]);
    }

    public function file_overtime_update(Request $r){

        $this->validate($r, [
            'remarks' => 'required',
            'date_selected' => 'required',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);

        $id = $r->input('id') ?? null;
        
        if(!$id) return Redirect::back()->withErrors(["Something went wrong!"])->withInput();
        
        $date_selected = $r->input('date_selected');
        $time_from = $r->input('time_from');
        $time_to = $r->input('time_to');
        $total_hours = $r->input('total_hours');

        // // check application
        // if($checkApplication = $this->checkApplication($date_selected, 'OT', 'U', $id))
        //     return Redirect::back()->withErrors([$checkApplication])->withInput();

        $approver_update = $r->input('approver_update') ?? null;

        // $time1 = strtotime($time_from);
        // $time2 = strtotime($time_to);
        // $difference = round(abs($time2 - $time1) / 3600,2);
       
        if(!$approver_update){

            // check application
            if($checkApplication = $this->checkApplication($date_selected, 'OT', 'U', $id))
                return Redirect::back()->withErrors([$checkApplication])->withInput();

            // Validate the selected date
            if(strtotime($r->input('date_selected')) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['Selected date must be less than or equals to current date!'])->withInput();
            
            if(DB::table('approved_ot')
                ->where('OT_ID', '!=', $id)
                ->where('is_approved', '!=', 2)
                ->where('is_deleted',0)
                ->where('employee_id', session('user')->emp_id)
                ->where('dtr_date', date('Y-m-d', strtotime($date_selected)))
                ->first()){
                return Redirect::back()->withErrors(["Overtime has already been submitted for ".date('M d, Y', strtotime($date_selected)).". Please delete any previous entries to avoid confusion."])->withInput();
            }
        }

        $total_hours = $this->calculateTotalOT($time_from, $time_to);
        
        // STORAGE FOR EMPLOYEES OVERTIME
        $data = array(
            "remarks" => $r->input('remarks'),
            "dtr_date" => date('Y-m-d', strtotime($date_selected)),
            "Time_Start" => date('H:i:s', strtotime($time_from)),
            "Time_End" => date('H:i:s', strtotime($time_to)),
            // "OT_Hours" => $difference,
            "OT_Hours" => $total_hours,
            "updated_at" => date('Y-m-d H:i:s'),
            "is_approved" => 0,
            "approved_by" => null,
            "rejected_by" => null,
            "app1_approved_on" => null,
            "app2_approved_on" => null
        );

        $route = 'my_overtime';

        if($approver_update == 1){
            unset($data['is_approved']);
            unset($data['approved_by']);
            unset($data['rejected_by']);
            unset($data['app1_approved_on']);
            unset($data['app2_approved_on']);
            $route = 'overtime_request';
        }
        
        DB::table('approved_ot')->where('OT_ID', $id)->update($data);
        return redirect()->back()->with('success_message', 'Overtime log was successfully updated.');
    }

    // 0 = pending
    // 1 = approved
    // 2 = rejected
    // 3 = partially approved
    public function approve(Request $request){
        
        $id = $this->__get_orig_id($request->input('id'));

        // check application
        // json
        // if($checkApplication = $this->checkApplication(null, 'OT', 'U', $id))
        //     return response()->json(array("code" => 0, "message" => $checkApplication));

        $overtime = DB::table('approved_ot')->where('OT_ID', $id)->first();

        $approvers = explode(",",$overtime->approved_by);
        
        // Check first if the approver already approve the request
        if($overtime->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && ($overtime->is_approved == 1 || $overtime->is_approved == 3)){
            return response()->json(array("code" => 0, "message" => "You already approved this request."));
        }
        // Check if the is_approved is 0 then update to 1
        elseif($overtime->is_approved == 0){
          DB::table('approved_ot')->where('OT_ID', $id)
              ->update([
                'is_approved' => 3,
                'rejected_by' => null,
                'approved_by' => session('employee')->UserID_Empl,
                'app1_approved_on' => date('Y-m-d H:i:s')
              ]);
          return response()->json(array("code" => 3, "message" => "Overtime partially approved"));
        }
        elseif($overtime->is_approved == 3){
          DB::table('approved_ot')->where('OT_ID', $id)
            ->update([
                'is_approved' => 1,
                'reason_remarks' => 'Approved',
                'stat' => 'APPROVED',
                'rejected_by' => null,
                'approved_by' => $overtime->approved_by.','.session('employee')->UserID_Empl,
                'date_approved' => date('Y-m-d H:i:s'),
                'app2_approved_on' => date('Y-m-d H:i:s')
            ]);
          return response()->json(array("code" => 1, "message" => "Overtime was successfully approved"));
        }elseif($overtime->is_approved == 2){
          return response()->json(array("code" => 2, "message" => "Overtime had already been rejected"));
        }
    }

    public function reject(Request $r){
        
        $reason_remarks = $r->input('reason_remarks');
        if(session('is_approver')){
            $id = $this->__get_orig_id($r->input('id'));

            // check application
            // json
            // if($checkApplication = $this->checkApplication(null, 'OT', 'U', $id))
            //     return response()->json(array("code" => 0, "message" => $checkApplication));

            // get the employee id first from leave table
            $ot = DB::table('approved_ot')->where('OT_ID', $id)->first();
            // get the approvers from approvers table
            $approver = DB::table('approvers')->where('emp_id', $ot->employee_number)->first();
            // check if what type of approver
            $current_approver = 'app1_approved_on';
            if($approver->approver_1_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app1_approved_on';
            elseif($approver->approver_2_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app2_approved_on';
            
            $reject = DB::table('approved_ot')->where('OT_ID', $id)
                ->update([
                    'is_approved' => 2,
                    'reason_remarks' => $reason_remarks,
                    'rejected_by' => session('employee')->UserID_Empl,
                    $current_approver => date('Y-m-d H:i:s')
                ]);
            if($reject) return response()->json(array("code" => 1, "message" => "Overtime rejected"));
        }
        else
          return response()->json(array("code" => 0, "message" => "Something went wrong!"));
        
    }

    public function calculateTotalOT($time_from, $time_to){
        // Convert the input time strings to Unix timestamps
        $time1 = strtotime($time_from);
        $time2 = strtotime($time_to);
        
        // Calculate the time difference in seconds
        $diff = $time2 - $time1;
        
        // Calculate the number of whole hours in the time difference
        $hours = floor($diff / 3600);
        
        // Calculate the remaining minutes after subtracting whole hours
        $minutes = ($diff % 3600) / 60;
    
        // Check if there are no remaining minutes
        if ($minutes == 0) {
            $number_of_hours = $hours;
        } else {
            // Calculate the total hours, including minutes as fractions
            $number_of_hours = $hours + $minutes / 60;
            
            // Check if the result is negative and adjust accordingly
            if ($number_of_hours < 0)
                $number_of_hours = $number_of_hours + 1;
        }
    
        // Check if the result is NaN and set it to 0
        if (is_nan($number_of_hours))
            $number_of_hours = 0;
    
        // Check if the result is negative and adjust accordingly for 24-hour format
        if ($number_of_hours < 0)
            $number_of_hours = $number_of_hours + 24;
    
        // Format the result with 2 decimal places and return it
        return number_format($number_of_hours, 2);
    }
    
}