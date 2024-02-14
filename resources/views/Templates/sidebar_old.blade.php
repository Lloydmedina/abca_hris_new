<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
   <!-- Sidebar - Brand -->
   <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
      <div class="sidebar-brand-icon">
         <img src="public/img/abaca_logo.png" alt="" style="width: 30%; ">
      </div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider my-0">
   <!-- Nav Item - Dashboard -->
   <li class="nav-item active">
      <a class="nav-link" href="{{ url('/') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
   </li>

   @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
   <!-- Divider -->
   <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading">
         SUPER ADMIN
      </div>
      <!-- Nav Item - Users -->
      <li class="nav-item">
      <a class="nav-link" href="{{ url('user_access_setup') }}">
         <i class="fas fa-users-cog"></i>
         <span>Users Accessiblity</span></a>
      </li>
      
      
   @endif

   @if(session('user')->employee_type_id == 5)

      <li class="nav-item">
         <a class="nav-link" href="{{ url('shift') }}">
         <i class="fas fa-fw fa-calendar"></i>
         <span>SHIFT</span></a>
      </li>

      <li class="nav-item">
         <a class="nav-link" href="{{ url('biometrics') }}">
            <i class="fas fa-fingerprint"></i>
         <span>BIOMETRIC LOGS</span></a>
      </li>

      <!-- Nav Item - DTR -->
      <li class="nav-item">
         <a class="nav-link" href="{{ url('dtr') }}">
         <i class="fas fa-fw fa-calendar"></i>
         <span>DTR</span></a>
      </li>

      <!-- Nav Item - Overtime -->
      {{-- <li class="nav-item">
         <a class="nav-link" href="{{ route('overtime') }}">
            <i class="fas fa-fw fa-clock"></i>
            <span>Overtime</span>
         </a>
      </li> --}}

      <!-- Nav Item - Leave -->
      <li class="nav-item">
         <a class="nav-link" href="{{ url('/leave') }}">
            <i class="fas fa-fw fa-calendar-minus"></i>
            <span>LEAVES</span>
         </a>
      </li>

      <li class="nav-item">
         <a class="nav-link" href="{{ url('/payslip') }}">
            <i class="fas fa-fw fa-calendar-minus"></i>
            <span>PAYSLIP</span>
         </a>
      </li>

      <li class="nav-item">
         <a class="nav-link" href="{{ url('/memo') }}">
            <i class="fas fa-fw fa-calendar-minus"></i>
            <span>MEMO</span>
         </a>
      </li>

      @php
         $outletApprover = array();
         if(session('employee')){
            $outletApprover = DB::table('outlet')
                        ->select('outlet_id')
                        ->whereRaw('FIND_IN_SET(?, approver_emp_id)', [session('employee')->SysPK_Empl])
                        ->get()->toArray();
         }
      @endphp

      @if(isset($outletApprover) && count($outletApprover))
         <!-- Heading -->
         <div class="sidebar-heading">
            Management
         </div>
         <!-- Nav Item - Employees Collapse Menu -->
         <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#employeeCollapse" aria-expanded="true" aria-controls="employeeCollapse">
            <i class="fas fa-fw fa-users"></i>
            <span>Management</span>
            </a>
            <div id="employeeCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                  <a class="collapse-item" href="{{ url('/shift-outlet') }}">Add Shift</a>
                  <a class="collapse-item" href="{{ url('/add-overtime') }}">Add Overtime</a>
                  {{-- <a class="collapse-item" href="{{ url('/approve-leave') }}">Approve Leave</a> --}}
                  {{-- Temp --}}
                  <a class="collapse-item" href="{{ url('/leave') }}">Approve Leave</a>
               </div>
            </div>
         </li>
      @endif
      {{-- <li class="nav-item">
         <a class="nav-link" href="{{ url('personel_itenirary') }}">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Personel Itenirary</span></a>
      </li> --}}

   @endif

   

   @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
   <!-- Divider -->
   <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading">
         User Management
      </div>
      <!-- Nav Item - Users -->
      <li class="nav-item">
      <a class="nav-link" href="{{ url('users') }}">
         <i class="fas fa-users-cog"></i>
         <span>Users</span></a>
      </li>
      
      <!-- Divider -->
      <hr class="sidebar-divider">
   @endif
   
   @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
      <!-- Heading -->
      <div class="sidebar-heading">
         HR Management
      </div>
      <!-- Nav Item - Employees Collapse Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#employeeCollapse" aria-expanded="true" aria-controls="employeeCollapse">
         <i class="fas fa-fw fa-users"></i>
         <span>Employees</span>
         </a>
         <div id="employeeCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Setup:</h6>
               <a class="collapse-item d-none" href="{{ url('/add-employee') }}">Add Employee</a>
               <a class="collapse-item" href="{{ url('/employees') }}">Employee List</a>
               <h6 class="collapse-header">Employee Management:</h6>
               <a class="collapse-item" href="{{ route('evaluation_1') }}">Performance Evaluation</a>
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

   <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#DMCollapse" aria-expanded="true" aria-controls="DMCollapse">
            <i class="fas fa-fw fa-users"></i>
            <span>Discipline Management</span>
         </a>
         <div id="DMCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Handbook:</h6>
               <a class="collapse-item" href="{{ url('/view_code_of_conduct') }}">Code of Conduct</a>
               <h6 class="collapse-header">Process:</h6>
               <a class="collapse-item" href="{{ url('/incident_report') }}">Incident Report Notification</a>
               <a class="collapse-item" href="{{ url('/disciplinary-action-form') }}">Disciplinary Action Form</a>
            </div>
         </div>
      </li>
   @endif

   @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
      <!-- Nav Item - DTR Collapse Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#DTRCollapse" aria-expanded="true" aria-controls="DTRCollapse">
         <i class="fas fa-fw fa-user-clock"></i>
         <span>DTR</span>
         </a>
         <div id="DTRCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">DTR:</h6>
               <a class="collapse-item" href="{{ url('/dtr-entry') }}">Entry</a>
               <!-- <a class="collapse-item" href="{{ url('/normalize_dtr') }}">Normalize</a> -->
               <a class="collapse-item" href="{{ url('/dtr_list') }}">List</a>
               <a class="collapse-item" href="{{ url('/late_dtr_list') }}">Late Employee List</a>
               <a class="collapse-item" href="{{ url('/incomplete_dtr_list') }}">Incomplete DTR</a>
               <a class="collapse-item" href="{{ url('/dtr_summary') }}">Summary</a>
               <h6 class="collapse-header">Import DTR:</h6>
               <a class="collapse-item" href="{{ url('/import-dtr') }}">Import DTR</a>
            </div>
         </div>
      </li>
   @endif

   @if(session('user')->employee_type_id != 5)
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
   @endif

   @if(session('user')->employee_type_id != 5)
      <!-- Nav Item - Overtime -->
      <li class="nav-item">
      <a class="nav-link" href="{{ route('absenteeism') }}">
         <i class="fas fa-fw fa-clock"></i>
         <span>Absenteeism</span></a>
      </li>
   @endif

   @if(session('user')->employee_type_id != 5)
      <!-- Nav Item - Leaves Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#LeavesCollapse" aria-expanded="true" aria-controls="LeavesCollapse">
         <i class="fas fa-fw fa-user-minus"></i>
         <span>Leaves</span>
         </a>
         <div id="LeavesCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Leaves:</h6>
               <a class="collapse-item" href="{{ url('/leave_monitoring') }}">Leave Monitoring</a>
               <a class="collapse-item" href="{{ url('/leave') }}">Leave</a>
               <a class="collapse-item" href="{{ url('/leave-request') }}">Leave Request</a>
               {{-- <a class="collapse-item" href="{{ url('/leave-entry') }}">Leave Entry</a> --}}
               <h6 class="collapse-header">Setup:</h6>
               <a class="collapse-item" href="{{ url('/leave_type') }}">Leave Type</a>
            </div>
         </div>
      </li>
   @endif

   @if(session('user')->employee_type_id != 5)
      <!-- Nav Item - Overtime -->
      <li class="nav-item">
      <a class="nav-link" href="{{ route('overtime') }}">
         <i class="fas fa-fw fa-clock"></i>
         <span>Overtime</span></a>
      </li>
   @endif

   @if(session('user')->employee_type_id != 5)
      <!-- Nav Item - Holiday -->
      <li class="nav-item">
      <a class="nav-link" href="{{ url('holiday_setup') }}">
         <i class="fas fa-fw fa-user-plus"></i>
         <span>Holiday</span></a>
      </li>
   @endif

   @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)

      <li class="nav-item">
      <a class="nav-link" href="{{ url('personel_itenirary') }}">
         <i class="fas fa-fw fa-user-plus"></i>
         <span>Personnel Itinerary</span></a>
      </li>

   
   
   
      <!-- Nav Item - shift monitoring -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#LineLeaderCollapse" aria-expanded="true" aria-controls="LineLeaderCollapse">
            <i class="fas fa-fw fa-users"></i>
            <span>Shift Monitoring</span>
         </a>
         <div id="LineLeaderCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <a class="collapse-item" href="{{ url('/shift-code') }}">Shift Code Setup</a>
               <a class="collapse-item" href="{{ route('shift_monitoring') }}">Shift Entry</a>
            </div>
         </div>
      </li>
   @endif
   <!-- <li class="nav-item">
      <a class="nav-link" href="{{ route('shift_monitoring') }}">
         <i class="fas fa-fw fa-clock"></i>
         <span>Shift Monitoring</span>
      </a>
   </li> -->

   
   @if(session('user')->employee_type_id != 5)
   <li class="nav-item">
      <a class="nav-link" href="{{ url('memo') }}">
         <i class="fas fa-fw fa-user-plus"></i>
         <span>Memo</span>
      </a>
   </li>
   @endif
   @if(session('user')->employee_type_id == 1)
      <!-- Nav Item - applicants -->
      <li class="nav-item">
         <a class="nav-link" href="{{ route('applicants') }}">
            <i class="fas fa-fw fa-clock"></i>
            <span>Applicants</span>
         </a>
      </li>
   @endif

   {{-- Admin and sub-admin only --}}
   @if(session('user')->employee_type_id == 2 || session('user')->employee_type_id == 1)

      <!-- Divider -->
      <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading">
         Financial Management
      </div>

   @endif

   @if(session('user')->employee_type_id == 2 || session('user')->employee_type_id == 1)

      <!-- Nav Item - Cash Advance -->
      <li class="nav-item">
         <a class="nav-link" href="{{ url('/cash_advance') }}">
            <i class="fas fa-fw fa-money-bill-alt"></i>
            <span>Cash Advance</span>
         </a>
      </li>

   @endif

   @if(session('user')->employee_type_id == 2 || session('user')->employee_type_id == 1)
      <!-- Nav Item - Payrol Collapse Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#PayrollCollapse" aria-expanded="true" aria-controls="PayrollCollapse">
         <i class="fas fa-fw fa-money-bill-alt"></i>
         <span>Payroll</span>
         </a>
         <div id="PayrollCollapse" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Payroll:</h6>
               <a class="collapse-item" href="{{ url('/payroll_entry') }}">Entry</a>
               <a class="collapse-item" href="{{ url('/payroll_list') }}">List</a>
               <a class="collapse-item" href="{{ url('/payslip') }}">Payslip</a>
               <h6 class="collapse-header">Others:</h6>
               <a class="collapse-item" href="{{ url('/service-charge') }}">Service Fee</a>
            </div>
         </div>
      </li>

      <!-- Nav Item - Cash Advance -->
      <li class="nav-item">
         <a class="nav-link" href="{{ url('/taxdue_list') }}">
            <i class="fas fa-fw fa-money-bill-alt"></i>
            <span>Taxdue List</span>
         </a>
      </li>

   @endif

   @if(session('user')->employee_type_id == 2 || session('user')->employee_type_id == 1)
      <!-- Nav Item - Payrol Collapse Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#LoanCollapse" aria-expanded="true" aria-controls="LoanCollapse">
         <i class="fas fa-fw fa-money-bill-alt"></i>
         <span>Loan</span>
         </a>
         <div id="LoanCollapse" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Loans:</h6>
               <a class="collapse-item" href="{{ url('/sss_loan') }}">SSS Loan</a>
               <a class="collapse-item" href="{{ url('/pagibig_loan') }}">PAG-IBIG Loan</a>
            </div>
         </div>
      </li>
   @endif

   @if(session('user')->employee_type_id == 2 || session('user')->employee_type_id == 1)
      <!-- Divider -->
         <!-- Heading -->
      <div class="sidebar-heading">
         Setup
      </div>
      <!-- Nav Item - Company Collapse Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#CompanyCollapse" aria-expanded="true" aria-controls="CompanyCollapse">
         <i class="fas fa-fw fa-cog"></i>
         <span>Company</span>
         </a>
         <div id="CompanyCollapse" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Company Setup:</h6>
               <a class="collapse-item" href="{{ url('/company') }}">Company</a>
               <a class="collapse-item" href="{{ url('/outlet') }}">Outlet</a>
               <a class="collapse-item" href="{{ url('/cost_center') }}">Cost Center</a>
               <a class="collapse-item" href="{{ url('/department') }}">Deparment</a>
               <a class="collapse-item" href="{{ url('/position') }}">Position</a>
               <a class="collapse-item" href="{{ url('/employee_level') }}">Employee Level</a>
               <a class="collapse-item" href="" data-toggle="modal" data-target="#weekly_hour">Weekly Hour</a>
            </div>
         </div>
      </li>
   @endif

   @if(session('user')->employee_type_id == 2 || session('user')->employee_type_id == 1)

      <!-- Nav Item - Payrol Collapse Menu -->
      <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#GovePremCollapse" aria-expanded="true" aria-controls="GovePremCollapse">
         <i class="fas fa-fw fa-chalkboard-teacher"></i>
         <span>Government Premiums</span>
         </a>
         <div id="GovePremCollapse" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
               <h6 class="collapse-header">Setup:</h6>
               <a class="collapse-item" href="">SSS</a>
               <a class="collapse-item" href="">PhilHealth</a>
               <a class="collapse-item" href="">PAG-IBIG</a>
               <a class="collapse-item" href="">Withholding Tax</a>
            </div>
         </div>
      </li>
   @endif

<!--    <li class="nav-item">
      <a class="nav-link" href="{{ url('/datatables') }}">
      <i class="fas fa-fw fa-money-bill-alt"></i>
      <span>Data Tables</span></a>
   </li> -->

   <!-- Divider -->
   <hr class="sidebar-divider d-none d-md-block">
   <!-- Sidebar Toggler (Sidebar) -->
   <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
   </div>
</ul>
<!-- End of Sidebar -->
