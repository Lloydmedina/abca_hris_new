<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //get the secret id
    public function __get_orig_id($secret_id)
    {
        return substr($secret_id, 0, strlen($secret_id) - 32);//always  minus 32 for md5 lenght
    }

    // Pass Slip - PS
    // Official Business Trip - OBT
    // Over Time - OT
    // Shift Schedule - SS

    // Insert - I
    // Update - U
    // Remove - R 
    // public function checkApplication($date, $type, $action, $item_id){

    //     $type = strtoupper($type);
    //     $action = strtoupper($action);
    //     $locakStatus = false;
    //     $data = null;
    //     $data_date = null;

    //     if($action == "I"){
    //         $locakStatus = DB::table('system_lock_status')->where('lock_date', date('Y-m-d', strtotime($date)))->where('is_lock', 1)->first();
    //     }
    //     elseif(in_array($action, ['U','R'])){

    //         // check the data first
    //         if( in_array($type, ['PS','OBT']) ){
    //             $data = DB::table('emp_pass_slip')->where('id', $item_id)->first();
    //             if($data)
    //                 $data_date = $data->ps_date_from;
    //         }
    //         elseif( in_array($type, ['OT']) ){
    //             $data = DB::table('approved_ot')->where('OT_ID', $item_id)->first();
    //             if($data)
    //                 $data_date = $data->dtr_date;
    //         }
                
    //         if($data)
    //             $locakStatus = DB::table('system_lock_status')->where('lock_date', date('Y-m-d', strtotime($data_date)))->where('is_lock', 1)->first();
    //     }

    //     if($locakStatus)
    //         return 'The application is locked, and any actions such as adding, inserting, or removing are not allowed for this date '.date('M d, Y', strtotime($locakStatus->lock_date)).'.';
    //     else 
    //         return false;
    // }

    public function checkApplication($date, $type, $action, $item_id)
    {
        $type = strtoupper($type);
        $action = strtoupper($action);
        $locakStatus = null;
        
        // Define a map of type to table names and date columns
        $typeToTable = [
            'PS' => ['table' => 'emp_pass_slip', 'date_column' => 'ps_date_from', 'id_column' => 'id'],
            'OBT' => ['table' => 'emp_pass_slip', 'date_column' => 'ps_date_from', 'id_column' => 'id'],
            'OT' => ['table' => 'approved_ot', 'date_column' => 'dtr_date', 'id_column' => 'OT_ID'],
            'LV' => ['table' => 'leaves', 'date1_column' => 'leave_date_from', 'date2_column' => 'leave_date_to', 'id_column' => 'leave_app_id'],
        ];

        // Check if the action is 'I'
        if ($action === 'I') {
            
            if($type === 'LV'){
                // expect the date type is array
                $locakStatus = DB::table('system_lock_status')
                    ->whereBetween('lock_date', [ date('Y-m-d', strtotime($date['from'])), date('Y-m-d', strtotime($date['to']))])
                    ->where('is_lock', 1)
                    ->first();
                
            }
            else{

                $locakStatus = DB::table('system_lock_status')
                    ->where('lock_date', date('Y-m-d', strtotime($date)))
                    ->where('is_lock', 1)
                    ->first();
            }
            
        }
        // Check if the action is 'U' or 'R'
        elseif (in_array($action, ['U', 'R']) && isset($typeToTable[$type])) {

            $tableInfo = $typeToTable[$type];
            $data = DB::table($tableInfo['table'])
                ->where($tableInfo['id_column'], $item_id)
                ->first();
            
            if ($data) {
                // make for leave

                if($type === 'LV'){

                    $date_from = $data->{$tableInfo['date1_column']};
                    $date_to = $data->{$tableInfo['date2_column']};
                    $locakStatus = DB::table('system_lock_status')
                        ->whereBetween('lock_date', [date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to))])
                        ->where('is_lock', 1)
                        ->first();

                    if(!$locakStatus && is_array($date)){
                        $locakStatus = DB::table('system_lock_status')
                        ->whereBetween('lock_date', [date('Y-m-d', strtotime($date['from'])), date('Y-m-d', strtotime($date['to']))])
                        ->where('is_lock', 1)
                        ->first();
                    }
                }
                else{
                    $data_date = $data->{$tableInfo['date_column']};
                    $locakStatus = DB::table('system_lock_status')
                        ->where('lock_date', date('Y-m-d', strtotime($data_date)))
                        ->where('is_lock', 1)
                        ->first();

                    // recheck if the date to update is not lock
                    if(!$locakStatus && $date){
                        $locakStatus = DB::table('system_lock_status')
                            ->where('lock_date', date('Y-m-d', strtotime($date)))
                            ->where('is_lock', 1)
                            ->first();
                    }
                }
                
            }
        }

        if ($locakStatus) {
            $lockDate = date('M d, Y', strtotime($locakStatus->lock_date));
            return "The application is locked, and any actions such as adding, inserting, or removing are not allowed for this date $lockDate.";
        } else {
            return false;
        }
    }

}
