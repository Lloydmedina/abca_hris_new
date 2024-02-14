@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
{{-- <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"> --}}
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Shift Code Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Shift Code List</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="text-center">Shift Code</th>
                     <th class="text-center">Graveyard</th>
                     <th class="">Description</th>
                     <th class="">Time Start</th>
                     <th class="">Time End</th>
                      <th class="">Is FlexTIme</th>
                     <th class="">Total Hrs</th>
                     <th class="">Break Hrs</th>
                     <th class="">Remarks</th>
                     <th class="">Status</th>
                     <th colspan="2" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="list_body" name="list">
                  @foreach($list as $row)
                  <tr>
                        <td class="text-center">
                        {{ $row->shift_code }}
                        </td>
                        <td class="text-center">
                           {{ ($row->is_grave_shift == 1) ? "Graveyard" : "" }}
                        </td>
                        <td class="">
                        {{ $row->Description }}
                        </td>
                        <td class="">
                           {{ $row->time_start }}
                        </td>
                        <td class="">
                           {{ $row->time_end }}
                        </td>
                        <td class="">
                           {{ $row->flex_time }}
                        </td>
                        <td class="">
                           {{ $row->total_hrs }}
                        </td>
                     <td class="">
                        {{ $row->default_break_hrs }}
                     </td>
                     <td class="">
                        {{ $row->Remarks }}
                     </td>
                     <td class="">
                        @if($row->is_active == 1)
                           <span class='badge badge-success'>Active</span>
                        @else
                           <span class='badge badge-danger'>Deleted</span>
                        @endif
                     </td>
                     <td class="text-center">
                        <div class="w-100">
                           <span class="text-dark update_shift_code" title="Update shift code" style="cursor: pointer;" 
                              data-id="{{ $row->id }}" 
                              data-shift_code="{{ $row->shift_code }}"
                              data-time_start="{{ $row->time_start }}"
                              data-time_end="{{ $row->time_end }}"
                              data-total_hrs="{{ $row->total_hrs }}"
                              data-flex_time="{{ $row->flex_time }}"
                              data-default_break_hrs="{{ $row->default_break_hrs }}"
                              data-description="{{ $row->Description }}"
                              data-remarks="{{ $row->Remarks }}"
                              data-is_grave_shift="{{ $row->is_grave_shift }}"
                              >
                              <span class="fa-solid fa-pen-to-square"></span>
                           </span>
                        </div>
                    </td>

                    <td class="text-center">
                        <div class="w-100">
                           @if($row->is_active == 1)
                              <span class="text-danger shift_delete_modal" title="Delete shift code" style="cursor: pointer;"
                                 data-delete_url="{{ url('/shift-code/delete/'.$row->id.'') }}"
                                 data-title="{{ $row->shift_code ?? ''}}"
                                 data-time_start="{{ $row->time_start }}"
                                 data-time_end="{{ $row->time_end }}"
                                 >
                                 <span class="fa-solid fa-trash-can"></span>
                              </span>
                           @else
                              <span class="shift_restore_modal" title="Restore shift code" style="cursor: pointer;"
                                 data-restore_url="{{ url('/shift-code/restore/'.$row->id.'') }}"
                                 data-title="{{ $row->shift_code ?? ''}}"
                                 data-time_start="{{ $row->time_start }}"
                                 data-time_end="{{ $row->time_end }}"
                                 >
                                 <i class="fa-solid fa-rotate-left"></i>
                              </span>
                           @endif
                        </div>
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
            <h4 class="modal-title">New Shift Code Entry</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/save_shift_code') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Shift Code</label>
                                <input type="text" id="shift_code" name="shift_code"  class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                           <div class="form-group">
                               <label class="control-label">Graveyard</label>
                               <input style="width: 17px;height: 17px;" class="form-control" name="is_grave_shift" value="1" type="checkbox" />
                           </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <input type="text" id="Description" name="Description"  class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Time Start</label>
                                <input type="time" id="time_start" name="time_start"  class="form-control" required onchange="get_diff()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Time End</label>
                                <input type="time" id="time_end" name="time_end" class="form-control" required onchange="get_diff()">
                            </div>
                        </div>
                     <!--/span-->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label">FLEXTIME</label>
                                  <select id="flex_time" name="flex_time" class="form-control custom-select" required="">
                                  <option value="NO" @if (old('flex_time') == "YES") {{ 'selected' }} @endif>NO</option>
                                  <option value="YES" @if (old('flex_time') == "YES") {{ 'selected' }} @endif>YES</option>
                                  </select>
                              </div>
                        </div>
                    </div>
                    <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Total Hours</label>
                           <input type="number" id="total_hrs" name="total_hrs"  class="form-control" required step="any">
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Break Hours</label>
                           <input type="number" id="default_break_hrs" name="default_break_hrs"  class="form-control" required step="any">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="Remarks" name="Remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
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
<div id="update_shift_code_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelUpdate" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Update Shift Code</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_shift_code') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" name="id" id="shift_code_id_to_update" hidden>
                                <label class="control-label">Shift Code</label>
                                <input type="text" id="u_shift_code" name="shift_code"  class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                               <label class="control-label">Graveyard</label>
                               <input style="width: 17px;height: 17px;" class="form-control" id="u_is_grave_shift" name="is_grave_shift" value="1" type="checkbox" />
                           </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <input type="text" id="u_description" name="Description"  class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Time Start</label>
                                <input type="time" id="u_time_start" name="time_start"  class="form-control" required onchange="get_diff()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Time End</label>
                                <input type="time" id="u_time_end" name="time_end" class="form-control" required onchange="get_diff()">
                            </div>
                        </div>
                     <!--/span-->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                              <div class="form-group">
                                  <label class="control-label">FLEXTIME</label>
                                  <select id="u_flex_time" name="flex_time" class="form-control custom-select" required="">
                                  <option value="NO" @if (old('flex_time') == "NO") {{ 'selected' }} @endif>NO</option>
                                  <option value="YES" @if (old('flex_time') == "YES") {{ 'selected' }} @endif>YES</option>

                                  </select>
                              </div>
                        </div>
                    </div>

                    <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Total Hours</label>
                           <input type="number" id="u_total_hrs" name="total_hrs"  class="form-control" required step="any">
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Break Hours</label>
                           <input type="number" id="u_default_break_hrs" name="default_break_hrs"  class="form-control" required step="any">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="u_remarks" name="Remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2"><i class="fa-solid fa-floppy-disk"></i> Update</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END UPDATE MODAL --}}

