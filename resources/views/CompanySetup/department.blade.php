@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Department Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">List of all departments</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Code</th>
                     <th class="">Department</th>
                     <th class="">Location</th>
                     <th class="">Dept. Head</th>
                     <th class="">Asst. Dept. Head</th>
                     <th class="">Remarks</th>
                     <th class="">Status</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tfoot>
                  <th class="">Code</th>
                  <th class="">Department</th>
                  <th class="">Location</th>
                  <th class="">Dept. Head</th>
                  <th class="">Asst. Dept. Head</th>
                  <th class="">Remarks</th>
                  <th class="">Status</th>
                  <th class="text-center">Action</th>
               </tfoot>
               <tbody id="list_body" name="list">
                  @foreach($department as $row)
                  <tr>
                     <td>{{ $row->DeptCode_Dept }}</td>
                     <td>{{ $row->Name_Dept }}</td>
                     <td>{{ $row->Location_Dept }}</td>
                     <td>{{ $row->Head_Dept }}</td>
                     <td>{{ $row->AssistantHead_Dept }}</td>
                     <td>{{ $row->Remarks_Dept }} </td>
                     <td>{{ $row->Status_Dept }}</td>
                     <td class="text-center">
                        <a href="#!" onclick="get_dept_id('{{ $row->SysPK_Dept.md5($row->SysPK_Dept) }}', '{{$row->DeptCode_Dept}}','{{ $row->Name_Dept}}','{{ $row->Location_Dept }}','{{$row->Head_Dept_ID.md5($row->Head_Dept_ID)}}', '{{ $row->Position_Dept_ID.md5($row->Position_Dept_ID)}}', '{{$row->AssistantHead_Dept_ID.md5($row->AssistantHead_Dept_ID)}}','{{ $row->AssistPosition_Dept_ID.md5($row->AssistPosition_Dept_ID)}}', '{{ $row->Remarks_Dept }}')" class="text-primary" data-toggle="modal" data-target=".update_modal" style="cursor: pointer;">
                        <span class="fa-solid fa-pen-to-square"></span>
                        </a>
                     </td>
                  </tr>
                  @endforeach
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
            <h4 class="modal-title">New Department</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_department') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Department Code</label>
                           <input type="text" id="" name="DepartmentCode" value="{{ old('DepartmentCode') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Department</label>
                           <input type="text" id="" name="Department" value="{{ old('Department') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Location</label>
                           <input type="text" id="" name="Location" value="{{ old('Location') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Head Department</label>
                           <select id="" name="Head_Dept"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Head Department</option>
                              @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl.md5($row->UserID_Empl) }}">{{ $row->Name_Empl }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Dept. Head Position</label>
                           <select id="" name="Position_Dept" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Head Position</option>
                              @foreach($employees_rate as $row)
                              <option value="{{ $row->id.md5($row->id) }}">{{ $row->Position_Empl }}</option>
                              @endforeach  
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Asst. Head Department</label>
                           <select id="" name="AssistantHead_Dept" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Asst. Head Department</option>
                              @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl.md5($row->UserID_Empl) }}">{{ $row->Name_Empl }}</option>
                              @endforeach    
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Asst. Dept. Position</label>
                           <select id="" name="AssistPosition_Dept" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Head Position</option>
                              @foreach($employees_rate as $row)
                              <option value="{{ $row->id.md5($row->id) }}">{{ $row->Position_Empl }}</option>
                              @endforeach   
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="" name="remarks" class="form-control" rows="3"></textarea>
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
<div id="" class="update_modal modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelUpdate" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-info text-white">
            <h4 class="modal-title">Update Department</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_department') }}" method="post">
            @csrf
            <input type="hidden" name="SysPK_Dept" id="SysPK_Dept">
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Department Code</label>
                           <input type="text" id="DepartmentCode" name="DepartmentCode" value="{{ old('DepartmentCode') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Department</label>
                           <input type="text" id="Department" name="Department" value="{{ old('Department') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Location</label>
                           <input type="text" id="Location" name="Location" value="{{ old('Location') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Head Department</label>
                           <select id="Head_Dept" name="Head_Dept"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl.md5($row->UserID_Empl) }}">{{ $row->Name_Empl }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Dept. Head Position</label>
                           <select id="Position_Dept" name="Position_Dept" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              @foreach($employees_rate as $row)
                              <option value="{{ $row->id.md5($row->id) }}">{{ $row->Position_Empl }}</option>
                              @endforeach  
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Asst. Head Department</label>
                           <select id="AssistantHead_Dept" name="AssistantHead_Dept" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl.md5($row->UserID_Empl) }}">{{ $row->Name_Empl }}</option>
                              @endforeach    
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Asst. Dept. Position</label>
                           <select id="AssistPosition_Dept" name="AssistPosition_Dept" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              @foreach($employees_rate as $row)
                              <option value="{{ $row->id.md5($row->id) }}">{{ $row->Position_Empl }}</option>
                              @endforeach   
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="remarks" name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-check"></i> Update</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
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