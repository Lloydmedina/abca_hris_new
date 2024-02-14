<?php

namespace App\Http\Controllers\Vehicles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TripsController extends Controller
{
        
    public function trip_details(){

      $list = DB::TABLE('vehicles_deploy')->get();

      return view('Vehicles.trip_details',['list' => $list]);
    }

    public function add_new_trip(Request $request)
    {

      $cc_id = DB::TABLE('vehicles_deploy')->insertGetId(
        [
          'trip_number'=>$request->input('trip_number'),
          'travel_details'=>$request->input('travel_details'),
          'passenger_names'=>$request->input('passenger_names'),
          'destination'=>$request->input('destination'),
          'et_departure'=>$request->input('et_departure'),
          'departure_odometer'=>$request->input('departure_odometer'),
          'et_arrival'=>$request->input('et_arrival'),
          'arrival_odometer'=>$request->input('arrival_odometer'),
          'remarks'=>$request->input('remarks')
        ]);

      return redirect('/trip_details')->with('success_message','New Trip Added');
    }

}
