{{-- NORMAL USER --}}
@if (!in_array(session('user')->employee_type_id, [1, 2]))

{{-- $current_route initialized in sidebar.blade.php --}}

    @php

        $employee_modules = DB::table('employee_modules')
                            ->where('is_active', 1)
                            ->where('is_approver',0)
                            ->orderBy('employee_module_header_id', 'asc')->get();
        $divider_idntfr = 0;

        $checkMemo = DB::table("memo")
                    ->select('memo_id', 'memo_date')
                    // ->whereIn("memo.outlet_id", [session('employee')->outlet_id, 0])
                    ->where(function($q){
                        return $q->whereRaw('FIND_IN_SET(?, outlet_id)', [session('employee')->outlet_id])
                                ->orWhereRaw('FIND_IN_SET(?, outlet_id)', [0]);
                    })
                    ->where('created_by', '!=', session('user')->id)
                    ->where('memo_date', '<=', date('Y-m-d'))
                    ->where(function($q){
                        return $q->whereRaw('NOT FIND_IN_SET(?, noticed_by)', [session('user')->id])
                                ->orWhereNull('noticed_by');
                    })
                    ->first();


        $checkNotice = DB::table("notices")
                    ->select('notice_id', 'notice_date')
                    ->whereRaw('FIND_IN_SET(?, notices.emp_ids)', [session('employee')->SysPK_Empl])
                    ->where('created_by', '!=', session('user')->id)
                    ->where('notice_date', '<=', date('Y-m-d'))
                    ->where(function($q){
                        return $q->whereRaw('NOT FIND_IN_SET(?, noticed_by)', [session('user')->id])
                                ->orWhereNull('noticed_by');
                    })
                    ->first();

    @endphp

    @if($current_route == 'memo' || $current_route == 'notices')
        <input type="text" name="check_memo" id="check_memo_input" value="0" hidden>
        <input type="text" name="check_notice" id="check_notice_input" value="0" hidden>
    @else
        <input type="text" data-date_from="{{ $checkNotice ? $checkNotice->notice_date : null }}" name="check_notice" id="check_notice_input" value="{{ $checkNotice ? 1 : 0 }}" hidden>
        <input type="text" data-date_from="{{ $checkMemo ? $checkMemo->memo_date : null }}" name="check_memo" id="check_memo_input" value="{{ $checkMemo ? 1 : 0 }}" hidden>
    @endif

    @foreach ( $employee_modules as $i => $row )

        @php
            // route_name + sub_riute_name in to array 
            $module_routes = $row->sub_route_name ? explode(",",$row->sub_route_name.",".$row->route_name) : [$row->route_name];
            
            // if($row->is_approver == 1)
            //     $module_routes_approver = $row->sub_route_name ? explode(",",$row->sub_route_name.",".$row->route_name) : [$row->route_name];
        @endphp

        @if(!in_array($row->employee_module_header_id, [0]))

            @if($row->is_approver == 0)

                @if($divider_idntfr != $row->employee_module_header_id)
                    @php $divider_idntfr = $row->employee_module_header_id @endphp
                    <hr class="sidebar-divider my-0">
                @endif

                <li class="nav-item {{ in_array($current_route, $module_routes) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route($row->route_name) }}">
                        {!! $row->icon !!}
                        <span>{{ $row->module }}</span>
                    </a>
                </li>
                
            @endif

        @endif

    @endforeach


    @if(session('is_approver'))

        @php

            $emp_id_numbers = [];
            // STORE THE EMP ID UNDER APPROVER

            $approvers_re_name_2 = DB::table('approvers')
                ->join('users', 'approvers.emp_id', '=', 'users.username')
                ->select('approvers.id', 'approvers.emp_id', 'approvers.approver_1_emp_id', 'approvers.approver_2_emp_id', 'users.status')
                ->whereIn('approvers.approver_1_emp_id', [session('employee')->UserID_Empl, session('employee')->UserID_Empl])
                ->orWhereIn('approvers.approver_2_emp_id', [session('employee')->UserID_Empl, session('employee')->UserID_Empl])
                ->where('users.status', 'ACTIVE')
                ->get()->toArray();
            
            foreach ($approvers_re_name_2 as $value) $emp_id_numbers[] = $value->emp_id;
        
            $total_pending_leaves = DB::table('leaves')
                ->whereIn('employee_number', $emp_id_numbers)
                ->where(function ($query) {
                    $query->where('approved_by', null)->orWhere('approved_by', '!=', session('employee')->UserID_Empl);
                })
                ->where('is_deleted', '=', 0)
                ->where(function ($query) {
                    $query->where('leaves.is_approved', 0)->orWhere('leaves.is_approved', 3); // partially approved
                })
                ->count();

            $total_pending_ot = DB::table('approved_ot')
                ->whereIn('employee_number', $emp_id_numbers)
                ->where(function ($query) {
                    $query->where('approved_by', null)->orWhere('approved_by', '!=', session('employee')->UserID_Empl);
                })
                ->where('is_deleted', '=', 0)
                ->where(function ($query) {
                    $query->where('approved_ot.is_approved', 0)->orWhere('approved_ot.is_approved', 3); // partially approved
                })
                ->count();


            $total_pending_tps = App\Http\Controllers\RequestAF\RequestAttendanceForm::getTotalPendingCount(0,$emp_id_numbers);
            
            $total_pending_undertime = App\Http\Controllers\RequestAF\RequestAttendanceForm::getTotalPendingCount(1,$emp_id_numbers);
        
            $total_pending_change_shcedule = App\Http\Controllers\RequestAF\RequestAttendanceForm::getTotalPendingCount(2,$emp_id_numbers);
            
            $total_pending_obt = App\Http\Controllers\RequestAF\RequestAttendanceForm::getTotalPendingCount(3,$emp_id_numbers);
            
            $total_pending = $total_pending_leaves + $total_pending_ot + $total_pending_tps + $total_pending_undertime + $total_pending_change_shcedule + $total_pending_obt;


            // Modules for approvers
            $employee_modules_approver = DB::table('employee_modules')->where('is_active', 1)->where('is_approver',1)->get();
            $all_module_routes = '';
            $count = 0;
            // get all route under apprver module, including the sub route
            foreach ($employee_modules_approver as $key => $value) {
                $all_module_routes .= $value->route_name.",";
                if($value->sub_route_name) $all_module_routes .= $value->sub_route_name .",". $value->route_name.",";
                $count++;
            }
            $all_module_routes = explode (",", $all_module_routes); 

            $divider_idntfr_approver = 0;
            $divider_idntfr_approver_ttl = count($employee_modules_approver);

            $noti_requests = [
                "shift_outlet" => $total_pending_change_shcedule,
                "time_pass_request" => $total_pending_tps,
                "undertime_request" => $total_pending_undertime,
                "overtime_request" => $total_pending_ot,
                "leave_request" => $total_pending_leaves,
                "obt_request" => $total_pending_obt
            ];
        @endphp

        @foreach ( $employee_modules_approver as $i => $row )
            
            @if($row->is_approver == 1)
                @php
                    // route_name + sub_riute_name in to array 
                    $module_routes = $row->sub_route_name ? explode(",",$row->sub_route_name.",".$row->route_name) : [$row->route_name];
                    $routeCheckerEmpManagement = in_array($current_route, $all_module_routes) ? true : false;
                @endphp

                @if($divider_idntfr_approver == 0)

                    <hr class="sidebar-divider ">
                    <!-- Heading -->
                    <div class="sidebar-heading">
                        STAFF
                    </div>

                    <!-- Nav Item - Employees Collapse Menu -->
                    <li class="nav-item">
                        <a class="nav-link {{ $routeCheckerEmpManagement ? '' : 'collapsed' }}" href="#"
                            data-toggle="collapse" data-target="#employeeCollapse"
                            aria-expanded="{{ $routeCheckerEmpManagement ? 'true' : 'false' }}"
                            aria-controls="employeeCollapse">
                            <i class="fas fa-fw fa-users"></i>
                            <span>Management
                                @if ($total_pending > 0)
                                    <span class="ml-2 badge badge-danger">{{ $total_pending }}</span>
                                @endif
                            </span>
                        </a>

                        <div id="employeeCollapse" class="collapse {{ $routeCheckerEmpManagement ? 'show' : '' }}"
                            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">

                @endif

                    <a class="collapse-item {{ in_array($current_route, $module_routes) ? 'bg-secondary text-white' : '' }}"
                        href="{{ route($row->route_name) }}">
                        {!! $row->icon !!}
                        {{ $row->module }}
                        
                        @if (isset($noti_requests[$row->route_name]) && $noti_requests[$row->route_name] > 0)
                            <span class="ml-2 badge badge-danger">{{ $noti_requests[$row->route_name] }}</span>
                        @endif
                    </a>
                
                @php
                    $divider_idntfr_approver++;
                @endphp

                @if($divider_idntfr_approver == $divider_idntfr_approver_ttl)
                            </div>
                        </div>
                    </li>
                @endif

            @endif

        @endforeach

    @endif
    {{-- session('is_approver') --}}


    {{-- OTHER LINKS --}}
    @if (count(session('other_links')))

        <hr class="sidebar-divider ">
        <!-- Heading -->
        <div class="sidebar-heading">
            Other Links
        </div>

        @php
            $header_stats = '';
        @endphp

        @foreach (session('other_links') as $key => $value)
            @if ($value->header)
                {{-- Header separator 1 --}}
                @if ($value->header != $header_stats)

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse"
                            data-target="#up_id_{{ $value->id }}" aria-expanded="true"
                            aria-controls="up_id_{{ $value->id }}">
                            <i class="fa fa-link"></i>
                            <span>
                                {{ $value->header }}
                            </span>
                        </a>
                        <div id="up_id_{{ $value->id }}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">

                @endif
                    {{-- End of header separator 1 --}}
                    <a class="collapse-item {{ $current_route == $value->route || $current_route == str_replace('-', '_', $value->route) ? 'bg-secondary text-white' : '' }}"
                        href="{{ url('/' . $value->route) }}">
                        <i class="fa-solid fa-circle fa-2xs"></i> {{ $value->item_name }}
                    </a>
                {{-- Header separator 2 --}}
                @if ($value->header != $value->header)
                            </div>
                        </div>
                    </li>
                @endif
                {{-- End of header separator 2 --}}

                @php
                    $header_stats = $value->header;
                @endphp
            @else
                <li class="nav-item {{ $current_route == $value->route ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/' . $value->route) }}">
                        <i class="fa fa-link"></i>
                        <span>{{ $value->item_name }}</span>
                    </a>
                </li>
            @endif
        @endforeach

    @endif
    {{-- END OF OTHER LINKS --}}

@endif
{{-- END OF NORMAL USER --}}