{{-- SHIFT CODE DELETE MODAL --}}
<div class="modal fade" id="shift_delete_modal" tabindex="-1" aria-labelledby="shift_delete_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="shift_delete_modalLabel">Are you sure?</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body text-center">
            <p>Are you sure you want to delete this shift code? <br>
            You CAN NOT view this shift code in your list anymore if you delete.</p>
   
            <h5 class="modal-title" id="shift_delete_title_modal"></h5>
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-dismiss="modal">No, Don't Delete</button>
            <a href="" id="btn-delete-shift-code-button" class="btn btn-dark" type="button">Yes, Delete</a>
         </div>
      </div>
   </div>
</div>
{{-- END SHIFT CODE DELETE MODAL --}}

{{-- SHIFT CODE RESTORE MODAL --}}
<div class="modal fade" id="shift_restore_modal" tabindex="-1" aria-labelledby="shift_restore_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="shift_restore_modalLabel">Are you sure?</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body text-center">
            <p>Are you sure you want to restore this shift code? <br>
            You CAN view this shift code in your list anymore if you restore.</p>
   
            <h5 class="modal-title" id="shift_restore_title_modal"></h5>
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-dismiss="modal">No, Don't Restore</button>
            <a href="" id="btn-restore-shift-code-button" class="btn btn-dark" type="button">Yes, Restore</a>
         </div>
      </div>
   </div>
</div>
{{-- END SHIFT CODE RESTORE MODAL --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
{{-- <script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script> --}}
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
{{-- <script src="{{ asset('uidesign/js/custom/department.js') }}"></script> --}}
<script>
   function get_diff() {
      var number_of_hours = 0;
      
      var start ="00:00";
      var end ="00:00";

      try {
         start = $('#time_start').val();
         end = $('#time_end').val();
      } catch(e) {

      }

      try {
         start = start.split(":");
         end = end.split(":");
         var startDate = new Date(0, 0, 0, start[0], start[1], 0);
         var endDate = new Date(0, 0, 0, end[0], end[1], 0);
         var diff = endDate.getTime() - startDate.getTime();
         var hours = Math.floor(diff / 1000 / 60 / 60);
         diff -= hours * 1000 * 60 * 60;
         var minutes = Math.floor(diff / 1000 / 60);
         // if(hours!=0){
         //   hours = hours - 1;
         // }
         
         minutes = minutes / 60;

         // If using time pickers with 24 hours format, add the below line get exact hours
         if (hours < 0){
            hours = hours + 24;
         }
         if (minutes==0) {
            number_of_hours = hours;
         }
         else
         number_of_hours = parseFloat( (hours + minutes) ).toFixed(2);
      } catch(e) {
         
      }
      if(isNaN(number_of_hours)){
         $('#total_hrs').val(0);
      }else{
         $('#total_hrs').val(number_of_hours);
      }
   }

   $(document).ready(function() {

      // update outlet modal
      $('.update_shift_code').click(function(){

         let is_grave_shift = $(this).data('is_grave_shift');

         // Set values
         $('#shift_code_id_to_update').val($(this).data('id'));
         $('#u_shift_code').val($(this).data('shift_code'));
         $('#u_time_start').val($(this).data('time_start'));
         $('#u_time_end').val($(this).data('time_end'));
         $('#u_total_hrs').val($(this).data('total_hrs'));
         $('#u_flex_time').val($(this).data('flex_time'));
         $('#u_default_break_hrs').val($(this).data('default_break_hrs'));
         $('#u_description').val($(this).data('description'));
         $('#u_remarks').val($(this).data('remarks'));

         if(is_grave_shift == 1 || is_grave_shift == '1'){
            $('#u_is_grave_shift').prop( "checked", true );
         }
         else{
            $('#u_is_grave_shift').prop( "checked", false );
         }
         

         $('#update_shift_code_modal').modal({backdrop: 'static', keyboard: true});
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