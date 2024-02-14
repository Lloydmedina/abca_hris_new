@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/update_employee.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Update Employee')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->

<div class="container-fluid">

   @include('Templates.alert_message')
   

   @if($employee)
      <div class="" style="min-height: 750px;">
         <a href="{{ url('/employees') }}" class="btn btn-sm btn-dark mb-2" onclick="return confirm('Are you sure you want to go back?')">
         <i class="fas fa-backspace"></i> Back
         </a>
         <form id="update_employee_form" class="form-material" action="{{ route('update_employee') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="SysPK_Empl" value="{{ $employee->SysPK_Empl.md5(1) }}">
            <div class="row p-t-20">
               
               <div class="col-lg-2 col-xlg-2 col-md-4">
                  <div class="card mb-3">
                     <div class="card-body">
                        @php
                           if (strtolower($employee->gender) == 'male')
                              $defProfile = 'public/default/profile/Male.jpg';
                           else
                              $defProfile = 'public/default/profile/Female.jpg';

                           $profilePic = $employee->picture_path ? $employee->picture_path : $defProfile;
                        @endphp
                        {{-- <label class="control-label">Employee Photo</label> --}}
                        {{-- dropify --}}
                        <input type="file" id="picture_path" name="picture_path" class="d-none" accept="image/*" />
                        <div class="hovereffect">
                           <img src="{{ $profilePic }}" class="img-thumbnail" id="blah" style="width: 100%; max-height: 200px">
                           <div class="overlay">
                              <h2>Change Photo</h2>
                              <a class="info btn" id="inputGroupFile01" href="javascript:void(0)">Select</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               
               <div class="col-lg-10 col-xlg-10 col-md-8">
                  <div class="card">
                     <!-- Nav tabs -->
                     <ul class="nav nav-tabs profile-tab" role="tablist">
                        <li class="nav-item">
                           <a class="nav-link active show" data-toggle="tab" href="#PersonProfile" role="tab" aria-selected="true">
                           Personal Info
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#EmployeeInfo" role="tab" aria-selected="false">
                           Employee Info
                           </a>
                        </li>
                        
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#SalriesInfo" role="tab" aria-selected="false">
                           Salary Info
                           </a>
                        </li>

                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#JobDescription" role="tab" aria-selected="false">
                           Job Description
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#Documents" role="tab" aria-selected="false">
                           Documents
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#Skills" role="tab" aria-selected="false">
                           Skills
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#Seminars" role="tab" aria-selected="false">
                           Trainings/Seminars
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#Organizational" role="tab" aria-selected="false">
                           Organizational Affiliations
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#MedicalRecord" role="tab" aria-selected="false">
                           Medical Records
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-toggle="tab" href="#Approvers" role="tab" aria-selected="false">
                           Approvers
                           </a>
                        </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="tab-content">
                        <!--Person tab-->
                        <div class="tab-pane active show" id="PersonProfile" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">First Name</label>
                                       <input type="text" id="FirstName_Empl" name="FirstName_Empl" value="{{ $employee->FirstName_Empl }}" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Middle Name</label>
                                       <input type="text" id="MiddleName_Empl" name="MiddleName_Empl" value="{{ $employee->MiddleName_Empl }}" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Last Name</label>
                                       <input type="text" id="LastName_Empl" name="LastName_Empl" value="{{ $employee->LastName_Empl }}" class="form-control" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label class="control-label">Address</label>
                                       <input type="text" id="Address_Empl" name="Address_Empl" value="{{ $employee->Address_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Gender</label>
                                       <select id="gender" name="gender" class="form-control custom-select" required="">
                                       <option value="Male" @if ($employee->gender == "Male") {{ 'selected' }} @endif>Male</option>
                                       <option value="Female" @if ($employee->gender == "Female") {{ 'selected' }} @endif>Female</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Civil Status</label>
                                       <select id="civilStatus" name="civilStatus" class="form-control custom-select" required="">
                                             <option value="Single" @if ($employee->civilStatus == "Single") {{ 'selected' }} @endif>Single</option>
                                             <option value="Married" @if ($employee->civilStatus == "Married") {{ 'selected' }} @endif>Married</option>
                                             <option value="Widow" @if ($employee->civilStatus == "Widow") {{ 'selected' }} @endif>Widow</option>
                                             <option value="Widower" @if ($employee->civilStatus == "Widower") {{ 'selected' }} @endif>Widower</option>
                                             <option value="Separated" @if ($employee->civilStatus == "Separated") {{ 'selected' }} @endif>Separated</option>
                                             <option value="Divorced" @if ($employee->civilStatus == "Divorced") {{ 'selected' }} @endif>Divorced</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Birthday</label>
                                       <input type="date" id="BirthDate_Empl" name="BirthDate_Empl" value="{{ $employee->BirthDate_Empl }}" class="form-control" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Height</label>
                                       <input type="text" id="height" name="height" value="{{ $employee->height }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Weight</label>
                                       <input type="text" id="weight" name="weight" value="{{ $employee->weight }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Blood Type</label>
                                       <input type="text" id="BloodType_Empl" name="BloodType_Empl" value="{{ $employee->BloodType_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Email</label>
                                       <input type="text" id="email" name="email" value="{{ $employee->email }}" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Contact Number</label>
                                       <input type="text" id="contact_no" name="contact_no" value="{{ $employee->contact_no }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">CTC</label>
                                       <input type="text" id="resident_certificate_no" name="resident_certificate_no" value="{{ $employee->resident_certificate_no }}" class="form-control" >
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
                                       <input type="text" id="EmergencyName_Empl" name="EmergencyName_Empl" value="{{ $employee->EmergencyName_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Contact No.</label>
                                       <input type="text" id="EmergencyTelNo_Empl" name="EmergencyTelNo_Empl" value="{{ $employee->EmergencyTelNo_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Contact Address</label>
                                       <input type="text" id="EmergencyAdd_Empl" name="EmergencyAdd_Empl" value="{{ $employee->EmergencyAdd_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <div class="row">
                                                <p class="text-left"><B>Dependents</B></p>
                                                <hr>
                                          </div>
                                          <div class="table-responsive mt-3">
                                             <table id="dependent_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Birth Day</th>
                                                      <th class="text-center">Full Name</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="dependent_body">
                                                   @if(count($dependents) > 0)
                                                      <?php $ctr = 0 ?>
                                                      @foreach ($dependents as $row)
                                                         <tr>
                                                            <td class="align-middle">
                                                               <div class="form-group" >
                                                                  <input type="date" id="dependent_bday_{{$ctr}}" name="dependent_bday[]" value="{{ $row->dependent_bday }}" class="form-control">
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center">
                                                               <div class="form-group" >
                                                                  <input type="text" id="dependent_full_name_{{$ctr}}" name="dependent_full_name[]" value="{{ $row->dependent_full_name }}" class="form-control" >
                                                               </div>
                                                            </td>
                                                            <td class="align-middle">
                                                               <div class="form-group form-inline">
                                                                  <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewDependentsRow( getRowIndex(this) )">
                                                                  <i class="fa fa-plus"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteDependentsRow(getRowIndex(this) )">
                                                                  <i class="fa fa-minus"></i>
                                                                  </button>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                         <?php $ctr++ ?>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="form-group" >
                                                               <input type="date" id="dependent_bday_0" name="dependent_bday[]" value="{{ old('dependent_bday[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group" >
                                                               <input type="text" id="dependent_full_name_0" name="dependent_full_name[]" value="{{ old('dependent_full_name[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewDependentsRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteDependentsRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <td class="text-right align-middle" colspan="2"><b>TOTAL DEPENDENTS: </b></td>
                                                      <td>
                                                         <input type="number" id="dependent" name="dependent" value="{{ $employee->dependent }}" class="form-control text-center" >
                                                      </td>
                                                   </tr>
                                                </tfoot>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Employee tab-->
                        <div class="tab-pane" id="EmployeeInfo" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">ID No.</label>
                                       <input type="text" id="UserID_Empl" name="UserID_Empl" value="{{ $employee->UserID_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Biometric ID</label>
                                       <input type="number" id="biometric_id" name="biometric_id" value="{{ $employee->biometric_id }}" class="form-control" >
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Employee Level</label>
                                       <select id="emp_level_id" name="emp_level_id" class="border form-control custom-select selectpicker" data-live-search="true">
                                       @foreach($emp_level as $row)
                                       <option value="{{ $row->emp_lvl_id.md5($row->emp_lvl_id) }}" @if ($employee->emp_level_id == $row->emp_lvl_id) {{ 'selected' }} @endif>{{ $row->emp_lvl }}</option>
                                       @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Status</label>
                                       <select id="Status_Empl" name="Status_Empl" class="form-group custom-select">
                                          <option value="PROBATIONARY" @if ($employee->Status_Empl == "PROBATIONARY") {{ 'selected' }} @endif>PROBATIONARY</option>
                                          <option value="REGULAR" @if ($employee->Status_Empl == "REGULAR") {{ 'selected' }} @endif>REGULAR</option>
                                          <option value="RESIGNED" @if ($employee->Status_Empl == "RESIGNED") {{ 'selected' }} @endif>RESIGNED</option>
                                          <option value="TERMINATED" @if ($employee->Status_Empl == "TERMINATED") {{ 'selected' }} @endif>TERMINATED</option>
                                          <option value="AWOL" @if ($employee->Status_Empl == "AWOL") {{ 'selected' }} @endif>AWOL</option>
                                          <option value="EOC" @if ($employee->Status_Empl == "EOC") {{ 'selected' }} @endif>EOC</option>
                                          <option value="PART TIME" @if (old('Status_Empl') == "EOC") {{ 'selected' }} @endif>PART TIME</option>
                                       </select>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Date Hired</label>
                                       <input type="date" id="DateHired_Empl" name="DateHired_Empl" value="{{ $employee->DateHired_Empl }}" class="form-control" >
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Date Resigned / Terminated / Awol</label>
                                       <input type="date" id="DateTerminated_Empl" name="DateTerminated_Empl" value="{{ $employee->DateTerminated_Empl }}" class="form-control">
                                    </div>
                                 </div>

                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Company</label>
                                       <select id="company_id" name="company_id" class="border form-control custom-select selectpicker" data-live-search="true">
                                          @foreach($company as $row)
                                          <option value="{{ $row->company_id.md5($row->company_id) }}" @if ($employee->company_id.md5($employee->company_id) == $row->company_id.md5($row->company_id)) {{ 'selected' }} @endif>{{ $row->company }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Outlet</label>
                                       <select id="outlet_id" name="outlet_id" class="border form-control custom-select selectpicker" data-live-search="true">
                                          @foreach($outlet as $row)
                                          <option value="{{ $row->outlet_id.md5($row->outlet_id) }}" @if ($employee->outlet_id.md5($employee->outlet_id) == $row->outlet_id.md5($row->outlet_id)) {{ 'selected' }} @endif>{{ $row->outlet }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Department</label>
                                       <select id="Department_Empl" name="Department_Empl" class="border form-control custom-select selectpicker" data-live-search="true">
                                       @foreach($department as $row)
                                       <option value="{{ $row->SysPK_Dept.md5($row->SysPK_Dept) }}" @if ( $employee->dept_id.md5($employee->dept_id) == $row->SysPK_Dept.md5($row->SysPK_Dept)) {{ 'selected' }} @endif>{{ $row->Name_Dept }}</option>
                                       @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Position</label>
                                       <select id="Position_Empl" name="Position_Empl" class="border form-control custom-select selectpicker" data-live-search="true">
                                       @foreach($employees_rate as $row)
                                       <option value="{{ $row->id.md5($row->id) }}" @if ($employee->Position_Empl.md5($employee->Position_Empl) == $row->id.md5($row->id)) {{ 'selected' }} @endif>{{ $row->Position_Empl }}</option>
                                       @endforeach
                                       </select>
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
                                    <label class="control-label">Require DTR?</label>
                                    <input class="" {{ ($employee->require_dtr == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $employee->require_dtr }}" id="require_dtr" name="require_dtr" {{ (old('require_dtr') == 1) ? ' checked' : '' }} /> 
                                 
                                 </div>

                              </div>

                              
                              <div class="row">
                                 <div class="col-md-6">
                                    <label class="control-label">Time In</label>
                                    <input type="time" id="TimeStart_Empl" name="TimeStart_Empl" value="{{ $employee->TimeStart_Empl }}" class="form-control" >
                                 </div>
                                 <div class="col-md-6">
                                    <label class="control-label">Time Out</label>
                                    <input type="time" id="TimeEnd_Empl" name="TimeEnd_Empl" value="{{ $employee->TimeEnd_Empl }}" class="form-control" >
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Rest Day</label>
                                       <select id="Restday" name="Restday" class="form-group custom-select">
                                       <option value="SUNDAY" @if ($employee->Restday == "SUNDAY") {{ 'selected' }} @endif>SUNDAY</option>
                                       <option value="MONDAY" @if ($employee->Restday == "MONDAY") {{ 'selected' }} @endif>MONDAY</option>
                                       <option value="TUESDAY" @if ($employee->Restday == "TUESDAY") {{ 'selected' }} @endif>TUESDAY</option>
                                       <option value="WEDNESDAY" @if ($employee->Restday == "WEDNESDAY") {{ 'selected' }} @endif>WEDNESDAY</option>
                                       <option value="THURSDAY" @if ($employee->Restday == "THURSDAY") {{ 'selected' }} @endif>THURSDAY</option>
                                       <option value="FRIDAY" @if ($employee->Restday == "FRIDAY") {{ 'selected' }} @endif>FRIDAY</option>
                                       <option value="SATURDAY" @if ($employee->Restday == "SATURDAY") {{ 'selected' }} @endif>SATURDAY</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">No. Of Days</label>
                                       <select id="no_of_days" name="no_of_days" class="form-group custom-select">
                                       <option value="21.77" @if ($employee->no_of_days == "21.77") {{ 'selected' }} @endif>21.77</option>
                                       <option value="26" @if ($employee->no_of_days == "26") {{ 'selected' }} @endif>26</option>
                                       <option value="30" @if ($employee->no_of_days == "30") {{ 'selected' }} @endif>30</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="text-left"><B>Leave Credits</B></p>
                                    <hr>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Sick Leave Credit</label>
                                       <input type="number" id="sick_leave_credit" name="sick_leave_credit" value="{{ $employee->sick_leave_credit }}" class="form-control" step="any" >
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Vacation Leave Credit</label>
                                       <input type="number" id="vacation_leave_credit" name="vacation_leave_credit" value="{{ $employee->vacation_leave_credit }}" class="form-control" step="any" >
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Maternity Leave Credit</label>
                                       <input type="number" id="maternity_leave_credit" name="maternity_leave_credit" value="{{ $employee->maternity_leave_credit }}" class="form-control" step="any" >
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Paternity Leave Credit</label>
                                       <input type="number" id="paternity_leave_credit" name="paternity_leave_credit" value="{{ $employee->paternity_leave_credit }}" class="form-control" step="any" >
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
                                       <input type="text" id="SSS_Empl" name="SSS_Empl" value="{{ $employee->SSS_Empl }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">PhilHealth No.</label>
                                       <input type="text" id="philhealth_no" name="philhealth_no" value="{{ $employee->philhealth_no }}" class="form-control" >
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">PAG-IBIG No.</label>
                                       <input type="text" id="pagibig_no" name="pagibig_no" value="{{ $employee->pagibig_no }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">TIN No.</label>
                                       <input type="text" id="TIN_Empl" name="TIN_Empl" value="{{ $employee->TIN_Empl }}" class="form-control" >
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
                                       <input type="text" id="etiqa_hmo_no" name="etiqa_hmo_no" value="{{ $employee->etiqa_hmo_no }}" class="form-control" >
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>
                        </div>
                        <!--Salaries tab-->
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
                                       <input type="text" id="bank" name="bank" value="{{ $employee->bank }}" class="form-control" >
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Account No</label>
                                       <input type="text" id="AccountNo_Empl" name="AccountNo_Empl" value="{{ $employee->AccountNo_Empl }}" class="form-control" >
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
                                    <label class="control-label">EMPLOYEE IS NET?</label>
                                    <input class="" {{ ($employee->employee_reimbursement == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $employee->employee_reimbursement }}" id="employee_reimbursement" name="employee_reimbursement" {{ (old('employee_reimbursement') == 1) ? ' checked' : '' }} /> 
                                 
                                 </div>

                              </div>

                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Rate Type</label>
                                       <select id="rate_type" name="rate_type" class="form-control custom-select">
                                       <option value="Hourly" @if ($employee->rate_type == "Hourly") {{ 'selected' }} @endif>Hourly</option>
                                       <option value="Daily" @if ($employee->rate_type == "Daily") {{ 'selected' }} @endif>Daily</option>
                                       <option value="Monthly" @if ($employee->rate_type == "Monthly") {{ 'selected' }} @endif>Monthly</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Rate</label>
                                       <input type="number" id="BasicSalary_Empls" name="BasicSalary_Empls" value="{{ $employee->BasicSalary_Empls }}" class="form-control" step="any">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label class="control-label">Deduction Effectivity</label>
                                       <select id="deduction_effectivity" name="deduction_effectivity" class="form-control custom-select">
                                       <option value="1-15" @if ($employee->deduction_effectivity == "1-15") {{ 'selected' }} @endif>1-15</option>
                                       <option value="16-31" @if ($employee->deduction_effectivity == "16-31") {{ 'selected' }} @endif>16-31</option>
                                       <option value="HALF" @if (old('deduction_effectivity') == "HALF") {{ 'selected' }} @endif>HALF</option>
                                       <option value="NONE" @if (old('deduction_effectivity') == "NONE") {{ 'selected' }} @endif>NONE</option>
                                       </select>
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
                                       <input type="number" id="transpo_allowance" name="transpo_allowance" value="{{ $employee->transpo_allowance }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                 <!-- added by lloyd -->
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Transportation Effective Date</label>
                                    
                                       <select id="transpo_allowance_eff" name="transpo_allowance_eff" class="form-control custom-select">
                                          <option value="1-15" @if ($employee->transpo_allowance_eff == "1-15") {{ 'selected' }} @endif>1-15</option>
                                          <option value="16-31" @if ($employee->transpo_allowance_eff == "16-31") {{ 'selected' }} @endif>16-31</option>
                                          <option value="HALF" @if (old('transpo_allowance_eff') == "HALF") {{ 'selected' }} @endif>HALF</option>
                                          <option value="NONE" @if (old('transpo_allowance_eff') == "NONE") {{ 'selected' }} @endif>NONE</option>
                                          </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Load Allowance</label>
                                       <input type="number" id="mobile_allowance" name="mobile_allowance" value="{{ $employee->mobile_allowance }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                    <!-- added by lloyd -->
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Load Effective Date</label>
                                    
                                       <select id="mobile_allowance_eff" name="mobile_allowance_eff" class="form-control custom-select">
                                          <option value="1-15" @if ($employee->mobile_allowance_eff == "1-15") {{ 'selected' }} @endif>1-15</option>
                                          <option value="16-31" @if ($employee->mobile_allowance_eff == "16-31") {{ 'selected' }} @endif>16-31</option>
                                          <option value="HALF" @if (old('mobile_allowance_eff') == "HALF") {{ 'selected' }} @endif>HALF</option>
                                          <option value="NONE" @if (old('mobile_allowance_eff') == "NONE") {{ 'selected' }} @endif>NONE</option>
                                          </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Housing Allowance</label>
                                       <input type="number" id="housing_allowance" name="housing_allowance" value="{{ $employee->housing_allowance }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                 
                                  <!-- added by lloyd -->
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Housing Effective Date</label>
                                    
                                       <select id="housing_allowance_eff" name="housing_allowance_eff" class="form-control custom-select">
                                          <option value="1-15" @if ($employee->housing_allowance_eff == "1-15") {{ 'selected' }} @endif>1-15</option>
                                          <option value="16-31" @if ($employee->housing_allowance_eff == "16-31") {{ 'selected' }} @endif>16-31</option>
                                          <option value="HALF" @if (old('housing_allowance_eff') == "HALF") {{ 'selected' }} @endif>HALF</option>
                                          <option value="NONE" @if (old('housing_allowance_eff') == "NONE") {{ 'selected' }} @endif>NONE</option>
                                          </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Allowance</label>
                                       <input type="number" id="Allowance" name="Allowance" value="{{ $employee->Allowance }}" class="form-control" step="any">
                                    </div>
                                 </div>

                                  <!-- added by lloyd -->
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Allowance Effective Date</label>
                                    
                                       <select id="allowance_eff" name="allowance_eff" class="form-control custom-select">
                                          <option value="1-15" @if ($employee->allowance_eff == "1-15") {{ 'selected' }} @endif>1-15</option>
                                          <option value="16-31" @if ($employee->allowance_eff == "16-31") {{ 'selected' }} @endif>16-31</option>
                                          <option value="HALF" @if (old('allowance_eff') == "HALF") {{ 'selected' }} @endif>HALF</option>
                                          <option value="NONE" @if (old('allowance_eff') == "NONE") {{ 'selected' }} @endif>NONE</option>
                                          </select>
                                    </div>
                                 </div>
                              </div>


                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Other Income</label>
                                       <input type="number" id="other_income" name="other_income" value="{{ $employee->other_income }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Meal Allowance</label>
                                       <input type="number" id="meal_allowance" name="meal_allowance" value="{{ $employee->meal_allowance }}" class="form-control" step="any">
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
                                       <input type="number" id="gp_sss_ee" name="gp_sss_ee" value="{{ $employee->gp_sss_ee }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">SSS ER</label>
                                       <input type="number" id="gp_sss_er" name="gp_sss_er" value="{{ $employee->gp_sss_er }}" class="form-control" step="any">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">PhilHealth EE</label>
                                       <input type="number" id="gp_phealth_ee" name="gp_phealth_ee" value="{{ $employee->gp_phealth_ee }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">PhilHealth ER</label>
                                       <input type="number" id="gp_phealth_er" name="gp_phealth_er" value="{{ $employee->gp_phealth_er }}" class="form-control" step="any">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">PAG-IBIG EE</label>
                                       <input type="number" id="gp_pagibig_ee" name="gp_pagibig_ee" value="{{ $employee->gp_pagibig_ee }}" class="form-control" step="any">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">PAG-IBIG ER</label>
                                       <input type="number" id="gp_pagibig_er" name="gp_pagibig_er" value="{{ $employee->gp_pagibig_er }}" class="form-control" step="any">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Withholding Tax</label>
                                       <input type="number" id="gp_wtax" name="gp_wtax" value="{{ $employee->gp_wtax }}" class="form-control" step="any">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Job Description tab-->
                        <div class="tab-pane" id="JobDescription" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <h6 class="card-subtitle">Add Job Description</h6>
                                          <div class="table-responsive">
                                             <table id="job_description_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Image</th>
                                                      <th class="text-center">Job Description</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="job_description_body">
                                                   @if(count($job_description) > 0)
                                                      <?php $ctr = 0; ?>
                                                      @foreach ($job_description as $row)
                                                         <tr>
                                                            <td class="align-middle">
                                                               <div class="card-body">
                                                                  <input type="hidden" name="def_jd_image_path[]" value="{{ $row->jd_image_path }}">
                                                                  <input type="file" id="jd_image_path_{{ $ctr }}" name="jd_image_path[]" data-default-file="{{ asset($row->jd_image_path) }}" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                                  <a href="{{ url($row->jd_image_path) }}" target="_blank">View docs</a>
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center">
                                                               <div class="form-group pt-5" >
                                                                  <input type="text" id="job_description_{{ $ctr }}" name="job_description[]" value="{{ $row->job_description }}" class="form-control" >
                                                               </div>
                                                            </td>
                                                            <td class="align-middle">
                                                               <div class="form-group form-inline">
                                                                  <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewJobDescriptionRow( getRowIndex(this) )">
                                                                  <i class="fa fa-plus"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteJobDescriptionRow(getRowIndex(this) )">
                                                                  <i class="fa fa-minus"></i>
                                                                  </button>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="card-body">
                                                               <input type="file" id="jd_image_path_0" name="jd_image_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group pt-5" >
                                                               <input type="text" id="job_description_0" name="job_description[]" value="{{ old('job_description[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewJobDescriptionRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteJobDescriptionRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                   
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Documents tab-->
                        <div class="tab-pane" id="Documents" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <h6 class="card-subtitle">Add Documents</h6>
                                          <div class="table-responsive">
                                             <table id="item_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Image</th>
                                                      <th class="text-center">Document Name</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="item_body">
                                                   @if(count($documents) > 0)
                                                      <?php $ctr = 0; ?>
                                                      @foreach ($documents as $row)
                                                         <tr>
                                                            <td class="align-middle">
                                                               <div class="card-body">
                                                                  <input type="hidden" name="def_dir_path[]" value="{{ $row->dir_path }}">
                                                                  <input type="file" id="dir_path_{{$ctr}}" name="dir_path[]" data-default-file="{{ asset($row->dir_path) }}" class="dropify" accept="image/*" data-height="100" data-height="100"/>
                                                                  <a href="{{ url($row->dir_path) }}" target="_blank">View docs</a>
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center">
                                                               <div class="form-group pt-5" >
                                                                  <input type="text" id="filename_{{$ctr}}" name="filename[]" value="{{ $row->filename }}" class="form-control" >
                                                               </div>
                                                            </td>
                                                            <td class="align-middle">
                                                               <div class="form-group form-inline">
                                                                  <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewDocRow( getRowIndex(this) )">
                                                                  <i class="fa fa-plus"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteDocRow(getRowIndex(this) )">
                                                                  <i class="fa fa-minus"></i>
                                                                  </button>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                         <?php $ctr++ ?>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="card-body">
                                                               <input type="file" id="dir_path_0" name="dir_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group pt-5" >
                                                               <input type="text" id="filename_0" name="filename[]" value="{{ old('filename[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewDocRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteDocRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Skills tab-->
                        <div class="tab-pane" id="Skills" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <h6 class="card-subtitle">Add Skills</h6>
                                          <div class="table-responsive">
                                             <table id="skills_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Image</th>
                                                      <th class="text-center">Skills</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="skills_body">
                                                   @if(count($skills) > 0)
                                                      @foreach ($skills as $row)
                                                         <?php $ctr = 0; ?>
                                                            <tr>
                                                               <td class="align-middle">
                                                                  <div class="card-body">
                                                                     <input type="hidden" name="def_skills_image_path[]" value="{{ $row->skills_image_path }}">
                                                                     <input type="file" id="skills_image_path_{{ $ctr }}" name="skills_image_path[]" data-default-file="{{ asset($row->skills_image_path) }}" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                                     <a href="{{ url($row->skills_image_path) }}" target="_blank">View docs</a>
                                                                  </div>
                                                               </td>
                                                               <td style="vertical-align: center">
                                                                  <div class="form-group pt-5" >
                                                                     <input type="text" id="skill_{{ $ctr }}" name="skill[]" value="{{ $row->skill }}" class="form-control" >
                                                                  </div>
                                                               </td>
                                                               <td class="align-middle">
                                                                  <div class="form-group form-inline">
                                                                     <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewSkillsRow( getRowIndex(this) )">
                                                                     <i class="fa fa-plus"></i>
                                                                     </button>
                                                                     <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteSkillsRow(getRowIndex(this) )">
                                                                     <i class="fa fa-minus"></i>
                                                                     </button>
                                                                  </div>
                                                               </td>
                                                            </tr>
                                                         <?php $ctr++; ?>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="card-body">
                                                               <input type="file" id="skills_image_path_0" name="skills_image_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group pt-5" >
                                                               <input type="text" id="skill_0" name="skill[]" value="{{ old('skill[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewSkillsRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteSkillsRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                   
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Seminars tab-->
                        <div class="tab-pane" id="Seminars" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <h6 class="card-subtitle">Add Trainings and Seminars</h6>
                                          <div class="table-responsive">
                                             <table id="seminar_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Image</th>
                                                      <th class="text-center" style="width: 260px">Date</th>
                                                      <th class="text-center">Training/Seminar</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="seminar_body">
                                                   @if(count($training_seminars) > 0)
                                                      <?php $ctr = 0 ?>
                                                      @foreach ($training_seminars as $row)
                                                         <tr>
                                                            <td class="align-middle">
                                                               <div class="card-body">
                                                                  <input type="hidden" name="def_certificate_path[]" value="{{ $row->certificate_path }}">
                                                                  <input type="file" id="seminar_path_{{ $ctr }}" name="certificate_path[]" data-default-file="{{ asset($row->certificate_path) }}" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                                  <a href="{{ url($row->certificate_path) }}" target="_blank">View docs</a>
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center;width: 260px">
                                                               <div class="form-group pt-3" >
                                                                  <div class="row">
                                                                     <div class="col-md-3">
                                                                        <label class="form-control-label">From: </label>
                                                                     </div>
                                                                     <div class="col-md-9">
                                                                        <input type="date" id="seminar_date_from_{{ $ctr }}" name="from_date[]" value="{{ $row->from_date }}" class="form-control" >
                                                                     </div>
                                                                  </div>
                                                                  <div class="row pt-2">
                                                                     <div class="col-md-3">
                                                                        <label class="form-control-label">To: </label>
                                                                     </div>
                                                                     <div class="col-md-9">
                                                                        <input type="date" id="seminar_date_to_{{ $ctr }}" name="to_date[]" value="{{ $row->to_date }}" class="form-control" >
                                                                     </div>
                                                                  </div>
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center">
                                                               <div class="form-group pt-3" >
                                                                  <div class="row">
                                                                     <div class="col-md-3">
                                                                        <label class="form-control-label">Name: </label>
                                                                     </div>
                                                                     <div class="col-md-9">
                                                                        <input type="text" id="seminar_name_{{ $ctr }}" name="seminar_training[]" value="{{ $row->seminar_training }}" class="form-control" >
                                                                     </div>
                                                                  </div>
                                                                  <div class="row pt-2">
                                                                     <div class="col-md-3">
                                                                        <label class="form-control-label">Address: </label>
                                                                     </div>
                                                                     <div class="col-md-9">
                                                                        <input type="text" id="seminar_address_{{ $ctr }}" name="seminar_address[]" value="{{ $row->seminar_address }}" class="form-control" >
                                                                     </div>
                                                                  </div>
                                                               </div>
                                                            </td>
                                                            <td class="align-middle">
                                                               <div class="form-group form-inline">
                                                                  <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewSeminarRow( getRowIndex(this) )">
                                                                  <i class="fa fa-plus"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteSeminarRow(getRowIndex(this) )">
                                                                  <i class="fa fa-minus"></i>
                                                                  </button>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      <?php $ctr++ ?>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="card-body">
                                                               <input type="file" id="seminar_path_0" name="certificate_path[]" class="dropify" accept="image/*" data-height="100" data-height="100" />
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center;width: 260px">
                                                            <div class="form-group pt-3" >
                                                               <div class="row">
                                                                  <div class="col-md-3">
                                                                     <label class="form-control-label">From: </label>
                                                                  </div>
                                                                  <div class="col-md-9">
                                                                     <input type="date" id="seminar_date_from_0" name="from_date[]" value="{{ old('from_date[0]') }}" class="form-control">
                                                                  </div>
                                                               </div>
                                                               <div class="row pt-2">
                                                                  <div class="col-md-3">
                                                                     <label class="form-control-label">To: </label>
                                                                  </div>
                                                                  <div class="col-md-9">
                                                                     <input type="date" id="seminar_date_to_0" name="to_date[]" value="{{ old('to_date[0]') }}" class="form-control">
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group pt-3" >
                                                               <div class="row">
                                                                  <div class="col-md-3">
                                                                     <label class="form-control-label">Name: </label>
                                                                  </div>
                                                                  <div class="col-md-9">
                                                                     <input type="text" id="seminar_name_0" name="seminar_training[]" value="{{ old('seminar_training[0]') }}" class="form-control">
                                                                  </div>
                                                               </div>
                                                               <div class="row pt-2">
                                                                  <div class="col-md-3">
                                                                     <label class="form-control-label">Address: </label>
                                                                  </div>
                                                                  <div class="col-md-9">
                                                                     <input type="text" id="seminar_address_0" name="seminar_address[]" value="{{ old('seminar_address[0]') }}" class="form-control">
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewSeminarRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteSeminarRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Organization tab-->
                        <div class="tab-pane" id="Organizational" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <h6 class="card-subtitle">Organizational Affiliations</h6>
                                          <div class="table-responsive">
                                             <table id="organizational_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Image</th>
                                                      <th class="text-center">Organizational Affilations</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="organizational_body">
                                                   @if(count($organizational) > 0)
                                                      <?php $ctr = 0 ?>
                                                      @foreach ($organizational as $row)
                                                         <tr>
                                                            <td class="align-middle">
                                                               <div class="card-body">
                                                                  <input type="hidden" name="def_organizational_path[]" value="{{ $row->organizational_path }}">
                                                                  <input type="file" id="organizational_path_{{ $ctr }}" name="organizational_path[]" data-default-file="{{ asset($row->organizational_path) }}" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                                  <a href="{{ url($row->organizational_path) }}" target="_blank">View docs</a>
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center">
                                                               <div class="form-group pt-5" >
                                                                  <input type="text" id="organizational_{{ $ctr }}" name="organizational[]" value="{{ $row->organizational }}" class="form-control" >
                                                               </div>
                                                            </td>
                                                            <td class="align-middle">
                                                               <div class="form-group form-inline">
                                                                  <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewOrganizationalRow( getRowIndex(this) )">
                                                                  <i class="fa fa-plus"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteOrganizationalRow(getRowIndex(this) )">
                                                                  <i class="fa fa-minus"></i>
                                                                  </button>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      <?php $ctr++ ?>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="card-body">
                                                               <input type="file" id="organizational_path_0" name="organizational_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"/>
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group pt-5" >
                                                               <input type="text" id="organizational_0" name="organizational[]" value="{{ old('organizational[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewOrganizationalRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteOrganizationalRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--Medical Records tab-->
                        <div class="tab-pane" id="MedicalRecord" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="card border border-1">
                                       <div class="card-body">
                                          <h6 class="card-subtitle">Medical Records</h6>
                                          <br>
                                          <div class="table-responsive">
                                             <table id="medical_table" class="table color-table info-table">
                                                <thead>
                                                   <tr>
                                                      <th class="text-center" width="75">Image</th>
                                                      <th class="text-center">Medical Record</th>
                                                      <th class="text-center" width="100">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody id="medical_body">
                                                   @if(count($medical_record) > 0)
                                                      <?php $ctr = 0; ?>
                                                      @foreach ($medical_record as $row)
                                                         <tr>
                                                            <td class="align-middle">
                                                               <div class="card-body">
                                                                  <input type="hidden" name="def_medical_record_path[]" value="{{ $row->medical_record_path }}">
                                                                  <input type="file" id="medical_record_path_{{ $ctr }}" name="medical_record_path[]" data-default-file="{{ asset($row->medical_record_path) }}" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                                  <a href="{{ url($row->medical_record_path) }}" target="_blank">View docs</a>  
                                                               </div>
                                                            </td>
                                                            <td style="vertical-align: center">
                                                               <div class="form-group pt-5" >
                                                                  <input type="text" id="medical_record_{{ $ctr }}" name="medical_record[]" value="{{ $row->medical_record }}" class="form-control" >
                                                               </div>
                                                            </td>
                                                            <td class="align-middle">
                                                               <div class="form-group form-inline">
                                                                  <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewMedicalRecordRow( getRowIndex(this) )">
                                                                  <i class="fa fa-plus"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteMedicalRecordRow(getRowIndex(this) )">
                                                                  <i class="fa fa-minus"></i>
                                                                  </button>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      <?php $ctr++; ?>
                                                      @endforeach
                                                   @else
                                                      <tr>
                                                         <td class="align-middle">
                                                            <div class="card-body">
                                                               <input type="file" id="medical_record_path_0" name="medical_record_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  />
                                                            </div>
                                                         </td>
                                                         <td style="vertical-align: center">
                                                            <div class="form-group pt-5" >
                                                               <input type="text" id="medical_record_0" name="medical_record[]" value="{{ old('organizational[0]') }}" class="form-control" >
                                                            </div>
                                                         </td>
                                                         <td class="align-middle">
                                                            <div class="form-group form-inline">
                                                               <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewMedicalRecordRow( getRowIndex(this) )">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteMedicalRecordRow(getRowIndex(this) )">
                                                               <i class="fa fa-minus"></i>
                                                               </button>
                                                            </div>
                                                         </td>
                                                      </tr>
                                                   @endif
                                                   
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
                        <div class="tab-pane" id="Approvers" role="tabpanel">

                           <div class="card-body">
                              <div class="row">
                                 <div class="col-12">
                                    <h6 class="card-subtitle">Employee's Approver <small>(Leave/OT)</small></h6>
                                    <br>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Approver 1</label>
                                       <select class="border form-control custom-select selectpicker" data-live-search="true" name="approver_1">
                                          <option value="" selected></option>
                                          @foreach ($employees as $emp)
                                             <option value="{{ $emp->UserID_Empl }}" {{ ( $approver_1 == $emp->UserID_Empl) ? 'selected' : '' }}>{{ ucwords(strtolower($emp->FirstName_Empl.' '.$emp->LastName_Empl)) }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Approver 2</label>
                                       <select class="border form-control custom-select selectpicker" data-live-search="true" name="approver_2">
                                          <option value="" selected></option>
                                          @foreach ($employees as $emp)
                                             <option value="{{ $emp->UserID_Empl }}" {{ ( $approver_2 == $emp->UserID_Empl) ? 'selected' : '' }}>{{ ucwords(strtolower($emp->FirstName_Empl.' '.$emp->LastName_Empl)) }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="row p-t-20">
               <div class="col-md-12">
                  <div class="form-actions m-auto text-center">
                     <a href="" class="btn btn-danger mr-2 mt-4" onclick="return confirm('Are you sure you want to reset?')"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                     <button type="submit" class="btn btn-dark ml-2 mt-4"> <i class="fa-solid fa-floppy-disk"></i> Update</button>
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

   <hr>

</div>
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/vendor/dropify/dist/js/dropify.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/update_employee.js') }}"></script>
<script>

   function getRowIndex(el) {
       while ((el = el.parentNode) && el.nodeName.toLowerCase() !== 'tr');

         if (el){
           return el.rowIndex;
         }
   }

   function DeleteDependentsRow(row_index) {
      let remove = confirm('Remove?');
      if(remove == true){
         if (document.getElementById("dependent_table").rows.length > 1) {
            document.getElementById("dependent_table").deleteRow(row_index);
         }
      }
   }

   function InsertNewDependentsRow(row_index) {
      var table = document.getElementById("dependent_table");
      var row = table.insertRow(row_index + 1);

      var td_bday = row.insertCell(0);
      var td_full_name = row.insertCell(1);
      var td_action = row.insertCell(2);

      td_bday.setAttribute('class', 'align-middle')
      td_bday.innerHTML ='<div class="form-group" >\
                                    <input type="date" id="dependent_bday_'+ row_index + 1 +'" name="dependent_bday[]" class="form-control"  required/>\
                              </div>';

      td_full_name.setAttribute('style', 'vertical-align: center')
      td_full_name.innerHTML ='<div class="form-group">\
                                    <input type="text" id="dependent_full_name_'+ row_index + 1 +'" name="dependent_full_name[]" class="form-control" required>\
                              </div>';

      td_action.setAttribute('class', 'align-middle')

      td_action.innerHTML ='<div class="form-group form-inline">\
                              <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewDependentsRow( getRowIndex(this) )">\
                                 <i class="fa fa-plus"></i>\
                              </button>\
                              <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteDependentsRow(getRowIndex(this) )">\
                                 <i class="fa fa-minus"></i>\
                              </button>\
                           </div>';
   }

   function DeleteJobDescriptionRow(row_index) {
      let remove = confirm('Remove?');
      if(remove == true){
         if (document.getElementById("job_description_table").rows.length > 1) {
            document.getElementById("job_description_table").deleteRow(row_index);
         }
      }
   }

   function InsertNewJobDescriptionRow(row_index) {
      var table = document.getElementById("job_description_table");
      var row = table.insertRow(row_index + 1);

      var td_dir_path = row.insertCell(0);
      var td_file_name = row.insertCell(1);
      var td_action = row.insertCell(2);

      td_dir_path.setAttribute('class', 'align-middle')
      td_dir_path.innerHTML ='<div class="card-body">\
                                    <input type="file" id="jd_image_path_'+ row_index + 1 +'" name="jd_image_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  required/>\
                              </div>';

      td_file_name.setAttribute('style', 'vertical-align: center')
      td_file_name.innerHTML ='<div class="form-group pt-5">\
                                    <input type="text" id="job_description_'+ row_index + 1 +'" name="job_description[]" class="form-control" required>\
                              </div>';

      td_action.setAttribute('class', 'align-middle')

      td_action.innerHTML ='<div class="form-group form-inline">\
                                 <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewJobDescriptionRow( getRowIndex(this) )">\
                                    <i class="fa fa-plus"></i>\
                                 </button>\
                                 <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteJobDescriptionRow(getRowIndex(this) )">\
                                    <i class="fa fa-minus"></i>\
                                 </button>\
                              </div>';
      $('.dropify').dropify();
   }

   function DeleteDocRow(row_index) {
      let remove = confirm('Remove?');
      if(remove == true){
         if (document.getElementById("item_table").rows.length > 1) {
           document.getElementById("item_table").deleteRow(row_index);
         }
      }
   }

   function InsertNewDocRow(row_index) {
      var table = document.getElementById("item_table");
      var row = table.insertRow(row_index + 1);

      var td_dir_path = row.insertCell(0);
      var td_file_name = row.insertCell(1);
      var td_action = row.insertCell(2);

      td_dir_path.setAttribute('class', 'align-middle')
      td_dir_path.innerHTML ='<div class="card-body">\
                                    <input type="file" id="dir_path_'+ row_index + 1 +'" name="dir_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  required/>\
                              </div>';

      td_file_name.setAttribute('style', 'vertical-align: center')
      td_file_name.innerHTML ='<div class="form-group pt-5">\
                                    <input type="text" id="filename_'+ row_index + 1 +'" name="filename[]" class="form-control" required>\
                              </div>';

      td_action.setAttribute('class', 'align-middle')

      td_action.innerHTML ='<div class="form-group form-inline">\
                              <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewDocRow( getRowIndex(this) )">\
                                 <i class="fa fa-plus"></i>\
                              </button>\
                              <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteDocRow(getRowIndex(this) )">\
                                 <i class="fa fa-minus"></i>\
                              </button>\
                           </div>';
      $('.dropify').dropify();
   }

   function DeleteSkillsRow(row_index) {
      let remove = confirm('Remove?');
         if(remove == true){
            if (document.getElementById("skills_table").rows.length > 1) {
               document.getElementById("skills_table").deleteRow(row_index);
            }
      }
   }

   function InsertNewSkillsRow(row_index) {
      var table = document.getElementById("skills_table");
      var row = table.insertRow(row_index + 1);

      var td_dir_path = row.insertCell(0);
      var td_file_name = row.insertCell(1);
      var td_action = row.insertCell(2);

      td_dir_path.setAttribute('class', 'align-middle')
      td_dir_path.innerHTML ='<div class="card-body">\
                                 <input type="file" id="skills_image_path_'+ row_index + 1 +'" name="skills_image_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  required/>\
                              </div>';

      td_file_name.setAttribute('style', 'vertical-align: center')
      td_file_name.innerHTML ='<div class="form-group pt-5">\
                                 <input type="text" id="skill_'+ row_index + 1 +'" name="skill[]" class="form-control" required>\
                              </div>';

      td_action.setAttribute('class', 'align-middle')
      td_action.innerHTML ='<div class="form-group form-inline">\
                              <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewSkillsRow( getRowIndex(this) )">\
                                 <i class="fa fa-plus"></i>\
                              </button>\
                              <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteSkillsRow(getRowIndex(this) )">\
                                 <i class="fa fa-minus"></i>\
                              </button>\
                           </div>';
      $('.dropify').dropify();
   }

   function DeleteSeminarRow(row_index) {
      
      let remove = confirm('Remove?');
      if(remove == true){
         if (document.getElementById("seminar_table").rows.length > 1) {
            document.getElementById("seminar_table").deleteRow(row_index);
         }
      }
   }

   function InsertNewSeminarRow(row_index) {
      var table = document.getElementById("seminar_table");
      var row = table.insertRow(row_index + 1);

      var seminar_path = row.insertCell(0);
      var seminar_date = row.insertCell(1);
      var seminar = row.insertCell(2);
      var td_action = row.insertCell(3);

      seminar_path.setAttribute('class', 'align-middle')
      seminar_path.innerHTML ='<div class="card-body">\
                                 <input type="file" id="seminar_path_'+ row_index + 1 +'" name="certificate_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  required/>\
                              </div>';

      seminar_date.setAttribute('style', 'vertical-align: center')
      seminar_date.innerHTML ='<div class="form-group pt-3" >\
                                 <div class="row">\
                                    <div class="col-md-3">\
                                       <label class="form-control-label">From: </label>\
                                    </div>\
                                    <div class="col-md-9">\
                                       <input type="date" id="seminar_date_from_'+ row_index + 1 +'" name="from_date[]" class="form-control" required>\
                                    </div>\
                                 </div>\
                                 <div class="row pt-2">\
                                    <div class="col-md-3">\
                                       <label class="form-control-label">To: </label>\
                                    </div>\
                                    <div class="col-md-9">\
                                       <input type="date" id="seminar_date_to_'+ row_index + 1 +'" name="to_date[]" class="form-control" required>\
                                    </div>\
                                 </div>\
                              </div>';

      seminar.setAttribute('style', 'vertical-align: center')
      seminar.innerHTML ='<div class="form-group pt-3" >\
                              <div class="row">\
                                 <div class="col-md-3">\
                                    <label class="form-control-label">Name: </label>\
                                 </div>\
                                 <div class="col-md-9">\
                                    <input type="text" id="seminar_name_0" name="seminar_training[]" class="form-control" required>\
                                 </div>\
                              </div>\
                              <div class="row pt-2">\
                                 <div class="col-md-3">\
                                    <label class="form-control-label">Address: </label>\
                                 </div>\
                                 <div class="col-md-9">\
                                    <input type="text" id="seminar_address_0" name="seminar_address[]" class="form-control" >\
                                 </div>\
                              </div>\
                           </div>';                        

      td_action.setAttribute('class', 'align-middle')
      td_action.innerHTML ='<div class="form-group form-inline">\
                              <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewSeminarRow( getRowIndex(this) )">\
                                 <i class="fa fa-plus"></i>\
                              </button>\
                              <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteSeminarRow(getRowIndex(this) )">\
                                 <i class="fa fa-minus"></i>\
                              </button>\
                           </div>';
      $('.dropify').dropify();
   }

   function DeleteOrganizationalRow(row_index) {
      let remove = confirm('Remove?');
      if(remove == true){
         if (document.getElementById("organizational_table").rows.length > 1) {
            document.getElementById("organizational_table").deleteRow(row_index);
         }
      }
   }

   function InsertNewOrganizationalRow(row_index) {
      var table = document.getElementById("organizational_table");
      var row = table.insertRow(row_index + 1);

      var td_dir_path = row.insertCell(0);
      var td_file_name = row.insertCell(1);
      var td_action = row.insertCell(2);

      td_dir_path.setAttribute('class', 'align-middle')
      td_dir_path.innerHTML ='<div class="card-body">\
                                    <input type="file" id="organizational_path_'+ row_index + 1 +'" name="organizational_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  required/>\
                              </div>';

      td_file_name.setAttribute('style', 'vertical-align: center')
      td_file_name.innerHTML ='<div class="form-group pt-5">\
                                    <input type="text" id="organizational_'+ row_index + 1 +'" name="organizational[]" class="form-control" required>\
                              </div>';

      td_action.setAttribute('class', 'align-middle')

      td_action.innerHTML ='<div class="form-group form-inline">\
                                 <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewOrganizationalRow( getRowIndex(this) )">\
                                    <i class="fa fa-plus"></i>\
                                 </button>\
                                 <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteOrganizationalRow(getRowIndex(this) )">\
                                    <i class="fa fa-minus"></i>\
                                 </button>\
                              </div>';
                              
      $('.dropify').dropify();
   }

   function DeleteMedicalRecordRow(row_index) {
      let remove = confirm('Remove?');
      if(remove == true){
            if (document.getElementById("medical_table").rows.length > 1) {
            document.getElementById("medical_table").deleteRow(row_index);
         }
      }
   }

   function InsertNewMedicalRecordRow(row_index) {
      var table = document.getElementById("medical_table");
      var row = table.insertRow(row_index + 1);

      var td_dir_path = row.insertCell(0);
      var td_file_name = row.insertCell(1);
      var td_action = row.insertCell(2);

      td_dir_path.setAttribute('class', 'align-middle')
      td_dir_path.innerHTML ='<div class="card-body">\
                                    <input type="file" id="medical_record_path_'+ row_index + 1 +'" name="medical_record_path[]" class="dropify" accept="image/*" data-height="100" data-height="100"  required/>\
                              </div>';

      td_file_name.setAttribute('style', 'vertical-align: center')
      td_file_name.innerHTML ='<div class="form-group pt-5">\
                                    <input type="text" id="medical_record_'+ row_index + 1 +'" name="medical_record[]" class="form-control" required>\
                              </div>';

      td_action.setAttribute('class', 'align-middle')

      td_action.innerHTML ='<div class="form-group form-inline">\
                              <button type="button" class="btn btn-dark btn-rounded btn-sm waves-effect waves-light mr-1" onclick="InsertNewMedicalRecordRow( getRowIndex(this) )">\
                                 <i class="fa fa-plus"></i>\
                              </button>\
                              <button type="button" class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mr-1" onclick="DeleteMedicalRecordRow(getRowIndex(this) )">\
                                 <i class="fa fa-minus"></i>\
                              </button>\
                           </div>';
      $('.dropify').dropify();
   }

$(document).ready(function(){
   $(document).on('change', '#require_dtr', function(e){

        let require_dtr = $('#require_dtr').val();

        if(require_dtr == 1){

            $('#require_dtr').val(0);
            

        }else{

            $('#require_dtr').val(1);
        }

        
    });

   $(document).on('change', '#employee_reimbursement', function(e){

        let employee_reimbursement = $('#employee_reimbursement').val();

        if(employee_reimbursement == 1){

            $('#employee_reimbursement').val(0);
            

        }else{

            $('#employee_reimbursement').val(1);
        }

        
    });
   

   $("#update_employee_form").submit(function(e){
      let require_dtr = $(this).data('require_dtr');

      if(require_dtr == 1 || require_dtr == '1'){
            $('#u_require_dtr').prop( "checked", true );
         }
         else{
            $('#u_require_dtr').prop( "checked", false );
         }

      e.preventDefault();
      Swal.fire({
            title: "Would you like to update the employee details?",
            // text: "Would you like to request a schedule change?",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-floppy-disk fa-xs text-dark"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, update!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
      }).then(function(result) {

            if (result.value) {
               
               Swal.fire({
                  width: "350",
                  title: 'Updating...',
                  icon: "question",
                  customClass: {
                        icon: 'no-border'
                  },
                  showClass: {
                        backdrop: 'swal2-noanimation', // disable backdrop animation
                        popup: '',                     // disable popup animation
                        icon: ''                       // disable icon animation
                  },
                  iconHtml: '<i class="fa-solid fa-spinner fa-spin-pulse fa-xs"></i>',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1500,
               }).then(function(){
                  $("#update_employee_form").unbind('submit').submit();
               });

            } else if (result.dismiss === "cancel") {
               
            }
      });
   });
});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}