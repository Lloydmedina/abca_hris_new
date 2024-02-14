<?php

namespace App\Http\Controllers\Vehicles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
  
    public function vehicle_details(){


      $list = DB::TABLE('vehicle_details')->get();

      return view('Vehicles.vehicle_details',['list' => $list]);
    }
      
    public function add_new_vehicle(Request $request)
    {

      $cc_id = DB::TABLE('vehicle_details')->insert(
        [
          'vehicle_id'=>$request->input('vehicle_id'),
          'plate_number'=>$request->input('plate_number'),
          'current_driver'=>$request->input('current_driver'),
          'year_model'=>$request->input('year_model'),
          'make'=>$request->input('make'),
          'series'=>$request->input('series'),
          'body_type'=>$request->input('body_type'),
          'color'=>$request->input('color'),
          'mv_file_no'=>$request->input('mv_file_no'),
          'engine_no'=>$request->input('engine_no'),
          'chassis_no'=>$request->input('chassis_no'),
          'lto_cr_no'=>$request->input('lto_cr_no'),
          'lto_or_no'=>$request->input('lto_or_no'),
          //'lto_or_date'=>date('Y-m-d', strtotime($request->input('lto_or_date'))),
        ]);

      return redirect('/vehicle_details')->with('success_message','New Vehicle Added');
    }

    public function store()
    {

      
    }

}
