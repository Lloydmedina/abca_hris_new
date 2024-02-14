<?php

namespace App\Http\Controllers\Holiday;

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

class HolidayController extends Controller
{

  public function holiday_setup()
  {
    
    $holiday = DB::table('hr_holidays')->orderByDesc('holiday_date')->get();

    return view('Holiday.holiday_setup',
      [
        'list'=> $holiday
      ]);
  }

  public function add_holiday_setup(Request $request)
  {

    DB::TABLE('hr_holidays')
        ->insert(
            [
              'holiday_date'=>date('Y-m-d',strtotime($request->Input('holiday_date'))),
              'description'=>$request->Input('description'),
              'holiday_type'=>$request->Input('holiday_type'),
              'remarks'=>$request->Input('remarks'),
            ]
        );

    return redirect('/holiday_setup')
            ->with('success_message','Holiday Added');
  }

  public function update_holiday(Request $r)
  {
    $this->validate($r, [
          'holiday_date' => 'required',
          'description' => 'required',
          'holiday_type' => 'required',
          'remarks' => 'required',
        ]);

    if(!$r->input('holiday_id'))
          return redirect()->back()->withErrors(['Holiday ID is Blank!']);

        $hr_holidays = DB::table('hr_holidays')
              ->where('holiday_id', $r->input('holiday_id'))
              ->update([
                  'description' => $r->input('description'),
                  'holiday_date' => $r->input('holiday_date'),
                  'holiday_type' => $r->input('holiday_type'),
                  'remarks' => $r->input('remarks')
                ]);
        if($hr_holidays)
            return redirect()->back()->with('success_message','Holiday Successfully Updated!');
        else
            return redirect()->back()->withErrors(['Holiday Update : Something went wrong!']);   

    
  }
}
