<?php

namespace App\Http\Controllers\DTR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Input;
use App\Models\Hr_emp_attendance as Dtr;
use App\Models\Emp_imported_dtr as Import_dtr;
use DateInterval;
use DateTime;
use DatePeriod;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
class DTRController extends Controller{

    public function dtr_entry(){

        $employee_number = Input::get('id');
        $employee_fullName = "";
        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');
        // $start_time = "08:00:00";
        // $end_time = "17:00:00";
        if(is_null($date_from)){
          $date_from = date('Y-m-d');
        }

        if(is_null($date_to)){
          $date_to = date('Y-m-d');
        }

        $attendance = array();
        $attendance1 = array();
        // $emp_details = array();
        // $holiday_checker = array();
        $shift_codes = DB::table('shift_code')->where('is_active', 1)->get();

        if ($employee_number) {

            $employee_number = $this->__get_orig_id($employee_number);
            $attendance = Dtr::where('employee_number', $employee_number)->whereBetween('dtr_date', [$date_from, $date_to])->get();
            // $emp_details = DB::SELECT(DB::RAW('SELECT TimeStart_Empl,TimeEnd_Empl FROM employees WHERE UserID_Empl = "'.$employee_number.'"'));
            // $start_time = $emp_details[0]->TimeStart_Empl;
            // $end_time = $emp_details[0]->TimeEnd_Empl;
            $name_sql = DB::SELECT(DB::RAW('SELECT Name_Empl FROM employees WHERE UserID_Empl="'.$employee_number.'" LIMIT 1'));
            $emp_d = DB::table('employees')->where('UserID_Empl', $employee_number)->first();
            $employee_fullName = $name_sql[0]->Name_Empl;
        
            $cnt = 0;
            $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
            $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt ) {
                $sm_id = 0;
                $allow_update = 0;
                $shift_monitoring_status="";
                $checker = 0;
                $is_active = null;
                $break_hrs = 0;
                $shift_start= "";
                $shift_end= "";
                $shift_sched= "";
                $shift_code_def= "";
                $shift_id= 1;
                $shift = DB::table('shift_monitoring')
                            ->join('shift_code','shift_monitoring.shift_code_id','shift_code.id')
                            ->join('employees','shift_monitoring.emp_id','employees.SysPK_Empl')
                            ->select(
                                'shift_code.is_active',
                                'shift_code.id',
                                'shift_code.shift_code',
                                'shift_code.Description',
                                'shift_code.time_start',
                                'shift_code.time_end',
                                'shift_monitoring.id AS sm_id',
                                'shift_monitoring.no_hr_break',
                                'shift_monitoring.is_deleted',
                                'shift_monitoring.status',
                                'shift_monitoring.allow_update',
                                'employees.UserID_Empl',
                            )
                            ->where('employees.UserID_Empl', $employee_number)
                            // ->where('shift_monitoring.emp_id', "=", $emp_d->SysPK_Empl)
                            ->where('shift_monitoring.is_deleted', 0)
                            ->where('shift_monitoring.status',1)
                            ->where('shift_monitoring.shift_date', "=", date("Y-m-d", strtotime($dt->format("Y-m-d"))))
                            ->first();

                if ($shift) {
                    $allow_update = $shift->allow_update;
                    $sm_id = $shift->sm_id;
                    $break_hrs = $shift->no_hr_break;
                    $shift_start =$shift->time_start;
                    $shift_end = $shift->time_end;
                    $shift_sched = $shift->shift_code."-".$shift->Description;
                    $shift_id = $shift->id;
                    $shift_monitoring_status = $shift->status;
                    $is_active = $shift->is_active;
                    $shift_code_def = $shift->shift_code;
                }

                foreach ($attendance as $attndnc) {
                    if($dt->format("Y-m-d") == $attndnc->dtr_date){
                    $checker = 1;
                    $attendance1[] = [
                        "employee_number"=>$employee_number,
                        "employee_fullName"=>$employee_fullName,
                        "SysPK_emp_attendance"=>$attndnc->SysPK_emp_attendance,
                        "TimeStart"=>$shift_start,
                        "TimeEnd"=>$shift_end,
                        "dtr_date"=>$attndnc->dtr_date,
                        "in_am"=>$attndnc->in_am,
                        "out_pm"=>$attndnc->out_pm,
                        "late"=>$attndnc->late,
                        "undertime"=>$attndnc->undertime,
                        "total_hours_worked"=>$attndnc->total_hours_worked,
                        "att_remarks"=>$attndnc->att_remarks,
                        "att_type"=>$attndnc->att_type,
                        "break_hours"=>$attndnc->break_hours,
                        "np_hours"=>$attndnc->np_hours,
                        "ot_hours"=>$attndnc->ot_hours,
                        "shift_sched"=>$shift_sched,
                        "shift_id"=>$shift_id,
                        "checker"=>$checker,
                        "sm_id"=>$sm_id,
                        'allow_update'=>$allow_update,
                        'shift_monitoring_status'=>$shift_monitoring_status,
                        'is_active' => $is_active,
                        'shift_code_def' => $shift_code_def,
                    ];
                    }
                }
                if($checker==0){
                    $break_hrs=0;
                        if ($employee_number) {
                            $shift = DB::TABLE('shift_monitoring')
                                ->where('emp_id', "=", $emp_d->SysPK_Empl)
                                ->where('shift_date', "=", date("Y-m-d", strtotime($dt->format("Y-m-d"))))
                                ->where('shift_monitoring.is_deleted', "!=", 1)
                                ->first();

                                if ($shift) {
                                    $break_hrs = $shift->no_hr_break;
                                }
                        }

                        $attendance1[] = [
                            "employee_number"=>$employee_number,
                            "employee_fullName"=>$employee_fullName,
                            "SysPK_emp_attendance"=>"0",
                            "TimeStart"=>$shift_start,
                            "TimeEnd"=>$shift_end,
                            "dtr_date"=>$dt->format("Y-m-d"),
                            "in_am"=>null,
                            "out_pm"=>null,
                            "late"=>"",
                            "undertime"=>"",
                            "total_hours_worked"=>0,
                            "att_remarks"=>"",
                            "att_type"=>"",
                            "checker"=>$checker,
                            "np_hours"=>"",
                            "ot_hours"=>"",
                            "shift_sched"=>$shift_sched,
                            "shift_id"=>$shift_id,
                            "break_hours"=>$break_hrs,
                            "sm_id"=>$sm_id,
                            "allow_update"=>$allow_update,
                            "shift_monitoring_status"=>$shift_monitoring_status,
                            'is_active' => $is_active,
                            'shift_code_def' => $shift_code_def,
                        ];
                }
                $cnt = $cnt + 1;
                
            }
        }

        $employees = DB::table('employees')->get();
    
