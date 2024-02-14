<?php

namespace App\Http\Controllers\Notice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function notices(Request $r)
    {
      $checkIfHasNoticePriv = false;
      if(session('other_links')) foreach (session('other_links') as $value) if($value->id == 49) $checkIfHasNoticePriv = true;
        
        $assigned_employees = [];

        $display_by = $r->input('display_by') ?? '10'; // default 10
        $date_from = $r->input('date_from') ?? date('Y-m-01');
        $date_to = $r->input('date_to') ?? date('Y-m-t');
        $date = array($date_from, $date_to);

        // invalid date inputed
        if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();

        $notices = DB::table("notices")
                ->leftJoin('users', 'notices.created_by', '=', 'users.id')
                ->select('notices.*', 'users.id', 'users.username')
                ->whereBetween('notice_date', $date);

        if ($checkIfHasNoticePriv == false && session('employee')) {
            $notices = $notices->whereRaw('FIND_IN_SET(?, notices.emp_ids)', [session('employee')->SysPK_Empl]);
        }

        if($display_by == 'all'){
            $notices = $notices->orderBy("notice_id", "desc")->get();
            $paginationLinks = null;
        }
        else{
            $notices = $notices->orderBy('notice_id', 'desc')->paginate($display_by);
            $paginationLinks = $$notices = $notices->appends(request()->query())->links();
        }

        $employees = DB::table('employees')
                        ->where('UserID_Empl','!=', null)
                        ->where('UserID_Empl','!=', '')
                        ->whereNotIn('Status_Empl', ['TERMINATED', 'AWOL', 'RESIGNED'])
                        ->get();

        if($notices){
            foreach($notices as $row){
                $emp_ids = [];
                if($row->emp_ids){
                    $emp_ids = explode(",", $row->emp_ids);
                    $emps = DB::table('employees')->select('UserID_Empl', 'Name_Empl')->whereIn('SysPK_Empl', $emp_ids)->get();
                    foreach($emps as $row2){
                        $assigned_employees[$row->notice_id][] = array(
                            'name' => $row2->Name_Empl,
                            'employee_id' => $row2->UserID_Empl,
                        );
                    }
                }
            }
        }
        
        return view("Notice.notices", [
            "notices" => $notices,
            "employees" => $employees,
            "assigned_employees" => $assigned_employees,
            'date_from' => $date_from,
            'date_to' => $date_to,
            "paginationLinks" => $paginationLinks
        ]);
    }

    public function add_notice(Request $request)
    {
        $this->validate($request, [
            "emp_ids" => "required|array",
            "notice_date" => "required",
            "notice_title" => "required|max:100",
            "description" => "required",
            "image_path" => "mimes:doc,docx,pdf,jpeg,png,jpg|max:65535",
        ]);

        $emp_ids_arr = $request->input("emp_ids");
        $emp_ids = implode(",", $emp_ids_arr);
        $file_name = "";

        if ($request->file("image_path")) {
            $file = $request->file("image_path");
            $dir = "storage/uploads/notice/";
            //count the number of files in the directory
            $filecount = count(glob($dir . "*")) + 1;
            $file_name = date("Y-m-d", strtotime($request->input("notice_date"))) . "-notice-" . $filecount . "." . $file->getClientOriginalExtension();
            $file->move($dir, $file_name);
        }

        DB::TABLE("notices")->insert([
            "notice_date" => date(
                "Y-m-d",
                strtotime($request->input("notice_date"))
            ),
            "notice_title" => $request->input("notice_title"),
            "description" => $request->input("description"),
            "image_path" => $file_name,
            "emp_ids" => $emp_ids,
            "created_by" => session("user")->id,
        ]);

        return redirect("/notices")->with("success_message", "The notice was successfully created.");
    }

    public function deleteNotice($notice_id = null)
    {
        if ($notice_id) {
            DB::table("notices")
                ->where("notice_id", $notice_id)
                ->delete();
        } else {
            die();
        }
        return redirect("/notices")->with("success_message", "The notice was successfully deleted.");
    }

    
    public function noticed_by(Request $r)
    {
      $notice_id = $r->input('notice_id');
      $notice = DB::table('notices')->where('notice_id', $notice_id)->first();

    //   if($notice->noticed_by)
    //     $noticed_by = $notice->noticed_by . ',' . session('user')->id;
    //   else
        $noticed_by = session('user')->id;
  
      if($notice){
        DB::table('notices')->where('notice_id', $notice_id)
            ->update([
                'noticed_by' => $noticed_by,
                'noticed_by_date' => date('Y-m-d H:i:s')
            ]);
        return redirect()->back()->with('success_message', 'Notice was acknowledged.');
      }
    }
}
