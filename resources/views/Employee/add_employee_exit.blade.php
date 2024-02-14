@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/update_employee.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Employee Exit Form')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->

<div class="container-fluid">

   @include('Templates.alert_message')
   

   @if($employee)
      <div class="" style="min-height: 750px;">
         <a href="{{ url('/employee_exit_list') }}" class="btn btn-sm btn-dark mb-2" onclick="return confirm('Are you sure you want to go back?')">
         <i class="fas fa-backspace"></i> Back
         </a>
         <form id="update_employee_form" class="form-material" action="{{ route('add_employee_exit') }}" method="post" enctype="multipart/form-data">
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
                              <h2>Profile Photo</h2>
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
                     </ul>
                     <!-- Tab panes -->
                     <div class="tab-content">
                        <!--Person tab-->
                        <div class="tab-pane active show" id="PersonProfile" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-8">
                                 <div class="row">
                                 <div class="col-sm-3">
                                       <label class="control-label">First Full Name:</label>
                                 </div>
                                 <div class="col-sm-9">
                                    <div class="form-group">
                                      {{ $employee->FirstName_Empl }} {{ $employee->MiddleName_Empl }} {{ $employee->LastName_Empl }}
                                    </div>
                                 </div>
                               
                              </div>
                              <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Address:</label>
                                    </div>
                                 </div>
                                 <div class="col-md-9">
                                    <div class="form-group">
                                       {{ $employee->Address_Empl }}
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Gender:</label>
                                    </div>
                                 </div>
                                 <div class="col-md-9">
                                    <div class="form-group">
                                       {{$employee->gender}}
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Civil Status:</label>
                                    </div>
                                 </div>
                                 <div class="col-md-9">
                                    <div class="form-group">
                                       {{$employee->civilStatus }}
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Birthday:</label>
                                    </div>
                                 </div>
                                 <div class="col-md-9">
                                    <div class="form-group">
                                       {{$employee->BirthDate_Empl }}
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Email & Contact Number:</label>
                                    </div>
                                 </div>
                                 <div class="col-md-9">
                                    <div class="form-group">
                                    {{ ($employee->email ) == NULL ? "N/A" : "$employee->email" }} | {{($employee->contact_no) == NULL ? "NULL" : "$employee->contact_no" }}
                                    </div>
                                 </div>
                              </div>

                              <div class="row">

                              </div>
                                 </div>
                                 <div class="col-md-4">
                                     <b>Employment Info. :</b>
                                     <div class="row">
                                       <div class="col-md-12">
                                          <div class="card border border-1">
                                             <div class="card-body">
                                             <div class="col-sm-12">
                                                <!-- <?php 
                                                   var_dump($employee);
                                                   ?> -->
                                                <table>
                                                   <tr>
                                                      <td>Employee Level :</td>
                                                      <td>{{$employee->emp_lvl}}</td>
                                                   </tr>
                                                   <tr>
                                                      <td>Status :</td>
                                                      <td>{{$employee->Status_Empl}}</td>
                                                   </tr>
                                                   <tr>
                                                      <td>Company :</td>
                                                      <td>{{$employee->company}}</td>
                                                   </tr>
                                                   <tr>
                                                      <td>Outlet :</td>
                                                      <td>{{$employee->outlet}}</td>
                                                   </tr>
                                                   <tr>
                                                      <td>Department :</td>
                                                      <td>{{$employee->Status_Empl}}</td>
                                                   </tr>
                                                   <tr>
                                                      <td>Position :</td>
                                                      <td>{{$employee->Position_Empl}}</td>
                                                   </tr>
                                                </table>
                                             </div>
                                            
                                             
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="accordion col-md-12" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#formone" aria-expanded="true" aria-controls="collapseOne">
          <h4>Exit Questioner Form</h4>
        </button>
      </h2>
    </div>

    <div id="formone" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body"> 
      <div class="row">   
      <div class="col-md-12">
         <h5><b>RESIGNATION</b></h5>
         </div>
         <div class="col-md-6">
         
            <!-- <input class="" {{ ($employee->employee_reimbursement == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $employee->employee_reimbursement }}" id="employee_reimbursement" name="employee_reimbursement" {{ (old('employee_reimbursement') == 1) ? ' checked' : '' }} />  -->
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Took another position</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Pregnancy/Home/Family needs</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Poor health/Physical disability</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Relocation to another city</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Travel difficulties</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">To attend school</label>
            </div>
            <div class="col-sm-12">
                <input type="checkbox"/>
                  <label class="control-label">Others (specify)</label>
                  <div class="col-md-6">
                  <div class="form-group">
                     <input type="text"  class="form-control" >
                  </div>
               </div>
  
            </div>

         </div>
         <div class="col-md-6">
            <!-- <input class="" {{ ($employee->employee_reimbursement == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $employee->employee_reimbursement }}" id="employee_reimbursement" name="employee_reimbursement" {{ (old('employee_reimbursement') == 1) ? ' checked' : '' }} />  -->
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Dissatisfaction with salary</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Dissatisfaction with type of work</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Dissatisfaction with supervisor</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Dissatisfaction with co-workers</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Dissatisfaction with working conditions</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Dissatisfaction with benefits</label>
            </div>
         </div>
        </div>
        <hr />
         
      <div class="row">

         <div class="col-md-6">
         <div class="col-sm-12">
         <h5><b>LAID OFF</b></h5>
         </div>   
         <!-- <input class="" {{ ($employee->employee_reimbursement == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $employee->employee_reimbursement }}" id="employee_reimbursement" name="employee_reimbursement" {{ (old('employee_reimbursement') == 1) ? ' checked' : '' }} />  -->
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Lack of work</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Abolition of work</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Lack of funds</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Others (specify)</label>
            <div class="col-md-6">
                  <div class="form-group">
                     <input type="text"  class="form-control" >
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6">
         <div class="col-sm-12">
         <h5><b>RETIREMENT</b></h5>
         </div>   
            <!-- <input class="" {{ ($employee->employee_reimbursement == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $employee->employee_reimbursement }}" id="employee_reimbursement" name="employee_reimbursement" {{ (old('employee_reimbursement') == 1) ? ' checked' : '' }} />  -->
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Voluntary retirement</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Disability retiremebn</label>
            </div>
            <div class="col-sm-12">
            <input type="checkbox"/>
            <label class="control-label">Regular retirement</label>
            </div>
         </div>
        </div>
        <hr />
        <div class="row">
               <div class="col-md-12">
                     <label>Have you accepted another position? </label>
                     <label>Yes</label>
                     <input type="checkbox"/>
                     <label>No</label>
                     <input type="checkbox"/>
               </div>
            
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">If yes, where?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label">Present Title</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label">New Title</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label">Present salary</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label">New salary</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">Additional Fringe Benefits offered by new employer:</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">How long ago did you begin searching for another job?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
               <hr />
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">1. What incident or circumstance(s) made you begin looking for another job?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">2. What were the reasons you decided your career goals not be met here or could be better met somewhere else?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">3. Did you speak with your supervisor or anyone else in management or the Administration Office concerning your career goals?</label>
                  </div>
                  <div class="col-md-12">
                  <label>Yes</label>
                     <input type="checkbox" />
                     <label>No</label>
                     <input type="checkbox"/>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">4. If the answer to 3 above was Yes, what was the outcome of this conversation?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">5. If tge answer to 3 above was No, why not?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">6. Did you get along well with your supervisor? </label>
                  </div>
                  <div class="col-md-12">
                  <label>Yes</label>
                     <input type="checkbox" />
                     <label>No</label>
                     <input type="checkbox"/>
                  </div>
                  <label class="control-label">If No, please explain: </label>
                  <div class="form-group">
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">7. How well did your supervisor handle any complaints or grievances you may have had?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">8. What could have been done to make your job here more rewarding?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">9. What did you like best about your job?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">10. What did you dislike about your job?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">11. What makes the ABACA Group a good place to work?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">12. What makes the ABACA Group a poor place to work?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">13. How would you rate the following: </label>
                  </div>
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="form-group">
                           <label class="control-label">Job responsibilities?</label>
                           <select id="gender" name="gender" class="form-control custom-select" required="">
                           <option value="">Outstanding</option>
                           <option value="">Very Good</option>
                           <option value="">Satisfactory</option>
                           <option value="">Fair</option>
                           <option value="">Unsatisfactory</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                           <label class="control-label">Opportunity for acheaving goals?</label>
                           <select id="gender" name="gender" class="form-control custom-select" required="">
                           <option value="">Outstanding</option>
                           <option value="">Very Good</option>
                           <option value="">Satisfactory</option>
                           <option value="">Fair</option>
                           <option value="">Unsatisfactory</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                           <label class="control-label">Work environment?</label>
                           <select id="gender" name="gender" class="form-control custom-select" required="">
                           <option value="">Outstanding</option>
                           <option value="">Very Good</option>
                           <option value="">Satisfactory</option>
                           <option value="">Fair</option>
                           <option value="">Unsatisfactory</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                           <label class="control-label">Supervisor?</label>
                           <select id="gender" name="gender" class="form-control custom-select" required="">
                           <option value="">Outstanding</option>
                           <option value="">Very Good</option>
                           <option value="">Satisfactory</option>
                           <option value="">Fair</option>
                           <option value="">Unsatisfactory</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                           <label class="control-label">Pay?</label>
                           <select id="gender" name="gender" class="form-control custom-select" required="">
                           <option value="">Outstanding</option>
                           <option value="">Very Good</option>
                           <option value="">Satisfactory</option>
                           <option value="">Fair</option>
                           <option value="">Unsatisfactory</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                           <label class="control-label">Benefits?</label>
                           <select id="gender" name="gender" class="form-control custom-select" required="">
                           <option value="">Outstanding</option>
                           <option value="">Very Good</option>
                           <option value="">Satisfactory</option>
                           <option value="">Fair</option>
                           <option value="">Unsatisfactory</option>
                           </select>
                        </div>
                     </div>


                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">14. What recommendation would you have for making your department and/or the Town a better place to work?</label>
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">15. Would you have stayed if a more-satisfactory arrangement could have been worked out?</label>
                  </div>
                  <div class="col-md-12">
                  <label>Yes</label>
                     <input type="checkbox" />
                     <label>No</label>
                     <input type="checkbox"/>
                  </div>
                  <label class="control-label">If Yes, please explain: </label>
                  <div class="form-group">
                     <input type="text"  class="form-control" >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">16. It has been explained to me that completion of this Exit interview form is voluntary and i was given the option not to complete this form if I so desired.</label>
                  </div>
                  <div class="col-md-12">
                  <label>Yes</label>
                     <input type="checkbox" />
                     <label>No</label>
                     <input type="checkbox"/>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">17. I autorize the placement of this Exit interview form in my personal file.</label>
                  </div>
                  <div class="col-md-12">
                  <label>Yes</label>
                     <input type="checkbox" />
                     <label>No</label>
                     <input type="checkbox"/>
                  </div>
               </div>


            </div>
</div>
         
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <h4>Employee Clearance Check List</h4>
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>

</div>
                              </div>
                           </div>
                        </div>
                  <!-- ----------END EMP INFO------- -->
                     </div>
                  </div>
               </div>
            </div>
            <div class="row p-t-20">
               <div class="col-md-12">
                  <div class="form-actions m-auto text-center">
                     <a href="{{ url('/employee_exit_list') }}" class="btn btn-danger mr-2 mt-4" onclick="return confirm('Are you sure you want to cancel?')"><i class="fa-solid fa-ban"></i> Cancel</a>
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