        return view('DTR.dtr_entry',[
            'employees' => $employees,
            'attendance' => $attendance,
            'attendance1' => $attendance1,
            'shift_codes'=>$shift_codes,
        ]);
    }


    // NORMAL EMPLOYEE DTR
    public function dtr(Request $r){
  
        $employee_number = session('user')->username;
        $employee_fullName = "";
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-d');
        // $start_time = "08:00:00";
        // $end_time = "17:00:00";
        
        $attendance = array();
        $attendance1 = array();
        $emp_details = array();

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
  
        $attendance = Dtr::where('employee_number', $employee_number)->whereBetween('dtr_date', [$date_from, $date_to])->get();
        $emp_details = DB::SELECT(DB::RAW('SELECT TimeStart_Empl,TimeEnd_Empl FROM employees WHERE UserID_Empl = "'.$employee_number.'"'));
        // $start_time = $emp_details[0]->TimeStart_Empl;
        // $end_time = $emp_details[0]->TimeEnd_Empl;
        $name_sql = DB::SELECT(DB::RAW('SELECT Name_Empl FROM employees WHERE UserID_Empl="'.$employee_number.'" LIMIT 1'));
        $emp_d = DB::TABLE('employees')->where('UserID_Empl', $employee_number)->first();
        $employee_fullName = $name_sql[0]->Name_Empl;
          
        
        $cnt = 0;
        $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
        $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
  
        foreach ($period as $dt ) {
            $checker = 0;
            $break_hrs = 0;
            $is_active = null;
            $shift_start="";
            $shift_end="";
            $shift_sched="";
            $shift = DB::TABLE('shift_monitoring')
                            ->join('shift_code','shift_monitoring.shift_code_id','shift_code.id')
                            ->select('shift_code.is_active','shift_code.shift_code','shift_code.Description','shift_code.time_start','shift_code.time_end','shift_monitoring.no_hr_break')
                            ->where('shift_monitoring.emp_id', "=", $emp_d->SysPK_Empl)
                            ->where('shift_monitoring.shift_date', "=", date("Y-m-d", strtotime($dt->format("Y-m-d"))))
                            ->where('shift_monitoring.is_deleted', "!=", 1)
                            ->first();

            if ($shift) {
                $break_hrs = $shift->no_hr_break;
                $is_active = $shift->is_active;
                $shift_start=$shift->time_start;
                $shift_end=$shift->time_end;
                $shift_sched=$shift->shift_code."-".$shift->Description;
            }

            foreach ($attendance as $attndnc) {
                if($dt->format("Y-m-d") == $attndnc->dtr_date){
                    $checker = 1;
                    $attendance1[] = [
                        "employee_number"=>$employee_number,
                        "employee_fullName"=>$employee_fullName,
                        "SysPK_emp_attendance"=>$attndnc->SysPK_emp_attendance,
                        "TimeStart"=>$shift_start,
                        "TimeEnd"=>$shift_end,
                        "dtr_date"=>$attndnc->dtr_date,
                        "in_am"=>$attndnc->in_am,
                        "out_pm"=>$attndnc->out_pm,
                        "late"=>$attndnc->late,
                        "undertime"=>$attndnc->undertime,
                        "total_hours_worked"=>$attndnc->total_hours_worked,
                        "att_remarks"=>$attndnc->att_remarks,
                        "att_type"=>$attndnc->att_type,
                        "break_hours"=>$attndnc->break_hours,
                        "np_hours"=>$attndnc->np_hours,
                        "ot_hours"=>$attndnc->ot_hours,
                        "shift_sched"=>$shift_sched,
                        "checker"=>$checker,
                        "is_active" => $is_active
                    ];
                }
            }
            
            if($checker == 0){
                    $attendance1[] = [
                        "employee_number"=>$employee_number,
                        "employee_fullName"=>$employee_fullName,
                        "SysPK_emp_attendance"=>"0",
                        "TimeStart"=>$shift_start,
                        "TimeEnd"=>$shift_end,
                        "dtr_date"=>$dt->format("Y-m-d"),
                        "in_am"=>"",
                        "out_pm"=>"",
                        "late"=>"",
                        "undertime"=>"",
                        "total_hours_worked"=>0,
                        "att_remarks"=>"",
                        "att_type"=>"",
                        "checker"=>$checker,
                        "np_hours"=>"",
                        "ot_hours"=>"",
                        "shift_sched"=>$shift_sched,
                        "break_hours"=>$break_hrs,
                        "is_active" => $is_active
                    ];
            }
            $cnt = $cnt + 1;
        }
        
        return view('NormalEmployees.dtr',[
            'attendance' => $attendance,
            'attendance1' => $attendance1,
            'date_from' => $date_from,
            'date_to' => $date_to
        ]);
    }


    public function manual_entry_dtr(Request $request){
        
        $row_cnt = count($request->input('employee_number'));
        
        for($cnt=0;$cnt<$row_cnt;$cnt++){
            $guid = mt_rand();
            $am_in=$request->input('in_am')[$cnt];
            $out_pm= $request->input('out_pm')[$cnt];
            $remarks = "";
            $shift_id=0;
            if( !is_null($request->input('in_am')) && isset($request->input('in_am')[$cnt]) ){
                $am_in = $request->input('dtr_date')[$cnt]." ".$request->input('in_am')[$cnt].":00";
            }
            if( !is_null($request->input('out_pm')) && isset($request->input('out_pm')[$cnt])){
                $out_pm = $request->input('dtr_date')[$cnt]." ".$request->input('out_pm')[$cnt].":00";
            }
            if( !is_null($request->input('att_type')) && isset($request->input('att_type')[$cnt]) ){
                $remarks = $request->input('att_type')[$cnt];
            }
            if( !is_null($request->input('shift_code_id')) && isset($request->input('shift_code_id')[$cnt]) ){
                $shift_id = $request->input('shift_code_id')[$cnt];
            }
            if($request->input('shift_code_id') == 1){
                $am_in = null;
                $out_pm = null;
            }

            if($request->input('SysPK_emp_attendance')[$cnt] == "0"){
                if($request->input('in_am')[$cnt] && $request->input('out_pm')[$cnt]){
                    DB::table('hr_emp_attendance')->insert(
                        [   
                            'SysPK_emp_attendance' => $guid,
                            'attendance_id' => 0,
                            'employee_number' => $request->input('employee_number')[$cnt],
                            'employee_fullName' =>$request->input('employee_fullName')[$cnt],
                            'dtr_date' => date('Y-m-d',strtotime($request->input('dtr_date')[$cnt])),
                            'in_am' => $am_in,
                            'out_pm' => $out_pm,
                            'entry_type' => 1,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_type' => $remarks,
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => 0,
                            'late' => $request->input('late')[$cnt],
                            'undertime' => $request->input('undertime')[$cnt],
                            'np_hours' => $request->input('np_hours')[$cnt],
                            'total_hours_worked' => $request->input('total_hours_worked')[$cnt],
                            'shift_code_id' => $request->input('shift_code_id')[$cnt],
                            'break_hours'=>$request->input('break')[$cnt],
                        ]
                    );
                }
                elseif($request->input('in_am')[$cnt]==null && $request->input('out_pm')[$cnt]){
                    DB::table('hr_emp_attendance')->insert(
                        [   
                            'SysPK_emp_attendance' => $guid,
                            'attendance_id' => 0,
                            'employee_number' => $request->input('employee_number')[$cnt],
                            'employee_fullName' =>$request->input('employee_fullName')[$cnt],
                            'dtr_date' => date('Y-m-d',strtotime($request->input('dtr_date')[$cnt])),
                            'out_pm' => $out_pm,
                            'entry_type' => 1,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_type' => $remarks ,
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => 0,
                            'late' => $request->input('late')[$cnt],
                            'undertime' => $request->input('undertime')[$cnt],
                            'np_hours' => $request->input('np_hours')[$cnt],
                            'total_hours_worked' => $request->input('total_hours_worked')[$cnt],
                            'break_hours'=>$request->input('break')[$cnt],
                            'shift_code_id' => $request->input('shift_code_id')[$cnt],
                        ]
                    );
                }
                elseif($request->input('in_am')[$cnt] && $request->input('out_pm')[$cnt]==null){
                    DB::table('hr_emp_attendance')->insert(
                        [   
                            'SysPK_emp_attendance' => $guid,
                            'attendance_id' => 0,
                            'entry_type' => 1,
                            'employee_number' => $request->input('employee_number')[$cnt],
                            'employee_fullName' =>$request->input('employee_fullName')[$cnt],
                            'dtr_date' => date('Y-m-d',strtotime($request->input('dtr_date')[$cnt])),
                            'in_am' => $am_in,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_type' => $remarks,
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => 0,
                            'late' => $request->input('late')[$cnt],
                            'undertime' => $request->input('undertime')[$cnt],
                            'np_hours' => $request->input('np_hours')[$cnt],
                            'total_hours_worked' => $request->input('total_hours_worked')[$cnt],
                            'break_hours'=>$request->input('break')[$cnt],
                            'shift_code_id' => $request->input('shift_code_id')[$cnt],
                        ]
                    );
                }
            }
            elseif($request->input('SysPK_emp_attendance')[$cnt] != "0"){

                if($request->input('in_am')[$cnt] && $request->input('out_pm')[$cnt]){
                    DB::table('hr_emp_attendance')
                        ->where(
                                [
                                    ['SysPK_emp_attendance','=',$request->input('SysPK_emp_attendance')[$cnt]],
                                    ['employee_number','=',$request->input('employee_number')[$cnt]],
                                    ['dtr_date','=',date('Y-m-d',strtotime($request->input('dtr_date')[$cnt]))]
                                ]
                            )
                        ->update(
                        [   
                            'in_am' => $am_in,
                            'out_pm' => $out_pm,
                            'entry_type' => 1,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_type' => $remarks,
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => 0,
                            'late' => $request->input('late')[$cnt],
                            'undertime' => $request->input('undertime')[$cnt],
                            'np_hours' => $request->input('np_hours')[$cnt],
                            'total_hours_worked' => $request->input('total_hours_worked')[$cnt],
                            'break_hours'=>$request->input('break')[$cnt],
                            'shift_code_id' => $request->input('shift_code_id')[$cnt],
                        ]
                    );
                }
                elseif($request->input('in_am')[$cnt]==null && $request->input('out_pm')[$cnt]){
                    DB::table('hr_emp_attendance')
                        ->where(
                                [
                                    ['SysPK_emp_attendance','=',$request->input('SysPK_emp_attendance')[$cnt]],
                                    ['employee_number','=',$request->input('employee_number')[$cnt]],
                                    ['dtr_date','=',date('Y-m-d',strtotime($request->input('dtr_date')[$cnt]))]
                                ]
                            )
                        ->update(
                        [   
                            'out_pm' => $out_pm,
                            'entry_type' => 1,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_type' => $remarks,
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => 0,
                            'late' => $request->input('late')[$cnt],
                            'undertime' => $request->input('undertime')[$cnt],
                            'np_hours' => $request->input('np_hours')[$cnt],
                            'total_hours_worked' => $request->input('total_hours_worked')[$cnt],
                            'break_hours'=>$request->input('break')[$cnt],
                            'shift_code_id' => $request->input('shift_code_id')[$cnt],
                        ]
                    );
                }
                elseif($request->input('in_am')[$cnt] && $request->input('out_pm')[$cnt]==null){
                    DB::table('hr_emp_attendance')
                        ->where(
                                [
                                    ['SysPK_emp_attendance','=',$request->input('SysPK_emp_attendance')[$cnt]],
                                    ['employee_number','=',$request->input('employee_number')[$cnt]],
                                    ['dtr_date','=',date('Y-m-d',strtotime($request->input('dtr_date')[$cnt]))]
                                ]
                            )
                        ->update(
                        [   
                            'in_am' => $am_in,
                            'entry_type' => 1,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_type' => $remarks,
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => 0,
                            'late' => $request->input('late')[$cnt],
                            'undertime' => $request->input('undertime')[$cnt],
                            'np_hours' => $request->input('np_hours')[$cnt],
                            'total_hours_worked' => $request->input('total_hours_worked')[$cnt],
                            'break_hours'=>$request->input('break')[$cnt],
                            'shift_code_id' => $request->input('shift_code_id')[$cnt],
                        ]
                    );
                }
            }
        }
        
        return Redirect::back()->with('success_message','Employee DTR saved');
    }

    public function generate_dtr_summary(Request $request){
        $date_from = date('Y-m-d',strtotime( $request->input('Sdate_from') ) );
        $date_to = date('Y-m-d',strtotime( $request->input('Sdate_to') ) );

        $dtr_list = DB::SELECT(DB::RAW('CALL get_dtr("'.$date_from.'","'.$date_to.'")'));

        if(Count($dtr_list) != 0){
            foreach ($dtr_list as $list) {
                
            }
        }

    }

    public function upload_dtr_summary(Request $request){

        DB::table('hr_emp_attendance')
            ->where('dtr_date','>=',date('Y-m-d',strtotime( $request->input('date_from') ) ) )
            ->where('dtr_date','<=',date('Y-m-d',strtotime( $request->input('date_to') ) ) )
            ->delete();
        $data = Excel::toArray(new UsersImport, request()->file('dtr'));
        $employee = DB::Table('employees')->get()->toArray();
        $attendance = array();
        
        foreach ($data[6] as $dtr) {
            
            if (!is_numeric($dtr[0])) {
                continue;
            }
            //GET ALL THE ARRAY INDEX OF THE SPECIFIC EMPLOYEE
            $emp_array_index = array_keys( array_column($employee, "SysPK_Empl"),$dtr[25] );
            if (count($emp_array_index)==0) {
                continue;
            }
 
            $emp_id = $employee[$emp_array_index[0]]->SysPK_Empl;
            $emp_no = $employee[$emp_array_index[0]]->UserID_Empl;
            $date_from = date('Y-m-d',strtotime($request->input('date_from')));
            $date_to = date('Y-m-d',strtotime($request->input('date_to')));
            $attendance[] = [
                "date_from"=>$date_from,
                "date_to"=>$date_to,
                "emp_id"=>$emp_id,
                "emp_name"=>$dtr[1],
                "extra_zero"=>$dtr[2],
                "required_days"=>$dtr[3],
                "required_hours"=>$dtr[4],
                "actual_hour"=>$dtr[5],
                "actual_day"=>$dtr[6],
                "absent_hour"=>$dtr[9],
                "paternity_leave_hour"=>$dtr[10],
                "sil_hour"=>$dtr[11],
                "late_min"=>$dtr[12],
                "ut"=>$dtr[13],
                "emp_no"=>$emp_no,
                "ot"=>$dtr[14],
                "nd"=>$dtr[15],
                "sun_hr"=>$dtr[16],
                "sun_ot"=>$dtr[17],
                "sun_nd"=>$dtr[18],
                "spl_hr"=>$dtr[19],
                "spl_ot"=>$dtr[20],
                "spl_nd"=>$dtr[21],
                "hol_hr"=>$dtr[22],
                "hol_ot"=>$dtr[23],
                "hol_nd"=>$dtr[24],
            ];
        }
        
        if(!empty($attendance)){
            DB::table('dtr_summary')->insert($attendance);
        }

        return redirect('/import_dtr_main_office')
            ->with('success_message', 'Imported file!');
    }


    public function convert_excel_time($the_value){

            $total = $the_value * 24; //multiply by the 24 hours
            $hours = floor($total); //Gets the natural number part
            $minute_fraction = $total - $hours; //Now has only the decimal part
            $minutes = $minute_fraction * 60; //Get the number of minutes
            $hours = sprintf('%02d', $hours);// format two digits
            $minutes = sprintf('%02d', $minutes);// format two digits
            $display = $hours . ":" . $minutes;

            return $display;
    }

    function validateDate($date, $format = 'Y-m-d H:i'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function save_dtr2(Request $request){
        
        DB::table('hr_emp_attendance')
            ->where('dtr_date','>=',date('Y-m-d',strtotime( $request->input('date_from') ) ) )
            ->where('dtr_date','<=',date('Y-m-d',strtotime( $request->input('date_to') ) ) )
            ->delete();
        $data = Excel::toArray(new UsersImport, request()->file('dtr'));
        $attendance = array();
        //admin
        foreach ($data[1] as $admin) {

            if ($admin[2]=="Name") {
                continue;
            }

            if ($admin[5]=="" && $admin[6]=="") {
                continue;
            }

            $in_checker = strpos($admin[5], "V");
            $out_checker = strpos($admin[6], "V");

            if ($in_checker == 1) {
                continue;
            }

            if ($out_checker == 1) {
                continue;
            }

            $employee_number = $admin[1];
            $employee_fullName = $admin[2];
            $dtr_date = date('Y-m-d',strtotime($admin[4]));
            $in = $admin[5];
            $out = $admin[6];
            if (is_numeric($admin[5])) {
                $in = $this->convert_excel_time($admin[5]);
            }
            if (is_numeric($admin[6])) {
                $out = $this->convert_excel_time($admin[6]);
            }
            $time_in =  $dtr_date." ".$in;
            $time_out = $dtr_date." ".$out;
            if ( date(strtotime($time_in)) > date(strtotime($time_out)) ) {
                $time_out = date('Y-m-d H:i',strtotime($time_out."+ 1 day"));
            }
            $date_in = date_create($time_in);
            $date_out = date_create($time_out);

            $diff = date_diff($date_out,$date_in);

            $actual_hour = $diff->h + ($diff->i/60);
            $late = $admin[25];
            $ut = $admin[26];
            $ot = $admin[9] + $admin[10] + $admin[11] + $admin[14] + $admin[16] ;
            try {
                $np = $admin[29] + $admin[30] + $admin[31];
            } catch (Exception $e) {
                echo $admin[29];
                echo "<br>";
                echo $admin[30];
                echo "<br>";
                echo $admin[31];
                echo "<br>";
                die();
            }
            $np = $admin[29] + $admin[30] + $admin[31];

            $attendance[] = [
                                'SysPK_emp_attendance' => mt_rand(),
                                'attendance_id' => 0,
                                "employee_number"=>$employee_number,
                                "employee_fullName"=>$employee_fullName,
                                "dtr_date"=>$dtr_date,
                                "in_am"=>$time_in,
                                "out_pm"=>$time_out,
                                'am_note' => "",
                                'pm_note' => "",
                                'note_am_in' => "",
                                'note_am_out' => "",
                                'note_pm_in' => "",
                                'note_pm_out' => "",
                                'att_remarks' => "",
                                'OB_notes' => "",
                                'modify_user' => "",
                                "late"=>$late,
                                "undertime"=>$ut,
                                "total_hours_worked"=>$actual_hour,
                                "sched_time_in"=>$dtr_date." 08:00",
                                "sched_time_out"=>$dtr_date." 18:00",
                                "np_hours"=>$np,
                                "ot_hours"=>$ot
                            ]; 
        }
        //Production
        foreach ($data[2] as $admin) {

            if ($admin[2]=="Name") {
                continue;
            }

            if ($admin[5]=="" && $admin[6]=="") {
                continue;
            }

            $in_checker = strpos($admin[5], "V");
            $out_checker = strpos($admin[6], "V");

            if ($in_checker == 1) {
                continue;
            }

            if ($out_checker == 1) {
                continue;
            }



            $employee_number = $admin[1];
            $employee_fullName = $admin[2];
            $dtr_date = date('Y-m-d',strtotime($admin[4]));
            $in = $admin[5];
            $out = $admin[6];
            if (is_numeric($admin[5])) {
                $in = $this->convert_excel_time($admin[5]);
            }
            if (is_numeric($admin[6])) {
                $out = $this->convert_excel_time($admin[6]);
            }
            $time_in =  $dtr_date." ".$in;
            $time_out = $dtr_date." ".$out;
            if ( date(strtotime($time_in)) > date(strtotime($time_out)) ) {
                $time_out = date('Y-m-d H:i',strtotime($time_out."+ 1 day"));
            }
            $date_in = date_create($time_in);
            $date_out = date_create($time_out);

            $diff = date_diff($date_out,$date_in);

            $actual_hour = $diff->h + ($diff->i/60);
            $late = $admin[23];
            $ut = $admin[24];
            $ot = $admin[9] + $admin[10]  + $admin[13] + $admin[15] ;
            try {
                $np = $admin[27] + $admin[28] + $admin[29];
            } catch (Exception $e) {
                echo $admin[29];
                echo "<br>";
                echo $admin[30];
                echo "<br>";
                echo $admin[31];
                echo "<br>";
                die();
            }
            $np = $admin[27] + $admin[28] + $admin[29];

            $attendance[] = [
                                'SysPK_emp_attendance' => mt_rand(),
                                'attendance_id' => 0,
                                "employee_number"=>$employee_number,
                                "employee_fullName"=>$employee_fullName,
                                "dtr_date"=>$dtr_date,
                                "in_am"=>$time_in,
                                "out_pm"=>$time_out,
                                'am_note' => "",
                                'pm_note' => "",
                                'note_am_in' => "",
                                'note_am_out' => "",
                                'note_pm_in' => "",
                                'note_pm_out' => "",
                                'att_remarks' => "",
                                'OB_notes' => "",
                                'modify_user' => "",
                                "late"=>$late,
                                "undertime"=>$ut,
                                "total_hours_worked"=>$actual_hour,
                                "sched_time_in"=>$dtr_date." 08:00",
                                "sched_time_out"=>$dtr_date." 18:00",
                                "np_hours"=>$np,
                                "ot_hours"=>$ot
                            ]; 
        }

        //QC
        foreach ($data[3] as $admin) {

            if ($admin[2]=="Name") {
                continue;
            }

            if ($admin[5]=="" && $admin[6]=="") {
                continue;
            }

            $in_checker = strpos($admin[5], "V");
            $out_checker = strpos($admin[6], "V");

            if ($in_checker == 1) {
                continue;
            }

            if ($out_checker == 1) {
                continue;
            }



            $employee_number = $admin[1];
            $employee_fullName = $admin[2];
            $dtr_date = date('Y-m-d',strtotime($admin[4]));
            $in = $admin[5];
            $out = $admin[6];
            if (is_numeric($admin[5])) {
                $in = $this->convert_excel_time($admin[5]);
            }
            if (is_numeric($admin[6])) {
                $out = $this->convert_excel_time($admin[6]);
            }
            $time_in =  $dtr_date." ".$in;
            $time_out = $dtr_date." ".$out;
            if ( date(strtotime($time_in)) > date(strtotime($time_out)) ) {
                $time_out = date('Y-m-d H:i',strtotime($time_out."+ 1 day"));
            }
            $date_in = date_create($time_in);
            $date_out = date_create($time_out);

            $diff = date_diff($date_out,$date_in);

            $actual_hour = $diff->h + ($diff->i/60);
            $late = $admin[23];
            $ut = $admin[24];
            $ot = $admin[9] + $admin[10]  + $admin[13] + $admin[15] ;
            try {
                $np = $admin[27] + $admin[28] + $admin[29];
            } catch (Exception $e) {
                echo $admin[29];
                echo "<br>";
                echo $admin[30];
                echo "<br>";
                echo $admin[31];
                echo "<br>";
                die();
            }
            $np = $admin[27] + $admin[28] + $admin[29];

            $attendance[] = [
                                'SysPK_emp_attendance' => mt_rand(),
                                'attendance_id' => 0,
                                "employee_number"=>$employee_number,
                                "employee_fullName"=>$employee_fullName,
                                "dtr_date"=>$dtr_date,
                                "in_am"=>$time_in,
                                "out_pm"=>$time_out,
                                'am_note' => "",
                                'pm_note' => "",
                                'note_am_in' => "",
                                'note_am_out' => "",
                                'note_pm_in' => "",
                                'note_pm_out' => "",
                                'att_remarks' => "",
                                'OB_notes' => "",
                                'modify_user' => "",
                                "late"=>$late,
                                "undertime"=>$ut,
                                "total_hours_worked"=>$actual_hour,
                                "sched_time_in"=>$dtr_date." 08:00",
                                "sched_time_out"=>$dtr_date." 18:00",
                                "np_hours"=>$np,
                                "ot_hours"=>$ot
                            ]; 
        }

        //Warehouse
        foreach ($data[4] as $admin) {

            if ($admin[2]=="Name") {
                continue;
            }

            if ($admin[5]=="" && $admin[6]=="") {
                continue;
            }

            $in_checker = strpos($admin[5], "V");
            $out_checker = strpos($admin[6], "V");

            if ($in_checker == 1) {
                continue;
            }

            if ($out_checker == 1) {
                continue;
            }



            $employee_number = $admin[1];
            $employee_fullName = $admin[2];
            $dtr_date = date('Y-m-d',strtotime($admin[4]));
            $in = $admin[5];
            $out = $admin[6];
            if (is_numeric($admin[5])) {
                $in = $this->convert_excel_time($admin[5]);
            }
            if (is_numeric($admin[6])) {
                $out = $this->convert_excel_time($admin[6]);
            }
            $time_in =  $dtr_date." ".$in;
            $time_out = $dtr_date." ".$out;
            if ( date(strtotime($time_in)) > date(strtotime($time_out)) ) {
                $time_out = date('Y-m-d H:i',strtotime($time_out."+ 1 day"));
            }
            $date_in = date_create($time_in);
            $date_out = date_create($time_out);

            $diff = date_diff($date_out,$date_in);

            $actual_hour = $diff->h + ($diff->i/60);
            $late = $admin[23];
            $ut = $admin[24];
            $ot = $admin[9] + $admin[10]  + $admin[13] + $admin[15] ;
            try {
                $np = $admin[27] + $admin[28] + $admin[29];
            } catch (Exception $e) {
                echo $admin[29];
                echo "<br>";
                echo $admin[30];
                echo "<br>";
                echo $admin[31];
                echo "<br>";
                die();
            }
            $np = $admin[27] + $admin[28] + $admin[29];

            $attendance[] = [
                                'SysPK_emp_attendance' => mt_rand(),
                                'attendance_id' => 0,
                                "employee_number"=>$employee_number,
                                "employee_fullName"=>$employee_fullName,
                                "dtr_date"=>$dtr_date,
                                "in_am"=>$time_in,
                                "out_pm"=>$time_out,
                                'am_note' => "",
                                'pm_note' => "",
                                'note_am_in' => "",
                                'note_am_out' => "",
                                'note_pm_in' => "",
                                'note_pm_out' => "",
                                'att_remarks' => "",
                                'OB_notes' => "",
                                'modify_user' => "",
                                "late"=>$late,
                                "undertime"=>$ut,
                                "total_hours_worked"=>$actual_hour,
                                "sched_time_in"=>$dtr_date." 08:00",
                                "sched_time_out"=>$dtr_date." 18:00",
                                "np_hours"=>$np,
                                "ot_hours"=>$ot
                            ]; 
        }

        if(!empty($attendance)){
            DB::table('hr_emp_attendance')->insert($attendance);
        }

        return redirect('/import_dtr_main_office')
            ->with('success_message', 'Imported file!');
    }

    public function save_dtr(Request $request){
        
        $cnt = 0;
        $attendance = array();
        $update_attendance = array();
        //Start DTR Has File
        if($request->hasFile('dtr')){

            $absent_checker = 0;
            $date_from = date('Y-m-d',strtotime($request->input('date_from')));
            $date_to = date('Y-m-d',strtotime($request->input('date_to')));
            $data = Excel::toArray(new UsersImport, request()->file('dtr'));
            $employees = DB::table('employees')
                        ->whereRAW('(Status_Empl="REGULAR") OR  (Status_Empl="PROBATIONARY")')
                        ->get();
            $leave = DB::TABLE('leaves')
                ->whereRaw('is_approved = 1 AND (leave_date_from BETWEEN "'.$date_from.'" AND "'.$date_to.'") OR (leave_date_to BETWEEN "'.$date_from.'" AND "'.$date_to.'")')
                ->get();

            $holiday = DB::TABLE('hr_holidays')
                ->whereRaw('holiday_date BETWEEN "'.$date_from.'" AND "'.$date_to.'"')
                ->get();

            $emp_sched = DB::TABLE('shift_monitoring')
                ->whereRaw('date_from <= "'.$date_from.'" AND  date_to >= "'.$date_to.'"')
                ->where('shift_monitoring.is_deleted', "!=", 1)
                ->get();

            $ot_list = DB::TABLE('approved_ot')
                ->whereRaw('dtr_date <= "'.$date_from.'" AND  dtr_date >= "'.$date_to.'"')
                ->get();

            $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
            $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            
            
            //-------------Start Emplooyee Loop-----------------------
            foreach ($employees as $emp) {
                $emp_id = $emp->biometric_id;
                $employee_number = $emp->UserID_Empl;
                $employee_fullName = $emp->Name_Empl;
                $start_time = $emp->TimeStart_Empl;
                $end_time = $emp->TimeEnd_Empl;
                $late_hrs=0;
                $ut_hrs=0;
                $ot_hrs=0;
                $ndif = 0;
                $work_hrs=0;

                //GET EMPLOYEE OVERTIME
                // $emp_ot = array_keys( array_column($ot_list,'employee_id'),$emp_id );
                //GET ALL THE ARRAY INDEX OF THE SPECIFIC EMPLOYEE
                $emp_array_index = array_keys( array_column($data[0], 2),$emp_id );
                //COUNT ALL DTR OF SPECIFIC EMPLOYEE
                $emp_record_cnt = count($emp_array_index);
                
                //CHECK IF EMPLOYEE HAS RECORD IF NONE PROCEED TO NEXT EMPLOYEE
                if ($emp_record_cnt==0) {
                    continue;
                }

                //-----START PERIOD LOOP-------------------------
                foreach ($period as $wrk_date ) {
                    $absent_checker = 1;
                    foreach ($holiday as $holiday_data) {
                        if( $holiday_data->holiday_date == $wrk_date->format("Y-m-d")  ) 
                        {
                            $holiday_checker = 1;
                            break;
                        }
                    }

                    //START EXCELL DATA LOOP
                    foreach ($emp_array_index as $index ) {
                        if ($data[0][$index][0]=="Department") {
                            continue;
                        }
                        $EXCEL_DATE = str_replace('/', '-', $data[0][$index][3]);
                        $UNIX_DATE=strtotime($EXCEL_DATE);
                        

                        //START TRY CATCH UNIX DATE
                        try{
                            $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;

                        } catch (Exception $e) {
                            $UNIX_DATE="";
                        }
                        //END TRY CATCH UNIX DATE
                            

                        //START COMPARE IF EMPLOYEE DATA FROM EXCELL
                        if( ($emp_id == $data[0][$index][2]) && 
                            ($wrk_date->format("Y-m-d")== gmdate("Y-m-d", $UNIX_DATE) ) ){
                            
                            $att_checker=0;
                            

                            //GET ALL THE ARRAY INDEX OF THE ATTENDANCE ARRAY
                            $att_array = array_keys( array_column($attendance, 'employee_number'),$employee_number );
                            //COUNT ALL DTR OF SPECIFIC EMPLOYEE
                            $att_array_cnt =0;
                            $att_array_cnt = count($att_array);

                            //START CHECK IF ATTENDANCE HAS RECORDS
                            if ($att_array_cnt!=0) {
                                // echo date('Y-m-d',strtotime(gmdate("Y-m-d", $UNIX_DATE)));
                                // die();
                                $work_hrs=0;
                                $ut_hrs = 0;
                                $late_hrs=0;
                                $ot_hrs = 0;
                                $ndif = 0;
                                        
                                $official_time_in=$wrk_date->format("Y-m-d")." ".$start_time;
                                $official_time_out=$wrk_date->format("Y-m-d")." ".$end_time;
                                $data_date=gmdate("Y-m-d H:i:s", $UNIX_DATE);
                                

                                $compare_date_out = gmdate("Y-m-d H:i:s", strtotime($wrk_date->format("Y-m-d")." 8:00:00") );

                                $out_checker=0;
                                // if($diff->h <= 4 && $data[0][$index][4]=="C/In"){
                                //     $out_checker=1;
                                // }
                                //CHECK IF EMPLOYEE IS NIGHT SHIFT
                                if( ( $data_date <= $compare_date_out ) &&
                                    ($data[0][$index][4]=="C/Out" || $data[0][$index][4]== "OverTime Out") ){
                                    
                                    //START LOOP LOOKING FOR EXISTING ATTENDANCE DATA    
                                    foreach( $att_array as $i ){
                                        if( ( $attendance[$i]['dtr_date']==date("Y-m-d",strtotime($data_date.'-1 day')) )  && 
                                            $attendance[$i]['employee_number']==$employee_number ){

                                            $attendance[$i]['out_pm']=gmdate("Y-m-d H:i:00", $UNIX_DATE);

                                            if(($attendance[$i]['in_am']!="") && ($attendance[$i]['out_pm']!="") ){
                                                $time_in = date_create($attendance[$i]['dtr_date']." ".$start_time);
                                                $time_out = date_create($attendance[$i]['dtr_date']." ".$end_time);
                                                if($time_in < date_create($attendance[$i]['in_am'])){
                                                    $late_dif = date_diff($time_in,date_create($attendance[$i]['in_am']));
                                                    $late_hrs=$late_dif->h + ($late_dif->i/60);
                                                }
                                                if($time_out > date_create($attendance[$i]['out_pm'])){
                                                    $ut_dif=date_diff(date_create($attendance[$i]['out_pm']),$time_out );
                                                    $ut_hrs=$ut_dif->h + ($ut_dif->i/60);
                                                }

                                                $str_night_dif_start=$attendance[$i]['dtr_date']." 22:00:00";
                                                $str_night_dif_end=date("Y-m-d",strtotime($data_date))." 6:00:00";

                                                $night_dif_start=date_create($str_night_dif_start);
                                                $night_dif_end=date_create($str_night_dif_end);

                                                $work_in = date_create($attendance[$i]['in_am']);
                                                $work_out = date_create($attendance[$i]['out_pm']);

                                                // $work_in = $time_in;
                                                // $work_out = $time_out;
                                                // if( $time_in < date_create($attendance[$i]['in_am']) ){
                                                //     $work_in =date_create($attendance[$i]['in_am']);
                                                // }
                                                // if( $time_out > date_create($attendance[$i]['out_pm']) ){
                                                //     $work_out=date_create($attendance[$i]['out_pm']);
                                                // }
                                                                                                
                                                $wrk_dif = date_diff($work_in,$work_out);
                                                $work_hrs=$wrk_dif->h + ($wrk_dif->i/60);
                                                if($work_hrs >= 6){
                                                    $work_hrs = $work_hrs - 1;
                                                }

                                                if (gmdate("w", $UNIX_DATE)==0) {
                                                    $late_hrs = 0;
                                                    $ut_hrs=0;
                                                }

                                                //COMPUTE NIGHT DIFF
                                                if( ($work_out >= $night_dif_end)  ){
                                                    if ( $work_in <= $night_dif_start) {
                                                        $ndif = date_diff($night_dif_start,$night_dif_end)->h;
                                                    }
                                                    elseif( $work_in > $night_dif_start ){
                                                        $ndif = date_diff($work_in,$night_dif_end)->h;
                                                    }
                                                    
                                                }
                                                elseif( ($work_out <= $night_dif_end)  ){
                                                    if ( $work_in <= $night_dif_start) {
                                                        $ndif = date_diff($night_dif_start,$work_out)->h;
                                                    }
                                                    elseif( $work_in > $night_dif_start ){
                                                        $ndif = date_diff($work_in,$work_out)->h;
                                                    }
                                                }
                                                //CHECK OVERTIME
                                                echo "string";
                                                die();
                                                if (count($emp_ot)>0) {
                                                    echo "string";
                                                    die();
                                                }

                                                $attendance[$i]['np_hours']=$ndif;
                                                $attendance[$i]['ot_hours']=$ot_hrs;
                                                $attendance[$i]['late']=$late_hrs;
                                                $attendance[$i]['undertime']=$ut_hrs;
                                                $attendance[$i]['total_hours_worked']=$work_hrs;
                                            }

                                            $att_checker=1;
                                            break;
                                        }
                                    }
                                    //END LOOP LOOKING FOR EXISTING ATTENDANCE DATA
                                }
                                //END CHECK IF EMPLOYEE IS NIGHT SHIFT

                                //CHECK IF ATTENDANCE ALREADY PROCESSED
                                if($att_checker==0){
                                    //START LOOP LOOKING FOR EXISTING ATTENDANCE DATA
                                    foreach ($att_array as $i ) {
                                        //START COMPARE IF ATTENDANCE DATE EQUAL TO EXCEL DATE
                                        if( ( $attendance[$i]['dtr_date']==gmdate("Y-m-d", $UNIX_DATE) )  && $attendance[$i]['employee_number']==$employee_number){

                                                //START CHECK IF EXCEL DATE IS AM IN
                                                if($data[0][$index][4]=="C/In"){
                                                    if($attendance[$i]['in_am']==""){
                                                        $attendance[$i]['in_am']=gmdate("Y-m-d H:i:00", $UNIX_DATE);
                                                    }
                                                    if($out_checker==1){
                                                        $attendance[$i]['out_pm']=gmdate("Y-m-d H:i:00", $UNIX_DATE);
                                                    }
                                                }
                                                //END CHECK IF EXCEL DATE IS AM IN
                                                //START CHECK IF EXCEL DATE IS PM OUT
                                                elseif($data[0][$index][4]=="C/Out" || $data[0][$index][4]== "OverTime Out"){
                                                    $attendance[$i]['out_pm']=gmdate("Y-m-d H:i:00", $UNIX_DATE);
                                                }
                                                //END CHECK IF EXCEL DATE IS PM OUT

                                                if(($attendance[$i]['in_am']!="") && ($attendance[$i]['out_pm']!="") ){
                                                    $time_in = date_create($attendance[$i]['dtr_date']." ".$start_time);
                                                    $time_out = date_create($attendance[$i]['dtr_date']." ".$end_time);
                                                    if($time_in < date_create($attendance[$i]['in_am'])){
                                                        $late_dif = date_diff($time_in,date_create($attendance[$i]['in_am']));
                                                        $late_hrs=$late_dif->h + ($late_dif->i/60);
                                                    }
                                                    if($time_out > date_create($attendance[$i]['out_pm'])){
                                                        $ut_dif=date_diff(date_create($attendance[$i]['out_pm']),$time_out );
                                                        $ut_hrs=$ut_dif->h + ($ut_dif->i/60);
                                                    }

                                                    $work_in = $time_in;
                                                    $work_out = $time_out;

                                                    if( $time_in < date_create($attendance[$i]['in_am']) ){
                                                        $work_in =date_create($attendance[$i]['in_am']);
                                                    }
                                                    if( $time_out > date_create($attendance[$i]['out_pm']) ){
                                                        $work_out=date_create($attendance[$i]['out_pm']);
                                                    }
                                                    
                                                    $str_night_dif_start=$attendance[$i]['dtr_date']." 22:00:00";
                                                    $str_night_dif_end=date("Y-m-d",strtotime($data_date.'+1 day'))." 6:00:00";

                                                    $night_dif_start=date_create($str_night_dif_start);
                                                    $night_dif_end=date_create($str_night_dif_end);

                                                    $wrk_dif = date_diff($work_in,$work_out);
                                                    $work_hrs=$wrk_dif->h + ($wrk_dif->i/60);
                                                    if($work_hrs >= 6){
                                                        $work_hrs = $work_hrs - 1;
                                                    }

                                                    if (gmdate("w", $UNIX_DATE)==0) {
                                                        $late_hrs = 0;
                                                        $ut_hrs=0;
                                                    }

                                                    //COMPUTE NIGHT DIFF
                                                    if( ($work_out >= $night_dif_start)  ){
                                                        $ndif = date_diff($night_dif_start,$work_out)->h;
                                                    }

                                                    $attendance[$i]['np_hours']=$ndif;
                                                    $attendance[$i]['ot_hours']=$ot_hrs;
                                                    $attendance[$i]['late']=$late_hrs;
                                                    $attendance[$i]['undertime']=$ut_hrs;
                                                    $attendance[$i]['total_hours_worked']=$work_hrs;
                                                }

                                                $att_checker=1;
                                                break;
                                        }
                                    }
                                    ////END LOOP LOOKING FOR EXISTING ATTENDANCE DATA
                                }
                                //END CHECK IF ATTENDANCE ALREADY PROCESS
                            }
                            //END CHECK IF ATTENDANCE HAS RECORDS
                            

                            //START IF ATTENDANCE IS NOT FOUND
                            if($att_checker==0 ){
                                //START IF ATTENDANCE IS AM IN
                                if( $data[0][$index][4]=="C/In"){
                                    $attendance[] = [
                                        'SysPK_emp_attendance' => mt_rand(),
                                        'attendance_id' => 0,
                                        "employee_number"=>$employee_number,
                                        "employee_fullName"=>$employee_fullName,
                                        "dtr_date"=>gmdate("Y-m-d", $UNIX_DATE),
                                        "in_am"=>gmdate("Y-m-d H:i:00", $UNIX_DATE),
                                        "out_pm"=>null,
                                        'am_note' => "",
                                        'pm_note' => "",
                                        'note_am_in' => "",
                                        'note_am_out' => "",
                                        'note_pm_in' => "",
                                        'note_pm_out' => "",
                                        'att_remarks' => "",
                                        'OB_notes' => "",
                                        'modify_user' => "",
                                        "late"=>0,
                                        "undertime"=>0,
                                        "np_hours"=>0,
                                        "ot_hours"=>0,
                                        "total_hours_worked"=>0,
                                        "sched_time_in"=>gmdate("Y-m-d", $UNIX_DATE)." ".$start_time,
                                        "sched_time_out"=>gmdate("Y-m-d", $UNIX_DATE)." ".$end_time
                                    ];     
                                }
                                //END IF ATTENDANCE IS AM IN
                                //START IF ATTENDANCE IS PM OUT
                                elseif( $data[0][$index][4]=="C/Out" || $data[0][$index][4]== "OverTime Out"){
                                    $attendance[] = [
                                        'SysPK_emp_attendance' => mt_rand(),
                                        'attendance_id' => 0,
                                        "employee_number"=>$employee_number,
                                        "employee_fullName"=>$employee_fullName,
                                        "dtr_date"=>gmdate("Y-m-d", $UNIX_DATE),
                                        "in_am"=>null,
                                        "out_pm"=>gmdate("Y-m-d H:i:00", $UNIX_DATE),
                                        'am_note' => "",
                                        'pm_note' => "",
                                        'note_am_in' => "",
                                        'note_am_out' => "",
                                        'note_pm_in' => "",
                                        'note_pm_out' => "",
                                        'att_remarks' => "",
                                        'OB_notes' => "",
                                        'modify_user' => "",
                                        "late"=>0,
                                        "undertime"=>0,
                                        "np_hours"=>0,
                                        "ot_hours"=>0,
                                        "total_hours_worked"=>0,
                                        "sched_time_in"=>gmdate("Y-m-d", $UNIX_DATE)." ".$start_time,
                                        "sched_time_out"=>gmdate("Y-m-d", $UNIX_DATE)." ".$end_time
                                    ];  
                                }
                                //END IF ATTENDANCE IS PM OUT
                            }
                            //END IF ATTENDANCE IS NOT FOUND 
                        }
                        //END COMPARE IF EMPLOYEE DATA FROM EXCELL
                        //START ELSE IF EMPLOYEE DATA FROM EXCELL DOES NOT EXIST
                        else
                            continue;
                        
                        //END ELSE IF EMPLOYEE DATA FROM EXCELL DOES NOT EXIST
                    }
                    //END EXCEL DATA LOOP
                }
                //-----END PERIOD LOOP-------------------------
            }
            //-------------END Emplooyee Loop-----------------------
        }
        //End DTR Has File

        // foreach($attendance as $list){
        //  echo "Name: ".$list['employee_fullName']."<br>";
        //  echo "Date: ".$list['dtr_date']."<br>";
        //  echo "In  : ".$list['in_am']."<br>";
        //  echo "Out : ".$list['out_pm']."<br><hr>";
        // }
        // die();

        if(!empty($attendance)){
            DB::table('hr_emp_attendance')->insert($attendance);
        }

        return redirect('/import_dtr_main_office')
            ->with('success_message', 'Imported file!');
    }

    public function import_dtr_main_office(Request $request){
        
        return view('DTR.import_dtr_main_office');
    }

    public function save_dtr_main_office(Request $request){
        
        $cnt = 0;
        $attendance = array();
        $update_attendance = array();
        //Start DTR Has File
        if($request->hasFile('dtr')){

            $date_from = date('Y-m-d',strtotime($request->input('date_from')));
            $date_to = date('Y-m-d',strtotime($request->input('date_to')));
            $data = Excel::toArray(new UsersImport, request()->file('dtr'));
            $employees = DB::table('employees')
                        ->whereRAW('(Status_Empl="REGULAR") OR  (Status_Empl="PROBATIONARY")')
                        ->get();
            $leave = DB::TABLE('leaves')
                ->whereRaw('is_approved = 1 AND (leave_date_from BETWEEN "'.$date_from.'" AND "'.$date_to.'") OR (leave_date_to BETWEEN "'.$date_from.'" AND "'.$date_to.'")')
                ->get();


            $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
            $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            
            
            
            //-------------Start Emplooyee Loop-----------------------
            foreach ($employees as $emp) {
                $emp_id = $emp->biometric_id;
                $employee_number = $emp->UserID_Empl;
                $employee_fullName = $emp->Name_Empl;
                $start_time = $emp->TimeStart_Empl;
                $end_time = $emp->TimeEnd_Empl;
                $late_hrs=0;
                $ut_hrs=0;
                $ot_hrs=0;
                $work_hrs=0;

                // var_dump(array_column($data[0], 2));
                // die();
                //GET ALL THE ARRAY INDEX OF THE SPECIFIC EMPLOYEE
                $emp_array_index = array_keys( array_column($data[0], 2),$emp_id );
                $emp_record_cnt = count($emp_array_index);
                var_dump($emp_array_index);
                die();

                //-----START PERIOD LOOP-------------------------
                foreach ($period as $wrk_date ) {

                    //START EXCELL DATA LOOP
                    foreach ($data[0] as $row ) {
                        if ($row[0]=="Department") {
                            continue;
                        }
                        $EXCEL_DATE = str_replace('/', '-', $row[3]);
                        $UNIX_DATE=strtotime($EXCEL_DATE);
                        
                        //if ($UNIX_DATE=="") {
                            //START TRY CATCH UNIX DATE
                        //    try {
                        //        $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;

                        //    } catch (Exception $e) {
                         //       $UNIX_DATE="";
                        //    }
                            //END TRY CATCH UNIX DATE
                        //}

                            //START TRY CATCH UNIX DATE
                            try {
                                $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;

                            } catch (Exception $e) {
                                $UNIX_DATE="";
                            }
                            //END TRY CATCH UNIX DATE
                            
                            //echo $wrk_date->format("Y-m-d")." ".$start_time."<br>";
                            //echo gmdate("Y-m-d H:i:s", $UNIX_DATE)."<br>";
                            
                        //START COMPARE IF EMPLOYEE DATA FROM EXCELL
                        if( ($emp_id == $row[2]) && 
                            ($wrk_date->format("Y-m-d")== gmdate("Y-m-d", $UNIX_DATE) ) ){

                            $att_checker=0;
                            
                            
                            //START COMPARE IF ATTENDANCE HAS DATA
                            if (count($attendance)!=0) {
                                //START LOOP LOOKING FOR EXISTING DATA
                                foreach ($attendance as &$att ) {
                                    //START COMPARE IF ATTENDANCE DATE EQUAL TO EXCEL DATE
                                    if( ( $att['dtr_date']==gmdate("Y-m-d", $UNIX_DATE) )  && $att['employee_number']==$employee_number){

                                            $work_hrs=0;
                                            $ut_hrs = 0;
                                            $late_hrs=0;
                                        
                                            $official_time_in=$wrk_date->format("Y-m-d")." ".$start_time;
                                            $official_time_out=$wrk_date->format("Y-m-d")." ".$end_time;
                                            $data_date=gmdate("Y-m-d H:i:s", $UNIX_DATE);
                                            $diff= date_diff(date_create($official_time_out),date_create($data_date));

                                            $out_checker=0;
                                            if($diff->h <= 4 && $row[4]=="C/In"){
                                                $out_checker=1;
                                            }
                                            //START CHECK IF EXCEL DATE IS AM IN
                                            if($row[4]=="C/In"){
                                                if($att['in_am']==""){
                                                    $att['in_am']=gmdate("Y-m-d H:i:00", $UNIX_DATE);
                                                }
                                                if($out_checker==1){
                                                    $att['out_pm']=gmdate("Y-m-d H:i:00", $UNIX_DATE);
                                                }
                                            }
                                            //END CHECK IF EXCEL DATE IS AM IN
                                            //START CHECK IF EXCEL DATE IS PM OUT
                                            elseif($row[4]=="C/Out" || $row[4]== "OverTime Out"){
                                                $att['out_pm']=gmdate("Y-m-d H:i:00", $UNIX_DATE);
                                            }
                                            //END CHECK IF EXCEL DATE IS PM OUT

                                            if(($att['in_am']!="") && ($att['out_pm']!="") ){
                                                $time_in = date_create($att['dtr_date']." ".$start_time);
                                                $time_out = date_create($att['dtr_date']." ".$end_time);
                                                if($time_in < date_create($att['in_am'])){
                                                    $late_dif = date_diff($time_in,date_create($att['in_am']));
                                                    $late_hrs=$late_dif->h + ($late_dif->i/60);
                                                }
                                                if($time_out > date_create($att['out_pm'])){
                                                    $ut_dif=date_diff(date_create($att['out_pm']),$time_out );
                                                    $ut_hrs=$ut_dif->h + ($ut_dif->i/60);
                                                }

                                                $work_in = $time_in;
                                                $work_out = $time_out;

                                                if( $time_in < date_create($att['in_am']) ){
                                                    $work_in =date_create($att['in_am']);
                                                }
                                                if( $time_out > date_create($att['out_pm']) ){
                                                    $work_out=date_create($att['out_pm']);
                                                }
                                                
                                                $wrk_dif = date_diff($work_in,$work_out);
                                                $work_hrs=$wrk_dif->h + ($wrk_dif->i/60);
                                                if($work_hrs >= 6){
                                                    $work_hrs = $work_hrs - 1;
                                                }

                                                if (gmdate("w", $UNIX_DATE)==0) {
                                                    $late_hrs = 0;
                                                    $ut_hrs=0;
                                                }

                                                $att['late']=$late_hrs;
                                                $att['undertime']=$ut_hrs;
                                                $att['total_hours_worked']=$work_hrs;
                                            }

                                            $att_checker=1;
                                            break;
                                    }
                                    //END COMPARE IF ATTENDANCE DATE EQUAL TO EXCEL DATE
                                }
                                //END LOOP LOOKING FOR EXISTING DATA
                            }
                            //END COMPARE IF ATTENDANCE HAS DATA

                            //START IF ATTENDANCE IS NOT FOUND
                            if($att_checker==0 ){
                                //START IF ATTENDANCE IS AM IN
                                if( $row[4]=="C/In"){
                                    $attendance[] = [
                                        'SysPK_emp_attendance' => mt_rand(),
                                        'attendance_id' => 0,
                                        "employee_number"=>$employee_number,
                                        "employee_fullName"=>$employee_fullName,
                                        "dtr_date"=>gmdate("Y-m-d", $UNIX_DATE),
                                        "in_am"=>gmdate("Y-m-d H:i:00", $UNIX_DATE),
                                        "out_pm"=>null,
                                        'am_note' => "",
                                        'pm_note' => "",
                                        'note_am_in' => "",
                                        'note_am_out' => "",
                                        'note_pm_in' => "",
                                        'note_pm_out' => "",
                                        'att_remarks' => "",
                                        'OB_notes' => "",
                                        'modify_user' => "",
                                        "late"=>0,
                                        "undertime"=>0,
                                        "total_hours_worked"=>0,
                                        "sched_time_in"=>gmdate("Y-m-d", $UNIX_DATE)." ".$start_time,
                                        "sched_time_out"=>gmdate("Y-m-d", $UNIX_DATE)." ".$end_time
                                    ];     
                                }
                                //END IF ATTENDANCE IS AM IN
                                //START IF ATTENDANCE IS PM OUT
                                elseif( $row[4]=="C/Out" || $row[4]== "OverTime Out"){
                                    $attendance[] = [
                                        'SysPK_emp_attendance' => mt_rand(),
                                        'attendance_id' => 0,
                                        "employee_number"=>$employee_number,
                                        "employee_fullName"=>$employee_fullName,
                                        "dtr_date"=>gmdate("Y-m-d", $UNIX_DATE),
                                        "in_am"=>null,
                                        "out_pm"=>gmdate("Y-m-d H:i:00", $UNIX_DATE),
                                        'am_note' => "",
                                        'pm_note' => "",
                                        'note_am_in' => "",
                                        'note_am_out' => "",
                                        'note_pm_in' => "",
                                        'note_pm_out' => "",
                                        'att_remarks' => "",
                                        'OB_notes' => "",
                                        'modify_user' => "",
                                        "late"=>0,
                                        "undertime"=>0,
                                        "total_hours_worked"=>0,
                                        "sched_time_in"=>gmdate("Y-m-d", $UNIX_DATE)." ".$start_time,
                                        "sched_time_out"=>gmdate("Y-m-d", $UNIX_DATE)." ".$end_time
                                    ];  
                                }
                                //END IF ATTENDANCE IS PM OUT
                            }
                            //END IF ATTENDANCE IS NOT FOUND 
                        }
                        //END COMPARE IF EMPLOYEE DATA FROM EXCELL
                        //START ELSE IF EMPLOYEE DATA FROM EXCELL DOES NOT EXIST
                        else
                            continue;
                        
                        //END ELSE IF EMPLOYEE DATA FROM EXCELL DOES NOT EXIST
                    }
                    //END EXCEL DATA LOOP
                }
                //-----END PERIOD LOOP-------------------------
            }
            //-------------END Emplooyee Loop-----------------------
       }
       //End DTR Has File
        // foreach($attendance as $list){
        //  echo "Name: ".$list['employee_fullName']."<br>";
        //  echo "Date: ".$list['dtr_date']."<br>";
        //  echo "In  : ".$list['in_am']."<br>";
        //  echo "Out : ".$list['out_pm']."<br><hr>";
        // }
        // die();
        if(!empty($attendance)){
            DB::table('hr_emp_attendance')->insert($attendance);
        }

        return redirect('/import_dtr_main_office')
            ->with('success_message', 'Imported file!');
    }
    
    public function get_dtr_main_office(Request $request){

        $cnt = 0;
        $attendance = array();
        if($request->hasFile('dtr'))
        {
            $data = Excel::toArray(new UsersImport, request()->file('dtr'));
            $employees = DB::table('employees')->get();

            $date_from = date('Y-m-d',strtotime($request->input('date_from')));
            $date_to = date('Y-m-d',strtotime($request->input('date_to')));

            foreach ($data[0] as $row) 
            {
                 
              $emp_id = 0;
              $employee_number = 0;
              $employee_fullName = "";
              $start_time = "08:00:00";
              $end_time = "17:00:00";
              $late_hrs=0;
              $ut_hrs=0;
              $ot_hrs=0;
              $work_hrs=0;

               // if(date_create($dtr_date) < date_create($date_from) || 
               //      date_create($dtr_date) > date_create($date_to) ) 
               //    {
               //      continue;
               //    }

              foreach ($employees as $emp) 
              {
                if($emp->biometric_id==$row[2])
                {
                    $emp_id = $emp->biometric_id;
                    $employee_number = $emp->UserID_Empl;
                    $employee_fullName = $emp->Name_Empl;
                    $start_time = $emp->TimeStart_Empl;
                    $end_time = $emp->TimeEnd_Empl;
                    break;
                }
              }

                if($emp_id == 0)
                {
                    continue;
                }
                else{
                    $EXCEL_DATE = $row[3];
                    $UNIX_DATE="";

                    try {

                        $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;

                    } catch (Exception $e) {
                        $UNIX_DATE="";
                    }
                    $att_checker=0;

                    if (count($attendance)!=0) {
                        foreach ($attendance as &$att ) {
                            if( ( $att['dtr_date']==gmdate("Y-m-d", $UNIX_DATE) )  && $att['employee_number']==$employee_number){
                                if($row[4]=="C/In"){
                                    if($att['in_am']==""){
                                        $att['in_am']=gmdate("Y-m-d H:i:s", $UNIX_DATE);
                                    }
                                    
                                }
                                elseif($row[4]=="C/Out"){
                                    $att['out_pm']=gmdate("Y-m-d H:i:s", $UNIX_DATE);
                                }

                                if(($att['in_am']!="") && ($att['out_pm']!="") ){
                                    $time_in = date_create($att['dtr_date']." ".$start_time);
                                    $time_out = date_create($att['dtr_date']." ".$end_time);
                                    if($time_in < date_create($att['in_am'])){
                                        $late_dif = date_diff($time_in,date_create($att['in_am']));
                                        $late_hrs=$late_dif->h + ($late_dif->i/60);
                                    }
                                    if($time_out > date_create($att['out_pm'])){
                                        $ut_dif=date_diff(date_create($att['out_pm']),$time_out );
                                        $ut_hrs=$ut_dif->h + ($ut_dif->i/60);
                                    }
                                    $work_in = $time_in;
                                    $work_out = $time_out;

                                    if( $time_in < date_create($att['in_am']) ){
                                        $work_in =date_create($att['in_am']);
                                    }
                                    if( $time_out > date_create($att['out_pm']) ){
                                        $work_out=date_create($att['out_pm']);
                                    }
                                    
                                    $wrk_dif = date_diff($work_in,$work_out);
                                    $work_hrs=$wrk_dif->h + ($wrk_dif->i/60);
                                    if($work_hrs >= 6){
                                        $work_hrs = $work_hrs - 1;
                                    }
                                    $att['late']=$late_hrs;
                                    $att['undertime']=$ut_hrs;
                                    $att['total_hours_worked']=$work_hrs;

                                }

                                $att_checker=1;
                                break;
                            }
                        }
                    }

                    if($att_checker==0){
                        if($row[4]=="C/In"){
                            $attendance[] = [
                                'SysPK_emp_attendance' => mt_rand(),
                                'attendance_id' => 0,
                                "employee_number"=>$employee_number,
                                "employee_fullName"=>$employee_fullName,
                                "dtr_date"=>gmdate("Y-m-d", $UNIX_DATE),
                                "in_am"=>gmdate("Y-m-d H:i:s", $UNIX_DATE),
                                "out_pm"=>null,
                                'am_note' => "",
                                'pm_note' => "",
                                'note_am_in' => "",
                                'note_am_out' => "",
                                'note_pm_in' => "",
                                'note_pm_out' => "",
                                'att_remarks' => "",
                                'OB_notes' => "",
                                'modify_user' => "",
                                "late"=>0,
                                "undertime"=>0,
                                "total_hours_worked"=>0,
                                "sched_time_in"=>gmdate("Y-m-d", $UNIX_DATE)." ".$start_time,
                                "sched_time_out"=>gmdate("Y-m-d", $UNIX_DATE)." ".$end_time
                            ];     
                         }
                        elseif($row[4]=="C/Out"){
                            $attendance[] = [
                                'SysPK_emp_attendance' => mt_rand(),
                                'attendance_id' => 0,
                                "employee_number"=>$employee_number,
                                "employee_fullName"=>$employee_fullName,
                                "dtr_date"=>gmdate("Y-m-d", $UNIX_DATE),
                                "in_am"=>null,
                                "out_pm"=>gmdate("Y-m-d H:i:s", $UNIX_DATE),
                                'am_note' => "",
                                'pm_note' => "",
                                'note_am_in' => "",
                                'note_am_out' => "",
                                'note_pm_in' => "",
                                'note_pm_out' => "",
                                'att_remarks' => "",
                                'OB_notes' => "",
                                'modify_user' => "",
                                "late"=>0,
                                "undertime"=>0,
                                "total_hours_worked"=>0,
                                "sched_time_in"=>gmdate("Y-m-d", $UNIX_DATE)." ".$start_time,
                                "sched_time_out"=>gmdate("Y-m-d", $UNIX_DATE)." ".$end_time
                            ];  
                        }
                    }
                }     
            }
        }

        if(!empty($attendance)){
            DB::table('hr_emp_attendance')->insert($attendance);
        }

        return redirect('/import_dtr_main_office')
            ->with('attendance',$attendance)
            ->with('success_message', 'Imported file!');
    }

    public function import_dtr(Request $request){
        $this->validate($request, [
            'dtr' => 'required',
            'date_from' => 'required',
            'date_to' => 'required'
        ]);

        $upload = 0;
        if ($request->hasFile('dtr')) {
            if ($request->file('dtr')->isValid()) {
                $check = getimagesize($_FILES["dtr"]["tmp_name"]);
                $dir = "storage/uploads/dtr/";
                // $file_path = 'dtr_'.date('m_d_y').'.'.$request->file('dtr')->getClientOriginalExtension();
                
                if ($check !== false || ($request->dtr->getClientOriginalExtension() != 'xls' && $request->dtr->getClientOriginalExtension() != 'xlsx')) {
                    return redirect('/import_dtr?invalid_file_ext='.$request->dtr->extension())->with('invalid','Invalid file!');
                }
                else{
                    $file_path = 'dtr_'.date('m_d_y').'.'.$request->file('dtr')->getClientOriginalExtension();
                    $full_path = $dir.$file_path;
                    //DELETE KUNG SAME UG NAME OR GI BALIK NA THEN UPLOAD BALIK
                    if (file_exists($full_path)) {
                        unlink($full_path);
                    }
                    //upload the file
                    $request->file('dtr')->move("storage/uploads/dtr", $file_path);
                    $upload = 1;
                    return redirect('/import_dtr?imported_file='.$file_path.'&date_from='.$request->input('date_from').'&date_to='.$request->input('date_to').'&upload_type='.$request->input('upload_type'))
                    ->with('success_message', 'Imported file!')
                    ->with('imported_file' , $full_path)
                    ->with('upload', $upload);
                }
            }
        }
    }

    public function save_dtr_notepad(Request $request){   
        
        $this->validate($request, [
            'imported_file' => 'required',
            'log_id' => 'required'

        ]);

        $validator = Validator::make($request->all(),
        [
            'log_id' => 'unique:emp_imported_dtr,log_id'
        ]);

        if ($validator->fails()) {

            return redirect("/import_dtr?imported_file=".$request->input('file_path')."&data_already_uploaded")->with('invalid','Some data already uploaded to database! Please check the data first!');
        }

        $file = fopen($request->input('imported_file'),"r");
        $ctr = 0;
        $data_att = null;
        while(! feof($file)){
            if ($ctr != 0){
                $new_file = trim(fgets($file));
                $get_date_time = substr(strrev($new_file), 0,20);
                $data_att[] = array(
                    'log_id' => substr($new_file, 0,6),
                    'employee_id' => substr($new_file, 9,9),
                    'employee_name' => substr($new_file, 19,12),
                    'date' => substr(strrev($get_date_time), 0,10),
                    'time' => substr(strrev($get_date_time), 12,8)
                );
            }
            else{
                fgets($file);
            }
        $ctr++;
        }

        // result array
        $data = [];
        // Loop thru array
        foreach ($data_att as $value) {
            // Map thru $value with trim to remove \n then push to result
            $value['date_time'] = date('Y-m-d', strtotime($value['date'])).' '.$value['time'];
            //current date
            $value['created_at'] = date('Y-m-d H:i:s');
            unset($value['date']);
            unset($value['time']);
            $value['emp_id'] = $value['employee_id'];
            $data[] = array_map('trim', $value);

        }

        Import_dtr::insert($data);
        return redirect('/import_dtr?imported_file='.$request->input('file_path').'&data_uploaded')
        ->with('success_message', 'DTR successfully saved to database!')
        ->with('imported_file' ,$request->input('imported_file'))//full path na ni
        ->with('save_data', 1)//check if the data uploaded to the database
        ->with('upload', 1);
    }

    public function dtr_list(Request $request){
        
        $company = DB::table('company')->select('company_id','company')->get();
        
        $company_id = Input::get('company_selected');
        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');
        
        if(is_null($date_from)){
          $date_from = date('Y-m-d');
        }

        if(is_null($date_to)){
          $date_to = date('Y-m-d');
        }
        
        $dtr_list=array();
        if($company_id){
            $dept_id_selected = 0;
            $dept_selected = DB::table('company')->where('company_id',$company_id)->get();
            if (!empty($dept_selected)) {
                $dept_id_selected = $dept_selected[0]->company_id;
            }
            $dtr_list = DB::SELECT(DB::RAW('CALL get_dtr_byDepartment("'.$dept_id_selected.'","'.$date_from.'","'.$date_to.'")'));
        }
        elseif($company_id==0){
            
            $dtr_list = DB::SELECT(DB::RAW('CALL get_dtr("'.$date_from.'","'.$date_to.'")'));
        }
        
        session::put(['dtr_list_print'=>$dtr_list]);
        $params = [
            "company"=>$company,
            'date_from'=>$date_from,
            'date_to'=>$date_to,
            'company_id'=>$company_id,
            "dtr_list"=>$dtr_list
        ];
        // var_dump($dtr_list);
        // die;
        return view('DTR.dtr_list', $params);
    }

    public function dtr_summary(Request $request){
        
        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');
        $company_id= Input::get('company_id');

        if(is_null($date_from)){
          $date_from = date('Y-m-d');
        }

        if(is_null($date_to)){
          $date_to = date('Y-m-d');
        }
        if(is_null($company_id)){
            $company_id = 0;
        }
        $dtr_list = DB::SELECT(DB::RAW('CALL get_dtr_summary('.$company_id.',"'.$date_from.'","'.$date_to.'")'));
        
        session::put(['dtr_list_print'=>$dtr_list]);
        
        $company = DB::TABLE('company')->get();

        $params = [
            'date_from'=>$date_from,
            'date_to'=>$date_to,
            "dtr_list"=>$dtr_list,
            "company"=>$company,
            'company_id'=>$company_id
        ];

        return view('DTR.dtr_summary', $params);
    }

    public function incomplete_dtr_list(Request $request){
        $deparment = DB::table('company')->select('company_id','company')->get();
        
        $dept_id = Input::get('deparment');
        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');

        if(is_null($date_from)){
          $date_from = date('Y-m-d');
        }

        if(is_null($date_to)){
          $date_to = date('Y-m-d');
        }
        $dtr_list = DB::TABLE('hr_emp_attendance')
                        ->join('employees', 'employees.UserID_Empl', '=', 'hr_emp_attendance.employee_number')
                        ->whereRaw('dtr_date BETWEEN "'.$date_from.'" AND "'.$date_to.'" AND (ISNULL(in_am) OR ISNULL(out_pm))')
                        ->select('hr_emp_attendance.*')
                        ->get();
        if($dept_id){
            $dtr_list = DB::TABLE('hr_emp_attendance')
                        ->join('employees', 'employees.UserID_Empl', '=', 'hr_emp_attendance.employee_number')
                        ->where('employees.company_id','=',$dept_id)
                        ->whereRaw('dtr_date BETWEEN "'.$date_from.'" AND "'.$date_to.'" AND (ISNULL(in_am) OR ISNULL(out_pm))')
                        ->select('hr_emp_attendance.*')
                        ->get();
        }  
          
        return view('DTR.incomplete_dtr_list',[
                                    'date_from'=>$date_from,
                                    'date_to'=>$date_to,
                                    "dtr_list"=>$dtr_list,
                                    'deparment'=>$deparment
                                ]);
    }

    public function normalize_dtr(){

        return view('DTR.normalize_dtr');
    }

    public function add_normalize_dtr(Request $request){

        $this->validate($request, [
            'date_from' => 'required',
            'date_to' => 'required'
        ]);
        
        $date_from = date('Y-m-d',strtotime($request->input('date_from')));
        $date_to = date('Y-m-d',strtotime($request->input('date_to')));
        $employee = DB::TABLE('employees')
                ->whereRaw('Status_Empl="PROBATIONARY" OR Status_Empl="REGULAR"')
                ->orderBy('UserID_Empl')
                ->get();
        $leave = DB::TABLE('leaves')
                ->whereRaw('is_approved = 1 AND (leave_date_from BETWEEN "'.$date_from.'" AND "'.$date_to.'") OR (leave_date_to BETWEEN "'.$date_from.'" AND "'.$date_to.'")')
                ->get();
	   $holiday = DB::TABLE('hr_holidays')
                ->whereRaw('holiday_date BETWEEN "'.$date_from.'" AND "'.$date_to.'"')
                ->get();

        $att = DB::TABLE('hr_emp_attendance')
                        ->where('dtr_date', '>=', $date_from )
                        ->where('dtr_date', '<=', $date_to )
                        ->get();

        $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
        $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $attendance = array();
        foreach($employee as $emp){
            $date_checker = 0;
            foreach($period as $dates){
                $att_checker = 0;
		$holiday_checker = 0;
                foreach ($att as $att_data) {
                    if( ($att_data->employee_number == $emp->UserID_Empl) && 
                        ($att_data->dtr_date == $dates->format("Y-m-d") ) ) 
                    {
                            $att_checker = 1;
                            break;
                    }
                }
		
		foreach ($holiday as $holiday_data) {
                    if( $holiday_data->holiday_date == $dates->format("Y-m-d")  ) 
                    {
                            $holiday_checker = 1;
                            break;
                    }
                }

                if( $dates->format("w") == 0){
                    continue;
                }

                if($att_checker == 0 && $holiday_checker == 0)
                {
                    if($emp->require_dtr==0){
                        $attendance[] = [
                            'SysPK_emp_attendance' => mt_rand(),
                            'attendance_id' => 0,
                            'entry_type' => 1,
                            "employee_number"=>$emp->UserID_Empl,
                            "employee_fullName"=>$emp->Name_Empl,
                            "dtr_date"=>$dates->format("Y-m-d"),
                            "in_am"=>$dates->format("Y-m-d")." ".$emp->TimeStart_Empl,
                            "out_pm"=>$dates->format("Y-m-d")." ".$emp->TimeEnd_Empl,
                            'am_note' => "",
                            'pm_note' => "",
                            'note_am_in' => "",
                            'note_am_out' => "",
                            'note_pm_in' => "",
                            'note_pm_out' => "",
                            'att_remarks' => "",
                            'OB_notes' => "",
                            'modify_user' => "",
                            'cost_center' => $emp->cost_center_id,
                            "late"=>0,
                            "undertime"=>0,
                            "total_hours_worked"=>8,
                            "sched_time_in"=>$dates->format("Y-m-d")." ".$emp->TimeStart_Empl,
                            "sched_time_out"=>$dates->format("Y-m-d")." ".$emp->TimeEnd_Empl,
                        ];
                    }
                    $absent_checker = 0;
                }
            }
        }

        if(!empty($attendance)){
            DB::table('hr_emp_attendance')->insert($attendance);
        }

        return redirect('/normalize_dtr')
            ->with('success_message', 'Successfully Normalize');
    }

    public function late_dtr_list(Request $request){

        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');

        if(is_null($date_from)){
          $date_from = date('Y-m-d');
        }

        if(is_null($date_to)){
          $date_to = date('Y-m-d');
        }
        $dtr_list = DB::TABLE('hr_emp_attendance')
                        ->whereRaw('late > 0')
                        ->where('dtr_date', '>=', $date_from )
                        ->where('dtr_date', '<=', $date_to )
                        ->get();
          
          
        return view('DTR.late_dtr_list',[
                                    'date_from'=>$date_from,
                                    'date_to'=>$date_to,
                                    "dtr_list"=>$dtr_list
                                ]);
    }

    public function print_dtr_list(){
      return view('DTR.print_dtr_list');
    }

    public function update_dtr(Request $request){
        
        $ot_checker = DB::TABLE('approved_ot')
                        ->WHERE('employee_number',$request->input('employee_number'))
                        ->WHERE('dtr_date',date('Y-m-d',strtotime( $request->input('dtr_date') )) )
                        ->GET();
        $emp = DB::TABLE('employees')->WHERE('UserID_Empl',$request->input('employee_number'))->get();


        $am_in=$request->input('in_am');
        $out_pm= $request->input('out_pm');

        if($request->input('in_am')){
            $am_in = $request->input('dtr_date')." ".$request->input('in_am').":00";
        }
        if($request->input('out_pm')){
            $out_pm = $request->input('dtr_date')." ".$request->input('out_pm').":00";
        }

        if($request->input('in_am') && $request->input('out_pm')){
            DB::table('hr_emp_attendance')
                ->where(
                    [
                        ['employee_number','=',$request->input('employee_number')],
                        ['dtr_date','=',date('Y-m-d',strtotime( $request->input('dtr_date') ))]
                    ]
                )
                ->update(
                    [   
                        'in_am' => $am_in,
                        'out_pm' => $out_pm,
                        'entry_type' => 1,
                        'att_remarks' => $request->input('att_remarks'),
                        'ot_hours' => $request->input('ot_hours'),
                        'np_hours' => $request->input('np_hours'),
                        'late' => $request->input('late'),
                        'undertime' => $request->input('undertime'),
                        'total_hours_worked' => $request->input('total_hours_worked')
                    ]
                );
        }
        elseif($request->input('in_am')==null && $request->input('out_pm')){
            DB::table('hr_emp_attendance')
                ->where(
                    [
                        ['employee_number','=',$request->input('employee_number')],
                        ['dtr_date','=',date('Y-m-d',strtotime( $request->input('dtr_date') ))]
                    ]
                )
                ->update(
                    [   
                        'out_pm' => $out_pm,
                        'entry_type' => 1,
                        'att_remarks' => $request->input('att_remarks'),
                        'ot_hours' => $request->input('ot_hours'),
                        'np_hours' => $request->input('np_hours'),
                        'late' => $request->input('late'),
                        'undertime' => $request->input('undertime'),
                        'total_hours_worked' => $request->input('total_hours_worked')
                    ]
                );
        }
        elseif($request->input('in_am') && $request->input('out_pm')==null){
            DB::table('hr_emp_attendance')
                ->where(
                    [
                        ['employee_number','=',$request->input('employee_number')],
                        ['dtr_date','=',date('Y-m-d',strtotime( $request->input('dtr_date') ))]
                    ]
                )
                ->update(
                    [   
                        'in_am' => $am_in,
                        'entry_type' => 1,
                        'att_remarks' => $request->input('att_remarks'),
                        'ot_hours' => $request->input('ot_hours'),
                        'np_hours' => $request->input('np_hours'),
                        'late' => $request->input('late'),
                        'undertime' => $request->input('undertime'),
                        'total_hours_worked' => $request->input('total_hours_worked')
                    ]
                );
        }

        if ($request->input('ot_hours')) {
            if ($request->input('ot_hours') > 0) {
                if (count($ot_checker)==0) {
                   DB::TABLE('approved_ot')
                   ->insert(
                    [
                        'employee_id'=>$emp[0]->SysPK_Empl,
                        'employee_number'=>$emp[0]->UserID_Empl,
                        'dtr_date'=>date('Y-m-d',strtotime( $request->input('dtr_date') )),
                        'OT_Hours'=>$request->input('ot_hours') ,
                        'remarks'=>"EDITED",
                        'Time_Start'=>date('H:i:s',strtotime(  $out_pm.'- 4 hours' )),
                        'Time_End'=>date('H:i:s',strtotime(  $out_pm )),
                   ]);
                }
                else{
                    DB::table('approved_ot')
                    ->where(
                        [
                            ['employee_number','=',$request->input('employee_number')],
                            ['dtr_date','=',date('Y-m-d',strtotime( $request->input('dtr_date') ))]
                        ]
                    )
                    ->update(
                        [   
                            'employee_id'=>$emp[0]->SysPK_Empl,
                            'employee_number'=>$emp[0]->UserID_Empl,
                            'dtr_date'=>date('Y-m-d',strtotime( $request->input('dtr_date') )),
                            'OT_Hours'=>$request->input('ot_hours') ,
                            'remarks'=>"EDITED",
                            'Time_Start'=>date('H:i:s',strtotime(  $out_pm.'- 4 hours' )),
                            'Time_End'=>date('H:i:s',strtotime(  $out_pm )),
                        ]
                    );
                }
            }
        }
        else{
            if (count($ot_checker) > 0) {
                    DB::table('approved_ot')
                        ->where(
                            [
                                ['employee_number','=',$request->input('employee_number')],
                                ['dtr_date','=',date('Y-m-d',strtotime( $request->input('dtr_date') ))]
                            ]
                        )
                        ->delete();
            }
        }

        return response()->json(['success'=>'Successfully Updated']);
    }

    public function update_dtr_summary(Request $request){
        echo $request->input('dtr_sum_id');
        DB::TABLE('dtr_editable')
            ->where(
                [
                    ['id','=',$request->input('id')]
                ]
            )
            ->update(
                [
                    'total_hours'=>$request->input('total_hours') ,
                    'meal_allowance'=>$request->input('meal_allowance') ,
                    'ot'=>$request->input('ot') ,
                    'rd'=>$request->input('rd') ,
                    'rdot'=>$request->input('rdot') ,
                    'np'=>$request->input('np') ,
                    'absent'=>$request->input('absent') ,
                    'late'=>$request->input('late') ,
                    'ut'=>$request->input('ut') ,
                    'lh'=>$request->input('lh') ,
                    'lhot'=>$request->input('lhot') ,
                    'lhrd'=>$request->input('lhrd') ,
                    'lhrdot'=>$request->input('lhrdot') ,
                    'sh'=>$request->input('sh') ,
                    'shot'=>$request->input('shot') ,
                    'shrd'=>$request->input('shrd') ,
                    'shrdot'=>$request->input('shrdot') ,
                    'sh2'=>$request->input('sh2') ,
                    'shot2'=>$request->input('shot2') ,
                    'shrd2'=>$request->input('shrd2') ,
                    'shrdot2'=>$request->input('shrdot2') ,
                    'lh2'=>$request->input('lh2') ,
                    'lhot2'=>$request->input('lhot2') ,
                    'lhrd2'=>$request->input('lhrd2') ,
                    'lhrdot2'=>$request->input('lhrdot2') ,
                    'lhsh'=>$request->input('lhsh') ,
                    'lhshot'=>$request->input('lhshot') ,
                    'lhshrd'=>$request->input('lhshrd') ,
                    'lhshrdot'=>$request->input('lhshrdot') ,
                    'vl_wp'=>$request->input('vl_wp') ,
                    'sl_wp'=>$request->input('sl_wp') ,
                    'vl_wop'=>$request->input('vl_wop') ,
                    'sl_wop'=>$request->input('sl_wop') ,
                    'spl'=>$request->input('spl') ,
                    'bl'=>$request->input('bl') ,
                    'ml'=>$request->input('ml') ,
                    'pl'=>$request->input('pl') ,
                ]
            );

        return response()->json(['success'=>'Successfully Updated']);
    }

    public function inquire_dtr(){
        
        $employee_number = Input::get('employee_number');
        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');

        $attendance = DB::table('hr_emp_attendance')
            ->where('employee_number','=',$employee_number)
            ->whereBetween('dtr_date', [$date_from, $date_to])
            ->get();

        return response()->json(['attendance'=>$attendance]);
    }

    public function import_dtr_dat_old(Request $request){
        
        $this->validate($request, [
            'imported_file' => 'required',
            'log_id' => 'required'
    
        ]);

        $date_from = date('Y-m-d',strtotime($request->input('from_date')));
        $date_to = date('Y-m-d',strtotime($request->input('to_date')));
        $shift = DB::table('shift_monitoring')
                ->join('shift_code', 'shift_monitoring.shift_code_id', '=', 'shift_code.id')
                ->select('shift_monitoring.emp_id','shift_monitoring.shift_date','shift_code.time_start','shift_code.time_end','shift_monitoring.no_hr_break')
                ->where('shift_monitoring.shift_date','>=',date('Y-m-d',strtotime( $request->input('from_date') ) ) )
                ->where('shift_monitoring.shift_date','<=',date('Y-m-d',strtotime( $request->input('to_date') ) ) )
                ->where('shift_monitoring.is_deleted', "!=", 1)
                ->get()
                ->toArray();
        $employees = DB::table('employees')
                    ->whereRAW('(Status_Empl!="DISMISSED") OR  (Status_Empl!="RESIGNED")')
                    ->get();
        //DELETE EXISTING DATA           
        // DB::table('hr_emp_attendance')
        //         ->where('dtr_date','>=',date('Y-m-d',strtotime( $request->input('from_date') ) ) )
        //         ->where('dtr_date','<=',date('Y-m-d',strtotime( $request->input('to_date') ) ) )
        //         ->delete();
        
        $validator = Validator::make($request->all(),
            [
                'log_id' => 'unique:emp_imported_dtr,log_id'
            ]);
    
        if ($validator->fails()) {
    
            return redirect("/import_dtr?imported_file=".$request->input('file_path')."&data_already_uploaded")->with('invalid','Some data already uploaded to database! Please check the data first!');
        }
    
        $file = fopen($request->input('imported_file'),"r");
        $ctr = 0;
        $data_att = null;
        while(! feof($file))
        {
               $new_file = trim(fgets($file));
               $str = $new_file;
               $delimiter = ' ';
               $dtr = explode(' ', $str);
               if (count($dtr)==1) {
                   continue;
               }
               $get_date_time = strrev(substr(strrev($dtr[0]), 0,10)).' '.substr($dtr[1], 0,8);//REVERSE FIRST BEFORE WE GET THE STRING
               $date = strrev(substr(strrev($dtr[0]), 0,10));
               $time = substr($dtr[1], 0,8);
               $employee_id = strrev(substr(strrev($dtr[0]), 10,10));//REVERSE FIRST BEFORE WE GET THE STRING
               $log_type = substr($dtr[1], 11,1);//REVERSE FIRST BEFORE WE GET THE STRING

                $log_id = $employee_id.$ctr;
                //INSERT TO ARRAY BASE ON THE DATE SELECTED
                if (strtotime($date) >= strtotime($request->input('from_date')) && strtotime($date) <= strtotime($request->input('to_date'))) {
                    $data_att[] = array(
                        'log_id' => $log_id,
                        'employee_id' => trim($employee_id),
                        'date' => $date,
                        'time' => $time,
                        'log_type' => $log_type
                    );
                }
            $ctr++;
        }

        $cnt = 0;
        $attendance = array();
        $update_attendance = array();
        $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
        $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        //-------------Start Emplooyee Loop-----------------------
        foreach ($employees as $emp) {
            $emp_pk = $emp->SysPK_Empl;
            $emp_id = $emp->biometric_id;
            $employee_number = $emp->UserID_Empl;
            $employee_fullName = $emp->Name_Empl;
            $start_time_am = "8:00:00";
            $end_time_am = "12:00:00";
            $start_time_pm = "13:00:00";
            $end_time_pm = "17:00:00";
            $late_hrs=0;
            $ut_hrs=0;
            $ot_hrs=0;
            $ndif = 0;
            $work_hrs=0;
            $break_hrs = 0;
    
            //GET ALL THE ARRAY INDEX OF THE SPECIFIC EMPLOYEE
            $emp_array_index = array_keys( array_column($data_att, "employee_id"),$emp_id );
            //COUNT ALL DTR OF SPECIFIC EMPLOYEE
            
            $emp_record_cnt = count($emp_array_index);

    
            //CHECK IF EMPLOYEE HAS RECORD IF NONE PROCEED TO NEXT EMPLOYEE
            if ($emp_record_cnt==0) {
                continue;
            }

            //GET ALL THE ARRAY INDEX OF THE SPECIFIC EMPLOYEE SHIFT
            $shift_array_index = array_keys( array_column($shift, "emp_id"),$emp_pk );

            //COUNT ALL DTR OF SPECIFIC EMPLOYEE SHIFT
            $shift_record_cnt = count($shift_array_index);
    
            //CHECK IF EMPLOYEE HAS RECORD IF NONE PROCEED TO NEXT EMPLOYEE SHIFT
            $shift_monitoring = array();

            if ($shift_record_cnt!=0) {
                //GET SHIFT MONITORING OF EMPLOYEE
                foreach ($shift_array_index as $index) {
                    $shift_monitoring[] = array(
                        'emp_id' => $shift[$index]->emp_id,
                        'shift_date' => $shift[$index]->shift_date,
                        'time_start' => $shift[$index]->time_start,
                        'time_end' => $shift[$index]->time_end,
                        'no_hr_break' => $shift[$index]->no_hr_break
                    );
                }
            }
            
            foreach ($period as $wrk_date ) {
                $att_checker=0;
                foreach ($emp_array_index as $index ) {
                    if( $wrk_date->format("Y-m-d") == date("Y-m-d", strtotime($data_att[$index]['date']) ) )
                    {
                        $sm_array_index = array_keys( array_column($shift_monitoring, "shift_date"),$wrk_date->format("Y-m-d") );

                        $official_time_in_am=$wrk_date->format("Y-m-d")." ".$start_time_am;
                        $official_time_out_pm=$wrk_date->format("Y-m-d")." ".$end_time_pm;

                        $with_shift = 0;
                        if(count($sm_array_index)!=0)
                        {
                            $with_shift = 1;
                            $official_time_in_am=$wrk_date->format("Y-m-d")." ".$shift_monitoring[$sm_array_index[0]]['time_start'];
                            $official_time_out_pm=$wrk_date->format("Y-m-d")." ".$shift_monitoring[$sm_array_index[0]]['time_end'];
                            $break_hrs = $shift_monitoring[$sm_array_index[0]]['no_hr_break'];
                        }
                        $att_time = $data_att[$index]['date']." ".$data_att[$index]['time'];
    
                        $date_time_in_am = date("Y-m-d H:i:s", strtotime($official_time_in_am) );
                        $date_time_out_pm = date("Y-m-d H:i:s", strtotime($official_time_out_pm) );
                        $date_att_time = date("Y-m-d H:i:s", strtotime($att_time) );
                        
                        //GET ALL THE ARRAY INDEX OF THE ATTENDANCE ARRAY
                        $att_array = array_keys( array_column($attendance, 'employee_number'),$employee_number );
                        //COUNT ALL DTR OF SPECIFIC EMPLOYEE
                        $att_array_cnt =0;
                        $att_array_cnt = count($att_array);
    
                        if($att_checker==0 ){
                            //START IF ATTENDANCE IS AM IN
                            if( $data_att[$index]["log_type"]==0  ){
                                $attendance[] = [
                                    'SysPK_emp_attendance' => mt_rand(),
                                    'attendance_id' => 0,
                                    "employee_number"=>$employee_number,
                                    "employee_fullName"=>$employee_fullName,
                                    "dtr_date"=>date("Y-m-d", strtotime($data_att[$index]['date'])),
                                    "in_am"=>$date_att_time,
                                    "out_am"=>null,
                                    "in_pm"=>null,
                                    "out_pm"=>null,
                                    'am_note' => "",
                                    'pm_note' => "",
                                    'note_am_in' => "",
                                    'note_am_out' => "",
                                    'note_pm_in' => "",
                                    'note_pm_out' => "",
                                    'att_remarks' => "",
                                    'OB_notes' => "",
                                    'modify_user' => "",
                                    "late"=>0,
                                    "undertime"=>0,
                                    "np_hours"=>0,
                                    "ot_hours"=>0,
                                    "total_hours_worked"=>0,
                                    "sched_time_in"=>$with_shift!=0 ? $date_time_in_am : null,
                                    "sched_time_out"=>$with_shift!=0 ? $date_time_out_pm : null
                                ];     
                            }
                            else if($data_att[$index]["log_type"]==1){
                                $attendance[] = [
                                    'SysPK_emp_attendance' => mt_rand(),
                                    'attendance_id' => 0,
                                    "employee_number"=>$employee_number,
                                    "employee_fullName"=>$employee_fullName,
                                    "dtr_date"=>date("Y-m-d", strtotime($data_att[$index]['date'])),
                                    "in_am"=>null,
                                    "out_am"=>null,
                                    "in_pm"=>null,
                                    "out_pm"=>$date_att_time,
                                    'am_note' => "",
                                    'pm_note' => "",
                                    'note_am_in' => "",
                                    'note_am_out' => "",
                                    'note_pm_in' => "",
                                    'note_pm_out' => "",
                                    'att_remarks' => "",
                                    'OB_notes' => "",
                                    'modify_user' => "",
                                    "late"=>0,
                                    "undertime"=>0,
                                    "np_hours"=>0,
                                    "ot_hours"=>0,
                                    "total_hours_worked"=>0,
                                    "sched_time_in"=>$with_shift!=0 ? $date_time_in_am : null,
                                    "sched_time_out"=>$with_shift!=0 ? $date_time_out_pm : null
                                ];
                            }
                            $att_checker=1;
                        }
                        else
                        {
                            foreach( $att_array as $i ){
                                if( $attendance[$i]['dtr_date'] == date("Y-m-d",strtotime($data_att[$index]['date'])) )
                                {
                                    if( $data_att[$index]["log_type"]==0 &&  is_null($attendance[$i]['out_pm'])){
                                        $attendance[$i]['in_am']=$date_att_time;
                                    }
                                    else if( $data_att[$index]["log_type"]==0 && !is_null($attendance[$i]['out_pm']) ){
                                        $attendance[$i]['in_am']=$date_att_time;
    
                                        $work_in = date_create($date_time_in_am);
                                        $work_out = date_create($date_time_out_pm);
                                        $actual_in = date_create($attendance[$i]['in_am']);
                                        $actual_out = date_create($attendance[$i]['out_pm']);
                                        //COMPUTE AM WORK HOURS
                                        $wrk_dif = date_diff($work_in,$work_out);
                                        $work_hrs=$wrk_dif->h + ($wrk_dif->i/60);
    
                                        //COMPUTE LATE
                                        $late_hrs = 0;
                                        if ($actual_in > $work_in) {
                                            $late_dif = date_diff($actual_in,$work_in);
                                            $late_hrs = $late_dif->h + ($late_dif->i/60);
                                        }
                                        //COMPUTE UNDERTIME
                                        $ut_hrs = 0;
                                        if ($actual_out < $work_out) {
                                            $ut_dif = date_diff($actual_out,$work_out);
                                            $ut_hrs = $ut_dif->h + ($ut_dif->i/60);
                                        }
                                        $work_hrs = $work_hrs - $late_hrs - $ut_hrs - $break_hrs;
                                        $work_hrs=round($work_hrs,2);
                                        if($with_shift ==0)
                                        {
                                            $late_hrs = 0;
                                            $ut_hrs = 0;
                                            $work_hrs = 0;
                                        }
                                        $attendance[$i]['late']= $late_hrs;
                                        $attendance[$i]['undertime']= $ut_hrs;
                                        $attendance[$i]['total_hours_worked']=$work_hrs;
                                    }
                                    else if( $data_att[$index]["log_type"]==1 && is_null($attendance[$i]['in_am']) ){
                                        $attendance[$i]['out_pm']=$date_att_time;
                                    }
                                    else if( $data_att[$index]["log_type"]==1 && !is_null($attendance[$i]['in_am']) ){
                                        $attendance[$i]['out_pm']=$date_att_time;
    
                                        $work_in = date_create($date_time_in_am);
                                        $work_out = date_create($date_time_out_pm);
                                        $actual_in = date_create($attendance[$i]['in_am']);
                                        $actual_out = date_create($attendance[$i]['out_am']);
                                        //COMPUTE AM WORK HOURS
                                        $wrk_dif = date_diff($work_in,$work_out);
                                        $work_hrs=$wrk_dif->h + ($wrk_dif->i/60);
    
                                        //COMPUTE LATE
                                        $late_hrs = 0;
                                        if ($actual_in > $work_in) {
                                            $late_dif = date_diff($actual_in,$work_in);
                                            $late_hrs = $late_dif->h + ($late_dif->i/60);
                                        }
                                        //COMPUTE UNDERTIME
                                        $ut_hrs = 0;
                                        if ($actual_out < $work_out) {
                                            $ut_dif = date_diff($actual_out,$work_out);
                                            $ut_hrs = $ut_dif->h + ($ut_dif->i/60);
                                        }
                                        $work_hrs = $work_hrs - $late_hrs - $ut_hrs - $break_hrs;
                                        $work_hrs=round($work_hrs,2);
                                        if($with_shift ==0)
                                        {
                                            $late_hrs = 0;
                                            $ut_hrs = 0;
                                            $work_hrs = 0;
                                        }
                                        $attendance[$i]['late']= $late_hrs;
                                        $attendance[$i]['undertime']= $ut_hrs;
                                        $attendance[$i]['total_hours_worked']= $work_hrs;
                                    }
                                }
                            }
                        }
                    }
                }
            }
    
        }
        if(!empty($attendance)){
            DB::table('hr_emp_attendance')->insert($attendance);
        }
    
        return redirect('/import_dtr?imported_file='.$request->input('file_path').'&date_from='.$date_from.'&date_to='.$date_to)
        ->with('success_message', 'DTR successfully saved to database!')
        ->with('imported_file' ,$request->input('imported_file'))//full path na ni
        ->with('save_data', 1)//check if the data uploaded to the database
        ->with('upload', 1);
    }

    public function import_dtr_excel_old(Request $request){
        
        $this->validate($request, [
            'imported_file' => 'required',
            'log_id' => 'required'
    
        ]);

        $date_from = date('Y-m-d',strtotime($request->input('from_date')));
        $date_to = date('Y-m-d',strtotime($request->input('to_date')));

        //DELETE EXISTING DATA           
        // DB::table('hr_emp_attendance')
        //         ->where('dtr_date','>=',date('Y-m-d',strtotime( $request->input('from_date') ) ) )
        //         ->where('dtr_date','<=',date('Y-m-d',strtotime( $request->input('to_date') ) ) )
        //         ->delete();
        
        $validator = Validator::make($request->all(),
            [
                'log_id' => 'unique:emp_imported_dtr,log_id'
            ]);
    
        if ($validator->fails()) {
    
            return redirect("/import_dtr?imported_file=".$request->input('file_path')."&data_already_uploaded")->with('invalid','Some data already uploaded to database! Please check the data first!');
        }
    
        $imported_file = $request->input('imported_file');
        $ctr = 0;
        $data_att = null;

        if ($request->input('upload_type') == 'Excel2') {
            $data = Excel::toArray(new UsersImport(), $imported_file);
            foreach ($data[0] as $dtr) {
                $unix_date = ($dtr[1] - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;

                $date = gmdate('Y-m-d', $unix_date);
                $time = gmdate('H:i:00', $unix_date);
                $employee_id = $dtr[0];
                $log_id = $dtr[3];
                //INSERT TO ARRAY BASE ON THE DATE SELECTED
                if ($date >= $date_from && $date <= $date_to) {
                    $data_att[] = [
                        'log_type' => $log_id,
                        'emp_no' => $employee_id,
                        'dtr_date' => $date,
                        'dtr_time' => $time,
                        'dtr'=>$date." ".$time
                    ];
                }
                $ctr++;
            }
        }
        
        if($request->input('upload_type')=="Excel"){
            $data = Excel::toArray(new UsersImport, $imported_file);
            foreach($data[0] as $dtr)
            {    
                if($ctr == 0){
                    $ctr = $ctr+1;
                    continue;         
                }
                if (count($dtr)==1) {
                    continue;
                }
                $date = date('Y-m-d',strtotime($dtr[2]));
                $date2 = date('Y-m-d',strtotime($dtr[2]));
                $time = date('H:i:00',strtotime($dtr[2]));
                $employee_id = $dtr[0];
                $log_id = $dtr[3] == "C/In" ? 0 : 1;
                $eight = date('H:i:00',strtotime($date.'08:00:00'));
                
                //INSERT TO ARRAY BASE ON THE DATE SELECTED
                // if (strtotime($date) >= strtotime($date_from) && strtotime($date) <= strtotime($date_to)) {
                //     $data_att[] = array(
                //         'log_type' => $log_id,
                //         'employee_id' => $employee_id,
                //         'date' => $date,
                //         'time' => $time,
                //         'dtr_time'=>$date." ".$time
                //     );
                                        
                // }
                if($log_id == 1 && (strtotime($eight) >= strtotime($time))){
                    $date2 = date('Y-m-d',strtotime($dtr[2]." -1 day"));
                }
                
                if (strtotime($date) >= strtotime($date_from) && strtotime($date) <= strtotime($date_to)) {
                    $data_att[] = array(
                        'log_type' => $log_id,
                        'emp_no' => $employee_id,
                        'dtr_date' => $date2,
                        'dtr_time' => $time,
                        'dtr'=>$date." ".$time
                    );
                                        
                }
                $ctr++;
                
            }
        }
                     
        if(!empty($data_att)){
            DB::table('dtr_logs')->insert($data_att);
            DB::table('emp_dtr_logs')->insert($data_att);
        }

        $dtr_list = DB::SELECT(DB::RAW('CALL get_dtr_logs()'));
        $emp_no_list = "";
        try{
            foreach($dtr_list as $dtr){
                if($emp_no_list == ""){
                    $emp_no_list = '"'.$dtr->emp_no.'"';
                }
                else{
                    $emp_no_list = $emp_no_list.',"'.$dtr->emp_no.'"';
                }
    
                if($dtr->shift_code == "RD"){
                    $attendance[] = [
                        'SysPK_emp_attendance' => mt_rand(),
                        'attendance_id' => 0,
                        "employee_number"=>$dtr->emp_no,
                        "employee_fullName"=>$dtr->Name_Empl,
                        "dtr_date"=>date("Y-m-d", strtotime($dtr->dtr_date)),
                        "in_am"=>$dtr->TimeIn,
                        "out_am"=>null,
                        "in_pm"=>null,
                        "out_pm"=>$dtr->TimeOut,
                        "late"=>$dtr->late,
                        "undertime"=>$dtr->ut,
                        "np_hours"=>$dtr->np,
                        "ot_hours"=>$dtr->ot,
                        "total_hours_worked"=>$dtr->total_hour,
                        "sched_time_in"=>$dtr->time_start,
                        "sched_time_out"=>$dtr->time_end,
                        "att_type"=>"RD"
                    ]; 
                }
                else{
                    $attendance[] = [
                        'SysPK_emp_attendance' => mt_rand(),
                        'attendance_id' => 0,
                        "employee_number"=>$dtr->emp_no,
                        "employee_fullName"=>$dtr->Name_Empl,
                        "dtr_date"=>date("Y-m-d", strtotime($dtr->dtr_date)),
                        "in_am"=>$dtr->TimeIn,
                        "out_am"=>null,
                        "in_pm"=>null,
                        "out_pm"=>$dtr->TimeOut,
                        "late"=>$dtr->late,
                        "undertime"=>$dtr->ut,
                        "np_hours"=>$dtr->np,
                        "ot_hours"=>$dtr->ot,
                        "total_hours_worked"=>$dtr->total_hour,
                        "sched_time_in"=>$dtr->time_start,
                        "sched_time_out"=>$dtr->time_end,
                        "att_type"=>"regular"
                    ]; 
                }
                
            }
            if(!empty($attendance)){
                DB::table('hr_emp_attendance')
                    ->where('dtr_date','>=',date('Y-m-d',strtotime( $request->input('from_date') ) ) )
                    ->where('dtr_date','<=',date('Y-m-d',strtotime( $request->input('to_date') ) ) )
                    ->whereRaw('employee_number IN ('.$emp_no_list.')')
                    ->delete();
    
                DB::table('hr_emp_attendance')->insert($attendance);
                DB::table('dtr_logs')->delete();
            }
        }catch(Exception $exeption) {
            var_dump($exeption->getMessage());
            die();
        }
        return redirect('/import_dtr?imported_file='.$request->input('file_path').'&date_from='.$date_from.'&date_to='.$date_to.'&upload_type='.$request->input('upload_type'))
        ->with('success_message', 'DTR successfully saved to database!')
        ->with('imported_file' ,$request->input('imported_file'))//full path na ni
        ->with('save_data', 1)//check if the data uploaded to the database
        ->with('upload', 1);
    }

    public function import_dtr_excel(Request $request){
        
        $this->validate($request, [
            'imported_file' => 'required',
            'log_id' => 'required'
    
        ]);

        $date_from = date('Y-m-d',strtotime($request->input('from_date')));
        $date_to = date('Y-m-d',strtotime($request->input('to_date')));
        
        $validator = Validator::make($request->all(),
            [
                'log_id' => 'unique:emp_imported_dtr,log_id'
            ]);
    
        if ($validator->fails()) {
    
            return redirect("/import_dtr?imported_file=".$request->input('file_path')."&data_already_uploaded")->with('invalid','Some data already uploaded to database! Please check the data first!');
        }
    
        $imported_file = $request->input('imported_file');
        $ctr = 0;
        $data_att = null;
        $data_iclock_transaction = null;
        
        if ($request->input('upload_type') == 'Excel2') {
            $data = Excel::toArray(new UsersImport(), $imported_file);
            foreach ($data[0] as $dtr) {
                $unix_date = ($dtr[1] - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;

                $date = gmdate('Y-m-d', $unix_date);
                $time = gmdate('H:i:00', $unix_date);
                $employee_id = $dtr[0];
                $log_id = $dtr[3];
                //INSERT TO ARRAY BASE ON THE DATE SELECTED
                if ($date >= $date_from && $date <= $date_to) {
                    $data_att[] = [
                        'log_type' => $log_id,
                        'emp_no' => $employee_id,
                        'dtr_date' => $date,
                        'dtr_time' => $time,
                        'dtr'=>$date." ".$time
                    ];
                    $data_iclock_transaction[] = [
                        'punch_state' => $log_id,
                        'emp_code' => $employee_id,
                        'punch_time'=>$date." ".$time,
                        'verify_type' => 667,
                        'terminal_alias' => $request->input('file_path'),
                        'upload_stat'=>5
                    ];
                }
                $ctr++;
            }
        }
        
        if($request->input('upload_type')=="Excel"){
            $data = Excel::toArray(new UsersImport, $imported_file);
            foreach($data[0] as $dtr)
            {    
                if($ctr == 0){
                    $ctr = $ctr+1;
                    continue;         
                }
                if (count($dtr)==1) {
                    continue;
                }
                $date = date('Y-m-d',strtotime($dtr[2]));
                $date2 = date('Y-m-d',strtotime($dtr[2]));
                $time = date('H:i:00',strtotime($dtr[2]));
                $employee_id = $dtr[0];
                $log_id = $dtr[3] == "C/In" ? 0 : 1;
                $eight = date('H:i:00',strtotime($date.'08:00:00'));
                
                if($log_id == 1 && (strtotime($eight) >= strtotime($time))){
                    $date2 = date('Y-m-d',strtotime($dtr[2]." -1 day"));
                }
                
                if (strtotime($date) >= strtotime($date_from) && strtotime($date) <= strtotime($date_to)) {
                    $data_att[] = array(
                        'log_type' => $log_id,
                        'emp_no' => $employee_id,
                        'dtr_date' => $date2,
                        'dtr_time' => $time,
                        'dtr'=>$date." ".$time
                    );
                    $data_iclock_transaction[] = [
                        'punch_state' => $log_id,
                        'emp_code' => $employee_id,
                        'punch_time'=>$date." ".$time,
                        'verify_type' => 667,
                        'terminal_alias' => $request->input('file_path'),
                        'upload_stat'=>5
                    ];
                                        
                }
                $ctr++;
                
            }
        }
                     
        try{
            if(!empty($data_att)){
                DB::table('dtr_logs')->insert($data_att);
                DB::table('emp_dtr_logs')->insert($data_att);
                foreach ($data_iclock_transaction as $transaction) {
                    try{
                        DB::table('iclock_transaction')
                        ->insert(
                            [
                                'punch_time' => $transaction['punch_time'],
                                'emp_code' => $transaction['emp_code'],
                                'punch_state' => $transaction['punch_state'],
                                'verify_type' => $transaction['verify_type'],
                                'terminal_alias' => $transaction['terminal_alias'],
                                'upload_stat' => $transaction['upload_stat'],
                            ]
                        );
                    }catch(Exception $exeption2) {
                        echo "Loading...";
                    }
                }
            }
        }catch(Exception $exeption) {
            var_dump($exeption->getMessage());
        }
        return redirect('/import_dtr?imported_file='.$request->input('file_path').'&date_from='.$date_from.'&date_to='.$date_to.'&upload_type='.$request->input('upload_type'))
        ->with('success_message', 'DTR successfully saved to database!')
        ->with('imported_file' ,$request->input('imported_file'))//full path na ni
        ->with('save_data', 1)//check if the data uploaded to the database
        ->with('upload', 1);
    }

    public function biometrics(Request $r){

        // Array of Month Names and Numbers
        // https://www.apharmony.com/software-sagacity/2014/07/php-one-liners-array-of-month-names-and-numbers/
        $months = array_reduce(range(1,12),function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; });
        // Array of years
        $years = array_combine(range(date("Y"), 2020), range(date("Y"), 2020));
        $year = $r->input('year') ?? date('Y');
        $month = $r->input('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year.'-'.$month));
        $date_to = date('Y-m-t', strtotime($year.'-'.$month));
        $biometrics = collect();
        $biometrics_dates = [];

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) {
            return view('NormalEmployees.biometrics', [
                'biometrics' => $biometrics,
            ])->withErrors(['Invalid date range']);
        }// invalid date inputed

        if(!is_null(session('employee'))){

            $emp_code = session('employee')->UserID_Empl;
            // dd($date_to . " 23:59:59");
            // $biometrics = DB::connection('wdms')
            $biometrics = DB::table('iclock_transaction')
                            // ->table('iclock_transaction')
                            ->where('emp_code', $emp_code)
                            ->orderBy('punch_time', 'desc')
                            ->where('punch_time', '>=', $date_from . " 00:00:00")->where('punch_time', '<=', $date_to . " 23:59:59")
                            ->get();
            
            if(count($biometrics)){
                foreach($biometrics as $b){
                    $biometrics_dates[] = date('Y-m-d', strtotime($b->punch_time));
                }
            }

            $biometrics_dates = array_unique($biometrics_dates);

            return view('NormalEmployees.biometrics',[
                'biometrics' => $biometrics,
                'biometrics_dates' => $biometrics_dates,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'months' => $months,
                'years' => $years,
            ]);
        }
        
    }

    // ADMIN SIDE
    public function employee_biometrics(Request $request){

        // Array of Month Names and Numbers
        // https://www.apharmony.com/software-sagacity/2014/07/php-one-liners-array-of-month-names-and-numbers/
        $months = array_reduce(range(1,12),function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; });
        // Array of years
        $years = array_combine(range(date("Y"), 2020), range(date("Y"), 2020));
        $year = Input::get('year') ?? date('Y');
        $month = Input::get('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year.'-'.$month));
        $date_to = date('Y-m-t', strtotime($year.'-'.$month));
        $biometrics = collect();
        $biometrics_dates = [];
        $emp_id = Input::get('emp_id') ?? 0;

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) {
            return view('DTR.biometrics', [
                'biometrics' => $biometrics,
            ])->withErrors(['Invalid date range']);
        }// invalid date inputed

        $employees = DB::table('employees')->where('UserID_Empl', '>', 0)->get();
        
        if($emp_id){

            $emp_code = $emp_id;
            // $biometrics = DB::connection('wdms')
            $biometrics = DB::table('iclock_transaction')
                            // ->table('iclock_transaction')
                            ->where('emp_code', $emp_code)
                            ->orderBy('punch_time', 'desc')
                            // ->orderByDesc('punch_time')
                            ->where('punch_time', '>=', $date_from . " 00:00:00")->where('punch_time', '<=', $date_to . " 23:59:59")
                            ->get();
            
            if(count($biometrics)){
                foreach($biometrics as $b){
                    $biometrics_dates[] = date('Y-m-d', strtotime($b->punch_time));
                }
            }

            $biometrics_dates = array_unique($biometrics_dates);
        }

        return view('DTR.biometrics',[
            'biometrics' => $biometrics,
            'biometrics_dates' => $biometrics_dates,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'months' => $months,
            'years' => $years,
            'emp_id' => $emp_id,
            'employees' => $employees,
        ]);
        
    }

    // APPROVER SIDE
    public function staff_biometric_logs(Request $r){

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 
        // Array of Month Names and Numbers
        // https://www.apharmony.com/software-sagacity/2014/07/php-one-liners-array-of-month-names-and-numbers/
        $months = array_reduce(range(1,12),function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; });
        // Array of years
        $years = array_combine(range(date("Y"), 2020), range(date("Y"), 2020));
        $year = $r->input('year') ?? date('Y');
        $month = $r->input('month') ?? date('n'); // 1-12
        $date_from = date('Y-m-01', strtotime($year.'-'.$month));
        $date_to = date('Y-m-t', strtotime($year.'-'.$month));

        $biometrics = collect();
        $biometrics_dates = [];
        $emp_id = $r->input('emp_id') ?? 0;

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $emp_id_numbers = array();
        if(session('is_approver')){
            // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
            $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

            foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
        }

        $employees = DB::table('employees')->whereIn('UserID_Empl', $emp_id_numbers)->where('UserID_Empl', '!=', null)->where('UserID_Empl', '!=', '')->get();
        
        if($emp_id){

            $emp_code = $emp_id;
            // $biometrics = DB::connection('wdms')
            $biometrics = DB::table('iclock_transaction')
                            // ->table('iclock_transaction')
                            ->where('emp_code', $emp_code)
                            ->orderBy('punch_time', 'desc')
                            ->where('punch_time', '>=', $date_from . " 00:00:00")->where('punch_time', '<=', $date_to . " 23:59:59")
                            ->get();
            
            if(count($biometrics)){
                foreach($biometrics as $b){
                    $biometrics_dates[] = date('Y-m-d', strtotime($b->punch_time));
                }
            }

            $biometrics_dates = array_unique($biometrics_dates);
        }

        return view('DTR.staff_biometric_logs',[
            'biometrics' => $biometrics,
            'biometrics_dates' => $biometrics_dates,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'months' => $months,
            'years' => $years,
            'emp_id' => $emp_id,
            'employees' => $employees,
        ]);
        
    }

    // APPROVER SIDE
    public function staff_dtr_logs(Request $r){

        if(!session('is_approver')){
            if (!in_array(session('user')->employee_type_id, [1,2])) abort(403);
        } 
        
        $employee_number = $r->input('emp_id') ?? 0;
        $employee_fullName = "";
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-d');
        // $start_time = "08:00:00";
        // $end_time = "17:00:00";
        
        $attendance = array();
        $attendance1 = array();
        // $emp_details = array();

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $emp_id_numbers = array();
        if(session('is_approver')){
            $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();
            foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;
        }
        // Get approvers staff
        $employees = DB::table('employees')->whereIn('UserID_Empl', $emp_id_numbers)->where('UserID_Empl', '!=', null)->where('UserID_Empl', '!=', '')->get();

        if($employee_number){

            $attendance = Dtr::where('employee_number', $employee_number)->whereBetween('dtr_date', [$date_from, $date_to])->get();
            // $emp_details = DB::SELECT(DB::RAW('SELECT TimeStart_Empl,TimeEnd_Empl FROM employees WHERE UserID_Empl = "'.$employee_number.'"'));
            // $start_time = $emp_details[0]->TimeStart_Empl;
            // $end_time = $emp_details[0]->TimeEnd_Empl;
            $name_sql = DB::SELECT(DB::RAW('SELECT Name_Empl FROM employees WHERE UserID_Empl="'.$employee_number.'" LIMIT 1'));
            $emp_d = DB::TABLE('employees')->where('UserID_Empl', $employee_number)->first();
            $employee_fullName = $name_sql[0]->Name_Empl;
            
            
            $cnt = 0;
            $begin = new DateTime(date('Y-m-d',strtotime($date_from) ));
            $end = new DateTime(date('Y-m-d',strtotime( $date_to.'+ 1 day') ));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
    
            foreach ($period as $dt ) {
                $allow_update = 0;
                $shift_monitoring_id = null;
                $shift_code_id = null;
                $checker = 0;
                $break_hrs = 0;
                $shift_monitoring_status = "";
                $shift_start = "";
                $shift_end = "";
                $shift_sched = "";
                $shift_date = "";
                $is_active = null;
                $shift = DB::TABLE('shift_monitoring')
                                ->join('shift_code','shift_monitoring.shift_code_id','shift_code.id')
                                ->select(
                                    'shift_code.is_active',
                                    'shift_code.shift_code',
                                    'shift_monitoring.allow_update',
                                    'shift_monitoring.shift_code_id',
                                    'shift_monitoring.status',
                                    'shift_monitoring.id',
                                    'shift_code.Description',
                                    'shift_code.time_start',
                                    'shift_code.time_end',
                                    'shift_monitoring.no_hr_break',
                                    'shift_monitoring.shift_date',
                                    )
                                ->where('shift_monitoring.emp_id', "=", $emp_d->SysPK_Empl)
                                ->where('shift_monitoring.shift_date', "=", date("Y-m-d", strtotime($dt->format("Y-m-d"))))
                                ->where('shift_monitoring.is_deleted', "!=", 1)
                                ->where('shift_monitoring.status', '=', 1)
                                ->first();

                if ($shift) {
                    $is_active = $shift->is_active;
                    $allow_update = $shift->allow_update;
                    $break_hrs = $shift->no_hr_break;
                    $shift_start = $shift->time_start;
                    $shift_end = $shift->time_end;
                    $shift_sched = $shift->shift_code."-".$shift->Description;
                    $shift_monitoring_id = $shift->id;
                    $shift_date = $shift->shift_date;
                    $shift_code_id = $shift->shift_code_id;
                    $shift_monitoring_status = $shift->status;
                }

                foreach ($attendance as $attndnc) {
                    if($dt->format("Y-m-d") == $attndnc->dtr_date){
                        $checker = 1;
                        $attendance1[] = [
                            "employee_number" => $employee_number,
                            "employee_fullName" => $employee_fullName,
                            "SysPK_emp_attendance" => $attndnc->SysPK_emp_attendance,
                            "TimeStart" => $shift_start,
                            "TimeEnd" => $shift_end,
                            "dtr_date" => $attndnc->dtr_date,
                            "in_am" => $attndnc->in_am,
                            "out_pm" => $attndnc->out_pm,
                            "late" => $attndnc->late,
                            "undertime" => $attndnc->undertime,
                            "total_hours_worked" => $attndnc->total_hours_worked,
                            "att_remarks" => $attndnc->att_remarks,
                            "att_type" => $attndnc->att_type,
                            // "break_hours" => $attndnc->break_hours,
                            "break_hours" => $break_hrs,
                            "np_hours" => $attndnc->np_hours,
                            "ot_hours" => $attndnc->ot_hours,
                            "shift_sched" => $shift_sched,
                            "checker" => $checker,
                            "shift_monitoring_id" => $shift_monitoring_id,
                            "shift_date" => $shift_date,
                            "shift_code_id" => $shift_code_id,
                            "shift_monitoring_status" => $shift_monitoring_status,
                            "allow_update" => $allow_update,
                            "is_active" => $is_active
                        ];
                    }
                }
                
                if($checker == 0){
                        $attendance1[] = [
                            "employee_number" => $employee_number,
                            "employee_fullName" => $employee_fullName,
                            "SysPK_emp_attendance" => "0",
                            "TimeStart" => $shift_start,
                            "TimeEnd" => $shift_end,
                            "dtr_date" => $dt->format("Y-m-d"),
                            "in_am" => "",
                            "out_pm" => "",
                            "late" => "",
                            "undertime" => "",
                            "total_hours_worked" => 0,
                            "att_remarks" => "",
                            "att_type" => "",
                            "checker" => $checker,
                            "np_hours" => "",
                            "ot_hours" => "",
                            "shift_sched" => $shift_sched,
                            "break_hours" => $break_hrs,
                            "shift_monitoring_id" => $shift_monitoring_id,
                            "shift_date" => $shift_date,
                            "shift_code_id" => $shift_code_id,
                            "shift_monitoring_status" => $shift_monitoring_status,
                            "allow_update" => $allow_update,
                            "is_active" => $is_active
                        ];
                }
                $cnt = $cnt + 1;
            }
        }

        $shift_codes = DB::table('shift_code')->where('is_active', 1)->get();
        
        return view('DTR.staff_dtr_logs',[
            'attendance' => $attendance,
            'attendance1' => $attendance1,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'emp_id' => $employee_number,
            'employees' => $employees,
            'shift_codes' => $shift_codes,
        ]);
    }

}
