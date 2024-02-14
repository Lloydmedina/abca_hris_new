<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Cookie;
use Session;

class SettingsController extends Controller
{
    public function settings(Request $r){

        $security_questions = DB::table('security_questions')->get();

        $my_question_and_answer = DB::table('security_question_and_answer')
                                ->join('security_questions', 'security_question_and_answer.security_question_id', '=', 'security_questions.id')
                                ->where('user_id', session('user')->id)
                                ->get();
                
        $my_pin = DB::table('security_pin')
                                ->where('user_id', session('user')->id)
                                ->where('is_enable', 1)
                                ->first();

        $page_lock = DB::table('employee_modules')
                            ->leftJoin('employee_page_lock',function ($page_lock) {
                                $page_lock->on('employee_page_lock.employee_module_id', '=' , 'employee_modules.id') ;
                                $page_lock->where('employee_page_lock.user_id','=',session('user')->id);
                            })
                            ->select('employee_modules.*', 'employee_page_lock.id as page_lock_id', 'employee_page_lock.is_lock', 'employee_page_lock.user_id')
                            ->where('employee_modules.is_active', 1)
                            ->orderBy('employee_modules.employee_module_header_id', 'asc')
                            ->orderBy('employee_modules.id', 'asc')
                            ->get();
        
        return view('Settings.security', [
            'security_questions' => $security_questions,
            'my_question_and_answer' => $my_question_and_answer,
            'my_pin' => $my_pin,
            'page_lock' => $page_lock,
        ]);
    }

    // set question
    public function set_question(Request $r){

        $this->validate($r, [
            'questions' => 'required|array|min:3',
            'answers' => 'required|array|min:3',
        ]);

        $questions = $r->input('questions');
        $answers = $r->input('answers');
        $questions_and_answers = array();

        for ($i=0;$i<count($questions);$i++){
            $questions_and_answers[$i] = array(
                "user_id" => session('user')->id,
                "security_question_id" => $questions[$i],
                "security_answer" => md5($answers[$i]),
                "created_at" => date('Y-m-d H:i:s'),
            );
        }

        $qa = DB::table('security_question_and_answer')->insert($questions_and_answers);

        if($qa) return redirect()->route('settings')->with('success_message', 'Your security question was successfully saved.');
        else return Redirect::back()->withErrors(["Something went wrong!"])->withInput();

    }

    // set pin
    public function set_pin(Request $r){

        $this->validate($r, [
            'question' => 'required',
            'answer' => 'required',
            'security_pin_1' => 'required',
            'security_pin_2' => 'required'
        ]);

        $my_question_and_answer = DB::table('security_question_and_answer')
                                ->where('user_id', session('user')->id)
                                ->where('security_question_id', $r->input('question'))
                                ->where('security_answer', md5($r->input('answer')))
                                ->first();
        
        
        if(!$my_question_and_answer){// invalid password
            return response()->json(
                array(
                    "code" => 0, 
                    "message" => "Security Questions Mismatched. Please ensure that your answers to the security questions are entered correctly."
                )
            );
        }
        if($r->input('security_pin_1') != $r->input('security_pin_1')){
            return response()->json(
                array(
                    "code" => 0, 
                    "message" => "Security PIN Mismatched."
                )
            );
        }

        $pin_data = array(
            "user_id" => session("user")->id,
            "pin" => md5($r->input('security_pin_1')),
            "created_at" => date('Y-m-d H:i:s')
        );

        $pin = DB::table('security_pin')->insert($pin_data);

        if($pin){
            return response()->json(array("code" => 1, "message" => "PIN successfully saved"));
        }
        else{
            return response()->json(array("code" => 0, "message" => "Something went wrong."));
        }

    }

    // chage pin
    public function change_pin(Request $r){

        $this->validate($r, [
            'question' => 'required',
            'answer' => 'required',
            'security_pin_1' => 'required',
            'security_pin_2' => 'required',
            'pin_id' => 'required'
        ]);

        $my_question_and_answer = DB::table('security_question_and_answer')
                                ->where('user_id', session('user')->id)
                                ->where('security_question_id', $r->input('question'))
                                ->where('security_answer', md5($r->input('answer')))
                                ->first();
        
        
        if(!$my_question_and_answer){// invalid password
            return response()->json(
                array(
                    "code" => 0, 
                    "message" => "Security Questions Mismatched. Please ensure that your answers to the security questions are entered correctly."
                )
            );
        }
        if($r->input('security_pin_1') != $r->input('security_pin_1')){
            return response()->json(
                array(
                    "code" => 0, 
                    "message" => "Security PIN Mismatched."
                )
            );
        }

        $pin_data = array(
            "user_id" => session("user")->id,
            "pin" => md5($r->input('security_pin_1'))
        );

        $pin = DB::table('security_pin')->where('id', $r->input('pin_id'))->update($pin_data);

        if($pin){
            return response()->json(array("code" => 1, "message" => "PIN successfully saved"));
        }
        else{
            return response()->json(array("code" => 0, "message" => "Something went wrong."));
        }

    }

