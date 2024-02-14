<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Str; 

class Training extends Controller {

    public function trainings(){
        
        $date_from = Input::get('date_from') ?? date('Y-m-01');
        $date_to = Input::get('date_to') ?? date('Y-m-t');
        $trainigs = collect();

        // Check date if valid
        if(strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
        
        $trainigs = DB::table('training')
                    ->leftJoin('training_attendees', 'training.tr_id', '=', 'training_attendees.training_id')
                    ->select('training.*', DB::raw("count(training_attendees.training_id) as count"))
                    ->whereBetween('tr_date', [ date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
                    ->groupBy('training.tr_id')
                    ->orderBY('training.tr_id', 'desc')
                    ->get();
        
        return view('Training.trainings',[
                "trainigs" => $trainigs,
                "date_from" => $date_from,
                "date_to" => $date_to,
            ]);
    }

    public function traning_attendees($tr_id = null){
        
        $attendees = collect();
        $training = DB::table('training')->where('tr_id', $tr_id)->first();
        $get_attendees_ids = array();
        $get_attendees = DB::table('training_attendees')->where('training_id', $tr_id)->select('emp_id')->get();
       
        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->join('company', 'employees.company_id', '=', 'company.company_id')
            ->select('employees.*', 'employees_rate.Position_Empl','company.company','outlet.outlet')
            ->whereNotNull('UserID_Empl')
            ->where('UserID_Empl', '!=', '')
            ->orderBy('employees.emp_level_id', 'ASC')
            // ->orderBy('employees_rate.id', 'ASC')
            ->orderBy('employees.LastName_Empl', 'DESC');
        
        if(count($get_attendees)){
            foreach($get_attendees as $val) $get_attendees_ids[] = $val->emp_id;
            $employees = $employees->whereNotIn('SysPK_Empl', $get_attendees_ids);
        } 
        
        $employees = $employees->get();
        
        if($training) {
            $attendees = DB::table('training_attendees')
                        ->join('employees', 'training_attendees.emp_id', '=', 'employees.SysPK_Empl')
                        ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
                        ->join('company', 'employees.company_id', '=', 'company.company_id')
                        // ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                        ->select('employees.*','outlet.outlet', 'company.company','training_attendees.id as att_id', 'training_attendees.result', 'training_attendees.certification', 'training_attendees.validity')
                        ->where('training_attendees.training_id', $tr_id)
                        ->orderBy('training_attendees.id', 'desc')
                        ->get();
        }
        return view('Training.training_attendees',[
            "training" => $training,
            "attendees" => $attendees,
            "employees" => $employees,
        ]);

    }

    public function add_attendees(Request $r){
        
        $this->validate($r, [
            'attendees' => 'required|array',
            'tr_id' => 'required',
        ]);

        $tr_id = $r->input('tr_id');
        $attendees = $r->input('attendees');
        $attendees_arr = [];

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->join('company', 'employees.company_id', '=', 'company.company_id')
            ->select('employees.*', 'employees_rate.Position_Empl','company.company','outlet.outlet')
            ->whereIn('employees.SysPK_Empl', $attendees)
            ->get();
        
        foreach($employees as $val){
            $attendees_arr[] = array(
                'training_id' => $tr_id,
                'emp_id' => $val->SysPK_Empl,
                'emp_full_name' => $val->Name_Empl,
                'emp_position' => $val->Position_Empl,
                'emp_brand' => $val->company,
                'emp_outlet' => $val->outlet,
            );
        }

        if(count($attendees_arr)){
            DB::table('training_attendees')->insert($attendees_arr);
            return Redirect::back()->with('success_message', 'Attendees successfully added.');
        }
        else return Redirect::back()->withErrors(['Something went wrong!'])->withInput();

    }

    public function training_calendar(){

        $clean_trainigs = array();

        $trainigs = DB::table('training')
                    // ->leftJoin('training_attendees', 'training.tr_id', '=', 'training_attendees.training_id')
                    // ->select('training.*', DB::raw("count(training_attendees.training_id) as count"))
                    // ->whereBetween('tr_date', [ date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
                    ->groupBy('training.tr_id')
                    ->orderBY('training.tr_id', 'desc')
                    ->get();

        foreach($trainigs as $t){
            $clean_trainigs[] = array(
                                    'title' => $t->tr_display_title, 
                                    // 'start' => $t->tr_date.'T'.$t->tr_time, 
                                    'start' => $t->tr_date, 
                                    'url' => url("/training/$t->tr_id/attendees"),
                                    'backgroundColor' => 'black',
                                    'description' => Str::limit($t->tr_description, 100),
                                );
        }
        
        return view('Training.training_calendar', ["clean_trainigs" => $clean_trainigs]);
    }

    public function training_request(){
        
    }

    public function new_training(){

        return view('Training.new_training');
    }

    public function add_new_traning(Request $r){

        $this->validate($r, [
            'tr_date' => 'required',
            'tr_time' => 'required',
            'tr_name' => 'required',
            'tr_description' => 'required',
            'trainers' => 'required',
        ]);

        $tr_date = date('Y-m-d', strtotime($r->input('tr_date')));
        $tr_time = date('H:i:s', strtotime($r->input('tr_time')));
        $tr_name = $r->input('tr_name');
        $tr_display_title = $r->input('tr_display_title');
        $tr_description = $r->input('tr_description');
        $trainers = $r->input('trainers');

        $training = DB::table('training')->insert([
            'tr_date' => $tr_date,
            'tr_time' => $tr_time,
            'tr_name' => $tr_name,
            'tr_display_title' => $tr_display_title,
            'tr_description' => $tr_description,
            'trainers' => $trainers,
        ]);

        if($training) return Redirect::back()->with('success_message', 'Training successfully created.');
        else return Redirect::back()->withErrors(['Something went wrong!'])->withInput();
    }

    public function delete_training($tr_id){

        if($tr_id) DB::table('training')->where('tr_id', $tr_id)->delete();
        else die();
        return Redirect::back()->with('success_message','Training Removed');
    }

    public function delete_attendee($id){

        if($id) DB::table('training_attendees')->where('id', $id)->delete();
        else die();
        return Redirect::back()->with('success_message','Attendee Removed');
    }

    public function update_attendee(Request $r){

        $id = $r->input('id');
        $result = $r->input('result');
        $certification = $r->input('certification');
        $validity = $r->input('validity');

        if($id){
            $update_attendee = DB::table('training_attendees')
                ->where('id', $id)
                ->update([
                    'result' => $result,
                    'certification' => $certification,
                    'validity' => $validity,
                ]);

                return Redirect::back()->with('success_message','Attendee Updated');
        }
        return Redirect::back()->withErrors(['Something went wrong!'])->withInput();

    }

    public function my_trainings(Request $r){
        
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $trainigs = collect();

        // Check date if valid
        if(strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
        
        $trainigs = DB::table('training')
                    ->leftJoin('training_attendees', 'training.tr_id', '=', 'training_attendees.training_id')
                    ->select('training.*', DB::raw("count(training_attendees.training_id) as count"))
                    ->whereBetween('tr_date', [ date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
                    ->where('training_attendees.emp_id', session('employee')->SysPK_Empl)
                    ->groupBy('training.tr_id')
                    ->orderBY('training.tr_id', 'desc')
                    ->get();
        
        return view('NormalEmployees.my_trainings',[
                "trainigs" => $trainigs,
                "date_from" => $date_from,
                "date_to" => $date_to,
            ]);
    }

    public function traning_attendee_result($tr_id = null){
        
        $result = collect();
        $training = DB::table('training')->where('tr_id', $tr_id)->first();
        
        if($training) {
            $result = DB::table('training_attendees')
                        ->where('training_attendees.training_id', $tr_id)
                        ->where('training_attendees.emp_id', session('employee')->SysPK_Empl)
                        ->first();
        }
        
        return view('NormalEmployees.my_training_result',[
            "training" => $training,
            "result" => $result,
        ]);

    }

    public function my_training_calendar(){

        $clean_trainigs = array();

        $trainigs = DB::table('training')
                    ->join('training_attendees', 'training.tr_id', '=', 'training_attendees.training_id')
                    ->where('training_attendees.emp_id', session('employee')->SysPK_Empl)
                    ->groupBy('training.tr_id')
                    ->orderBY('training.tr_id', 'desc')
                    ->get();

        foreach($trainigs as $t){
            $clean_trainigs[] = array(
                                    'title' => $t->tr_display_title, 
                                    // 'start' => $t->tr_date.'T'.$t->tr_time, 
                                    'start' => $t->tr_date, 
                                    'url' => url("/my-training/$t->tr_id/result?v=2"),
                                    'backgroundColor' => 'black',
                                    'description' => Str::limit($t->tr_description, 100),
                                );
        }
        
        return view('NormalEmployees.my_training_calendar', ["clean_trainigs" => $clean_trainigs]);
    }
    
}