@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Line Leader Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Line Leader List</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="text-center">Line Leader</th>
                     <th class="text-center">Departments</th>
                     <th class="text-center">Remarks</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <th class="text-center">Line Leader</th>
                     <th class="text-center">Departments</th>
                     <th class="text-center">Remarks</th>
                     <th class="text-center">Action</th>
                  </tr>
               </tfoot>
               <tbody id="list_body" name="list">
                  @foreach($list as $row)
                  <tr>
                     <td class="text-left">
                        {{ $row->line_leader }}
                     </td>
                     <td>
                        
                     </td>
                     <td class="text-left">
                        {{ $row->remarks }}
                     </td>
                     <td>
                        
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
            <h4 class="modal-title">New Line Leader</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_holiday_setup') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee</label>
                           <select id="Employee" name="Employee" {{ (session('user')->employee_type_id == 5 || session('user')->employee_type_id == 6) ? 'disabled' : '' }} }}  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Employee</option>
                              @foreach($employees as $row)
                                 @if(session('user')->employee_type_id == 5 || session('user')->employee_type_id == 6)
                                    <option value="{{ $row->SysPK_Empl }}" {{ (session('employee')->SysPK_Empl == $row->SysPK_Empl) ? 'selected' : '' }}>{{ $row->Name_Empl }}</option>
                                 @else
                                    <option value="{{ $row->SysPK_Empl }}">{{ $row->Name_Empl }}</option>
                                 @endif
                              @endforeach
                           </select>
                        </div>
                     </div>

                     <div class="col-md-6">
                         <label class="control-label">Remarks</label>
                         <input type="text" class="form-control" id='remarks' name="remarks">
                     </div>
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