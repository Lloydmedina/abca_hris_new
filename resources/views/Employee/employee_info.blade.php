@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/css/custom/update_employee.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Employee Data Information')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

@if($employee)
   <div class="" style="min-height: 750px;">

      <div class="row">
         <div class="col-6">
            <a href="{{ url('/employees') }}" class="btn btn-sm btn-dark mb-2" onclick="return confirm('Are you sure you want to go back?')">
            <i class="fas fa-backspace"></i> Back
            </a>
         </div>
      
         <div class="col-6 text-right">
            <a href="{{ url('/update-employee?id='.$employee->SysPK_Empl.md5(1)) }}" onclick="return confirm('Are you sure you want to update?')" class="btn btn-sm btn-success mb-2">
            <i class="fas fa-edit"></i> Edit
            </a>
         </div>
      
      </div>

      <form class="form-material" action="{{ route('update_employee') }}" method="post">
         @csrf
         <input type="hidden" name="SysPK_Empl" value="{{ $employee->SysPK_Empl.md5(1) }}">
         <div class="row p-t-20">
            <div class="col-lg-2 col-xlg-2 col-md-4">
               <div class="card">
                  <div class="card-body">
                     @php
                        if (strtolower($employee->gender) == 'male')
                           $defProfile = 'public/default/profile/Male.jpg';
                        else
                           $defProfile = 'public/default/profile/Female.jpg';

                        $profilePic = $employee->picture_path ? $employee->picture_path : $defProfile;
                     @endphp
                     {{-- <label class="control-label">Employee Photo</label> --}}
                     <img src="{{ $profilePic }}" class="img-thumbnail" style="width: 100%; max-height: 300px">
                  </div>
               </div>
            </div>
            <div class="col-lg-10 col-xlg-10 col-md-8">
               <div class="card">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs profile-tab" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link {{ (app('request')->input('approver') == 1) ? ' ' : ' show active ' }}" data-toggle="tab" href="#PersonProfile" role="tab" aria-selected="{{ (app('request')->input('approver') == 1) ? ' false ' : ' true ' }}">
                        Personal Info
                        </a>
                     </li>

                     <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#EmployeeInfo" role="tab" aria-selected="false">
                        Employee Info
                        </a>
                     </li>
                     {{-- The user can view his/her own details --}}
                     {{-- @if(in_array(session('user')->employee_type_id, [1,2,3,4])) --}}
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#SalriesInfo" role="tab" aria-selected="false">
                           Salary Info
                           </a>
                        </li>
                     {{-- @endif --}}
                     <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#Documents" role="tab" aria-selected="false">
                        Documents
                        </a>
                     </li>
                     <li class="nav-item">
                        <a id="the_approvers" class="nav-link {{ (app('request')->input('approver') == 1) ? ' show active ' : '' }}" data-toggle="tab" href="#Approvers" role="tab" aria-selected="{{ (app('request')->input('approver') == 1) ? ' true ' : ' fasle ' }}">
                        Approvers
                        </a>
                     </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                     <div class="tab-pane {{ (app('request')->input('approver') == 1) ? '' : ' show active ' }}" id="PersonProfile" role="tabpanel">
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    <input type="text" id="FirstName_Empl" name="FirstName_Empl" value="{{ $employee->FirstName_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Middle Name</label>
                                    <input type="text" id="MiddleName_Empl" name="MiddleName_Empl" value="{{ $employee->MiddleName_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Last Name</label>
                                    <input type="text" id="LastName_Empl" name="LastName_Empl" value="{{ $employee->LastName_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label class="control-label">Address</label>
                                    <input type="text" id="Address_Empl" name="Address_Empl" value="{{ $employee->Address_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Gender</label>
                                    <select id="gender" name="gender" class="form-control custom-select" disabled="">
                                    <option value="Male" @if ($employee->gender == "Male") {{ 'selected' }} @endif>Male</option>
                                    <option value="Female" @if ($employee->gender == "Female") {{ 'selected' }} @endif>Female</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Civil Status</label>
                                    <select id="civilStatus" name="civilStatus" class="form-control custom-select" disabled="">
                                       <option value="Single" @if ($employee->civilStatus == "Single") {{ 'selected' }} @endif>Single</option>
                                       <option value="Married" @if ($employee->civilStatus == "Married") {{ 'selected' }} @endif>Female</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Birthday</label>
                                    <input type="date" id="BirthDate_Empl" name="BirthDate_Empl" value="{{ $employee->BirthDate_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Height</label>
                                    <input type="text" id="height" name="height" value="{{ $employee->height }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Weight</label>
                                    <input type="text" id="weight" name="weight" value="{{ $employee->weight }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Blood Type</label>
                                    <input type="text" id="BloodType_Empl" name="BloodType_Empl" value="{{ $employee->BloodType_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input type="text" id="email" name="email" value="{{ $employee->email }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Contact Number</label>
                                    <input type="text" id="contact_no" name="contact_no" value="{{ $employee->contact_no }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">CTC</label>
                                    <input type="text" id="resident_certificate_no" name="resident_certificate_no" value="{{ $employee->resident_certificate_no }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-center"><B>In Case of Emergency. Contact The Following</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Contact Person</label>
                                    <input type="text" id="EmergencyName_Empl" name="EmergencyName_Empl" value="{{ $employee->EmergencyName_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Contact No.</label>
                                    <input type="text" id="EmergencyTelNo_Empl" name="EmergencyTelNo_Empl" value="{{ $employee->EmergencyTelNo_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Contact Address</label>
                                    <input type="text" id="EmergencyAdd_Empl" name="EmergencyAdd_Empl" value="{{ $employee->EmergencyAdd_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--second tab-->
                     <div class="tab-pane" id="EmployeeInfo" role="tabpanel">
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">ID No.</label>
                                    <input type="text" id="UserID_Empl" name="UserID_Empl" value="{{ $employee->UserID_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Biometric ID</label>
                                    <input type="text" id="" name="" value="{{ $employee->biometric_id }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Employee Level</label>
                                    @foreach($emp_level as $row)
                                       @if ($employee->emp_level_id == $row->emp_lvl_id)
                                          <input type="text" id="" name="" value="{{ $row->emp_lvl }}" class="form-control" disabled>
                                       @endif
                                    @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <input type="text" id="" name="" value="{{ $employee->Status_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Date Hired</label>
                                    <input value="{{ $employee->DateHired_Empl ? date('M d, Y', strtotime($employee->DateHired_Empl)) : "N/A" }}" class="form-control" disabled>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Date Resigned / Terminated / Awol</label>
                                    <input value="{{ $employee->DateTerminated_Empl ? date('M d, Y', strtotime($employee->DateTerminated_Empl)) : "N/A" }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Department</label>
                                    @foreach($department as $row)
                                       @if (strtoupper($employee->Department_Empl) == strtoupper($row->Name_Dept))
                                          <input type="text" id="" name="" value="{{ $row->Name_Dept }}" class="form-control" disabled>
                                       @endif
                                    @endforeach
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Position</label>
                                    @foreach($employees_rate as $row)
                                       @if ($employee->Position_Empl == $row->id)
                                          <input type="text" id="" name="" value="{{ $row->Position_Empl }}" class="form-control" disabled>
                                       @endif
                                    @endforeach
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left"><B>Schedule</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <!-- <div class="form-group">
                                     <label class="control-label">Graveyard</label>
                                     <input style="width: 17px;height: 17px;" class="form-control" id="u_require_dtr" name="require_dtr" value="{{ $employee->require_dtr ==1}} " type="checkbox" />
                                 </div> -->

                                 <div class="form-group">
                                    <label class="control-label">Require DTR?</label>
                                    <input type="text" id="require_dtr" name="require_dtr" value="{{ ($employee->require_dtr ==1) ? 'YES' : 'NO'}} " class="form-control" disabled>
                                 </div>
                              </div>

                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <label class="control-label">Time In</label>
                                 <input type="text" id="" name="" value="{{ date('h:i A', strtotime($employee->TimeStart_Empl )) }}" class="form-control" disabled>
                              </div>
                              <div class="col-md-6">
                                 <label class="control-label">Time Out</label>
                                 <input type="text" id="" name="" value="{{ date('h:i A', strtotime($employee->TimeEnd_Empl)) }}" class="form-control" disabled>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Rest Day</label>
                                    <input type="text" id="" name="" value="{{ $employee->Restday }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">No. Of Days</label>
                                    <input type="text" id="" name="" value="{{ $employee->no_of_days }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left"><B>Contribution Information</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">SSS No.</label>
                                    <input type="text" id="SSS_Empl" name="SSS_Empl" value="{{ $employee->SSS_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">PhilHealth No.</label>
                                    <input type="text" id="philhealth_no" name="philhealth_no" value="{{ $employee->philhealth_no }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">PAG-IBIG No.</label>
                                    <input type="text" id="pagibig_no" name="pagibig_no" value="{{ $employee->pagibig_no }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">TIN No.</label>
                                    <input type="text" id="TIN_Empl" name="TIN_Empl" value="{{ $employee->TIN_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left"><B>ETIQA HMO</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">ETIQA NUMBER</label>
                                    <input type="text" id="etiqa_hmo_no" name="etiqa_hmo_no" value="{{ $employee->etiqa_hmo_no }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>


                        </div>
                     </div>
                     <!--Third tab-->
                     <div class="tab-pane" id="SalriesInfo" role="tabpanel">
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left">Bank Details</p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Bank</label>
                                    <input type="text" id="bank" name="bank" value="{{ $employee->bank }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Account No</label>
                                    <input type="text" id="AccountNo_Empl" name="AccountNo_Empl" value="{{ $employee->AccountNo_Empl }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left"><B>Salary Details</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">EMPLOYEE IS NET?</label>
                                    <input type="text" id="employee_reimbursement" name="employee_reimbursement" value="{{ ($employee->employee_reimbursement ==1) ? 'YES' : 'NO'}} " class="form-control" disabled>
                                 </div>
                              </div>

                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Rate Type</label>
                                    <input type="text" id="" name="" value="{{ $employee->rate_type }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Rate</label>
                                    <input type="number" id="BasicSalary_Empls" name="BasicSalary_Empls" value="{{ $employee->BasicSalary_Empls }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label class="control-label">Deduction Effectivity</label>
                                    <input type="text" id="" name="" value="{{ $employee->deduction_effectivity }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left"><B>Allowance Details</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Transportation</label>
                                    <input type="number" id="transpo_allowance" name="transpo_allowance" value="{{ $employee->transpo_allowance }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <!-- added by lloyd -->
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Transportation Effective Date</label>
                                    <input type="text" id="" name="" value="{{ $employee->transpo_allowance_eff }}" class="form-control" disabled>
                                 </div>
                              </div>
 
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Load Allowance</label>
                                    <input type="number" id="mobile_allowance" name="mobile_allowance" value="{{ $employee->mobile_allowance }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <!-- added by lloyd -->
                              <div class="col-md-6">
                              <div class="form-group">
                                    <label class="control-label">Load Allowance Effective Date</label>
                                    <input type="text" id="" name="" value="{{ $employee->mobile_allowance_eff }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Housing Allowance</label>
                                    <input type="number" id="housing_allowance" name="housing_allowance" value="{{ $employee->housing_allowance }}" class="form-control" disabled>
                                 </div>
                              </div>
                               <!-- added by lloyd -->
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Housing Allowance Effective Date</label>
                                    <input type="text" id="" name="" value="{{ $employee->housing_allowance_eff }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <!-- added by lloyd -->
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Allowance</label>
                                    <input type="number" id="Allowance" name="Allowance" value="{{ $employee->Allowance }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Allowance Effective Date</label>
                                    <input type="text" id="" name="" value="{{ $employee->allowance_eff }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                           <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Meal Allowance</label>
                                    <input type="number" id="meal_allowance" name="meal_allowance" value="{{ $employee->meal_allowance }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Other Income</label>
                                    <input type="number" id="other_income" name="other_income" value="{{ $employee->other_income }}" class="form-control" disabled>
                                 </div>
                              </div>
                              
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="text-left"><B>Deduction Details</B></p>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">SSS EE</label>
                                    <input type="number" id="gp_sss_ee" name="gp_sss_ee" value="{{ $employee->gp_sss_ee }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">SSS ER</label>
                                    <input type="number" id="gp_sss_er" name="gp_sss_er" value="{{ $employee->gp_sss_er }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">PhilHealth EE</label>
                                    <input type="number" id="gp_phealth_ee" name="gp_phealth_ee" value="{{ $employee->gp_phealth_ee }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">PhilHealth ER</label>
                                    <input type="number" id="gp_phealth_er" name="gp_phealth_er" value="{{ $employee->gp_phealth_er }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">PAG-IBIG EE</label>
                                    <input type="number" id="gp_pagibig_ee" name="gp_pagibig_ee" value="{{ $employee->gp_pagibig_ee }}" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">PAG-IBIG ER</label>
                                    <input type="number" id="gp_pagibig_er" name="gp_pagibig_er" value="{{ $employee->gp_pagibig_er }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Withholding Tax</label>
                                    <input type="number" id="gp_wtax" name="gp_wtax" value="{{ $employee->gp_wtax }}" class="form-control" disabled>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--Fourth tab-->
                     <div class="tab-pane" id="Documents" role="tabpanel">
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="card border border-1">
                                    <div class="card-body">
                                       <h6 class="card-subtitle">Documents</h6>
                                       <div class="table-responsive">
                                          <table id="item_table" class="table color-table info-table">
                                             <thead>
                                                <tr>
                                                   <th class="text-center" width="20%">Image</th>
                                                   <th class="text-center">File Name</th>
                                                </tr>
                                             </thead>
                                             <tbody id="item_body">
                                                <tr>
                                                   <td class="align-middle">
                                                      <a href="{{ $employee->dir_path }}" target="_blank">
                                                         <img src="{{ $employee->dir_path }}" class="img-thumbnail" id="" style="width: 100%; max-height: 300px">
                                                      </a>
                                                   </td>
                                                   <td style="vertical-align: center">
                                                      <div class="form-group pt-3" >
                                                         <input type="text" id="" name="" value="{{ $employee->filename }}" class="form-control" disabled>
                                                      </div>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     {{-- Approvers --}}
                     <div class="tab-pane {{ (app('request')->input('approver') == 1) ? ' show active ' : '' }}" id="Approvers" role="tabpanel">
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="card border border-1">
                                    <div class="card-body">
                                       <h6 class="card-subtitle">Employee's Approvers</h6>
                                       <div class="table-responsive">
                                          <table id="item_table" class="table color-table info-table">
                                             <thead>
                                                <tr>
                                                   <th colspan="2">Approver</th>
                                                   <th>Approver Name</th>
                                                </tr>
                                             </thead>
                                             <tbody id="">
                                                <tr>
                                                   <td colspan="2">Approver 1</td>
                                                   <td>
                                                      {{ ($approvers['approver_1']) ? $approvers['approver_1']->Name_Empl : "N/A" }}
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td colspan="2">Approver 2</td>
                                                   <td>
                                                      {{ ($approvers['approver_2']) ? $approvers['approver_2']->Name_Empl : "N/A" }}
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
@else
   <a href="{{ url('/employees') }}" class="btn btn-sm btn-primary mb-2">
   <i class="fas fa-backspace"></i> Back
   </a>

   <div class="text-center">
      <h2>No results found</h2>
   </div>
@endif

</div>
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script >
   $(document).ready(function() {

      // update outlet modal
      $('.update-employee').click(function(){

         let require_dtr = $(this).data('require_dtr');

         // Set values
         $('#shift_code_id_to_update').val($(this).data('id'));
         $('#u_shift_code').val($(this).data('shift_code'));
         $('#u_time_start').val($(this).data('time_start'));
         $('#u_time_end').val($(this).data('time_end'));
         $('#u_total_hrs').val($(this).data('total_hrs'));
         $('#u_default_break_hrs').val($(this).data('default_break_hrs'));
         $('#u_description').val($(this).data('description'));
         $('#u_remarks').val($(this).data('remarks'));

         if(require_dtr == 1 || require_dtr == '1'){
            $('#u_require_dtr').prop( "checked", true );
         }
         else{
            $('#u_require_dtr').prop( "checked", false );
         }
         

         
      });

      // Delete shift code
      $('.shift_delete_modal').click(function(){
         $('#shift_delete_title_modal').text($(this).data('title') +" "+ $(this).data('time_start') + "-" + $(this).data('time_end'));
         $('#btn-delete-shift-code-button').attr('href', $(this).data('delete_url'));
         $('#shift_delete_modal').modal({backdrop: 'static', keyboard: true});
      });

      // Restore shift code
      $('.shift_restore_modal').click(function(){
         $('#shift_restore_title_modal').text($(this).data('title') +" "+ $(this).data('time_start') + "-" + $(this).data('time_end'));
         $('#btn-restore-shift-code-button').attr('href', $(this).data('restore_url'));
         $('#shift_restore_modal').modal({backdrop: 'static', keyboard: true});
      });
   });


</script>

@endsection
{{-- END PAGE LEVEL SCRIPT --}}