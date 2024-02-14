<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Inventory_imported as Import_inventory;

class InventoryController extends Controller
{
    public function import_inventory(Request $request)
    {
        $this->validate($request, [
            'inventory' => 'required'
        ]);

        $upload = 0;

        if ($request->hasFile('inventory')) {
            if ($request->file('inventory')->isValid()) {
                $check = getimagesize($_FILES["inventory"]["tmp_name"]);
                $dir = "storage/uploads/inventory/";
                $file_path = 'inventory_'.date('m_d_y').'.'.$request->file('inventory')->getClientOriginalExtension();
                $full_path = $dir.$file_path;
                
                if ($check !== false || $request->inventory->extension() != 'xlsx') {
                    return redirect('/import-inventory?invalid_file_ext='.$request->inventory->extension())->with('invalid','Invalid file!');
                }
                else{
                    //DELETE KUNG SAME UG NAME OR GI BALIK NA THEN UPLOAD BALIK
                    if (file_exists($full_path)) {
                        unlink($full_path);
                    }
                    //upload the file
                    $request->file('inventory')->move("storage/uploads/inventory", $file_path);
                    $upload = 1;
                    return redirect('/import-inventory?imported_file='.$file_path)
                    ->with('success_message', 'Imported file!')
                    ->with('imported_file' , $full_path)
                    ->with('upload', $upload);
                }
            }
        }
    }

    public function save_inventory(Request $request)
    {
    	$this->validate($request, [
            'imported_file' => 'required',
            'Item_ID' => 'required'
        ]);

        $validator = Validator::make($request->all(),
        [
            'Item_ID' => 'unique:inventory_imported,Item_ID'
        ]);

        if ($validator->fails()) {

            return redirect("/import-inventory?imported_file=".$request->input('file_path')."&data_already_uploaded")->with('invalid','Some data already uploaded to database! Please check the data first!');
        }

    	$xlsx = (new \App\XLSXReader\SimpleXLSX($request->input('imported_file')));
    	$sheet = $data = array();
    	$ctr = 0;

    	if (strpos($xlsx->sheetNames()[1], 'ENVIRO') == true) {
    		$sheet = $xlsx->rows();
        }

        foreach ($sheet as $row) {
        	if ($ctr != 0 && $row[0]) {
        		$data[] = array(
        			'Item_ID' => $row[0],
        			'Item_Desc' => $row[1],
        			'Item_Type' => $row[2],
        			'Stocking' => $row[3],
        			'Qty_On_Hand' => (int)$row[4],//convert to int
        			'Last_Unit_Cost' => (float)$row[5],//convert to float
        			'Desc_For_Purchases' => $row[6],
        			'created_at' => date('Y-m-d H:i:s')
        		);
        	}
        $ctr++;
        }

       if (count($data) > 0) {
       	Import_inventory::insert($data);
       	return redirect('/import-inventory?imported_file='.$request->input('file_path').'&data_uploaded')
        ->with('success_message', 'Inventory successfully saved to database!')
        ->with('imported_file' ,$request->input('imported_file'))//full path na ni
        ->with('save_data', 1)//check if the data uploaded to the database
        ->with('upload', 1);
       }

    }
}
