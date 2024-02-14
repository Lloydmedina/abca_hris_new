<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Auth\Guard;
use App\Http\Controllers\Controller;
use App\Models\Employees as Emp;
use App\Models\Employee_attachment as Emp_att;
use App\Models\Employee_job_desc as Emp_job;
use App\Models\Employee_skill as Emp_skill;
use App\Models\Employee_training_seminars as Emp_training_seminars;
use App\Models\Employee_organizational as Emp_org;
use App\Models\Employee_medical_record as Emp_med_rec;
use App\Models\Users as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;

class Employee extends Controller {

    public function add_employee_view() 
    {

        // Guard::restrict_emp_user();

        $emp_level = DB::table('emp_level')->select('emp_lvl_id', 'emp_lvl')->get();
        $department = DB::table('department')->select('SysPK_Dept', 'Name_Dept', 'DeptCode_Dept')->get();
        $company = DB::table('company')->get();
        $outlet = DB::table('outlet')->get();
        $employees_rate = DB::table('employees_rate')->select('id', 'Position_Empl')->get();

        $employees = DB::table('employees')
                        ->where('UserID_Empl','!=', null)
                        ->where('UserID_Empl','!=', '')
                        ->get();

        return view('Employee.add_employee', 
                [
                    'emp_level' => $emp_level,
                    'department' => $department,
                    'company' => $company,
                    'outlet' => $outlet,
                    'employees_rate' => $employees_rate,
                    'employees' => $employees
                ]
        );
    }

    public function add_employee_process(Request $request){

        $this->validate($request, [
            'FirstName_Empl' => 'required',
            'Department_Empl' => 'required',
            'LastName_Empl' => 'required',
            'gender' => 'required',
            'civilStatus' => 'required',
            'BirthDate_Empl' => 'required',
            'UserID_Empl' => 'required',
        ]);

        $target_file = $profile_pic = null;
        
        if ($request->file('picture_path')) {

            $img_file = $request->file('picture_path');
            $dir = 'storage/uploads/profile_picture/';
            //count the number of files in the directory
            $filecount = count(glob($dir . "*")) + 1;
            $file_path = date('m_d_Y_H_i_s') . '_profile_' . $filecount . '.' . $img_file->getClientOriginalExtension();
            $target_file = $dir . $file_path;
            $profile_pic = $target_file;
            $img_file->move($dir, $file_path);
        }

        //GET ALL INPUT DATA
        $input_data = $request->input();
        //SET FULL NAME
        $input_data['Name_Empl'] = $request->input('LastName_Empl').', '.$request->input('FirstName_Empl') . ' ' . $request->input('MiddleName_Empl');

        $input_data['emp_level_id'] = $this->__get_orig_id($input_data['emp_level_id']);
        $input_data['Position_Empl'] = $this->__get_orig_id($input_data['Position_Empl']);
        $input_data['dept_id'] = $this->__get_orig_id($input_data['Department_Empl']);
        $input_data['outlet_id'] = $this->__get_orig_id($input_data['outlet_id']);
        $input_data['company_id'] = $this->__get_orig_id($input_data['company_id']);

        $dept = DB::table('department')->where('SysPK_Dept', $input_data['dept_id'])->first();

        $input_data['Department_Empl'] = $dept->Name_Dept;

        $input_data['require_dtr'] = $request->input('require_dtr', 0);




        //REMOVE DATA
        unset($input_data['_token']);
        unset($input_data['filename']);
        unset($input_data['dir_path']);
        unset($input_data['job_description']);
        unset($input_data['skill']);
        unset($input_data['seminar_date_from']);
        unset($input_data['seminar_date_to']);
        unset($input_data['seminar_name']);
        unset($input_data['seminar_address']);
        unset($input_data['organizational']);
        unset($input_data['dependent_bday']);
        unset($input_data['dependent_full_name']);
        unset($input_data['medical_record']);


        // CHECK FIRST IF THERE'S ALREADY A RECORD
        $check_employee = DB::table('employees')->where('UserID_Empl', $input_data['UserID_Empl'])->first();

        if($input_data['approver_1'] == $input_data['approver_2'] && $input_data['approver_1']) return \Redirect::back()->withInput($request->input())->withErrors(['The approvers must not be the same.']);

        if($check_employee){
            return \Redirect::back()->withInput($request->input())->withErrors(['Employee ID# Already Exists.']);
        }else{

            $approvers = DB::table('approvers')->where('emp_id', $input_data['UserID_Empl'])->first();

            if($approvers){
                // update
                DB::table('approvers')->where('emp_id', $input_data['UserID_Empl'])
                ->update([
                    'approver_1_emp_id' => $input_data['approver_1'] ?? $approvers->approver_1_emp_id, 
                    'approver_2_emp_id' => $input_data['approver_2'] ?? $approvers->approver_2_emp_id
                ]);
            }
            else{
                // Insert
                DB::table('approvers')->insert([
                    'emp_id' => $input_data['UserID_Empl'],
                    'approver_1_emp_id' => $input_data['approver_1'],
                    'approver_2_emp_id' => $input_data['approver_2']
                ]);
            }
            
        }
        unset($input_data['approver_1']);
        unset($input_data['approver_2']);

        
        if ($target_file != null) {
            $data = array_merge($input_data, array('picture_path' => $target_file));
        } else {
            $data = $input_data;
        }
        // INSERT DATA TO EMPLOYEES
        // All data for employees stored in $data var to be inserted
        Emp::insert($data);
        // GET PRIMARY KEY
        $SysPK_Empl = DB::getPdo()->lastInsertId();

        if ($SysPK_Empl > 0) {
            
            $target_file = null;

            // Add employee docs
            if ($request->file('dir_path')) {
                if (count($request->file('dir_path')) > 0) {
                    for ($i=0; $i < count($request->file('dir_path')); $i++) { 
                        $img_file = $request->file('dir_path')[$i];
                        $dir = 'storage/uploads/employees_att/';
                        //count the number of files in the directory
                        $filecount = count(glob($dir . "*")) + 1;
                        $file_path = date('m_d_Y_H_i_s') . '_attachment_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                        $target_file = $dir . $file_path;
                        $img_file->move($dir, $file_path);

                        $data = array('dir_path' => $target_file, 'emp_id' => $SysPK_Empl, 'filename' => $request->input('filename')[$i]);
                        Emp_att::insert($data);
                    }
                }
            }
            

            $target_file = null;

            // Add employee job desc
            if ($request->file('jd_image_path')) {
                if (count($request->file('jd_image_path')) > 0) {
                    for ($i=0; $i < count($request->file('jd_image_path')); $i++) { 
                        $img_file = $request->file('jd_image_path')[$i];
                        $dir = 'storage/uploads/employees_job/';
                        //count the number of files in the directory
                        $filecount = count(glob($dir . "*")) + 1;
                        $file_path = date('m_d_Y_H_i_s') . '_job_desc_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                        $target_file = $dir . $file_path;
                        $img_file->move($dir, $file_path);

                        $data = array('jd_image_path' => $target_file, 'emp_id' => $SysPK_Empl, 'job_description' => $request->input('job_description')[$i]);
                        Emp_job::insert($data);
                    }
                }
            }
            

            $target_file = null;

            // Add employee skills
            if ($request->file('skills_image_path')) {
                if (count($request->file('skills_image_path')) > 0) {
                    for ($i=0; $i < count($request->file('skills_image_path')); $i++) { 
                        $img_file = $request->file('skills_image_path')[$i];
                        $dir = 'storage/uploads/employees_skill/';
                        //count the number of files in the directory
                        $filecount = count(glob($dir . "*")) + 1;
                        $file_path = date('m_d_Y_H_i_s') . '_skill_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                        $target_file = $dir . $file_path;
                        $img_file->move($dir, $file_path);

                        $data = array('skills_image_path' => $target_file, 'emp_id' => $SysPK_Empl, 'skill' => $request->input('skill')[$i]);
                        Emp_skill::insert($data);
                    }
                }
            }
            

            $target_file = null;

            // Add employee training & seminars
            if ($request->file('seminar_path')) {
                if (count($request->file('seminar_path')) > 0) {
                    for ($i=0; $i < count($request->file('seminar_path')); $i++) { 
                        $img_file = $request->file('seminar_path')[$i];
                        $dir = 'storage/uploads/employees_training_seminars/';
                        //count the number of files in the directory
                        $filecount = count(glob($dir . "*")) + 1;
                        $file_path = date('m_d_Y_H_i_s') . '_training_seminars_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                        $target_file = $dir . $file_path;
                        $img_file->move($dir, $file_path);

                        $data = array(
                            'certificate_path' => $target_file, 'emp_id' => $SysPK_Empl, 
                            'from_date' => $request->input('seminar_date_from')[$i],
                            'to_date' => $request->input('seminar_date_to')[$i],
                            'seminar_training' => $request->input('seminar_name')[$i],
                            'seminar_address' => $request->input('seminar_address')[$i],
                        );
                        Emp_training_seminars::insert($data);
                    }
                }
            }
            

            $target_file = null;

            // Add org
            if ($request->file('organizational_path')) {
                if (count($request->file('organizational_path')) > 0) {
                    for ($i=0; $i < count($request->file('organizational_path')); $i++) { 
                        $img_file = $request->file('organizational_path')[$i];
                        $dir = 'storage/uploads/employees_org/';
                        //count the number of files in the directory
                        $filecount = count(glob($dir . "*")) + 1;
                        $file_path = date('m_d_Y_H_i_s') . '_org_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                        $target_file = $dir . $file_path;
                        $img_file->move($dir, $file_path);

                        $data = array('organizational_path' => $target_file, 'emp_id' => $SysPK_Empl, 'organizational' => $request->input('organizational')[$i]);
                        Emp_org::insert($data);
                    }
                }
            }
            
            $target_file = null;

            // Add dependent
            if ($request->input('dependent_bday')) {
                if (count($request->input('dependent_bday')) > 0) {
                    for ($i=0; $i < count($request->input('dependent_bday')); $i++) { 

                        if($request->input('dependent_bday')[$i] || $request->input('dependent_full_name')[$i] ){

                            $data = array('emp_id' => $SysPK_Empl,
                                        'dependent_bday' => $request->input('dependent_bday')[$i],
                                        'dependent_full_name' => $request->input('dependent_full_name')[$i]
                                    );
                            DB::table('emp_dependent')->insert($data);

                        }
                    }
                }
            }
            

            $target_file = null;

            // Add Emp_med_rec
            if ($request->file('medical_record_path')) {
                if (count($request->file('medical_record_path')) > 0) {
                    for ($i=0; $i < count($request->file('medical_record_path')); $i++) { 
                        $img_file = $request->file('medical_record_path')[$i];
                        $dir = 'storage/uploads/medical_record/';
                        //count the number of files in the directory
                        $filecount = count(glob($dir . "*")) + 1;
                        $file_path = date('m_d_Y_H_i_s') . '_medical_record_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                        $target_file = $dir . $file_path;
                        $img_file->move($dir, $file_path);

                        $data = array('medical_record_path' => $target_file, 'emp_id' => $SysPK_Empl, 'medical_record' => $request->input('medical_record')[$i]);
                        Emp_med_rec::insert($data);
                    }
                }
            }
            
        }

        // Add user login
        // Generate employee's user credentials
        $this->import_credentials();

        return redirect('/add-employee')->with('success_message', 'New employee successfully added!');
    }

