@if (in_array(session('user')->employee_type_id, [1, 2]))

{{-- $current_route initialized in sidebar.blade.php --}}

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ $current_route == 'dashboard' || $current_route == null ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        SUPER ADMIN
    </div>
    <!-- Nav Item - Users -->
    <li class="nav-item {{ $current_route == 'user_privilege' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('user_privilege') }}">
            <i class="fa-solid fa-users-gear"></i>
            <span>User Privilege</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        User Management
    </div>
    <!-- Nav Item - Users -->
    <li class="nav-item {{ $current_route == 'users' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('users') }}">
            <i class="fa-solid fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        HR Management
    </div>
    <!-- Nav Item - Employees Collapse Menu -->
    @php
        $menuEmpsArr = ['employees', 'evaluation_1', 'trainings', 'training_calendar', 'add-employee', 'employee_info', 'update-employee', 'new_training', 'traning_attendees'];
        
        $routeCheckerEmployees = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerEmployees ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#hremployeeCollapse" aria-expanded="{{ $routeCheckerEmployees ? 'true' : 'false' }}"
            aria-controls="hremployeeCollapse">
            <i class="fa-solid fa-people-roof"></i>
            <span>Employees</span>
        </a>
        <div id="hremployeeCollapse" class="collapse {{ $routeCheckerEmployees ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Setup:</h6>
                <a class="collapse-item d-none" href="{{ url('/add-employee') }}">Add Employee</a>
                <a class="collapse-item {{ $current_route == 'employees' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/employees') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Employee List</a>
                <h6 class="collapse-header">Employee Management:</h6>
                <a class="collapse-item {{ $current_route == 'evaluation_1' ? 'bg-secondary text-white' : '' }}"
                    href="{{ route('evaluation_1') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Performance Evaluation</a>

                <h6 class="collapse-header">Trainings & Dev't</h6>
                <a class="collapse-item {{ $current_route == 'trainings' ? 'bg-secondary text-white' : '' }}"
                    href="{{ route('trainings') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Trainings</a>
                <a class="collapse-item {{ $current_route == 'training_calendar' ? 'bg-secondary text-white' : '' }}"
                    href="{{ route('training_calendar') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Training Calendar</a>
                {{-- <a class="collapse-item" href="{{ route('training_request') }}">Training Request</a> --}}
            </div>
        </div>
    </li>

    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#LineLeaderCollapse" aria-expanded="true" aria-controls="LineLeaderCollapse">
        <i class="fas fa-fw fa-users"></i>
        <span>Line Leader</span>
        </a>
        <div id="LineLeaderCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ url('/line-leader') }}">List</a>
            <a class="collapse-item" href="{{ url('/employee-shift') }}">Employee Shift</a>
        </div>
        </div>
    </li> -->

    @php
        $menuEmpsArr = ['view_code_of_conduct', 'incident_report', 'disciplinary_action_form'];
        
        $routeCheckerDM = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerDM ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#DMCollapse" aria-expanded="{{ $routeCheckerDM ? 'true' : 'false' }}"
            aria-controls="DMCollapse">
            <i class="fa fa-tasks" aria-hidden="true"></i>
            <span>Discipline Management</span>
        </a>
        <div id="DMCollapse" class="collapse {{ $routeCheckerDM ? 'show' : '' }}" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Handbook:</h6>
                <a class="collapse-item {{ $current_route == 'view_code_of_conduct' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/view_code_of_conduct') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Code of Conduct</a>
                <h6 class="collapse-header">Process:</h6>
                <a class="collapse-item {{ $current_route == 'incident_report' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/incident_report') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Incident Report</a>
                <a class="collapse-item {{ $current_route == 'disciplinary_action_form' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/disciplinary-action-form') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Disciplinary Action</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - DTR Collapse Menu -->
    @php
        $menuEmpsArr = ['employee_biometrics', 'dtr_entry', 'dtr_list', 'late_dtr_list', 'incomplete_dtr_list', 'dtr_summary', 'import_dtr'];
        
        $routeCheckerDTR = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerDTR ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#DTRCollapse" aria-expanded="{{ $routeCheckerDTR ? 'true' : 'false' }}"
            aria-controls="DTRCollapse">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Daily Time Record</span>
        </a>
        <div id="DTRCollapse" class="collapse {{ $routeCheckerDTR ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">DTR:</h6>
                <a class="collapse-item {{ $current_route == 'employee_biometrics' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/employee-biometrics') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Biometric Logs</a>
                <a class="collapse-item {{ $current_route == 'dtr_entry' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/dtr-entry') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> DTR Entry</a>
                <!-- <a class="collapse-item" href="{{ url('/normalize_dtr') }}">Normalize</a> -->
                <a class="collapse-item {{ $current_route == 'dtr_list' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/dtr_list') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> DTR List</a>
                <a class="collapse-item {{ $current_route == 'late_dtr_list' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/late_dtr_list') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Late Employee List</a>
                <a class="collapse-item {{ $current_route == 'incomplete_dtr_list' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/incomplete_dtr_list') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Incomplete DTR</a>
                <a class="collapse-item {{ $current_route == 'dtr_summary' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/dtr_summary') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> DTR Summary</a>
                <h6 class="collapse-header">Import DTR:</h6>
                <a class="collapse-item {{ $current_route == 'import_dtr' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/import_dtr') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Import DTR</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - INVENTORY Collapse Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#InventoryCollapse" aria-expanded="true" aria-controls="InventoryCollapse">
        <i class="fas fa-fw fa-file-import"></i>
        <span>Inventory</span>
        </a>
        <div id="InventoryCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Inventory:</h6>
            <a class="collapse-item" href="{{ url('/import-inventory') }}">Import Inventory</a>
            <a class="collapse-item" href="{{ url('/inventory-similarity') }}">Similarity</a>
        </div>
        </div>
    </li> --}}

    <!-- Nav Item - Overtime -->
    <li class="nav-item {{ in_array($current_route, ['absenteeism', 'add_absent_view']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('absenteeism') }}">
            <i class="fa-regular fa-calendar-minus"></i>
            <span>Absenteeism</span>
        </a>
    </li>

    <!-- Nav Item - Leaves Menu -->
    @php
        $menuEmpsArr = ['leave_monitoring', 'leave', 'leave_request', 'leave_type', 'leave_entry'];
        
        $routeCheckerLeave = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerLeave ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#LeavesCollapse" aria-expanded="{{ $routeCheckerLeave ? 'true' : 'false' }}"
            aria-controls="LeavesCollapse">
            <i class="fa-solid fa-calendar-day"></i>
            <span>Leaves</span>
        </a>
        <div id="LeavesCollapse" class="collapse {{ $routeCheckerLeave ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Leaves:</h6>
                <a class="collapse-item {{ $current_route == 'leave_monitoring' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/leave_monitoring') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Leave Monitoring</a>
                <a class="collapse-item {{ $current_route == 'leave' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/leave') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Leaves</a>
                <a class="collapse-item {{ $current_route == 'leave_request' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/leave-request') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Leave Request</a>
                {{-- <a class="collapse-item" href="{{ url('/leave-entry') }}">Leave Entry</a> --}}
                <h6 class="collapse-header">Setup:</h6>
                <a class="collapse-item {{ $current_route == 'leave_type' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/leave_type') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Leave Type</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Overtime -->
    <li class="nav-item {{ in_array($current_route, ['overtime', 'add_overtime_view']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('overtime') }}">
            <i class="fa-solid fa-clock"></i>
            <span>Overtime</span>
        </a>
    </li>

    <!-- Nav Item - Holiday -->
    <li class="nav-item {{ $current_route == 'holiday_setup' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('holiday_setup') }}">
            <i class="fa-solid fa-gifts"></i>
            <span>Holiday</span>
        </a>
    </li>

    <li class="nav-item {{ $current_route == 'personel_itenirary' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('personel_itenirary') }}">
            <i class="fa-solid fa-person-walking-luggage"></i>
            <span>Personnel Itinerary</span>
        </a>
    </li>


    <!-- Nav Item - shift monitoring -->
    @php
        $menuEmpsArr = ['shift_code', 'shift_monitoring', 'shift_entry'];
        
        $routeCheckerShiftM = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerShiftM ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#LineLeaderCollapse" aria-expanded="{{ $routeCheckerShiftM ? 'true' : 'false' }}"
            aria-controls="LineLeaderCollapse">
            <i class="fa-regular fa-calendar-days"></i>
            <span>Shift Monitoring</span>
        </a>
        <div id="LineLeaderCollapse" class="collapse {{ $routeCheckerShiftM ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ $current_route == 'shift_code' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/shift-code') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Shift Code Setup</a>
                <a class="collapse-item {{ $current_route == 'shift_monitoring' ? 'bg-secondary text-white' : '' }}"
                    href="{{ route('shift_monitoring') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Shift Entry</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ $current_route == 'memo' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('memo') }}">
            <i class="fa-solid fa-file-pen"></i>
            <span>Memo</span>
        </a>
    </li>

    <!-- Nav Item - applicants -->
    <li class="nav-item {{ $current_route == 'applicants' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('applicants') }}">
            <i class="fa-solid fa-blender-phone"></i>
            <span>Applicants</span>
        </a>
    </li>

    {{-- <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        Financial Management
    </div> --}}

    <!-- Nav Item - Cash Advance -->
    <li class="nav-item {{ $current_route == 'cash_advance' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/cash_advance') }}">
            <i class="fa-solid fa-cash-register"></i>
            <span>Cash Advance</span>
        </a>
    </li>

    <!-- Nav Item - Cash Advance -->
    <li class="nav-item {{ $current_route == 'atd' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/atd') }}">
            <i class="fa-solid fa-coins"></i>
            <span>ATD</span>
        </a>
    </li>

    <!-- Nav Item - Payrol Collapse Menu -->
    @php
        $menuEmpsArr = ['payroll_entry', 'payroll_list', 'payslip', 'service_charge', 'other_income', 'deduction', 'adjustments'];
        
        $routeCheckerPayroll = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerPayroll ? '' : 'collapsed' }}" href="#"
            data-toggle="collapse" data-target="#PayrollCollapse"
            aria-expanded="{{ $routeCheckerPayroll ? 'true' : 'false' }}" aria-controls="PayrollCollapse">
            <i class="fa-solid fa-file-invoice"></i>
            <span>Payroll</span>
        </a>
        <div id="PayrollCollapse" class="collapse {{ $routeCheckerPayroll ? 'show' : '' }}"
            aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Payroll:</h6>
                <a class="collapse-item {{ $current_route == 'payroll_entry' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/payroll_entry') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Payroll Entry</a>
                <a class="collapse-item {{ $current_route == 'payroll_list' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/payroll_list') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Payroll List</a>
                <a class="collapse-item {{ $current_route == 'payslip' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/payslip') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Payslip</a>
                <h6 class="collapse-header">Others:</h6>
                <a class="collapse-item {{ $current_route == 'service_charge' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/service-charge') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Service Charge</a>
                <a class="collapse-item {{ $current_route == 'other_income' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/other-income') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Other Income</a>
                <a class="collapse-item {{ $current_route == 'deduction' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/deduction') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Deduction</a>
                <a class="collapse-item {{ $current_route == 'adjustments' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/adjustments') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Adjustments</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Cash Advance -->
    <li class="nav-item {{ $current_route == 'taxdue_list' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/taxdue_list') }}">
            <i class="fa-solid fa-toilet-paper"></i>
            <span>Taxdue List</span>
        </a>
    </li>


    <!-- Nav Item - Payrol Collapse Menu -->
    @php
        $menuEmpsArr = ['sss_loan', 'pagibig_loan', 'moorgate_loan'];
        
        $routeCheckerLoan = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerLoan ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#LoanCollapse" aria-expanded="{{ $routeCheckerLoan ? 'true' : 'false' }}"
            aria-controls="LoanCollapse">
            <i class="fa-solid fa-landmark"></i>
            <span>Loan</span>
        </a>
        <div id="LoanCollapse" class="collapse {{ $routeCheckerLoan ? 'show' : '' }}"
            aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Loans:</h6>
                <a class="collapse-item {{ $current_route == 'sss_loan' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/sss_loan') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> SSS Loan</a>
                <a class="collapse-item {{ $current_route == 'pagibig_loan' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/pagibig_loan') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> PAG-IBIG Loan</a>
                <a class="collapse-item {{ $current_route == 'moorgate_loan' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/moorgate_loan') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Moorgate Loan</a>
                <a class="collapse-item {{ $current_route == 'access_loan' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/access-loan') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Access Loan</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <!-- Heading -->
    <div class="sidebar-heading">
        Setup
    </div>
    <!-- Nav Item - Company Collapse Menu -->
    @php
        $menuEmpsArr = ['company','company_group', 'outlet', 'cost_center', 'department', 'position', 'employee_level'];
        
        $routeCheckerCompany = in_array($current_route, $menuEmpsArr) ? true : false;
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $routeCheckerCompany ? '' : 'collapsed' }}" href="#"
            data-toggle="collapse" data-target="#CompanyCollapse"
            aria-expanded="{{ $routeCheckerCompany ? 'true' : 'false' }}" aria-controls="CompanyCollapse">
            <i class="fa-solid fa-building"></i>
            <span>Company</span>
        </a>
        <div id="CompanyCollapse" class="collapse {{ $routeCheckerCompany ? 'show' : '' }}"
            aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Company Setup:</h6>
                <a class="collapse-item {{ $current_route == 'company' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/company') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Company</a>
                <a class="collapse-item {{ $current_route == 'company_group' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/company_group') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Company Group</a>
                <a class="collapse-item {{ $current_route == 'outlet' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/outlet') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Outlet</a>
                <a class="collapse-item {{ $current_route == 'cost_center' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/cost_center') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Cost Center</a>
                <a class="collapse-item {{ $current_route == 'department' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/department') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Deparment</a>
                <a class="collapse-item {{ $current_route == 'position' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/position') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Position</a>
                <a class="collapse-item {{ $current_route == 'employee_level' ? 'bg-secondary text-white' : '' }}"
                    href="{{ url('/employee_level') }}">
                    <i class="fa-solid fa-circle fa-2xs"></i> Employee Level</a>
                {{-- <a class="collapse-item" href="" data-toggle="modal" data-target="#weekly_hour">
                    <i class="fa-solid fa-circle fa-2xs"></i> Weekly Hour</a> --}}
            </div>
        </div>
    </li>


    <!-- Nav Item - Payrol Collapse Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#GovePremCollapse" aria-expanded="true" aria-controls="GovePremCollapse">
        <i class="fa-solid fa-building-ngo"></i>
        <span>Government Premiums</span>
        </a>
        <div id="GovePremCollapse" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Setup:</h6>
            <a class="collapse-item" href="">
                <i class="fa-solid fa-circle fa-2xs"></i> SSS</a>
            <a class="collapse-item" href="">
                <i class="fa-solid fa-circle fa-2xs"></i> PhilHealth</a>
            <a class="collapse-item" href="">
                <i class="fa-solid fa-circle fa-2xs"></i> PAG-IBIG</a>
            <a class="collapse-item" href="">
                <i class="fa-solid fa-circle fa-2xs"></i> Withholding Tax</a>
        </div>
        </div>
    </li> --}}


    <!-- Nav Item - Cash Advance -->
    <li class="nav-item {{ $current_route == 'system-lock' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/system-lock') }}">
            <i class="fa-solid fa-key"></i>
            <span>System Lock</span>
        </a>
    </li>
@endif