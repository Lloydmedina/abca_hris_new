@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Disciplinary Action Form')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">List of all Disciplinary Action</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Date</th>
                     <th class="">From</th>
                     <th class="">To</th>
                     <th class="">Type of Offense</th>
                     <th class="">Prepared By</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tfoot>
                  <th class="">Date</th>
                     <th class="">From</th>
                     <th class="">To</th>
                     <th class="">Type of Offense</th>
                     <th class="">Prepared By</th>
                     <th class="text-center">Action</th>
               </tfoot>
               <tbody id="list_body" name="list">
                  @foreach($disciplinary_action as $row)
                  <tr>
                     <td>{{ date('M d, Y', strtotime($row->date))}}</td>
                     <td>{{ $row->from_emp_name }}</td>
                     <td>{{ $row->to_emp_name }}</td>
                     <td>{{ $row->type_of_offense }}</td>
                     <td>{{ $row->prepared_by }}</td>
                     <td class="text-center">
                           <a href="{{ url('/disciplinary-action-delete?id='.$row->id.md5($row->id) ) }}" title="Delete" class="mr-1" onclick="return confirm('Delete form?')">
                              <i class="text-danger fas fa-trash"></i>
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
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Disciplinary Action Form</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ route('disciplinary_action.save') }}" method="POST">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                    <div class="col-md-12">
                        <div class="form-group">
                           <input type="hidden" id="to_emp_name" name="to_emp_name" class="form-control">
                            <label class="control-label">To</label>
                            <select id="to_emp_id" name="to_emp_id"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                                <option selected disabled value="">Select Employee</option>
                                @foreach($incident_report as $row)
                                    <option value="{{ $row->emp_id }}" to_emp_name="{{ $row->emp_name }}" from_emp_id="{{ $row->emp_mngr_id }}" from_emp_name="{{ $row->emp_mngr_name }}">{{ $row->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">From</label>
                           <input type="hidden" id="from_emp_id" name="from_emp_id" class="form-control">
                           <input type="text" id="from_emp_name" name="from_emp_name" class="form-control" readonly>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-12">
                        <div class="form-group has-danger">
                           <label class="control-label">Date</label>
                           <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Re</label>
                            <input type="text" id="re" name="re" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Type of offense</label>
                            <input type="text" id="type_of_offense" name="type_of_offense" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Level of disciplinary action</label>
                            <textarea id="lvl_of_disciplinary" name="lvl_of_disciplinary" class="form-control" rows="3">{{ old('lvl_of_disciplinary') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Impact of action on section/department</label>
                            <textarea id="impact_of_action" name="impact_of_action" class="form-control" rows="3">{{ old('impact_of_action') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Statement of expectations & any follow up (If any)</label>
                            <textarea id="statement_of_expectation" name="statement_of_expectation" class="form-control" rows="3">{{ old('statement_of_expectation') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Consequence of future behavior</label>
                            <textarea id="consequence" name="consequence" class="form-control" rows="3">{{ old('consequence') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Prepared By</label>
                            <input type="text" id="prepared_by" name="prepared_by" value="{{ old('prepared_by') }}" class="form-control" required>
                        </div>
                    </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2"><i class="fa-solid fa-floppy-disk"></i> Save</button>
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
<script src="{{ asset('uidesign/js/custom/disciplinary_action.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}