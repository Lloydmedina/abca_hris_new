<?php

namespace App\Http\Controllers\RequestAF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Input;

class RequestAttendanceForm extends Controller
{

    public static function getTotalPendingCount($psCode = 0, $empIdNumbers) {

        set_time_limit(500);
        
        $pending = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->whereIn('employees.UserID_Empl', $empIdNumbers)
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', $psCode)
            ->where('emp_pass_slip.ps_status', 0)
            ->where(function ($query) {
                $query->whereNull('emp_pass_slip.approved_by')
                    ->orWhere('emp_pass_slip.approved_by', '!=', session('employee')->UserID_Empl);
            })
            ->get();

        $pa = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->join('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            ->whereIn('employees.UserID_Empl', $empIdNumbers)
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', $psCode)
            ->where('emp_pass_slip.ps_status',3)
            ->where(function ($query) {
                $query->whereNotNull('emp_pass_slip.approved_by')
                    ->where('emp_pass_slip.approved_by', '!=', session('employee')->UserID_Empl);
            })
            ->get();
        
        // Get the approved by the previous approvers
        $pa_not_mine = 0;
        if(count($pa) > 0){
            foreach ($pa as $value){
                if($value->approved_by != $value->approver_1_emp_id && $value->approver_1_emp_id == session('employee')->UserID_Empl){
                    $pa_not_mine++;
                }
            }
        }
        
        return count($pending) + count($pa) - $pa_not_mine;
    }

    // TIME PASS SLIP
    public function my_time_pass(Request $r){

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status

        $timePass = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $timePass = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->leftJoin('system_lock_status', 'emp_pass_slip.ps_date_from', '=', 'system_lock_status.lock_date')
            ->select('emp_pass_slip.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'system_lock_status.lock_date', 'system_lock_status.is_lock') // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 0)
            ->where('emp_id', session('user')->emp_id);

        if($ps_status !== '4') $timePass = $timePass->where('ps_status', $ps_status);
        
        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $timePass = $timePass->whereBetween('ps_date_from', $date);
        }
        $timePass = $timePass->orderBy('emp_pass_slip.id', 'desc')->get();
        
        return view('NormalEmployees.my_time_pass', [
            'timePass' => $timePass,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    public function file_time_pass(){

        $emp_id_numbers = array();
        if(session('is_approver')){
            $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    // ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

            foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
        }
        else{
            abort(403);
        }

        $staffs = DB::table('employees')
                    ->whereIn('UserID_Empl', $emp_id_numbers)
                    ->where('UserID_Empl', '!=', null)
                    ->where('UserID_Empl', '!=', '')
                    ->whereNotIn('Status_Empl', ['RESIGNED', 'AWOL', 'TERMINATED'])
                    ->get();
        
        return view('NormalEmployees.file_time_pass',[
            'staffs' => $staffs,
        ]);
    }

    public function file_time_pass_request(Request $r){
        
        $this->validate($r, [
            'date_selected' => 'required',
            'time_selected' => 'required',
            'time_type' => 'required',
            'emp_id' => 'required',
        ]);

        $time = $r->input('time_selected');
        $date = $r->input('date_selected');
        $timeType = $r->input('time_type');
        $remarks = $r->input('remarks');
        $emp_id = $r->input('emp_id');

        // check application
        if($checkApplication = $this->checkApplication($date, 'PS', 'I', null))
            return Redirect::back()->withErrors([$checkApplication])->withInput();

        // Check date if valid
        if(strtotime($date) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['The chosen date must be less than or equal to the current date.'])->withInput();
        
        // if(DB::table('emp_pass_slip')->where('ps_status', '!=', 2)->where('ps_code',0)->where('is_deleted',0)->where('emp_id', session('user')->emp_id)->where('ps_date_from', date('Y-m-d', strtotime($date)))->where('time_type', $timeType)->first()){
        if(DB::table('emp_pass_slip')->where('ps_status', '!=', 2)->where('ps_code',0)->where('is_deleted',0)->where('emp_id', $emp_id)->where('ps_date_from', date('Y-m-d', strtotime($date)))->where('time_type', $timeType)->first()){
            return Redirect::back()->withErrors(["Time pass has already been submitted for ".date('M d, Y', strtotime($date)).". Please delete any previous entries to avoid confusion."])->withInput();
        }

        $staff = DB::table('employees')->where('SysPK_Empl', $emp_id)->first();
        
        $time_pass = DB::table('emp_pass_slip')->insert([
            'emp_id' => $emp_id,
            'emp_name' => $staff->Name_Empl,
            'ps_status' => 3, // 1 approved before
            'status_remarks' => 'Partially Approved',
            'approved_by' => session('employee')->UserID_Empl,
            'Approver_emp_name' => session('employee')->Name_Empl,
            'Approver_id' => session('employee')->UserID_Empl, // approver 1
            'app1_approved_on' => date('Y-m-d H:i:s'),
            'updated_at' => now(),

            // 'emp_id' => session('user')->emp_id,
            // 'emp_name' => session('employee')->Name_Empl,
            'ps_code' => 0,
            'ps_date_from' => date('Y-m-d', strtotime($date)),
            'ps_date_to' => date('Y-m-d', strtotime($date)),
            'time' => date('H:i:s', strtotime($time)),
            'time_type' => $timeType,
            'remarks' => $remarks,
            // 'ps_status' => 0,
            'total_time' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // if($time_pass) return redirect()->route('my_time_pass')->with('success_message', 'The pass slip was successfully saved.');
        if($time_pass) return redirect()->route('time_pass_request')->with('success_message', 'The pass slip was successfully saved.');
        else return Redirect::back()->withErrors(["Something went wrong!"])->withInput();
    }

    public function file_time_pass_to_update(Request $r){
        abort(403);

        $emp_id = session('employee')->SysPK_Empl ?? null;
        $id = $this->__get_orig_id($r->input('id'));
        
        $timePass = DB::table('emp_pass_slip')->where('emp_id', $emp_id)->where('id', $id)->where('is_deleted', 0)->where('ps_code', 0)->first();
        if(!$timePass) return redirect('/file-time-pass');
        
        // check application
        $checkApplication = $this->checkApplication(null, 'PS', 'U', $id);
        
        return view('NormalEmployees.file_time_pass_to_update', [
            'timePass' => $timePass,
            'checkApplication' => $checkApplication
        ]);
    }

    public function file_time_pass_to_update_approver(Request $r){

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
                if(!in_array($employee->UserID_Empl, $emp_id_numbers)) return redirect('/time-pass-request')->withErrors(['Something went wrong!']);
            }
            else return redirect('/time-pass-request')->withErrors(['Something went wrong! Employee not found.']);
        }
        else return redirect('/time-pass-request')->withErrors(['Something went wrong!']);

        $timePass = DB::table('emp_pass_slip')->where('emp_id', $emp_id)->where('id', $id)->where('is_deleted', 0)->where('ps_code', 0)->first();
        if(!$timePass) return redirect('/time-pass-request')->withErrors(['Something went wrong!']);

        // check application
        // $checkApplication = $this->checkApplication(null, 'PS', 'U', $id);
        
        return view('NormalEmployees.file_time_pass_to_update', [
            'timePass' => $timePass,
            'employee' => $employee,
            'approver_update' => 1,
            // 'checkApplication' => $checkApplication
            'checkApplication' => null
        ]);

    }
    
    public function file_time_pass_update(Request $r){

        $this->validate($r, [
            'date_selected' => 'required',
            'time_selected' => 'required',
            'time_type' => 'required',
        ]);

        $id = $r->input('id') ?? null;
        if(!$id) return Redirect::back()->withErrors(["Something went wrong!"])->withInput();
        
        $time = $r->input('time_selected');
        $date = $r->input('date_selected');
        $timeType = $r->input('time_type');
        $remarks = $r->input('remarks');

        // // check application
        // if($checkApplication = $this->checkApplication($date, 'PS', 'U', $id))
        //     return Redirect::back()->withErrors([$checkApplication])->withInput();

        $approver_update = $r->input('approver_update') ?? null;

        // Run validation
        if(!$approver_update){

            // check application
            if($checkApplication = $this->checkApplication($date, 'PS', 'U', $id))
                return Redirect::back()->withErrors([$checkApplication])->withInput();
            
            // Check date if valid
            if(strtotime($date) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['The chosen date must be less than or equal to the current date.'])->withInput();
            
            if(DB::table('emp_pass_slip')
                ->where('id', '!=', $id)
                ->where('ps_status', '!=', 2)
                ->where('ps_code',0)
                ->where('is_deleted',0)
                ->where('emp_id', session('user')->emp_id)
                ->where('ps_date_from', date('Y-m-d', strtotime($date)))
                ->where('time_type', $timeType)
                ->first()){
                return Redirect::back()->withErrors(["Time pass has already been submitted for ".date('M d, Y', strtotime($date)).". Please delete any previous entries to avoid confusion."])->withInput();
            }
        }

        $data = array(
            'ps_date_from' => date('Y-m-d', strtotime($date)),
            'ps_date_to' => date('Y-m-d', strtotime($date)),
            'time' => date('H:i:s', strtotime($time)),
            'time_type' => $timeType,
            'remarks' => $remarks,
            'ps_status' => 0,
            'Approver_id' => null,
            'approved_by' => null,
            "app1_approved_on" => null,
            "app2_approved_on" => null,
            'updated_at' => now()
        );
        $route = 'my_time_pass';

        if($approver_update == 1){
            unset($data['ps_status']);
            unset($data['Approver_id']);
            unset($data['approved_by']);
            unset($data['app1_approved_on']);
            unset($data['app2_approved_on']);
            $route = 'time_pass_request';
        }
        
        DB::table('emp_pass_slip')->where('id', $id)->update($data);
        
        return redirect()->back()->with('success_message', 'Pass slip was successfully updated.');
    }

    public function delete_time_pass(Request $r){
        
        $id = $this->__get_orig_id($r->input('id'));

        // check application
        // json
        if($checkApplication = $this->checkApplication(null, 'PS', 'R', $id))
            return response()->json(array("code" => 0, "message" => $checkApplication));

        DB::table('emp_pass_slip')
            ->where('id', $id)
            ->update([
                'is_deleted' => 1,
                "deleted_by" => session('user')->id,
                'updated_at' => now()
            ]);
        
        return response()->json(array("code" => 1, "message" => "Pass slip has been deleted."));

    }

    public function time_pass_request(Request $r){

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 
        
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status
        $display_by = $r->input('display_by') ?? '10'; // default 10

        $timePass = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
        
        $timePass = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            ->leftJoin('system_lock_status', 'emp_pass_slip.ps_date_from', '=', 'system_lock_status.lock_date')
            // ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select(
                DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
                'emp_pass_slip.*', 
                // 'employees.Name_Empl' , 
                'employees.picture_path',
                'employees.gender',
                'employees.SysPK_Empl', 
                'employees.UserID_Empl',
                'approvers.approver_1_emp_id',
                'approvers.approver_2_emp_id',
                'system_lock_status.lock_date', 'system_lock_status.is_lock',
                ) // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 0)
            ->whereNotIn('employees.Status_Empl', ['RESIGNED', 'AWOL', 'TERMINATED'])
            ->groupBy('emp_pass_slip.id');
        
        if($ps_status !== '4') $timePass = $timePass->where('ps_status', $ps_status);
        
        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $timePass = $timePass->whereBetween('ps_date_from', $date);
        }

        // CHECK IF THE USER IS APPROVER OF THIS EMPLOYEE
        if(session('is_approver')){
            $emp_id_numbers = array();

            $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

            foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
            $timePass = $timePass->whereIn('employees.UserID_Empl', $emp_id_numbers);
        }

        if($display_by == 'all'){
            $timePass = $timePass->orderBy('emp_pass_slip.id', 'desc')->get();
            $paginationLinks = null;
        }
        else{
            $timePass = $timePass->orderBy('emp_pass_slip.id', 'desc')->paginate($display_by);
            $paginationLinks = $timePass->appends(request()->query())->links();
        }
        
        $timePass_temp = collect();
        if(count($timePass) > 0) {
            foreach($timePass as $i => $row){
                $emp = DB::table('employees')
                        ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
                        ->where('UserID_Empl', $row->approver_1_emp_id)
                        ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                        ->get();
                $timePass_temp[$i]= $row;
                if($emp) $timePass_temp[$i]->approvers = $emp->toArray();
            }
        }
        $timePass = $timePass_temp;
        
        return view('RequestAF.time_pass_request', [
            'timePass' => $timePass,
            'date_from' => $date_from,
            'date_to' => $date_to,
            "paginationLinks" => $paginationLinks
        ]);
    }

    // approve multiple pass slip
    public function approves_multiple_pass_slip(Request $r){
        set_time_limit(0);
        try {
            $ids = $r->input('approves') ?? [];
        
            if (count($ids)) {
                $success = false;
        
                foreach ($ids as $id) {
                    $id = trim($id);
                    $timePass = DB::table('emp_pass_slip')->where('id', $id)->first();
                    $approvers = explode(",", $timePass->approved_by);
        
                    // Check if the approver already approved the request
                    if ($timePass->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && in_array($timePass->ps_status, [1, 3])) {
                        continue;
                    }
        
                    // Update the pass slip status
                    if (in_array($timePass->ps_status, [0, null])) {
                        DB::table('emp_pass_slip')->where('id', $id)->update([
                            'ps_status' => 3,
                            'status_remarks' => 'Partially Approved',
                            'approved_by' => session('employee')->UserID_Empl,
                            'Approver_emp_name' => session('employee')->Name_Empl,
                            'Approver_id' => session('employee')->UserID_Empl,
                            'app1_approved_on' => now(),
                            'updated_at' => now()
                        ]);
                    } elseif ($timePass->ps_status == 3) {
                        DB::table('emp_pass_slip')->where('id', $id)->update([
                            'ps_status' => 1,
                            'status_remarks' => 'Approved',
                            'Approver_emp_name' => $timePass->Approver_emp_name . ' & ' . session('employee')->Name_Empl,
                            'approved_by' => $timePass->approved_by . ',' . session('employee')->UserID_Empl,
                            'date_approved' => now(),
                            'app2_approved_on' => now(),
                            'updated_at' => now()
                        ]);
        
                        $pass_slip = DB::table('emp_pass_slip')->where('id', $id)->first();
                        $employee = DB::table('employees')->where('SysPK_Empl', '=', $pass_slip->emp_id)->first();
                        $dtr = DB::table('hr_emp_attendance')
                            ->join('employees', 'hr_emp_attendance.employee_number', '=', 'employees.UserID_Empl')
                            ->select('hr_emp_attendance.*')
                            ->where('hr_emp_attendance.dtr_date', '=', date('Y-m-d', strtotime($pass_slip->ps_date_from)))
                            ->where('employees.SysPK_Empl', '=', $pass_slip->emp_id)
                            ->first();
        
                        // if ($dtr) {
                        //     // $time_slip = $pass_slip->ps_date_from . ' ' . $pass_slip->time;
                        //     $columnToUpdate = $pass_slip->time_type == 0 ? 'in_am' : 'out_pm';
        
                        //     DB::table('hr_emp_attendance')
                        //         ->where('employee_number', '=', $employee->UserID_Empl)
                        //         ->where('dtr_date', '=', date('Y-m-d', strtotime($pass_slip->ps_date_from)))
                        //         ->update([$columnToUpdate => now(), 'entry_type' => 1]);
                        // }
                    }
        
                    $success = true;
                }
        
                if ($success) {
                    return redirect()->back()->with('success_message', 'The approval process was successful');
                } else {
                    return redirect()->back()->withErrors(['Oops! The approval process was unsuccessful.'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['Oops! Please select at least 1 item.'])->withInput();
            }
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['Oops! The approval process was unsuccessful.'])->withInput();
        }
        
    }

    public function approve_time_pass(Request $r){
        
        $id = $this->__get_orig_id($r->input('id'));
        $timePass = DB::table('emp_pass_slip')->where('id', $id)->first();

        // check application
        // json
        // if($checkApplication = $this->checkApplication(null, 'PS', 'U', $id))
        //     return response()->json(array("code" => 0, "message" => $checkApplication));

        $approvers = explode(",",$timePass->approved_by);
        
        // Check first if the approver already approve the request
        if($timePass->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && ($timePass->ps_status == 1 || $timePass->ps_status == 3)){
            return response()->json(array("code" => 0, "message" => "You already approved this request."));
        }
        // Check if the is_approved is 0 then update to 1
        elseif($timePass->ps_status == 0 || $timePass->ps_status == null){
            DB::table('emp_pass_slip')->where('id', $id)
              ->update([
                'ps_status' => 3, // 1 approved before
                'status_remarks' => 'Partially Approved',
                'approved_by' => session('employee')->UserID_Empl,
                'Approver_emp_name' => session('employee')->Name_Empl,
                'Approver_id' => session('employee')->UserID_Empl, // approver 1
                'app1_approved_on' => date('Y-m-d H:i:s'),
                'updated_at' => now()
              ]);
          return response()->json(array("code" => 3, "message" => "Pass slip partially approved."));
        }
        elseif($timePass->ps_status == 3){ // approve
            DB::table('emp_pass_slip')->where('id', $id)
              ->update([
                'ps_status' => 1,
                'status_remarks' => 'Approved',
                'Approver_emp_name' => $timePass->Approver_emp_name.' & '.session('employee')->Name_Empl,
                'approved_by' => $timePass->approved_by.','.session('employee')->UserID_Empl,
                // 'Approver_id' => session('employee')->UserID_Empl,
                'date_approved' => date('Y-m-d H:i:s'),
                'app2_approved_on' => date('Y-m-d H:i:s'),
                'updated_at' => now()
              ]);
            $pass_slip = DB::table('emp_pass_slip')->where('id', $id)->first();
            $employee = DB::TABLE('employees')->where('SysPK_Empl','=',$pass_slip->emp_id)->first();
            $dtr = DB::TABLE('hr_emp_attendance')
                ->join('employees','hr_emp_attendance.employee_number','=','employees.UserID_Empl')
                ->select('hr_emp_attendance.*')
                ->where('hr_emp_attendance.dtr_date','=',date('Y-m-d',strtotime($pass_slip->ps_date_from)))
                ->where('employees.SysPK_Empl','=',$pass_slip->emp_id)
                ->first();
            // if($dtr){
            //     $time_slip = $pass_slip->ps_date_from.' '.$pass_slip->time;
            //     if($pass_slip->time_type==0){
            //         DB::TABLE('hr_emp_attendance')
            //         ->where('employee_number','=',$employee->UserID_Empl)
            //         ->where('dtr_date','=',date('Y-m-d',strtotime($pass_slip->ps_date_from)))
            //         ->update([
            //             'in_am' => date("Y-m-d H:i:s",strtotime($time_slip)),
            //             'entry_type' => 1
            //         ]);
            //     }else{
            //         DB::TABLE('hr_emp_attendance')
            //         ->where('employee_number','=',$employee->UserID_Empl)
            //         ->where('dtr_date','=',date('Y-m-d',strtotime($pass_slip->ps_date_from)))
            //         ->update([
            //             'out_pm' => date("Y-m-d H:i:s",strtotime($time_slip)),
            //             'entry_type' => 1
            //         ]);
            //     }
            // }
              return response()->json(array("code" => 1, "message" => "Pass slip was approved successfully."));
        }
        elseif($timePass->ps_status == 2){
            return response()->json(array("code" => 2, "message" => "Pass slip had already been rejected."));
        }
    }

    public function reject_time_pass(Request $r){
        
        $id = $this->__get_orig_id($r->input('id'));
        $reason_remarks = $r->input('reason_remarks');
        $timePass = DB::table('emp_pass_slip')->where('id', $id)->first();

        // check application
        // json
        // if($checkApplication = $this->checkApplication(null, 'PS', 'U', $id))
        //     return response()->json(array("code" => 0, "message" => $checkApplication));

        // Check first if the is_approved is 0 then update to 1
        // if($timePass->ps_status == 0){
        if(session('is_approver')){

            // get the employee number from employees
            $employee = DB::table('employees')->where('SysPK_Empl', $timePass->emp_id)->first();
            // get the approvers from approvers table
            $approver = DB::table('approvers')->where('emp_id', $employee->UserID_Empl)->first();
            // check if what type of approver
            $current_approver = 'app1_approved_on';
            if($approver->approver_1_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app1_approved_on';
            elseif($approver->approver_2_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app2_approved_on';

          DB::table('emp_pass_slip')->where('id', $id)
              ->update([
                'ps_status' => 2,
                'Approver_emp_name' => session('employee')->Name_Empl,
                'status_remarks' => $reason_remarks,
                'Approver_id' => session('employee')->UserID_Empl,
                $current_approver => date('Y-m-d H:i:s'),
                'updated_at' => now()
              ]);
          return response()->json(array("code" => 1, "message" => "The pass slip was rejected."));
        }
        else
            return response()->json(array("code" => 0, "message" => "Something went wrong!"));
    }

    // UNDER TIME
    public function my_undertime(Request $r){

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status

        $undertime = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $undertime = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            // ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('emp_pass_slip.*', 'employees.Name_Empl', 'employees.SysPK_Empl') // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 1)
            ->where('emp_id', session('user')->emp_id);

        if($ps_status !== '4') $undertime = $undertime->where('ps_status', $ps_status);
        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $undertime = $undertime->whereBetween('ps_date_from', $date);
        }
        $undertime = $undertime->orderBy('emp_pass_slip.id', 'desc')->get();

        return view('NormalEmployees.my_undertime', [
                    'undertime' => $undertime,
                    'date_from' => $date_from,
                    'date_to' => $date_to
                ]);
    }

    public function file_undertime(Request $r){
        
        return view('NormalEmployees.file_undertime');
    }

    public function file_undertime_request(Request $r){
        
        $this->validate($r, [
            'date_selected' => 'required',
            'total_time' => 'required',
        ]);

        $time = $r->input('time_from');
        $timeEnd = $r->input('time_to');
        $date = $r->input('date_selected');
        $totalTime = $r->input('total_time');
        $remarks = $r->input('remarks');
        // Check date if valid
        if(strtotime($date) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['The chosen date must be less than or equal to the current date.'])->withInput();
        
        if(DB::table('emp_pass_slip')->where('ps_status', '!=', 2)->where('ps_code',1)->where('is_deleted',0)->where('emp_id', session('user')->emp_id)->where('ps_date_from', date('Y-m-d', strtotime($date)))->first()){
            return Redirect::back()->withErrors(["Undertime has already been submitted for ".date('M d, Y', strtotime($date)).". Please delete any previous entries to avoid confusion."])->withInput();
        }

        DB::table('emp_pass_slip')->insert([
            'emp_id' => session('user')->emp_id,
            'emp_name' => session('employee')->Name_Empl,
            'ps_code' => 1,
            'ps_date_from' => date('Y-m-d', strtotime($date)),
            'ps_date_to' => date('Y-m-d', strtotime($date)),
            'time_type' => 2,
            'remarks' => $remarks,
            'ps_status' => 0,
            'total_time' => $totalTime,
            'time' => date('H:i:s', strtotime($time)),
            'time_end' => date('H:i:s', strtotime($timeEnd)),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('my_undertime')->with('success_message', 'Undertime log was successfully saved.');

    }

    public function undertime_request(Request $r){

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status
        $display_by = $r->input('display_by') ?? '10'; // default 10

        $undertime = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $undertime = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            // ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select(
                DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
                'emp_pass_slip.*', 
                // 'employees.Name_Empl' , 
                'employees.picture_path',
                'employees.gender', 
                'employees.SysPK_Empl', 
                'employees.UserID_Empl',
                'approvers.approver_1_emp_id',
                'approvers.approver_2_emp_id',
                ) // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 1)
            ->groupBy('emp_pass_slip.id');

        if($ps_status !== '4') $undertime = $undertime->where('ps_status', $ps_status);

        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $undertime = $undertime->whereBetween('ps_date_from', $date);
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
            $undertime = $undertime->whereIn('employees.UserID_Empl', $emp_id_numbers);
        }

        // $undertime = $undertime->orderBy('emp_pass_slip.id', 'desc')->get();

        if($display_by == 'all'){
            $undertime = $undertime->orderBy('emp_pass_slip.id', 'desc')->get();
            $paginationLinks = null;
        }
        else{
            $undertime = $undertime->orderBy('emp_pass_slip.id', 'desc')->paginate($display_by);
            $paginationLinks = $undertime->appends(request()->query())->links();
        }

        $undertime_temp = collect();
        if(count($undertime) > 0) {
            foreach($undertime as $i => $row){
                $emp = DB::table('employees')
                        ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
                        ->where('UserID_Empl', $row->approver_1_emp_id)
                        ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                        ->get();
                $undertime_temp[$i]= $row;
                if($emp) $undertime_temp[$i]->approvers = $emp->toArray();
            }
        }
        $undertime = $undertime_temp;

        return view('RequestAF.undertime_request', [
                'undertime' => $undertime,
                'date_from' => $date_from,
                'date_to' => $date_to,
                "paginationLinks" => $paginationLinks
            ]);
    }

    public function file_undertime_to_update(Request $r){

        $emp_id = session('employee')->SysPK_Empl ?? null;
        $id = $this->__get_orig_id($r->input('id'));

        $undertime = DB::table('emp_pass_slip')
                    ->where('id', $id)
                    ->where('emp_id', $emp_id)
                    ->where('is_deleted', 0)
                    ->where('ps_code', 1)
                    ->first();
        
        if(!$undertime) return redirect('/file-undertime');

        return view('NormalEmployees.file_undertime_to_update', ['undertime' => $undertime]);
    }

    public function file_undertime_to_update_approver(Request $r){

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
                if(!in_array($employee->UserID_Empl, $emp_id_numbers)) return redirect('/undertime-request')->withErrors(['Something went wrong!']);
            }
            else return redirect('/undertime-request')->withErrors(['Something went wrong! Employee not found.']);
        }
        else return redirect('/undertime-request')->withErrors(['Something went wrong!']);

        $undertime = DB::table('emp_pass_slip')->where('emp_id', $emp_id)->where('id', $id)->where('is_deleted', 0)->where('ps_code', 1)->first();
        if(!$undertime) return redirect('/undertime-request')->withErrors(['Something went wrong!']);

        return view('NormalEmployees.file_undertime_to_update', ['undertime' => $undertime, 'employee' => $employee, 'approver_update' => 1]);

    }

    public function file_undertime_update(Request $r){

        $this->validate($r, [
            'date_selected' => 'required',
            'total_time' => 'required',
        ]);

        $id = $r->input('id') ?? null;
        if(!$id) return Redirect::back()->withErrors(["Something went wrong!"])->withInput();

        $time = $r->input('time_from');
        $timeEnd = $r->input('time_to');
        $date = $r->input('date_selected');
        $totalTime = $r->input('total_time');
        $remarks = $r->input('remarks');

        $approver_update = $r->input('approver_update') ?? null;

        // Run validation
        if(!$approver_update){

            // Check date if valid
            if(strtotime($date) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['The chosen date must be less than or equal to the current date.'])->withInput();
        
            if(DB::table('emp_pass_slip')
                ->where('id', '!=', $id)
                ->where('ps_status', '!=', 2)
                ->where('ps_code',1)
                ->where('is_deleted',0)
                ->where('emp_id', session('user')->emp_id)
                ->where('ps_date_from', date('Y-m-d', strtotime($date)))
                ->first()){
                return Redirect::back()->withErrors(["Undertime has already been submitted for ".date('M d, Y', strtotime($date)).". Please delete any previous entries to avoid confusion."])->withInput();
            }
        }

        $data = array(
            'ps_date_from' => date('Y-m-d', strtotime($date)),
            'ps_date_to' => date('Y-m-d', strtotime($date)),
            'remarks' => $remarks,
            'total_time' => $totalTime,
            'time' => date('H:i:s', strtotime($time)),
            'time_end' => date('H:i:s', strtotime($timeEnd)),
            'ps_status' => 0,
            'Approver_id' => null,
            'approved_by' => null,
            "app1_approved_on" => null,
            "app2_approved_on" => null,
        );
        $route = 'my_undertime';

        if($approver_update == 1){
            unset($data['ps_status']);
            unset($data['Approver_id']);
            unset($data['approved_by']);
            unset($data['app1_approved_on']);
            unset($data['app2_approved_on']);
            $route = 'undertime_request';
        }

        DB::table('emp_pass_slip')->where('id', $id)->update($data);

        return redirect()->route($route)->with('success_message', 'Undertime log was successfully updated.');
    }

    public function delete_undertime(Request $r){
        
        $id = $this->__get_orig_id($r->input('id'));

        DB::table('emp_pass_slip')
            ->where('id', $id)
            ->update([
                'is_deleted' => 1,
                "deleted_by" => session('user')->id,
                'updated_at' => now()
            ]);
        
        return response()->json(array("code" => 1, "message" => "Undertime has been deleted."));

    }

    public function approve_undertime(Request $r){
  
        $id = $this->__get_orig_id($r->input('id'));
        $undertime = DB::table('emp_pass_slip')->where('id', $id)->first();

        $approvers = explode(",",$undertime->approved_by);
        
        // Check first if the approver already approve the request
        if($undertime->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && ($undertime->ps_status == 1 || $undertime->ps_status == 3)){
            return response()->json(array("code" => 0, "message" => "You already approved this request."));
        }
        // Check if the is_approved is 0 then update to 1
        elseif($undertime->ps_status == 0 || $undertime->ps_status == null){
          DB::table('emp_pass_slip')->where('id', $id)
                ->update([
                    'ps_status' => 3, // 1 approved before
                    'status_remarks' => 'Partially Approved',
                    'approved_by' => session('employee')->UserID_Empl,
                    'Approver_emp_name' => session('employee')->Name_Empl,
                    'Approver_id' => session('employee')->UserID_Empl, // approver 1
                    'app1_approved_on' => date('Y-m-d H:i:s'),
                    'updated_at' => now()
              ]);
          return response()->json(array("code" => 3, "message" => "Undertime partially approved."));
        }
        elseif($undertime->ps_status == 3){ // approve
            DB::table('emp_pass_slip')->where('id', $id)
                ->update([
                    'ps_status' => 1,
                    'status_remarks' => 'Approved',
                    'Approver_emp_name' => $undertime->Approver_emp_name.' & '.session('employee')->Name_Empl,
                    'approved_by' => $undertime->approved_by.','.session('employee')->UserID_Empl,
                    // 'Approver_id' => session('employee')->UserID_Empl,
                    'date_approved' => date('Y-m-d H:i:s'),
                    'app2_approved_on' => date('Y-m-d H:i:s'),
                    'updated_at' => now()
                ]);
            return response()->json(array("code" => 1, "message" => "Undertime was approved successfully."));
        }
        // elseif($undertime->ps_status == 1){
        //   return response()->json(array("code" => 1, "message" => "Undertime had already been approved."));
        // }
        elseif($undertime->ps_status == 2){
            return response()->json(array("code" => 2, "message" => "Undertime had already been rejected."));
        }

    }

    public function reject_undertime(Request $r){

        $id = $this->__get_orig_id($r->input('id'));
        $reason_remarks = $r->input('reason_remarks');
        $undertime = DB::table('emp_pass_slip')->where('id', $id)->first();
        // Check first if the is_approved is 0 then update to 1
        // if($undertime->ps_status == 0){
        if(session('is_approver')){

            // get the employee number from employees
            $employee = DB::table('employees')->where('SysPK_Empl', $undertime->emp_id)->first();
            // get the approvers from approvers table
            $approver = DB::table('approvers')->where('emp_id', $employee->UserID_Empl)->first();
            // check if what type of approver
            $current_approver = 'app1_approved_on';
            if($approver->approver_1_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app1_approved_on';
            elseif($approver->approver_2_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app2_approved_on';

            DB::table('emp_pass_slip')->where('id', $id)
              ->update([
                'ps_status' => 2,
                'Approver_emp_name' => session('employee')->Name_Empl,
                'status_remarks' => $reason_remarks,
                'Approver_id' => session('employee')->UserID_Empl,
                $current_approver => date('Y-m-d H:i:s'),
                'updated_at' => now()
              ]);
          return response()->json(array("code" => 1, "message" => "Undertime was rejected."));
        }
        else
            return response()->json(array("code" => 0, "message" => "Something went wrong!"));
    }
    
    public function change_schedule_request(Request $r){

        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');

        $chageSchedule = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $shift_codes = DB::table('shift_code')->get();

        $chageSchedule = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            ->join('shift_monitoring', 'emp_pass_slip.emp_sched_id', '=', 'shift_monitoring.id')
            ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
            ->select(
                'emp_pass_slip.*',
                'shift_code.Description', 
                'shift_code.shift_code', 
                'employees.Name_Empl' , 
                'employees.picture_path',
                'employees.gender', 
                'employees.SysPK_Empl',
                'approvers.approver_1_emp_id',
                'approvers.approver_2_emp_id',
                'employees.UserID_Empl'
            ) // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 2)
            ->groupBy('emp_pass_slip.id');
            // ->where('emp_pass_slip.ps_status', 0);
        
        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $chageSchedule = $chageSchedule->whereBetween('ps_date_from', $date);
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
            $chageSchedule = $chageSchedule->whereIn('employees.UserID_Empl', $emp_id_numbers);
        }

        $chageSchedule = $chageSchedule->orderBy('emp_pass_slip.id', 'desc')->get();

        $chageSchedule_temp = collect();
        if(count($chageSchedule) > 0) {
            foreach($chageSchedule as $i => $row){
                $emp = DB::table('employees')
                        ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
                        ->where('UserID_Empl', $row->approver_1_emp_id)
                        ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                        ->get();
                $chageSchedule_temp[$i]= $row;
                if($emp) $chageSchedule_temp[$i]->approvers = $emp->toArray();
            }
        }
        $chageSchedule = $chageSchedule_temp;
        
        return view('RequestAF.change_schedule_request', [
            'chageSchedule' => $chageSchedule,
            'shift_codes' => $shift_codes
        ]);
    }
    
    public function approve_change_schedule(Request $r){
  
        $id = $this->__get_orig_id($r->input('id'));
        $chageSchedule = DB::table('emp_pass_slip')->where('id', $id)->first();
        $shift_code = DB::table('shift_code')->where('id', $chageSchedule->shift_code_id)->first();

        $approvers = explode(",",$chageSchedule->approved_by);
        
        // Check first if the approver already approve the request
        if($chageSchedule->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && ($chageSchedule->ps_status == 1 || $chageSchedule->ps_status == 3)){
            return response()->json(array("code" => 0, "message" => "You already approved this request."));
        }
        // Check if the is_approved is 0 then update to 1
        elseif($chageSchedule->ps_status == 0 || $chageSchedule->ps_status == null){
            DB::table('emp_pass_slip')->where('id', $id)
                ->update([
                    'ps_status' => 3, // 1 approved before
                    'status_remarks' => 'Partially Approved',
                    'approved_by' => session('employee')->UserID_Empl,
                    'Approver_emp_name' => session('employee')->Name_Empl,
                    'Approver_id' => session('employee')->UserID_Empl, // approver 1
                    'app1_approved_on' => date('Y-m-d H:i:s'),
                    'updated_at' => now()
                ]);

        //   return response()->json(array("code" => 1, "message" => "Change schedule was approved successfully."));
            return response()->json(array("code" => 3, "message" => "Official Business Trip partially approved."));
        }
        elseif($chageSchedule->ps_status == 3){ // approve
            DB::table('emp_pass_slip')->where('id', $id)
            ->update([
                'ps_status' => 1,
                'status_remarks' => 'Approved',
                'Approver_emp_name' => $chageSchedule->Approver_emp_name.' & '.session('employee')->Name_Empl,
                'approved_by' => $chageSchedule->approved_by.','.session('employee')->UserID_Empl,
                'date_approved' => date('Y-m-d H:i:s'),
                'app2_approved_on' => date('Y-m-d H:i:s'),
                'updated_at' => now()
            ]);

            DB::table('shift_monitoring')->where('id', $chageSchedule->emp_sched_id)
            ->update([
                'shift_date' => date('Y-m-d', strtotime($chageSchedule->ps_date_to)),
                'shift_code_id' => $chageSchedule->shift_code_id,
                'shift_code' => $shift_code->shift_code,
                'no_hr_break' => $shift_code->default_break_hrs,
                'updated_at' => now()
            ]);
            return response()->json(array("code" => 1, "message" => "Official Business Trip was approved successfully."));
        }
        elseif($chageSchedule->ps_status == 2){
            return response()->json(array("code" => 2, "message" => "Change schedule had already been rejected."));
        }

    }

    public function reject_change_schedule(Request $r){
  
        $id = $this->__get_orig_id($r->input('id'));
        $reason_remarks = $r->input('reason_remarks');
        $chageSchedule = DB::table('emp_pass_slip')->where('id', $id)->first();
        // Check first if the is_approved is 0 then update to 1
        if(session('is_approver')){

            // get the employee number from employees
            $employee = DB::table('employees')->where('SysPK_Empl', $chageSchedule->emp_id)->first();
            // get the approvers from approvers table
            $approver = DB::table('approvers')->where('emp_id', $employee->UserID_Empl)->first();
            // check if what type of approver
            $current_approver = 'app1_approved_on';
            if($approver->approver_1_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app1_approved_on';
            elseif($approver->approver_2_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app2_approved_on';

          DB::table('emp_pass_slip')->where('id', $id)
              ->update([
                'ps_status' => 2,
                'Approver_emp_name' => session('employee')->Name_Empl,
                'status_remarks' => $reason_remarks,
                'Approver_id' => session('employee')->UserID_Empl,
                $current_approver => date('Y-m-d H:i:s'),
                'updated_at' => now()
              ]);
          return response()->json(array("code" => 1, "message" => "Change schedule requested was rejected."));
        }
    else
        return response()->json(array("code" => 0, "message" => "Something went wrong!"));
    }

    // OBT
    public function my_obt(Request $r){

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status

        $obt = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
        
        $obt = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->leftJoin('system_lock_status', 'emp_pass_slip.ps_date_from', '=', 'system_lock_status.lock_date')
            // ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('emp_pass_slip.*', 'employees.Name_Empl', 'employees.SysPK_Empl', 'system_lock_status.lock_date', 'system_lock_status.is_lock') // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 3)
            ->where('emp_id', session('user')->emp_id);
        
        if($ps_status !== '4') $obt = $obt->where('ps_status', $ps_status);
        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $obt = $obt->whereBetween('ps_date_from', $date);
        }
        $obt = $obt->orderBy('emp_pass_slip.id', 'desc')->get();
        
        return view('NormalEmployees.my_obt', [
                'obt' => $obt,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]);
    }

    public function file_obt(Request $r){
        
        return view('NormalEmployees.file_obt');
    }

    public function file_obt_request(Request $r){
        
        // $this->validate($r, [
        //     'date_from' => 'required',
        //     'date_to' => 'required',
        // ]);

        // $date_from = $r->input('date_from');
        // $date_to = $r->input('date_to');
        // $remarks = $r->input('remarks');
        // // Check date if valid
        // if(strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['The chosen Date From must be less than or equal to the Date To.'])->withInput();
        
        // if(DB::table('emp_pass_slip')->where('ps_status', '!=', 2)->where('ps_code',3)->where('is_deleted',0)->where('emp_id', session('user')->emp_id)->where('ps_date_from', date('Y-m-d', strtotime($date_from)))->where('ps_date_to', date('Y-m-d', strtotime($date_to)))->first()){
        //     return Redirect::back()->withErrors(["Official Business Trip has already been submitted for ".date('M d, Y', strtotime($date_from))." - ".date('M d, Y', strtotime($date_to))." Please delete any previous entries to avoid confusion."])->withInput();
        // }

        // $obt = DB::table('emp_pass_slip')->insert([
        //     'emp_id' => session('user')->emp_id,
        //     'emp_name' => session('employee')->Name_Empl,
        //     'ps_code' => 3,
        //     'ps_date_from' => date('Y-m-d', strtotime($date_from)),
        //     'ps_date_to' => date('Y-m-d', strtotime($date_to)),
        //     'time_type' => 4,
        //     'remarks' => $remarks,
        //     'ps_status' => 0,
        //     'total_time' => 0,
        //     'created_at' => date('Y-m-d H:i:s')
        // ]);


        $this->validate($r, [
            'date_selected' => 'required',
            'time_selected' => 'required',
            'time_type' => 'required',
        ]);

        $time = $r->input('time_selected');
        $date = $r->input('date_selected');
        $timeType = $r->input('time_type');
        $remarks = $r->input('remarks');

        // check application
        if($checkApplication = $this->checkApplication($date, 'OBT', 'I', null))
            return Redirect::back()->withErrors([$checkApplication])->withInput();

        // Check date if valid
        // if(strtotime($date) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['The chosen date must be less than or equal to the current date.'])->withInput();
        
        if(DB::table('emp_pass_slip')->where('ps_status', '!=', 2)->where('ps_code',3)->where('is_deleted',0)->where('emp_id', session('user')->emp_id)->where('ps_date_from', date('Y-m-d', strtotime($date)))->where('time_type', $timeType)->first()){
            return Redirect::back()->withErrors(["Time pass has already been submitted for ".date('M d, Y', strtotime($date)).". Please delete any previous entries to avoid confusion."])->withInput();
        }

        $obt = DB::table('emp_pass_slip')->insert([
                'emp_id' => session('user')->emp_id,
                'emp_name' => session('employee')->Name_Empl,
                'ps_code' => 3,
                'ps_date_from' => date('Y-m-d', strtotime($date)),
                'ps_date_to' => date('Y-m-d', strtotime($date)),
                'time' => date('H:i:s', strtotime($time)),
                'time_type' => $timeType,
                'remarks' => $remarks,
                'ps_status' => 0,
                'total_time' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

        if($obt) return redirect()->route('my_obt')->with('success_message', 'The official business trip was successfully saved.');
        else return Redirect::back()->withErrors(["Something went wrong!"])->withInput();
    }

    public function file_obt_to_update(Request $r){

        $id = $this->__get_orig_id($r->input('id'));
        $emp_id = session('employee')->SysPK_Empl ?? null;
        
        $obt = DB::table('emp_pass_slip')
                    ->where('id', $id)
                    ->where('emp_id', $emp_id)
                    ->where('is_deleted', 0)
                    ->where('ps_code', 3)
                    ->first();
        
        if(!$obt) return redirect('/file-obt');

        // check application
        $checkApplication = $this->checkApplication(null, 'OBT', 'U', $id);

        return view('NormalEmployees.file_obt_to_update', [
            'obt' => $obt,
            'checkApplication' => $checkApplication
        ]);
    }

    public function file_obt_to_update_approver(Request $r){

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
                if(!in_array($employee->UserID_Empl, $emp_id_numbers)) return redirect('/obt-request')->withErrors(['Something went wrong!']);
            }
            else return redirect('/obt-request')->withErrors(['Something went wrong! Employee not found.']);
        }
        else return redirect('/obt-request')->withErrors(['Something went wrong!']);

        $obt = DB::table('emp_pass_slip')->where('emp_id', $emp_id)->where('id', $id)->where('is_deleted', 0)->where('ps_code', 3)->first();
        if(!$obt) return redirect('/obt-request')->withErrors(['Something went wrong!']);

        // check application
        // $checkApplication = $this->checkApplication(null, 'OBT', 'U', $id);

        return view('NormalEmployees.file_obt_to_update', [
            'obt' => $obt,
            'employee' => $employee,
            'approver_update' => 1,
            // 'checkApplication' => $checkApplication
            'checkApplication' => null
        ]);

    }

    public function file_obt_update(Request $r){

        $id = $r->input('id') ?? null;
        if(!$id) return Redirect::back()->withErrors(["Something went wrong!"])->withInput();

        $this->validate($r, [
            'date_selected' => 'required',
            'time_selected' => 'required',
            'time_type' => 'required',
        ]);

        $time = $r->input('time_selected');
        $date = $r->input('date_selected');
        $timeType = $r->input('time_type');
        $remarks = $r->input('remarks');

        // // check application
        // if($checkApplication = $this->checkApplication(null, 'OBT', 'U', $id))
        //     return Redirect::back()->withErrors([$checkApplication])->withInput();

        $approver_update = $r->input('approver_update') ?? null;

        // Run validation
        if(!$approver_update){

            // check application
            if($checkApplication = $this->checkApplication(null, 'OBT', 'U', $id))
                return Redirect::back()->withErrors([$checkApplication])->withInput();
            // Check date if valid
            // if(strtotime($date) > strtotime(date('Y-m-d'))) return Redirect::back()->withErrors(['The chosen date must be less than or equal to the current date.'])->withInput();
                
                if(DB::table('emp_pass_slip')
                ->where('id', '!=', $id)
                ->where('ps_status', '!=', 2)
                ->where('ps_code',3)
                ->where('is_deleted',0)
                ->where('emp_id', session('user')->emp_id)
                ->where('ps_date_from', date('Y-m-d', strtotime($date)))
                ->where('time_type', $timeType)
                ->first()){
                return Redirect::back()->withErrors(["Time pass has already been submitted for ".date('M d, Y', strtotime($date)).". Please delete any previous entries to avoid confusion."])->withInput();
            }
        }

        $data = array(
            'ps_date_from' => date('Y-m-d', strtotime($date)),
            'ps_date_to' => date('Y-m-d', strtotime($date)),
            'time' => date('H:i:s', strtotime($time)),
            'time_type' => $timeType,
            'remarks' => $remarks,
            'ps_status' => 0,
            'total_time' => 0,
            'ps_status' => 0,
            'Approver_id' => null,
            'approved_by' => null,
            "app1_approved_on" => null,
            "app2_approved_on" => null,
            'updated_at' => now(),
        );
        $route = 'my_obt';

        if($approver_update == 1){
            unset($data['ps_status']);
            unset($data['Approver_id']);
            unset($data['approved_by']);
            unset($data['app1_approved_on']);
            unset($data['app2_approved_on']);
            $route = 'obt_request';
        }
        
        DB::table('emp_pass_slip')->where('id', $id)->update($data);

        return redirect()->back()->with('success_message', 'The official business trip was successfully updated.');
    }

    public function delete_obt(Request $r){
        
        $id = $this->__get_orig_id($r->input('id'));

        // check application
        // json
        if($checkApplication = $this->checkApplication(null, 'OBT', 'R', $id))
            return response()->json(array("code" => 0, "message" => $checkApplication));

        DB::table('emp_pass_slip')
            ->where('id', $id)
            ->update([
                'is_deleted' => 1,
                "deleted_by" => session('user')->id,
                'updated_at' => now()
            ]);
        
        return response()->json(array("code" => 1, "message" => "Official business trip has been deleted."));

    }

    public function obt_request(Request $r){

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $ps_status = $r->input('status') ?? '4'; // default 4 - all status
        $display_by = $r->input('display_by') ?? '10'; // default 10

        $obt = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
        
        $obt = DB::table('emp_pass_slip')
            ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
            ->leftjoin('approvers', 'employees.UserID_Empl', '=', 'approvers.emp_id')
            // ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->leftJoin('system_lock_status', 'emp_pass_slip.ps_date_from', '=', 'system_lock_status.lock_date')
            ->select(
                DB::raw('CONCAT(employees.FirstName_Empl," ",employees.LastName_Empl) AS Name_Empl'),
                'emp_pass_slip.*', 
                // 'employees.Name_Empl' ,
                'employees.picture_path',
                'employees.gender', 
                'employees.SysPK_Empl', 
                'employees.UserID_Empl',
                'approvers.approver_1_emp_id',
                'approvers.approver_2_emp_id',
                'system_lock_status.lock_date',
                'system_lock_status.is_lock'
                ) // , 'employees_rate.Position_Empl'
            ->where('emp_pass_slip.is_deleted', 0)
            ->where('emp_pass_slip.ps_code', 3)
            ->whereNotIn('employees.Status_Empl', ['RESIGNED', 'AWOL', 'TERMINATED'])
            ->groupBy('emp_pass_slip.id');
        
        if($ps_status !== '4') $obt = $obt->where('ps_status', $ps_status);

        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $obt = $obt->whereBetween('ps_date_from', $date);
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
            $obt = $obt->whereIn('employees.UserID_Empl', $emp_id_numbers);
        }

        // $obt = $obt->orderBy('emp_pass_slip.id', 'desc')->get();

        if($display_by == 'all'){
            $obt = $obt->orderBy('emp_pass_slip.id', 'desc')->get();
            $paginationLinks = null;
        }
        else{
            $obt = $obt->orderBy('emp_pass_slip.id', 'desc')->paginate($display_by);
            $paginationLinks = $obt->appends(request()->query())->links();
        }
        
        $obt_temp = collect();
        if(count($obt) > 0) {
            foreach($obt as $i => $row){
                $emp = DB::table('employees')
                        ->select(DB::raw('CONCAT(FirstName_Empl," ",LastName_Empl) AS approver_name'), 'UserID_Empl as approver_emp_id')
                        ->where('UserID_Empl', $row->approver_1_emp_id)
                        ->orWhere('UserID_Empl', $row->approver_2_emp_id)
                        ->get();
                $obt_temp[$i]= $row;
                if($emp) $obt_temp[$i]->approvers = $emp->toArray();
            }
        }
        $obt = $obt_temp;

        return view('RequestAF.obt_request', [
            'obt' => $obt,
            'date_from' => $date_from,
            'date_to' => $date_to,
            "paginationLinks" => $paginationLinks
        ]);
    }

    public function approve_obt(Request $r){
  
        $id = $this->__get_orig_id($r->input('id'));

        // check application
        // json
        // if($checkApplication = $this->checkApplication(null, 'OBT', 'U', $id))
        //     return response()->json(array("code" => 0, "message" => $checkApplication));

        $obt = DB::table('emp_pass_slip')->where('id', $id)->first();

        $approvers = explode(",",$obt->approved_by);
        
        // Check first if the approver already approve the request
        if($obt->approved_by && in_array(session('employee')->UserID_Empl, $approvers) && ($obt->ps_status == 1 || $obt->ps_status == 3)){
            return response()->json(array("code" => 0, "message" => "You already approved this request."));
        }
        // Check first if the is_approved is 0 then update to 1
        elseif($obt->ps_status == 0 || $obt->ps_status == null){
          DB::table('emp_pass_slip')->where('id', $id)
              ->update([
                'ps_status' => 3, // 1 approved before
                'status_remarks' => 'Partially Approved',
                'approved_by' => session('employee')->UserID_Empl,
                'Approver_emp_name' => session('employee')->Name_Empl,
                'Approver_id' => session('employee')->UserID_Empl, // approver 1
                'app1_approved_on' => date('Y-m-d H:i:s'),
                'updated_at' => now()
            ]);
          return response()->json(array("code" => 3, "message" => "Official Business Trip partially approved."));
        }
        elseif($obt->ps_status == 3){ // approve
            DB::table('emp_pass_slip')->where('id', $id)
                ->update([
                    'ps_status' => 1,
                    'status_remarks' => 'Approved',
                    'Approver_emp_name' => $obt->Approver_emp_name.' & '.session('employee')->Name_Empl,
                    'approved_by' => $obt->approved_by.','.session('employee')->UserID_Empl,
                    'date_approved' => date('Y-m-d H:i:s'),
                    'app2_approved_on' => date('Y-m-d H:i:s'),
                    'updated_at' => now()
                ]);
            return response()->json(array("code" => 1, "message" => "Official Business Trip was approved successfully."));
        }
        elseif($obt->ps_status == 2){
            return response()->json(array("code" => 2, "message" => "Official Business Trip had already been rejected."));
        }

    }

    public function reject_obt(Request $r){
  
        $id = $this->__get_orig_id($r->input('id'));
        $reason_remarks = $r->input('reason_remarks');
        $obt = DB::table('emp_pass_slip')->where('id', $id)->first();
        // Check first if the is_approved is 0 then update to 1
        if(session('is_approver')){

            // check application
            // json
            // if($checkApplication = $this->checkApplication(null, 'OBT', 'U', $id))
            //     return response()->json(array("code" => 0, "message" => $checkApplication));

            // get the employee number from employees
            $employee = DB::table('employees')->where('SysPK_Empl', $obt->emp_id)->first();
            // get the approvers from approvers table
            $approver = DB::table('approvers')->where('emp_id', $employee->UserID_Empl)->first();
            // check if what type of approver
            $current_approver = 'app1_approved_on';
            if($approver->approver_1_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app1_approved_on';
            elseif($approver->approver_2_emp_id == session('employee')->UserID_Empl)
                $current_approver = 'app2_approved_on';

            DB::table('emp_pass_slip')->where('id', $id)
                ->update([
                    'ps_status' => 2,
                    'Approver_emp_name' => session('employee')->Name_Empl,
                    'status_remarks' => $reason_remarks,
                    'Approver_id' => session('employee')->UserID_Empl,
                    $current_approver => date('Y-m-d H:i:s'),
                    'updated_at' => now()
                ]);
            return response()->json(array("code" => 1, "message" => "The official business trip was rejected."));
        }
        else
            return response()->json(array("code" => 0, "message" => "Something went wrong!"));
    }

}