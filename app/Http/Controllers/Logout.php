<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session;
use Redirect;

class Logout extends Controller
{

    public function user_logout()
    {
        

    	if (Session::has('logged') && 32 == strlen(Input::get('id'))){
    		Session::flush();
    		return redirect('/login?user_logout='.date('m-d_H:i:s'))->with('logout_success', 'Account successfully logged out!');
    	}
    	else{
    		return redirect()->back();
    	}
    }
}
