@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Incident Report Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')
   
   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">List of all Incident Report</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Date</th>
                     <th class="">Employee</th>
                     <th class="">Supervisor</th>
                     <th class="">Department</th>
                     <th class="">Type of Incident</th>
                     <th class="">Incident Details</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tfoot>
                  <th class="">Date</th>
                     <th class="">Employee</th>
                     <th class="">Supervisor</th>
                     <th class="">Department</th>
                     <th class="">Type of Incident</th>
                     <th class="">Incident Details</th>
                     <th class="text-center">Action</th>
               </tfoot>
               <tbody id="list_body" name="list">

               </tbody>
            </table>
         </div>
      </div>
   </div>
   
   <hr>
</div>
{{-- ENTRY MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">New Incident Report</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ route('incident_report.save') }}" method="POST">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Prepared By</label>
                           <input type="text" id="prepared_by" name="prepared_by" value="{{ old('prepared_by') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Date Prepared</label>
                           <input type="date" id="date_prepared" name="date_prepared" value="{{ old('date_prepared') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Contact Details</label>
                           <input type="text" name="contact_info" value="{{ old('contact_info') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Department</label>
                              <select id="dept_id" name="dept_id" class="form-control custom-select" required>
                                 @foreach($department as $row)
                                    @if(isset($_GET['dept_id']))
                                       @if($_GET['dept_id'] == $row->DeptCode_Dept )
                                          <option value="{{ $row->DeptCode_Dept }}" selected>{{ $row->Name_Dept }}</option>
                                       @else
                                          <option value="{{ $row->DeptCode_Dept }}">{{ $row->Name_Dept }}</option>
                                       @endif
                                    @else
                                       <option value="{{ $row->DeptCode_Dept }}">{{ $row->Name_Dept }}</option>
                                    @endif
                                    
                                 @endforeach
                              </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Employee</label>
                           <select id="emp_id" name="emp_id"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Employee</option>
                              @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl.md5($row->UserID_Empl) }}">{{ $row->Name_Empl }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Supervisor/Manager</label>
                           <select id="emp_mngr_id" name="emp_mngr_id"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Supervisor/Manager</option>
                              @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl.md5($row->UserID_Empl) }}">{{ $row->Name_Empl }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Incident Date</label>
                           <input type="date" id="incident_date" name="incident_date" value="{{ old('incident_date') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Incident Time</label>
                           <input type="time" id="incident_time" name="incident_time" value="{{ old('incident_time') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Type of Event</label>
                              <select id="type_of_event" name="type_of_event" class="form-control custom-select" required>
                                 <option value="Working Day">
                                    Working Day
                                 </option>
                                 <option value="Breaktime">
                                    Breaktime
                                 </option>
                                 <option value="Work-Related Trip">
                                    Work-Related Trip
                                 </option>
                              </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Type of Incident</label>
                              <select id="type_of_incident" name="type_of_incident" class="form-control custom-select" required>
                                 
                                 <option value="Accident">
                                    Accident
                                 </option>
                                 <option value="Injury">
                                    Injury
                                 </option>
                                 <option value="Physical Altercation">
                                    Physical Altercation
                                 </option>
                                 <option value="Verbal Altercation">
                                    Verbal Altercation
                                 </option>
                                 <option value="Damage To Property">
                                    Damage To Property
                                 </option>
                                 <option value="Theft">
                                    Theft
                                 </option>
                                 <option value="Fire">
                                    Fire
                                 </option>
                              </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Incident Details</label>
                           <textarea id="incident_details" name="incident_details" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>

                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Location</label>
                           <input type="text" id="location" name="location" value="{{ old('location') }}" class="form-control" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">External Involvement</label>
                           <textarea id="external_involvement" name="external_involvement" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Final Outcome</label>
                           <textarea id="final_outcome" name="final_outcome" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Future Prevention</label>
                           <textarea id="future_prevention" name="future_prevention" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Supplementary Information</label>
                           <textarea id="supplementary_information" name="supplementary_information" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Employee Verbal Explanation</label>
                           <textarea id="emp_explanation" name="emp_explanation" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Action Taken by Supervisor/Manager</label>
                           <textarea id="mngr_actions" name="mngr_actions" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Add</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END ENTRY MODAL --}}
{{-- UPDATE MODAL --}}

{{-- END UPDATE MODAL --}}
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/department.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}