    public function page_lock(Request $r){
        
        $is_lock = $r->input('is_lock');
        $employee_module_id = $r->input('employee_module_id');

        $page_lock_data = array(
            "user_id" => session("user")->id,
            "is_lock" => $is_lock,
            "employee_module_id" => $employee_module_id,
            "created_at" => date('Y-m-d H:i:s'),
        );

        $do = $is_lock ? "locked" : "unlocked";

        // Check first if null then insert
        $check_page_lock = $pin = DB::table('employee_page_lock')->where('employee_module_id', $employee_module_id)->where('user_id', session("user")->id)->first();
        // update
        if($check_page_lock)
            $lock = DB::table('employee_page_lock')->where('employee_module_id', $employee_module_id)->where('user_id', session('user')->id)->update($page_lock_data);
        else
            $lock = DB::table('employee_page_lock')->insert($page_lock_data);

        if($lock){
            return response()->json(array("code" => 1, "message" => $r->input('module_name')." successfully ".$do));
        }
        else{
            return response()->json(array("code" => 0, "message" => "Something went wrong."));
        }
    }

    public function page_locked_view(Request $r){

        $route_name = $r->input('m') ?? null;

        $current_module = DB::table('employee_modules')
                ->join('employee_page_lock', 'employee_page_lock.employee_module_id', '=' , 'employee_modules.id')
                ->where('employee_page_lock.user_id','=',session('user')->id)
                ->where('employee_page_lock.is_lock','=',1)
                ->select('employee_page_lock.*', 'employee_modules.sub_route_name', 'employee_modules.route_name', 'employee_modules.module')
                ->where('employee_modules.is_active', 1)
                // ->where('employee_modules.route_name', $route_name)
                ->where(function ($current_module)use($route_name) {
                    $current_module->where('employee_modules.route_name', $route_name)
                                    ->orWhereRaw('FIND_IN_SET(?, employee_modules.sub_route_name)', $route_name);          
                })
                ->first();

        if($current_module)  
            if(Cookie::get(md5($route_name)))
                return redirect()->route($r->input('m'))->with('success_message', 'Page was successfully unlocked.');
            
        // if(is_null($current_module)) return view("errors.404");

        return view('Pages.page_locked_pin', [
            'current_module' => $current_module
        ]);

    }

    public function page_unlock(Request $r){

        $pin = $r->input('pin') ?? null;
        $route_name = $r->input('route_name') ?? null;

        if(!$route_name) return response()->json(array("code" => 0, "message" => "Something went wrong."));
        elseif($pin){
            $check_pin = DB::table('security_pin')->where('user_id', session('user')->id)->where('pin', md5($pin))->first();
            if($check_pin){

                $current_module = DB::table('employee_modules')
                    ->join('employee_page_lock', 'employee_page_lock.employee_module_id', '=' , 'employee_modules.id')
                    ->where('employee_page_lock.user_id','=',session('user')->id)
                    ->where('employee_page_lock.is_lock','=',1)
                    ->select('employee_page_lock.*', 'employee_modules.sub_route_name', 'employee_modules.route_name', 'employee_modules.module')
                    ->where('employee_modules.is_active', 1)
                    // ->where('employee_modules.route_name', $route_name)
                    ->where(function ($current_module)use($route_name) {
                        $current_module->where('employee_modules.route_name', $route_name)
                                        ->orWhereRaw('FIND_IN_SET(?, employee_modules.sub_route_name)', $route_name);          
                    })
                    ->first();

                $mins = 60;
                $route_nameS = $current_module->route_name;
                if($current_module->sub_route_name) $route_nameS .= ",".$current_module->sub_route_name;
                $route_nameS =  explode(",",$route_nameS);
                // set cookie
                foreach ($route_nameS as $r) Cookie::queue(Cookie::make(md5($r), date('Y-m-d H:i:s'), $mins));

                return response()->json(array("code" => 1, "message" => "Page content unlocked."));
            }else
                return response()->json(array("code" => 0, "message" => "Security PIN Mismatched."));
        }
        else{
            // Somethinf went wrong.
            return response()->json(array("code" => 0, "message" => "Something went wrong."));
        }
    }

