<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Redirect;

class MemoController extends Controller
{
    public function memo(Request $r)
    {
      $checkIfHasMemoPriv = false;
      if(session('other_links')) foreach (session('other_links') as $value) if($value->id == 25) $checkIfHasMemoPriv = true;

      $display_by = $r->input('display_by') ?? '10'; // default 10
      $date_from = $r->input('date_from') ?? date('Y-m-01');
      $date_to = $r->input('date_to') ?? date('Y-m-t');
      $date = array($date_from, $date_to);

      // invalid date inputed
      if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

      $memo = DB::table("memo")
              ->leftJoin('users', 'memo.created_by', '=', 'users.id')
              // ->leftJoin("outlet","memo.outlet_id","=","outlet.outlet_id")
              ->select('memo.*', 'users.id', 'users.username')
              ->whereBetween('memo_date', $date);

        if ($checkIfHasMemoPriv == false && session('employee')) {
          // $emp = DB::table("employees")->where("UserID_Empl", session("user")->username)->first();
          // $memo = $memo->whereIn("memo.outlet_id", [session('employee')->outlet_id,0]);

          $memo = $memo->where(function($q){
            return $q->whereRaw('FIND_IN_SET(?, outlet_id)', [session('employee')->outlet_id])
                    ->orWhereRaw('FIND_IN_SET(?, outlet_id)', [0]);
            });
        }
        
        if($display_by == 'all'){
          $memo = $memo->orderBy("memo_id", "desc")->get();
          $paginationLinks = null;
        }
        else{
            $memo = $memo->orderBy('memo_id', 'desc')->paginate($display_by);
            $paginationLinks = $memo->appends(request()->query())->links();
        }
        
        $outlet = DB::table("outlet")->orderBy("outlet_id", "asc")->get();
        
        return view("Memo.memo", [
            "memo" => $memo,
            "outlet" => $outlet,
            'date_from' => $date_from,
            'date_to' => $date_to,
            "paginationLinks" => $paginationLinks
        ]);
    }

    public function add_memo(Request $request)
    {
        $this->validate($request, [
            "outlet" => 'required|array',
            "memo_date" => "required",
            "memo_title" => "required|max:100",
            "description" => "required",
            "image_path" => "mimes:doc,docx,pdf,jpeg,png,jpg",
        ]);

        $file_name = "";

        if ($request->file("image_path")) {

          if(($request->file('image_path')->getSize() / 1000000) >= 8){

            $fileExtension = strtolower($request->file('image_path')->getClientOriginalExtension());
            $href = "";
            if( $fileExtension =='pdf' ){
              $href = "Visit the link to compress the file: https://www.ilovepdf.com/compress_pdf";
            }
            elseif( in_array($fileExtension, ['jpg','png','jpg']) ){
              $href = "Visit the link to compress the file: https://www.iloveimg.com/compress-image";
            }

            return Redirect::back()->withErrors(['Maximum file size to upload is 8MB (8192 KB). If you are uploading file, try to reduce its resolution to make it under 8MB. '. $href])->withInput();
          }
          $file = $request->file("image_path");
          $dir = "storage/uploads/memo/";
          //count the number of files in the directory
          $filecount = count(glob($dir . "*")) + 1;
          $file_name = date("Y-m-d", strtotime($request->input("memo_date"))) . "-memo-" . $filecount . "." . $file->getClientOriginalExtension();
          $file->move($dir, $file_name);
        }
        
        $select_all = $request->input("select_all");
        
        if($select_all === "0"){
          $outlet_id = 0;
        }
        else{
          $outlet_id = implode(",",$request->input("outlet"));
        }

        DB::table("memo")->insert([
            "memo_date" => date("Y-m-d",strtotime($request->input("memo_date"))),
            "memo_title" => $request->input("memo_title"),
            "description" => $request->input("description"),
            "image_path" => $file_name,
            "outlet_id" => $outlet_id,
            "created_by" => session("user")->id,
        ]);

        return redirect()->back()->with("success_message", "The memo was successfully created.");
    }

    public function deleteMemo($memo_id = null)
    {
        if ($memo_id) {
            DB::table("memo")
                ->where("memo_id", $memo_id)
                ->delete();
        } else {
            die();
        }
        return redirect("/memo")->with("success_message", "The memo was successfully deleted.");
    }

    public function noticed_by(Request $r)
    {
      $memo_id = $r->input('memo_id');
      $memo = DB::table('memo')->where('memo_id', $memo_id)->first();

      if($memo->noticed_by)
        $noticed_by = $memo->noticed_by . ',' . session('user')->id;
      else
        $noticed_by = session('user')->id;
  
      if($memo){
        DB::table('memo')->where('memo_id', $memo_id)->update(['noticed_by' => $noticed_by]);
        return redirect()->back()->with('success_message', 'Memo ✔️ as read.');
      }
    }

    public function noticed_by_employees(Request $r)
    {
      $memo_id = $r->input('id');
      $memo = DB::table('memo')->where('memo_id', $memo_id)->first();

      $emp_ids = explode(",", $memo->noticed_by);

      $employees = DB::table('employees')
                    ->join('users', 'employees.UserID_Empl', '=', 'users.username')
                    ->select('employees.Name_Empl', 'users.username')
                    ->whereIn('users.id', $emp_ids)
                    ->get();
      
      if($employees)
        return response()->json(array("code" => 1, "employees" => $employees));
      else
        return response()->json(array("code" => 0, "message" => "Something went wrong."));
    }

    public function view_outlets(Request $r)
    {
      $memo_id = $r->input('id');
      $memo = DB::table('memo')->where('memo_id', $memo_id)->first();

      $outlet_ids = explode(",", $memo->outlet_id);

      $outlets = DB::table('outlet')
                    ->whereIn('outlet_id', $outlet_ids)
                    ->get();
      
      if($outlets)
        return response()->json(array("code" => 1, "outlets" => $outlets));
      else
        return response()->json(array("code" => 0, "message" => "Something went wrong."));
    }

}
