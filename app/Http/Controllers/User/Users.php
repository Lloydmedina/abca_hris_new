<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Employee_type;
use App\Models\Users as User;
use App\Http\Controllers\Auth\Guard;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{
    // public function add_user_view()
    // {
        

    //     $emp_type = Employee_type::all();
    //     return view('Users.add_user', ['emp_type' => $emp_type]);
    // }

    public function add_user_pro(Request $request)
    {
        

        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|unique:users,email|max:100',
            'employee_type_id' => 'required',
            'photo_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::orderBy('SysPK_User', 'desc')->take(1)->first();
        $employee_type_id = $this->__get_orig_id( $request->input('employee_type_id') );

        $employee = Employee_type::find( $employee_type_id );
        $SysPK_User = $user->SysPK_User + 1;

        $file_path = null;

        if($SysPK_User)
        {
            
            $full_path = 'storage/uploads/profile_picture/default.png';

            if($request->file('photo_path')){
                $img_file = $request->file('photo_path');
                $dir = 'storage/uploads/profile_picture/';
                //count the number of files in the directory
                $filecount = count(glob($dir . "*")) + 1;
                $file_path = date('m_d_Y_H_i_s').'_profile_'.$filecount.'.'.$img_file->getClientOriginalExtension();

                $full_path = $dir.$file_path;
                $img_file->move($dir, $file_path);
            }
            

            //create generated username & password
            $username = $request->input('email');
            // $pos = strpos($username, '@');//fin the position of @
            // $username = substr($username, 0, $pos);//remove starting from @
            //PASSWORD IS YOUR EMAIL WITHOUT @GMAIL ECT. PLUS CURRENT YEAR EX. EMAIL@GMAIL.COM THE PASSOWRD WILL BE EMAIL2019
            $password = password_hash($username, PASSWORD_DEFAULT,  ['option' => 12]);
            $salt = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 8)), 0, 8);

            $user = new User();
            $user->fill([
                'username' => $request->input('email'), 
                'first_name' => strtoupper($request->input('first_name')), 
                'last_name' => strtoupper($request->input('last_name')), 
                'email' => $request->input('email'),
                'employee_type_id' => $employee_type_id,
                'employee_type' => strtoupper($employee->employee_type),
                'web_password' => $password,
                'salt' => $salt,
                'photo' => $full_path,
                'SysPK_User' => $SysPK_User
                ]);
            $user->save();
        }
        
        return redirect('/users?new_user_reg='.date('m-d'))->with('success_message', 'New user successfully registered!');
    }

    public function user_list_view()
    {
        
        // Guard::restrict_emp_user();

        $users = User::all();

        $emp_type = Employee_type::all();

        return view('Users.user_lists', [
                    'users' => $users, 
                    'emp_type' => $emp_type
                ]);
    }

    public function deactivate_user($id)
    {
        
        //GET THE ORIGINAL ID FROM MD5
        $id = $this->__get_orig_id($id);
        $user = User::where('id', $id)->update(['status' => 'INACTIVE']);
        return redirect('/users?account_deactivated='.$id.md5($id))->with('success_message', 'User account deactivated');
    }

    public function activate_user($id)
    {
        
        //GET THE ORIGINAL ID FROM MD5
        $id = $this->__get_orig_id($id);
        $user = User::where('id', $id)->update(['status' => 'ACTIVE']);
        return redirect('/users?account_activated='.$id.md5($id))->with('success_message', 'User account activated');
    }
    
    //AJAX REQUEST
    public function get_user_details()
    {
        //USER ID
        $id = $this->__get_orig_id($_GET['id']);
        $user = User::find($id);
        $employee = DB::table('employees')->select('picture_path', 'gender')->where('SysPK_Empl', $user->emp_id)->first();
        
        $profilePic = 'public/default/profile/Male.jpg';

        if($employee){
            if ( strtolower($employee->gender) == 'male')
                $defProfile = 'public/default/profile/Male.jpg';
            else
                $defProfile = 'public/default/profile/Female.jpg';

            $profilePic = $employee->picture_path ? $employee->picture_path : $defProfile;
        }
        
        $ajax_ret = null;

        if ($user) {
            if( password_verify($user->username, $user->web_password) )
                $is_def_pass = 1;
            else
                $is_def_pass = 0;

            $ajax_ret[] = array(
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                // 'employee_type_id' => $user->employee_type_id, 
                'employee_type_id' => $user->employee_type_id.md5($user->employee_type_id),
                'username' => $user->username,
                'employee_type' => $user->employee_type,
                'photo' => $profilePic,
                'is_def_pass' => $is_def_pass,
                'status' => $user->status
            );
        }
        else{
            $ajax_ret[] = array(
                'fist_name' => '',
                'last_name' => '',
                'email' => '',
                'employee_type_id' => '',
                'employee_type' => '',
                'photo' => ''
            );
        }
        return Response::json($ajax_ret);
    }

    public function update_emp_type(Request $request)
    {
        

        $this->validate($request, [
            'employee_type_id' => 'required',
            'user_id' => 'required'//USER ID
        ]);

        $status = $request->input('status');
        $reset_pass = $request->input('reset_pass') ?? null;
        $id = $this->__get_orig_id( $request->input('user_id') );
        $employee_type_id = $this->__get_orig_id( $request->input('employee_type_id') );

        //USER ID
        $employee = Employee_type::find( $employee_type_id );
        $user = User::where('id', $id)->update( [
            'employee_type' => strtoupper( $employee->employee_type ), 
            'employee_type_id' => $employee_type_id,
            'status' => $status,
        ]);

        if($reset_pass){
            $user = User::where('id', $id)->first();
            $password = password_hash($user->username, PASSWORD_DEFAULT, ['option' => 12]);
            $user = User::where('id', $id)->update( ['web_password' => $password]);
        }

        return redirect('/users?user_type_updated='.$id.md5($id))->with('success_message', 'User employee type successfully updated');
    }

    public function change_pass(Request $request)
    {
        
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ]);

        $user = User::where('id', session('user')->id)->first();

        $my_question_and_answer = DB::table('security_question_and_answer')
                                ->where('user_id', session('user')->id)
                                ->where('security_question_id', $request->input('question'))
                                ->where('security_answer', md5($request->input('answer')))
                                ->first();
        
        if(!$my_question_and_answer){// invalid password
            return response()->json(
                array(
                    "code" => 0, 
                    "message" => "Security Questions Mismatched. Please ensure that your answers to the security questions are entered correctly."
                )
            );
        }             

        if ($user && password_verify($request->input('current_password'), $user->web_password)){
            
            $password = password_hash($request->input('new_password'), PASSWORD_DEFAULT,  ['option' => 12]);
            $salt = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 8)), 0, 8);

            User::where('id', session('user')->id)->update(['web_password' => $password, 'salt' => $salt]);

            return response()->json(array("code" => 1, "message" => "Password successfully changed"));
        }
        else{
            // invalid password
            return response()->json(array("code" => 0, "message" => "Invalid password"));
        }

    }
}