    public function page_lock_access(Request $r){

        $pin = $r->input('pin') ?? null;

        if($pin){
            $check_pin = DB::table('security_pin')->where('user_id', session('user')->id)->where('pin', md5($pin))->first();
            if($check_pin){

                // set session
                Session::put('page_lock_access', md5("page_lock_access"));

                return response()->json(array("code" => 1, "message" => "Content unlocked."));
            }else
                return response()->json(array("code" => 0, "message" => "Security PIN Mismatched."));
        }
        else{
            // Somethinf went wrong.
            return response()->json(array("code" => 0, "message" => "Something went wrong."));
        }

    }

    public function system_lock(Request $r){

        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');

        $systemLock = array();
        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        // Define the start and end dates for the range
        $start_date = $date_from;
        $end_date = $date_to;

        // Create an array to store the list of dates
        $date_list = array();

        // Iterate through the date range and add each date to the array
        $current_date = $start_date;
        while ($current_date <= $end_date) {
            $date_list[] = date('Y-m-d', strtotime($current_date));
            $current_date = date('Y-m-d',strtotime($current_date.'+ 1 day'));
        }

        $systemLock = DB::table('system_lock_status');

        // return based on searched date
        if($date_from && $date_to){ 
            $date = array($date_from, $date_to);
            $systemLock = $systemLock->whereBetween('lock_date', $date);
        }
        $systemLock = $systemLock->orderBy('lock_date', 'asc')->get();
        $systemLockMerge = array();

        foreach($date_list as $date){
            $exist = false;
            foreach($systemLock as $row){
                
                if($date == $row->lock_date){
                    $exist = true;
                    $systemLockMerge[] = array(
                        'date' => $date,
                        'id' => $row->id,
                        'lock_date' => $row->lock_date,
                        'is_lock' => $row->is_lock,
                        'date_created' => $row->date_created
                    );
                    
                }
            }
            if($exist == false){
                $systemLockMerge[] = array(
                    'date' => $date,
                    'id' => null,
                    'lock_date' => null,
                    'is_lock' => null,
                    'date_created' => null
                );
            }
            
        }
        
        return view('Pages.system_lock', [
            'systemLock' => $systemLock,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'date_list' => $date_list,
            'systemLockMerge' => $systemLockMerge
        ]);

    }

    public function save_system_lock(Request $r){
        
        set_time_limit(0);
        try {

            $date_from = $r->input('date_from') ?? date('Y-m-01');
            $date_to = $r->input('date_to') ?? date('Y-m-t');
            $dates = $r->input('lock') ?? [];

            // set to is_lock = 0 first
            $date = array($date_from, $date_to);
            DB::table('system_lock_status')->whereBetween('lock_date', $date)->update(["is_lock" => 0]);
            // set to allow_update = 1 first SHIFT MONITORING
            DB::table('shift_monitoring')->whereBetween('shift_date', $date)->update([
                "allow_update" => 1,
                'updated_at' => now()
            ]);
        
            if (count($dates)) {

                foreach($dates as $d){
                    $checker = DB::table('system_lock_status')->where('lock_date', $d)->first();
                    if($checker)
                        DB::table('system_lock_status')->where('lock_date', $d)->update(["is_lock" => 1]); // update
                    else
                        DB::table('system_lock_status')->insert(["is_lock" => 1, 'lock_date' => $d, 'date_created' => date('Y-m-d H:i:s')]); // insert
                    // SHIFT MONITRING
                    $checker2 = DB::table('shift_monitoring')->where('shift_date', $d)->where('is_deleted', 0)->first();
                    if($checker2)
                        DB::table('shift_monitoring')->where('shift_date', $d)->update(["allow_update" => 0, 'updated_at' => now()]);
                }
                return redirect()->back()->with('success_message', 'The system lock process was successful')->withInput();
                
            } else {
                // return redirect()->back()->withErrors(['Oops! Please select at least 1 item.'])->withInput();
                return redirect()->back()->with('success_message', 'The system lock process was successful')->withInput();
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage());
            return redirect()->back()->withErrors(['Oops! The lock process was unsuccessful.'])->withInput();
        }

    }

}