    public function import_credentials(){

        set_time_limit(500);
        $check = Input::get('check') ?? 0;
        
        Guard::restrict_emp_user();

        $employees = Emp::all();

        $user_credentials = array();

        $user = User::orderBy('SysPK_User', 'desc')->take(1)->first();

        $plus1 = 1;

        //create generated username & password
        // Ex. Elvin Cabua
        // Your username will be elvin_cabua
        // Your password will be cabua_2019

        foreach ($employees as $row) {

            // Check if already have a credentials, if true then skip
            if(DB::table('users')->where('username', $row->UserID_Empl)->whereNotIn('employee_type_id',[1,2])->first())
            continue;

            $SysPK_User = $user->SysPK_User + $plus1;
            $plus1++;
            
            // $username = strtolower($row->FirstName_Empl) . strtolower($row->LastName_Empl);
            // $password = password_hash(strtolower(str_replace(' ', '', $row->LastName_Empl)) . date('Y'), PASSWORD_DEFAULT, ['option' => 12]);
            $salt = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 8)), 0, 8);
            $username = $row->UserID_Empl;
            $password = password_hash($row->UserID_Empl,PASSWORD_DEFAULT, ['option' => 12]);

            // store to array
            $user_credentials[] = [
                // 'username' => utf8_encode(str_replace(' ', '', $username)),
                'username' => $username,
                'first_name' => utf8_encode(strtoupper($row->FirstName_Empl)),
                'last_name' => utf8_encode(strtoupper($row->LastName_Empl)),
                'email' => utf8_encode($row->email),
                'employee_type_id' => 5,
                'employee_type' => 'EMP USER',
                'web_password' => $password,
                'salt' => $salt,
                // 'photo' => $row->picture_path,
                'SysPK_User' => $SysPK_User,
                'emp_id' => $row->SysPK_Empl,
            ];

        }
        
        if($check != 0)
            dd($user_credentials);

        if(count($user_credentials))
            DB::table('users')->insert($user_credentials);

        return Redirect::back()->with('success_message', 'Credentials successfully imported!');
    }

    public function employees_view() 
    {
        
        // Guard::restrict_emp_user();

        $arr_emp_status = Input::get('emp_status') ?? ['REGULAR'];

        $departmentSelected = Input::get('department') ?? 0;
        $outletSelected = Input::get('outlet') ?? 0;

        $department = DB::table('department')->get();
        $outlets = DB::TABLE('outlet')->orderBy('outlet_id', 'desc')->get();

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->join('company', 'employees.company_id', '=', 'company.company_id')
            ->select('employees.*', 'employees_rate.Position_Empl','company.company','outlet.outlet');
        
        if($arr_emp_status) $employees = $employees->whereIn('Status_Empl', $arr_emp_status);
        else $employees = $employees->where('Status_Empl', null);

        if(is_numeric($departmentSelected) && $departmentSelected > 0) $employees = $employees->where('employees.dept_id', $departmentSelected);
        if(is_numeric($outletSelected) && $outletSelected > 0) $employees = $employees->where('outlet.outlet_id', $outletSelected);

        $employees = $employees
                    ->orderBy('employees.emp_level_id', 'ASC')
                    ->orderBy('employees_rate.id', 'ASC')
                    ->orderBy('employees.LastName_Empl', 'DESC')
                    ->get();

        $empStatus = DB::table('employees')->select('Status_Empl')->groupBy('Status_Empl')->orderBy('Status_Empl', 'desc')->get();
        // return view('Employee.employee_list', ['employees' => $employees]);
        return view('Employee.employee_list', [
            'employees' => $employees,
            'department' => $department,
            'outlets' => $outlets,
            'empStatus' => $empStatus
        ]);
        // return view('Employee.employee_list_2', [
        //     'employees' => $employees,
        //     'department' => $department,
        //     'outlets' => $outlets,
        //     'empStatus' => $empStatus
        // ]);
    }
    
    public function add_employee_exit() 
    { 
        $SysPK_Empl = $this->__get_orig_id(Input::get('id'));

        $employees = DB::table('employees')
                        ->where('SysPK_Empl','!=', $SysPK_Empl)
                        ->where('UserID_Empl','!=', null)
                        ->where('UserID_Empl','!=', '')
                        ->get();
        $approvers = [];
        $approver_1 = '';
        $approver_2 = '';
        
        //GET THE ITEM OF EMPLOYEE
        $employee = DB::table('employees')
            ->leftJoin('employee_attachment', 'employees.SysPK_Empl', '=', 'employee_attachment.emp_id')
            ->leftJoin('emp_level', 'employees.emp_level_id', '=', 'emp_level.emp_lvl_id')
            ->leftJoin('company', 'employees.company_id', '=', 'company.company_id')
            ->leftJoin('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->leftJoin('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.*', 'employee_attachment.*','emp_level.emp_lvl','company.company','outlet.outlet','employees_rate.Position_Empl')
            ->where('employees.SysPK_Empl', $SysPK_Empl)->first();
        
        if(isset($employee->UserID_Empl)) $approvers = DB::table('approvers')->where('emp_id', $employee->UserID_Empl)->first();
        
        $emp_level = DB::table('emp_level')->select('emp_lvl_id', 'emp_lvl')->get();
        $department = DB::table('department')->select('SysPK_Dept', 'Name_Dept')->get();
        $company = DB::table('company')->get();
        $outlet = DB::table('outlet')->get();
        $employees_rate = DB::table('employees_rate')->select('id', 'Position_Empl')->get();

        $dependents = DB::table('emp_dependent')->where('emp_id', $SysPK_Empl)->get();
        $documents = DB::table('employee_attachment')->where('emp_id', $SysPK_Empl)->get();
        $job_description = DB::table('emp_job_description')->where('emp_id', $SysPK_Empl)->get();
        $skills = DB::table('emp_skills')->where('emp_id', $SysPK_Empl)->get();
        $training_seminars = DB::table('emp_training_seminars')->where('emp_id', $SysPK_Empl)->get();
        $organizational = DB::table('emp_organizational')->where('emp_id', $SysPK_Empl)->get();
        $medical_record = DB::table('emp_medical_record')->where('emp_id', $SysPK_Empl)->get();

        if($approvers){
            $approver_1 = $approvers->approver_1_emp_id;
            $approver_2 = $approvers->approver_2_emp_id;
        }
        
        $params = [
                'emp_level' => $emp_level, 
                'department' => $department,
                'company' => $company,
                'outlet' => $outlet,
                'employees_rate' => $employees_rate, 
                'employee' => $employee,
                'employees' => $employees,
                'approvers' => $approvers,
                'approver_1' => $approver_1,
                'approver_2' => $approver_2,
                'dependents' => $dependents,
                'documents' => $documents,
                'job_description' => $job_description,
                'skills' => $skills,
                'training_seminars' => $training_seminars,
                'organizational' => $organizational,
                'medical_record' => $medical_record
            ];

        return view('Employee.add_employee_exit', $params);
    }
    public function employee_exit_list() 
    {       
        // Guard::restrict_emp_user();

        $arr_emp_status = Input::get('emp_status') ?? ['REGULAR'];

        $departmentSelected = Input::get('department') ?? 0;
        $outletSelected = Input::get('outlet') ?? 0;

        $department = DB::table('department')->get();
        $outlets = DB::TABLE('outlet')->orderBy('outlet_id', 'desc')->get();

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->join('outlet', 'employees.outlet_id', '=', 'outlet.outlet_id')
            ->join('company', 'employees.company_id', '=', 'company.company_id')
            ->select('employees.*', 'employees_rate.Position_Empl','company.company','outlet.outlet');
        
        if($arr_emp_status) $employees = $employees->whereIn('Status_Empl', $arr_emp_status);
        else $employees = $employees->where('Status_Empl', null);

        if(is_numeric($departmentSelected) && $departmentSelected > 0) $employees = $employees->where('employees.dept_id', $departmentSelected);
        if(is_numeric($outletSelected) && $outletSelected > 0) $employees = $employees->where('outlet.outlet_id', $outletSelected);

        $employees = $employees
                    ->orderBy('employees.emp_level_id', 'ASC')
                    ->orderBy('employees_rate.id', 'ASC')
                    ->orderBy('employees.LastName_Empl', 'DESC')
                    ->get();

        $empStatus = DB::table('employees')->select('Status_Empl')->groupBy('Status_Empl')->orderBy('Status_Empl', 'desc')->get();
        return view('Employee.employee_exit_list', [
            'employees' => $employees,
            'department' => $department,
            'outlets' => $outlets,
            'empStatus' => $empStatus
        ]);
    }

    public function employee_info_view()
    {
        
        $SysPK_Empl = $this->__get_orig_id((session('user')->employee_type_id == 5) ? session('employee')->SysPK_Empl.md5('1') : Input::get('id'));
        
        $approvers['approver_1'] = null;
        $approvers['approver_2'] = null;
        //GET THE ITEM OF EMPLOYEE
        $employee = DB::table('employees')
            ->leftJoin('employee_attachment', 'employees.SysPK_Empl', '=', 'employee_attachment.emp_id')
            ->select('employees.*', 'employee_attachment.*')
            ->where('employees.SysPK_Empl', $SysPK_Empl)->first();

        $approver_ids = DB::table('approvers')
                        // ->join('employees', 'approvers.emp_id', '=', 'employees.UserID_Empl')
                        ->select('approver_1_emp_id', 'approver_2_emp_id')
                        ->where('emp_id', $employee->UserID_Empl)
                        ->first();
        
        if($approver_ids){
            if($approver_ids->approver_1_emp_id){
            $approvers['approver_1'] = DB::table('employees')
                        ->where('UserID_Empl', $approver_ids->approver_1_emp_id)
                        ->first();
        
            }
            if($approver_ids->approver_2_emp_id){
            $approvers['approver_2'] = DB::table('employees')
                        ->where('UserID_Empl', $approver_ids->approver_2_emp_id)
                        ->first();
            }
        }

        $emp_level = DB::table('emp_level')->select('emp_lvl_id', 'emp_lvl')->get();
        $department = DB::table('department')->select('SysPK_Dept', 'Name_Dept')->get();
        $employees_rate = DB::table('employees_rate')->select('id', 'Position_Empl')->get();

        return view('Employee.employee_info',[
            'emp_level' => $emp_level,
            'department' => $department,
            'employees_rate' => $employees_rate,
            'employee' => $employee,
            'approvers' => $approvers,
        ]);
    }

    public function update_employee_view() 
    {
        
        // Guard::restrict_emp_user();

        $SysPK_Empl = $this->__get_orig_id(Input::get('id'));

        $employees = DB::table('employees')
                        ->where('SysPK_Empl','!=', $SysPK_Empl)
                        ->where('UserID_Empl','!=', null)
                        ->where('UserID_Empl','!=', '')
                        ->get();
        $approvers = [];
        $approver_1 = '';
        $approver_2 = '';
        
        //GET THE ITEM OF EMPLOYEE
        $employee = DB::table('employees')
            ->leftJoin('employee_attachment', 'employees.SysPK_Empl', '=', 'employee_attachment.emp_id')
            ->select('employees.*', 'employee_attachment.*')
            ->where('employees.SysPK_Empl', $SysPK_Empl)->first();
        
        if(isset($employee->UserID_Empl)) $approvers = DB::table('approvers')->where('emp_id', $employee->UserID_Empl)->first();
        
        $emp_level = DB::table('emp_level')->select('emp_lvl_id', 'emp_lvl')->get();
        $department = DB::table('department')->select('SysPK_Dept', 'Name_Dept')->get();
        $company = DB::table('company')->get();
        $outlet = DB::table('outlet')->get();
        $employees_rate = DB::table('employees_rate')->select('id', 'Position_Empl')->get();

        $dependents = DB::table('emp_dependent')->where('emp_id', $SysPK_Empl)->get();
        $documents = DB::table('employee_attachment')->where('emp_id', $SysPK_Empl)->get();
        $job_description = DB::table('emp_job_description')->where('emp_id', $SysPK_Empl)->get();
        $skills = DB::table('emp_skills')->where('emp_id', $SysPK_Empl)->get();
        $training_seminars = DB::table('emp_training_seminars')->where('emp_id', $SysPK_Empl)->get();
        $organizational = DB::table('emp_organizational')->where('emp_id', $SysPK_Empl)->get();
        $medical_record = DB::table('emp_medical_record')->where('emp_id', $SysPK_Empl)->get();

        if($approvers){
            $approver_1 = $approvers->approver_1_emp_id;
            $approver_2 = $approvers->approver_2_emp_id;
        }
        
        $params = [
                'emp_level' => $emp_level, 
                'department' => $department,
                'company' => $company,
                'outlet' => $outlet,
                'employees_rate' => $employees_rate, 
                'employee' => $employee,
                'employees' => $employees,
                'approvers' => $approvers,
                'approver_1' => $approver_1,
                'approver_2' => $approver_2,
                'dependents' => $dependents,
                'documents' => $documents,
                'job_description' => $job_description,
                'skills' => $skills,
                'training_seminars' => $training_seminars,
                'organizational' => $organizational,
                'medical_record' => $medical_record
            ];

        return view('Employee.update_employee', $params);
    }

    public function update_employee_process(Request $request) 
    {
        
        $this->validate($request, [
            'FirstName_Empl' => 'required',
            'LastName_Empl' => 'required',
            'Address_Empl' => 'required',
            'Address_Empl' => 'required',
            'gender' => 'required',
            'civilStatus' => 'required',
            'BirthDate_Empl' => 'required'
        ]);

        $target_file = $profile_pic = null;
        if ($request->file('picture_path')) {

            $img_file = $request->file('picture_path');
            $dir = 'storage/uploads/profile_picture/';
            //count the number of files in the directory
            $filecount = count(glob($dir . "*")) + 1;
            $file_path = date('m_d_Y_H_i_s') . '_profile_' . $filecount . '.' . $img_file->getClientOriginalExtension();
            $target_file = $dir . $file_path;
            $profile_pic = $target_file;
            $img_file->move($dir, $file_path);
        }
        
        //GET ALL INPUT DATA
        $input_data = $request->input();

        if ($request->input('require_dtr') == 1)
        {
            $input_data['require_dtr'] =  1;
        }
        else
        {
            $input_data['require_dtr'] =  0;
        }

        if ($request->input('employee_reimbursement') == 1)
        {
            $input_data['employee_reimbursement'] =  1;
        }
        else
        {
            $input_data['employee_reimbursement'] =  0;
        }


        
        //SET FULL NAME
        $input_data['Name_Empl'] = $request->input('LastName_Empl').', '.$request->input('FirstName_Empl') . ' ' . $request->input('MiddleName_Empl');

        $input_data['emp_level_id'] = $this->__get_orig_id($input_data['emp_level_id']);
        $input_data['Position_Empl'] = $this->__get_orig_id($input_data['Position_Empl']);
        $input_data['outlet_id'] = $this->__get_orig_id($input_data['outlet_id']);
        $input_data['company_id'] = $this->__get_orig_id($input_data['company_id']);
        $input_data['dept_id'] = $this->__get_orig_id($input_data['Department_Empl']);
        $input_data['updated_at'] = now();

        $dept = DB::table('department')->where('SysPK_Dept', $input_data['dept_id'])->first();

        $input_data['Department_Empl'] = $dept->Name_Dept;
        
        //REMOVE TOKEN
        unset($input_data['_token']);
        unset($input_data['filename']);
        unset($input_data['dir_path']);
        unset($input_data['SysPK_Empl']);
        unset($input_data['job_description']);
        unset($input_data['skill']);
        unset($input_data['seminar_date_from']);
        unset($input_data['seminar_date_to']);
        unset($input_data['seminar_name']);
        unset($input_data['seminar_address']);
        unset($input_data['organizational']);
        unset($input_data['dependent_bday']);
        unset($input_data['dependent_full_name']);
        unset($input_data['medical_record']);
        // added by default
        unset($input_data['def_dir_path']);
        unset($input_data['def_jd_image_path']);
        unset($input_data['def_skills_image_path']);
        unset($input_data['def_certificate_path']);
        unset($input_data['from_date']);
        unset($input_data['to_date']);
        unset($input_data['seminar_training']);
        unset($input_data['def_organizational_path']);
        unset($input_data['def_medical_record_path']);
        
    

        // CHECK FIRST IF THERE'S ALREADY A RECORD
        $approvers = DB::table('approvers')->where('emp_id', $input_data['UserID_Empl'])->first();

        if($input_data['approver_1'] == $input_data['approver_2'] && $input_data['approver_1']) return \Redirect::back()->withInput($request->input())->withErrors(['The approvers must not be the same.']);

        if($approvers){
            // update
            DB::table('approvers')->where('emp_id', $input_data['UserID_Empl'])
            ->update([
                'approver_1_emp_id' => $input_data['approver_1'], 
                'approver_2_emp_id' => $input_data['approver_2']
            ]);
        }else{
            // Insert
            DB::table('approvers')->insert([
                'emp_id' => $input_data['UserID_Empl'],
                'approver_1_emp_id' => $input_data['approver_1'],
                'approver_2_emp_id' => $input_data['approver_2']
            ]);
        }
        unset($input_data['approver_1']);
        unset($input_data['approver_2']);

        if ($target_file != null) {
            $data = array_merge($input_data, array('picture_path' => $target_file));
        } else {
            $data = $input_data;
        }

        //INSERT DATA TO EMPLOYEES_RATE
        $SysPK_Empl = $this->__get_orig_id($request->input('SysPK_Empl'));
        //UPDATE EMPLOYEE TABLE
        Emp::where('SysPK_Empl', $SysPK_Empl)->update($data);

        if ($SysPK_Empl > 0) {
            
            $target_file = null;
            // Delete employee_attachment first
            DB::table('employee_attachment')->where('emp_id', $SysPK_Empl)->delete();
            // Add employee docs
            if ($request->file('dir_path')) {
                $ctr_docs = count($request->file('dir_path')) + count($request->input('def_dir_path'));
                if ($ctr_docs > 0) {
                    for ($i=0; $i < $ctr_docs; $i++) {

                        if(count($request->file('dir_path')) > 0){
                            if(array_key_exists($i,$request->file('dir_path'))){
                                $img_file = $request->file('dir_path')[$i];
                                $dir = 'storage/uploads/employees_att/';
                                //count the number of files in the directory
                                $filecount = count(glob($dir . "*")) + 1;
                                $file_path = date('m_d_Y_H_i_s') . '_attachment_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                                $target_file = $dir . $file_path;
                                $img_file->move($dir, $file_path);
                            }
                            else{
                                if(array_key_exists($i,$request->input('def_dir_path'))){
                                    $target_file = $request->input('def_dir_path')[$i];
                                }
                                else{
                                    // $i -= 1;
                                    break;
                                    // $target_file = $request->input('def_dir_path')[$i];
                                }
                            }
                        }
                        else{
                            $target_file = $request->input('def_dir_path')[$i];
                        }
                        
                        $data = array('dir_path' => $target_file, 'emp_id' => $SysPK_Empl, 'filename' => $request->input('filename')[$i]);
                        Emp_att::insert($data);
                    }
                }
            }
            
            
            // Delete dependents first
            DB::table('emp_dependent')->where('emp_id', $SysPK_Empl)->delete();
            // update/ insert new dependent
            if ($request->input('dependent_bday')) {
                if (count($request->input('dependent_bday')) > 0) {
                    for ($i=0; $i < count($request->input('dependent_bday')); $i++) { 

                        if($request->input('dependent_bday')[$i] || $request->input('dependent_full_name')[$i] ){

                            $data = array('emp_id' => $SysPK_Empl,
                                        'dependent_bday' => $request->input('dependent_bday')[$i],
                                        'dependent_full_name' => $request->input('dependent_full_name')[$i]
                                    );
                            DB::table('emp_dependent')->insert($data);

                        }
                    }
                }
            }
            

            $target_file = null;
            // Delete emp_job_description first
            DB::table('emp_job_description')->where('emp_id', $SysPK_Empl)->delete();
            // Add employee docs
            if ($request->file('jd_image_path')) {
               $ctr_job_desc = count($request->file('jd_image_path')) + count($request->input('def_jd_image_path'));
                if ($ctr_job_desc > 0) {
                    for ($i=0; $i < $ctr_job_desc; $i++) {

                        if(count($request->file('jd_image_path')) > 0){
                            if(array_key_exists($i,$request->file('jd_image_path'))){
                                $img_file = $request->file('jd_image_path')[$i];
                                $dir = 'storage/uploads/employees_job/';
                                //count the number of files in the directory
                                $filecount = count(glob($dir . "*")) + 1;
                                $file_path = date('m_d_Y_H_i_s') . '_job_desc_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                                $target_file = $dir . $file_path;
                                $img_file->move($dir, $file_path);
                            }
                            else{
                                if(array_key_exists($i,$request->input('def_jd_image_path'))){
                                    $target_file = $request->input('def_jd_image_path')[$i];
                                }
                                else{
                                    // $i -= 1;
                                    break;
                                    // $target_file = $request->input('def_jd_image_path')[$i];
                                }
                            }
                        }
                        else{
                            $target_file = $request->input('def_jd_image_path')[$i];
                        }
                        
                        $data = array('jd_image_path' => $target_file, 'emp_id' => $SysPK_Empl, 'job_description' => $request->input('job_description')[$i]);
                        Emp_job::insert($data);
                    }
                } 
            }
            

            $target_file = null;
            // Delete emp_skills first
            DB::table('emp_skills')->where('emp_id', $SysPK_Empl)->delete();
            // Add employee docs
            if ($request->file('skills_image_path')) {
                $ctr_skills = count($request->file('skills_image_path')) + count($request->input('def_skills_image_path'));
                if ($ctr_skills > 0) {
                    for ($i=0; $i < $ctr_skills; $i++) {

                        if(count($request->file('skills_image_path')) > 0){
                            if(array_key_exists($i,$request->file('skills_image_path'))){
                                $img_file = $request->file('skills_image_path')[$i];
                                $dir = 'storage/uploads/employees_skill/';
                                //count the number of files in the directory
                                $filecount = count(glob($dir . "*")) + 1;
                                $file_path = date('m_d_Y_H_i_s') . '_skill_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                                $target_file = $dir . $file_path;
                                $img_file->move($dir, $file_path);
                            }
                            else{
                                if(array_key_exists($i,$request->input('def_skills_image_path'))){
                                    $target_file = $request->input('def_skills_image_path')[$i];
                                }
                                else{
                                    // $i -= 1;
                                    break;
                                    // $target_file = $request->input('def_skills_image_path')[$i];
                                }
                            }
                        }
                        else{
                            $target_file = $request->input('def_skills_image_path')[$i];
                        }
                        
                        $data = array('skills_image_path' => $target_file, 'emp_id' => $SysPK_Empl, 'skill' => $request->input('skill')[$i]);
                        Emp_skill::insert($data);
                    }
                }
            }
            

            $target_file = null;
            // Delete emp_training_seminars first
            DB::table('emp_training_seminars')->where('emp_id', $SysPK_Empl)->delete();
            // Add employee docs
            if ($request->file('certificate_path')) {
                $cert = count($request->file('certificate_path')) + count($request->input('def_certificate_path'));
                if ($cert > 0) {
                    for ($i=0; $i < $cert; $i++) {

                        if(count($request->file('certificate_path')) > 0){
                            if(array_key_exists($i,$request->file('certificate_path'))){
                                $img_file = $request->file('certificate_path')[$i];
                                $dir = 'storage/uploads/employees_training_seminars/';
                                //count the number of files in the directory
                                $filecount = count(glob($dir . "*")) + 1;
                                $file_path = date('m_d_Y_H_i_s') . '_training_seminars_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                                $target_file = $dir . $file_path;
                                $img_file->move($dir, $file_path);
                            }
                            else{
                                if(array_key_exists($i,$request->input('def_certificate_path'))){
                                    $target_file = $request->input('def_certificate_path')[$i];
                                }
                                else{
                                    // $i -= 1;
                                    break;
                                    // $target_file = $request->input('def_certificate_path')[$i];
                                }
                            }
                        }
                        else{
                            $target_file = $request->input('def_certificate_path')[$i];
                        }
                        
                        $data = array(
                            'certificate_path' => $target_file,
                            'emp_id' => $SysPK_Empl,
                            'from_date' => $request->input('from_date')[$i],
                            'to_date' => $request->input('to_date')[$i],
                            'seminar_training' => $request->input('seminar_training')[$i],
                            'seminar_address' => $request->input('seminar_address')[$i],
                        );
                        Emp_training_seminars::insert($data);
                    }
                }
            }
            

            $target_file = null;
            // Delete emp_organizational first
            DB::table('emp_organizational')->where('emp_id', $SysPK_Empl)->delete();
            // Add employee docs
            if ($request->file('organizational_path')) {
                $ctr_org = count($request->file('organizational_path')) + count($request->input('def_organizational_path'));
                if ($ctr_org > 0) {
                    for ($i=0; $i < $ctr_org; $i++) {

                        if(count($request->file('organizational_path')) > 0){
                            if(array_key_exists($i,$request->file('organizational_path'))){
                                $img_file = $request->file('organizational_path')[$i];
                                $dir = 'storage/uploads/employees_org/';
                                //count the number of files in the directory
                                $filecount = count(glob($dir . "*")) + 1;
                                $file_path = date('m_d_Y_H_i_s') . '_org_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                                $target_file = $dir . $file_path;
                                $img_file->move($dir, $file_path);
                            }
                            else{
                                if(array_key_exists($i,$request->input('def_organizational_path'))){
                                    $target_file = $request->input('def_organizational_path')[$i];
                                }
                                else{
                                    // $i -= 1;
                                    break;
                                    // $target_file = $request->input('def_organizational_path')[$i];
                                }
                            }
                        }
                        else{
                            $target_file = $request->input('def_organizational_path')[$i];
                        }
                        
                        $data = array('organizational_path' => $target_file, 'emp_id' => $SysPK_Empl, 'organizational' => $request->input('organizational')[$i]);
                        Emp_org::insert($data);
                    }
                }
            }
            

            $target_file = null;
            // Delete emp_medical_record first
            DB::table('emp_medical_record')->where('emp_id', $SysPK_Empl)->delete();
            // Add employee docs
            if ($request->file('medical_record_path')) {
                $ctr_med = count($request->file('medical_record_path')) + count($request->input('def_medical_record_path'));
                if ($ctr_med > 0) {
                    for ($i=0; $i < $ctr_med; $i++) {

                        if(count($request->file('medical_record_path')) > 0){
                            if(array_key_exists($i,$request->file('medical_record_path'))){
                                $img_file = $request->file('medical_record_path')[$i];
                                $dir = 'storage/uploads/medical_record/';
                                //count the number of files in the directory
                                $filecount = count(glob($dir . "*")) + 1;
                                $file_path = date('m_d_Y_H_i_s') . '_medical_record_' . $filecount . '.' . $img_file->getClientOriginalExtension();
                                $target_file = $dir . $file_path;
                                $img_file->move($dir, $file_path);
                            }
                            else{
                                if(array_key_exists($i,$request->input('def_medical_record_path'))){
                                    $target_file = $request->input('def_medical_record_path')[$i];
                                }
                                else{
                                    // $i -= 1;
                                    break;
                                    // $target_file = $request->input('def_medical_record_path')[$i];
                                }
                            }
                        }
                        else{
                            $target_file = $request->input('def_medical_record_path')[$i];
                        }
                        
                        $data = array('medical_record_path' => $target_file, 'emp_id' => $SysPK_Empl, 'medical_record' => $request->input('medical_record')[$i]);
                        Emp_med_rec::insert($data);
                    }
                }
            }
            

        }
        // end if

        return redirect('/update-employee?id='.$SysPK_Empl.md5($SysPK_Empl))->with('success_message', 'Employee successfully updated!');
    }

    // Get employee
    public function get_employee(Request $request){

        $this->validate($request, [
            'employee' => 'required',
        ]);

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.SysPK_Empl', 'employees.Name_Empl', 'employees.Department_Empl', 'employees_rate.Position_Empl','employees.UserID_Empl', 'employees.DateHired_Empl')
            ->where('SysPK_Empl', $this->__get_orig_id($request->input('employee')))
            ->get();

        return response()->json($employees);
        // var_dump($employees);
    }


    // Search employee
    public function search_employee(Request $r){
        
        if(!$r->dept_id) abort(404);

        // $outlet_ids = array();
        // if(session('employee')){
        //     $outletApprover = DB::table('outlet')
        //                     ->select('outlet_id')
        //                     ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
        //                     ->get()->toArray();
                            
        //     // Check if outlet approver
        //     if(count($outletApprover)) $outlet_ids = array_column($outletApprover, 'outlet_id');
        // }
        
        $emp_id_numbers = array();
        // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
        $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

        foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;


        $employees = DB::table('employees')
                    ->leftJoin('employees_rate', 'employees.Position_Empl', 'employees_rate.id')
                    ->select('employees.*', 'employees_rate.id as pos_id', 'employees_rate.Position_Empl as emp_position')
                    ->where('dept_id', $r->dept_id)
                    ->where(function ($employees) use ($r) {
                        $employees->where('FirstName_Empl', 'like', '%'.$r->emp_name.'%')
                        ->orWhere('MiddleName_Empl', 'like', '%'.$r->emp_name.'%')
                        ->orWhere('LastName_Empl', 'like', '%'.$r->emp_name.'%');
                    })
                    ->where('UserID_Empl', '!=', '')
                    ->where('UserID_Empl', '!=', null)
                    ->orderBy('Name_Empl', 'asc');

        // array arr_emp_status
        if(isset($r->arr_emp_status)) $employees = $employees->whereIn('Status_Empl', $r->arr_emp_status);
        else $employees = $employees->where('Status_Empl', null);

        if($emp_id_numbers){
            $employees = $employees->where(function ($query) use ($emp_id_numbers) {
                $query->where('SysPK_Empl', '=', session('user')->emp_id) // approver shift
                      ->orWhereIn('employees.UserID_Empl', $emp_id_numbers); // approvers emp
            });
        }
        // if(count($outlet_ids)) $employees = $employees->whereIn('outlet_id', $outlet_ids);
        $employees = $employees->get();
        
        return view('Employee.searched_employees',[
                'employees' => $employees,
            ]
        );
    }

    public function search_employee_2(Request $r){
        
        // dept_id: dept_id,
        // outlet_id: outlet_id,
        // arr_emp_status: arr_emp_status
        
        $emp_id_numbers = array();
        // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
        $approvers = DB::table('approvers')
                    ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                    ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                    ->get()->toArray();

        foreach ($approvers as $value) if(is_numeric($value->emp_id)) $emp_id_numbers[] = $value->emp_id;

        $employees = DB::table('employees')
                    ->leftJoin('employees_rate', 'employees.Position_Empl', 'employees_rate.id')
                    ->select('employees.*', 'employees_rate.id as pos_id', 'employees_rate.Position_Empl as emp_position')
                    ->where('UserID_Empl', '!=', '')
                    ->where('UserID_Empl', '!=', null)
                    ->orderBy('Name_Empl', 'asc');

        if($r->dept_id != 'all') $employees = $employees->where('dept_id', $r->dept_id);
        if($r->company_id != 'all') $employees = $employees->where('company_id', $r->company_id);                
        if($r->outlet_id != 'all') $employees = $employees->where('outlet_id', $r->outlet_id);                

        // array arr_emp_status
        if(isset($r->arr_emp_status)) $employees = $employees->whereIn('Status_Empl', $r->arr_emp_status);
        else $employees = $employees->where('Status_Empl', null);

        if($emp_id_numbers){
            $employees = $employees->where(function ($query) use ($emp_id_numbers) {
                // $query->where('SysPK_Empl', '=', session('user')->emp_id) // approver shift
                //       ->orWhereIn('employees.UserID_Empl', $emp_id_numbers); // approvers emp
                $query->whereIn('employees.UserID_Empl', $emp_id_numbers);
            });
        }
        
        $employees = $employees->get();
        
        return view('Employee.searched_employees',[
                'employees' => $employees,
            ]
        );
    }

    public function evaluation_1(){

        // Guard::restrict_emp_user();

        $employees = DB::table('employees')
                    ->where('Department_Empl', session('employee')->Department_Empl)
                    ->where('employees.SysPK_Empl', '!=', session('employee')->SysPK_Empl)->get();

        $part_1_eval = DB::table('part_1_evaluations')
                        ->join('part_1_scoring', 'part_1_evaluations.part_1_scoring_id', '=', 'part_1_scoring.id')
                        ->where('part_1_evaluations.is_active', 1)
                        ->select('part_1_evaluations.*', 'part_1_scoring.score1', 'part_1_scoring.score2', 'part_1_scoring.score3')
                        ->get();
        
        $part_2_eval = DB::table('part_2_evaluations')
                        ->join('part_2_scoring', 'part_2_evaluations.part_2_scoring_id', '=', 'part_2_scoring.id')
                        ->where('part_2_evaluations.is_active', 1)
                        ->select('part_2_evaluations.*', 'part_2_scoring.score1', 'part_2_scoring.score2', 'part_2_scoring.score3')
                        ->get();

        $params = ["employess" => $employees, 'part_1_eval' => $part_1_eval, 'part_2_eval' => $part_2_eval];

        return view('Employee.evaluation_1', $params);
    }

    public function evaluation_2_1(){

        return redirect('/employee-evaluation-1');
    }

    public function evaluation_2(Request $request){

        $input_data = $request->input();

        $input_data['employee_id'] = $this->__get_orig_id($input_data['employee_id']);

        // To avoid multiple inserts during page refresh
        // if(session('has_eval_batch_id') == false || session('has_eval_batch_id') == null){
             // Insert first to batchevaluation to get the id
            $eval_batch_id = DB::table('evaluation_batch')->insertGetId(['emp_id' => $input_data['employee_id'], 'evaluated_by' => session('user')->emp_id]);
            Session::put(['has_eval_batch_id' => true, 'eval_batch_id' => $eval_batch_id]);
        // }
        
        // Part 1
        // Store scores to array
        $p1_scores = array();

        for ($i=0; $i < $input_data['part_1_array_score']; $i++) { 
            $p1_scores[] = $input_data['p1_no_'.($i+1).'_score'];
        }
        // paarial or initicial
        $part_1_ini_data = [
            "scores" => $p1_scores, 
            "part_1_eval_ids" => $input_data['part_1_eval_id'],
            "part_1_eval_titles" => $input_data['part_1_eval_title'],
            "emp_id" => $input_data['employee_id'],
        ];
        
        $part_1_data = array();

        for ($i=0; $i < count($part_1_ini_data['part_1_eval_ids']); $i++) { 
            $part_1_data[] = [
                'part_1_eval_id' => $part_1_ini_data['part_1_eval_ids'][$i],
                'title' => $part_1_ini_data['part_1_eval_titles'][$i],
                'score' => $part_1_ini_data['scores'][$i],
                'emp_id' => $part_1_ini_data['emp_id'],
                'eval_batch_id' => session('eval_batch_id'),
            ];
        }

        $part_1_total_scores = array_sum($p1_scores);
        $toal_items = count($part_1_ini_data['part_1_eval_ids']);
        // $part_1_score_percentage = ($part_1_total_scores / 100) * $toal_items / 5;
        $part_1_score_percentage = ($part_1_total_scores / $toal_items) * 0.2;

        // session('part_1_data') // mao ni i insert sa part_1_eval_data
        // DB::table('part_1_eval_data')->insert(session('part_1_data'));
        // End part 1

        // Part 2
        // Store scores to array
        $p2_scores = array();

        for ($i=0; $i < $input_data['part_2_array_score']; $i++) { 
            $p2_scores[] = $input_data['p2_no_'.($i+1).'_score'];
        }
        // paarial or initicial
        $part_2_ini_data = [
            "scores" => $p2_scores, 
            "part_2_eval_ids" => $input_data['part_2_eval_id'],
            "part_2_eval_titles" => $input_data['part_2_eval_title'],
            "emp_id" => $input_data['employee_id'],
        ];
        
        $part_2_data = array();

        for ($i=0; $i < count($part_2_ini_data['part_2_eval_ids']); $i++) { 
            $part_2_data[] = [
                'part_2_eval_id' => $part_2_ini_data['part_2_eval_ids'][$i],
                'title' => $part_2_ini_data['part_2_eval_titles'][$i],
                'score' => $part_2_ini_data['scores'][$i],
                'emp_id' => $part_2_ini_data['emp_id'],
                'eval_batch_id' => session('eval_batch_id'),
            ];
        }

        $part_2_total_scores = array_sum($p2_scores);
        $toal_items = count($part_2_ini_data['part_2_eval_ids']);
        // $part_2_score_percentage = ($part_2_total_scores / 100) * $toal_items / 5;
        $part_2_score_percentage = ($part_2_total_scores / $toal_items) * 0.8;

        // end part 2
        // var_dump($part_2_data);

        Session::put(
            [
                'part_1_data' => $part_1_data,
                'part_2_data' => $part_2_data,
                'part_1_score_percentage' => $part_1_score_percentage,
                'part_2_score_percentage' => $part_2_score_percentage,
                'superior_assessment' => $request->input('superior_assessment'),
                'rated_emp_comments' => $request->input('rated_emp_comments'),
            ]
        );
     
        // DB::table('part_2_eval_data')->insert(session('part_2_data'));

        $id = $input_data['employee_id'];

        $employee = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.*','employees_rate.Position_Empl')
            ->where('SysPK_Empl', $id)
            ->first();

        $total_allowance = $employee->transpo_allowance + $employee->mobile_allowance + $employee->housing_allowance + $employee->meal_allowance;

        $params = [
            'employee' => $employee, 
            'part_1_score_percentage' => $part_1_score_percentage,
            'part_1_total_scores' => $part_1_total_scores,
            'part_2_score_percentage' => $part_2_score_percentage,
            'part_2_total_scores' => $part_2_total_scores,
            'total_allowance' => $total_allowance
        ];
        
        if($employee){
            return view('Employee.evaluation_2', $params);
        }
        else{
            return redirect('/employee-evaluation-1');
        }

    }

    public function save_print_evaluation(Request $request){

        $input_data = $request->input();

        $input_data['eval_batch_id'] = session('eval_batch_id');
        $input_data['superior_assessment'] = session('superior_assessment');
        $input_data['rated_emp_comments'] = session('rated_emp_comments');
        
        unset($input_data['_token']);

        // var_dump($input_data);
        // var_dump(session('part_1_data'));
        // var_dump(session('part_2_data'));

        DB::table('part_1_2_3_eval_data')->insert($input_data);
        DB::table('part_1_eval_data')->insert(session('part_1_data'));
        DB::table('part_2_eval_data')->insert(session('part_2_data'));

        $eval_batch = session('eval_batch_id');

        $this->flushEvalSession();
        return redirect('/employee-evaluation?eval_batch='.$eval_batch);
    }

    public function employee_evaluations(){
        
        // Guard::restrict_emp_user();

        $employees = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.*', 'employees_rate.Position_Empl')
            ->get();

        $evaluations = DB::table('evaluation_batch')
                        ->join('employees', 'evaluation_batch.emp_id', '=', 'employees.SysPK_Empl')
                        ->join('part_1_2_3_eval_data', 'evaluation_batch.id', '=', 'part_1_2_3_eval_data.eval_batch_id')
                        ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                        ->leftJoin('employees as employee_evaluated', 'evaluation_batch.evaluated_by', '=', 'employee_evaluated.SysPK_Empl')
                        ->select(
                            'part_1_2_3_eval_data.*','employees.UserID_Empl', 
                            'employees.Department_Empl', 'employees.Name_Empl', 
                            'employees.Position_Empl', 'employees_rate.Position_Empl',
                            'evaluation_batch.*',
                            'employee_evaluated.Name_Empl as evaluated_by')
                        ->get();
        // var_dump($evaluations);
        // die;
        $params = ['evaluations' => $evaluations];

        return view('Employee.employee_evaluations', $params);

    }

    public function view_this_eval(){

        // Guard::restrict_emp_user();

        $eval_batch_id = Input::get('eval_batch');

        if(!$eval_batch_id){
            return redirect('/employee-evaluations');
        }

        $part_1_2_3_eval_data = DB::table('part_1_2_3_eval_data')->where('eval_batch_id', $eval_batch_id)->first();
        $part_1_eval_data = DB::table('part_1_eval_data')->where('eval_batch_id', $eval_batch_id)->get();
        $part_2_eval_data = DB::table('part_2_eval_data')->where('eval_batch_id', $eval_batch_id)->get();

        $employee = DB::table('employees')
            ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
            ->select('employees.*', 'employees_rate.Position_Empl')
            ->where('employees.SysPK_Empl', $part_1_2_3_eval_data->emp_id)
            ->first();
        
        $total_allowance = $employee->transpo_allowance + $employee->mobile_allowance + $employee->housing_allowance + $employee->meal_allowance;

        $evaluation_batch = DB::table('evaluation_batch')
                            ->where('id', $eval_batch_id)
                            ->first();
        
        $evaluator = DB::table('employees')
                    ->join('employees_rate', 'employees.Position_Empl', '=', 'employees_rate.id')
                    ->where('employees.SysPK_Empl', $evaluation_batch->evaluated_by)
                    ->select('employees.*', 'employees_rate.Position_Empl')
                    ->first();
        $part_1_scoring = DB::table('part_1_scoring')->first();
        $part_2_scoring = DB::table('part_2_scoring')->first();
        $part_1_evaluations = DB::table('part_1_evaluations')->get();
        $part_2_evaluations = DB::table('part_2_evaluations')->get();

        $params = [
            'part_1_2_3_eval_data' => $part_1_2_3_eval_data,
            'part_1_eval_data' => $part_1_eval_data,
            'part_2_eval_data' => $part_2_eval_data,
            'employee' => $employee,
            'evaluator' => $evaluator,
            'part_1_scoring' => $part_1_scoring,
            'part_2_scoring' => $part_2_scoring,
            'part_1_evaluations' => $part_1_evaluations,
            'part_2_evaluations' => $part_2_evaluations,
            'total_allowance' => $total_allowance
        ];

        return view('Employee.employee_eval_batch', $params);

    }

    public function flushEvalSession(){

        // Destroy specefic session
        Session::forget(
            [
                'has_eval_batch_id', 'eval_batch_id', 'part_1_data', 
                'part_1_score_percentage', 'part_2_score_percentage',
                'rated_emp_comments', 'rated_emp_comments'
            ]
        );
    }

    public function personel_itenirary(){

        $employees = DB::table('employees')->get();

        $list = DB::table('personel_itenirary')->get();
        
        return view('Employee.personel_itenirary',['list'=>$list,'employees'=> $employees]);

    }

    public function add_personel_itenirary(Request $request){

        $emp_id = (session('user')->employee_type_id == 5) ? session('employee')->SysPK_Empl : $request->input('Employee');

        $employees = DB::table('employees')->where('SysPK_Empl',$emp_id)->first();

        $status = (session('user')->employee_type_id == 5) ? "PENDING" : "APPROVED";

        DB::table('personel_itenirary')->insert(
            [
                "emp_id"=> $emp_id,
                "emp_name"=> $employees->Name_Empl,
                "date_from"=> date('Y-m-d',strtotime($request->input('date_from'))),
                "date_to"=> date('Y-m-d',strtotime($request->input('date_to'))),
                "location"=> $request->input('location'),
                "travel_type"=> $request->input('travel_type'),
                "status" => $status,
                "remarks"=> $request->input('remarks'),
            ]
        );

        return redirect('/personel_itenirary')
        ->with('success_message','New Personel Itinerary Added');
    }

    public function approve_pi()
    {

        $id = $this->__get_orig_id(Input::get('id'));

        DB::table('personel_itenirary')->where('pi_id', $id)->update(['status' => 'APPROVED']);

        return redirect('/personel_itenirary')->with('success_message','Personel Itenirary Updated');
    }

    public function reject_pi()
    {
        $id = $this->__get_orig_id(Input::get('id'));

        DB::table('personel_itenirary')->where('pi_id', $id)->update(['status' => 'REJECTED']);

        return redirect('/personel_itenirary')->with('success_message','Personel Itenirary Rejected');
    }
}
