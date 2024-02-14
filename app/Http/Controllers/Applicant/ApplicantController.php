<?php

namespace App\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\Guard;
use Illuminate\Support\Facades\Input;

class ApplicantController extends Controller
{
    public function index()
    {
        $positions = DB::table('employees_rate')->orderBy('Position_Empl')->get();

        return view('Applicant.applicant', ["positions" => $positions]);
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'position' => 'required',
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'middle_name' => 'max:100',
            'file' => 'required|mimes:doc,docx,pdf,jpeg,png,jpg|max:2048',
        ]);

        $full_path = null;
        
        if ($request->file('file')) {

            $file = $request->file('file');
            $dir = 'storage/uploads/applications/';
            //count the number of files in the directory
            $filecount = count(glob($dir . "*")) + 1;
            $file_path = date('m_d_Y_H_i_s') . '_application_' . $filecount . '.' . $file->getClientOriginalExtension();
            $full_path = $dir . $file_path;
            $file->move($dir, $file_path);
        }

        DB::table('applicants')->insert([
            'full_path' => $full_path,
            'file_ex' => $file->getClientOriginalExtension(),
            'position' => $request->input('position'),
            'first_name' => ucwords(strtolower(strtoupper($request->input('first_name')))),
            'middle_name' => ucwords(strtolower(strtoupper($request->input('middle_name')))),
            'last_name' => ucwords(strtolower(strtoupper($request->input('last_name'))))
        ]);

        return redirect('/applicant?')->with('success_message', 'Application has been successfully submitted');
    }

    public function applicants()
    {

        // Guard::restrict_emp_user();

        $applicants = DB::table('applicants')->where(['is_deleted' => 0])->get();

        return view('Applicant.applicants', ["applicants" => $applicants]);
    }

    public function hire()
    {

        // Guard::restrict_emp_user();

        $id = $this->__get_orig_id(Input::get('id'));

        $applicant = DB::table('applicants')->where('id', $id)->first();
        $position = DB::table('employees_rate')->where('Position_Empl',$applicant->position)->first();
        $pos = "";

        DB::table('applicants')->where('id', $id)->update(['status' => 1]);

        //SAVE EMPLOYEE
        DB::TABLE('employees')->insert(
            [
                'Name_Empl' => $applicant->last_name . ", " . $applicant->first_name . " " . $applicant->middle_name,
                'FirstName_Empl' => $applicant->first_name,
                'MiddleName_Empl' => $applicant->middle_name,
                'LastName_Empl' => $applicant->last_name,
                'Address_Empl' => "",
                'Position_Empl'=>$position->id,
                'DateHired_Empl'=>date('Y-m-d'),
                'Status_Empl'=>"PROBATIONARY",
                'Restday'=>'SUNDAY',
                'gender'=>'Male'
            ]
        );
        //save user
         $user = DB::TABLE('users')->orderBy('SysPK_User', 'desc')->take(1)->first();
         $employee = DB::TABLE('employees')->orderBy('SysPK_Empl', 'desc')->take(1)->first();
            $SysPK_User = $user->SysPK_User + 1;

            //create generated username & password
            // Ex. Elvin Cabua
            // Your username will be elvin_cabua
            // Your password will be cabua_2019
            $username = strtolower($applicant->first_name) . strtolower($applicant->last_name);
            $password = password_hash(strtolower($applicant->last_name) . date('Y'), PASSWORD_DEFAULT, ['option' => 12]);
            $salt = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 8)), 0, 8);

            $user=DB::TABLE('users')->insert([
                'username' => $username,
                'first_name' => strtoupper($applicant->first_name),
                'last_name' => strtoupper($applicant->last_name),
                'email' => "",
                'employee_type_id' => 5,
                'employee_type' => 'EMP USER',
                'web_password' => $password,
                'salt' => $salt,
                'SysPK_User' => $SysPK_User,
                'emp_id' => $employee->SysPK_Empl,
            ]);

            return redirect('/applicants?')->with('success_message','Applicant has been hired');

        // return redirect('/add-employee?')
        //             ->with('Applicant_Fname',$applicant->first_name)
        //             ->with('Applicant_Mname',$applicant->middle_name)
        //             ->with('Applicant_Lname',$applicant->last_name)
        //             ->with('success_message','Applicant has been hired. Please Input Employee Details');
    }

    public function decline()
    {
        
        // Guard::restrict_emp_user();

        $id = $this->__get_orig_id(Input::get('id'));

        DB::table('applicants')->where('id', $id)->update(['status' => 2]);

        return redirect('/applicants?')->with('success_message','Applicant has been rejected');

    }

    public function delete()
    {
        
        // Guard::restrict_emp_user();

        $id = $this->__get_orig_id(Input::get('id'));

        DB::table('applicants')->where('id', $id)->update(['is_deleted' => 1]);

        return redirect('/applicants?')->with('success_message','Applicantion has been deleted');
    }
}
