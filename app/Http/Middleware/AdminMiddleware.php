<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class AdminMiddleware
{

    public function handle($request, Closure $next)
    {
        $currentUrl = Request::segment(1);
        
        if (in_array(session('user')->employee_type_id, [1,2])) {
            return $next($request);
        }
        // FOR NORMAL USERS
        elseif(count(session('other_links'))){
            $otherLinks = array();
            $i = 0;
            foreach(session('other_links') as $row){
                
                $otherLinks[$i] = strtolower($row->route);
                if(strtolower($row->route) == 'employees'){
                    // add to user priv
                    $otherLinks[++$i] = 'add-employee';
                    $otherLinks[++$i] = 'update-employee';
                }
                elseif(strtolower($row->route) == 'trainings'){
                    // add to user priv
                    $otherLinks[++$i] = 'new-training';
                    $otherLinks[++$i] = 'training';
                }
                elseif(strtolower($row->route) == 'employee-evaluations'){
                    // add to user priv
                    $otherLinks[++$i] = 'employee-evaluation-1';
                    $otherLinks[++$i] = 'employee-evaluation-2';
                }
                elseif(strtolower($row->route) == 'moorgate_loan'){
                    // add to user priv
                    $otherLinks[++$i] = 'add_moorgate_loan';
                }
                $i++;
            } 
            
            if(in_array(strtolower($currentUrl), $otherLinks)) return $next($request);
        }
        abort(403, 'Unauthorized action.');
        
    }

}

