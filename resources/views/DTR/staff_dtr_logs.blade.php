@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
{{-- <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/css/custom/payslip_modal.css') }}" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Staff DTR Logs')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <form action="{{ route('staff_dtr_logs') }}" method="get">
            {{-- @csrf --}}
            <div class="row">

               <div class="col-lg-6 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Staff</label>
                     <select id="" name="emp_id" class="border form-control custom-select selectpicker" data-live-search="true" required>
                           <option selected disabled value="">Select Staff</option>
                           @foreach($employees as $row)
                              <option value="{{ $row->UserID_Empl }}" {{ (app('request')->input('emp_id') == $row->UserID_Empl) ? ' selected' : '' }}>{{ $row->Name_Empl }}</option>
                           @endforeach    
                     </select>
                     </div>
               </div>
               
               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">From: <i class="text-small text-danger">*</i></label>
                        <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from" required>
                  </div>
               </div>

               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">To: <i class="text-small text-danger">*</i></label>
                        <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to" required>
                  </div>
               </div>

               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="hide" style="visibility: hidden">Search Button</label>
                     @include('button_component.search_button', ['margin_top' => "1"])
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>

   <hr>

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-lg-4 col-sm-12">
               <h4 class="card-title">Staff DTR Logs <small>({{ count($attendance1) }})</small></h4>
            </div>
            <div class="col-lg-4 col-sm-12">
            {{-- <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names.."> --}}
            </div>
            <div class="col-lg-4 col-sm-12 text-right">
                  
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="text-center">Date</th>
                     <th class="text-center">In</th>
                     <th class="text-center">Out</th>
                     <th class="text-center">OT</th>
                     <th class="text-center">NP</th>
                     <th class="text-center">Hrs. Late</th>
                     <th class="text-center">Undertime</th>
                     <th class="text-center">Hrs. Break</th>
                     <th class="text-center">Total hrs.</th>
                     <th>Shift Code Schedule</th>
                     <th>Remarks</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
                  @if(count($attendance1))
                     <?php $ctr = 0; ?>
                     @foreach($attendance1 as $row)
                        <tr>
                           <td>{{date('M d, Y',strtotime($row['dtr_date']))}}</td>
                           <td class="text-center">{{ $row['in_am'] ? date('H:i',strtotime($row['in_am'])) : '' }}</td>
                           <td class="text-center">{{ $row['out_pm'] ? date('H:i',strtotime($row['out_pm'])) : '' }}</td>
                           <td class="text-center">{{ $row['ot_hours'] == 0 ? "" : $row['ot_hours'] }}</td>
                           <td class="text-center">{{ $row['np_hours'] == 0 ? "" : $row['np_hours']}}</td>
                           <td class="text-center">{{ $row['late'] == 0 ? "" : $row['late']}}</td>
                           <td class="text-center">{{ $row['undertime'] == 0 ? "" : $row['undertime']}}</td>
                           <td class="text-center">{{ $row['checker'] ? $row['break_hours'] : "" }}</td>
                           <td class="text-center">{{ $row['checker'] ? $row['total_hours_worked'] : "" }}</td>
                           <td>
                              @if($row['checker'] == 1)
                                 @if($row['allow_update'] == 1)
                                    <i class="fa-solid fa-pen-to-square shift_view_modal mr-2"
                                    title="Update" 
                                    style="cursor: pointer"
                                    data-shift_code="{{ $row['shift_sched'] }}"
                                    data-shift_code_id="{{ $row['shift_code_id'] }}"
                                    data-shift_monitoring_id="{{ $row['shift_monitoring_id'] }}"
                                    data-shift_brk_hrs="{{ ($row['break_hours']) }}"
                                    data-shift_status="{{ ($row['shift_monitoring_status'] == 1) ? ($row['is_active'] === 1) ? "Approved" : "Deleted" : "Pending" }}"
                                    data-shift_date="{{ ($row['shift_date']) ? date('m/d/Y', strtotime($row['shift_date'])) : '' }}"
                                    ></i>
                                 @else
                                    <i class="fa-solid fa-ellipsis" title="No action"></i>
                                 @endif
                              @endif
                              {{ $row['shift_sched'] }}
                              @if($row['is_active'] === 0)
                                 <span class="badge badge-danger text-sm" title="The shift code has been deleted. Please report to your admin or update the shift code.">Shift code has been deleted</span>
                              @endif
                           </td>
                           <td class="">{{ ucwords(strtolower($row['att_type'])) }}</td>
                        </tr>
                        <?php $ctr = $ctr + 1; ?>
                     @endforeach
                  @else
                     <tr><td class="text-center" colspan="12">No record found</td></tr>
                  @endif
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <hr>
</div>
<!-- /.container-fluid -->


{{-- SHIFT VIEW MODAL --}}
<div class="modal fade" id="shift_view_modal" tabindex="-1" aria-labelledby="shift_view_modalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="shift_view_modalLabel">Change Schedule</h5>
               <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
           </div>
           <form id="approver_change_schedule" action="{{ route('approver_change_schedule') }}" method="POST">
               @csrf
               <div class="modal-body">
                   
                       <center><h5 class="modal-title" id="shift_date_modal"></h5></center>
                       {{-- <a class="btn btn-link text-info" id="change_sched_id">Change Schedule</a> <i class="fa fa-times text-danger" aria-hidden="true" style="cursor: pointer" title="Cancel"></i> --}}
                       {{-- <input type="date" class="form-control" name="shift_date" id="change_date_to" value="" required/> --}}
                       <label class="control-label mt-2">Shift Code</label>
                       <select id="shift_code" name="shift_code" class="form-control custom-select">
                           {{-- <option value="" id="def_shift_code"></option> --}}
                           @foreach($shift_codes as $i => $sc)
                               <option value="{{ $sc->id }}">{{ $sc->shift_code }} - {{ $sc->Description }}</option>
                           @endforeach
                       </select>
                       <label class="control-label mt-2">Reason</label>
                       <textarea id="remarks_reason_text" class="form-control" name="remarks" placeholder="Type in your message" rows="5" maxlength="100" required></textarea>
                       <h6 class="pull-right mt-1" id="count_message"></h6>

                       <input type="hidden" name="shift_monitoring_id" id="shift_monitoring_id" />
                   
                   <br>
                  <table class="display nowrap table table-sm table-hover table-bordered" cellspacing="0" width="100%">
                     <thead>
                        <tr>
                           <th class="">Shift Code</th>
                           <th class="text-center">No. Hrs Break</th>
                           <th class="text-center">Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td> <small id="shift_code_modal"></small></td>
                           <td class="text-center"> <small id="shift_brk_hrs_modal"></small></td>
                           <td class="text-center"> <small id="shift_status_modal"></small></td>
                        </tr>
                     </tbody>

                  </table>
               </div>
               <div class="modal-footer">
                   <button class="btn btn-dark" type="submit"><i class="fa-solid fa-floppy-disk"></i> Update</button>
               </div>
           </form>
       </div>
   </div>
</div>
{{-- END SHIFT VIEW MODAL --}}

@endsection

{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>

$(document).ready(function(){

   let text_max = 100;
   $('#count_message').html(text_max + ' remaining');
   $('#remarks_reason_text').keyup(function() {
      var text_length = $('#remarks_reason_text').val().length;
      var text_remaining = text_max - text_length;
      $('#count_message').html(text_remaining + ' remaining');
   });  
   
   // View shift modal
   $('.shift_view_modal').click(function(){

      $('#shift_date_modal').text(formatDateAbaca(new Date($(this).data('shift_date'))));
      $('#shift_code_modal').text($(this).data('shift_code'));
      $('#shift_brk_hrs_modal').text($(this).data('shift_brk_hrs'));
      $('#shift_status_modal').text($(this).data('shift_status'));
      $('#shift_monitoring_id').val($(this).data('shift_monitoring_id'));
      // $('#shift_date_id').val($(this).data('shift_date'));
      $("#shift_code").val($(this).data('shift_code_id')).change();

      $('#shift_view_modal').modal('show');
   });


   $("#approver_change_schedule").submit(function(e){
      e.preventDefault();
      Swal.fire({
         title: "Would you like to update the schedule?",
         // text: "Would you like to request a schedule change?",
         icon: "question",
         showCancelButton: true,
         confirmButtonColor: "#222222",
         confirmButtonText: "Yes, continue!",
         cancelButtonText: "No, cancel!",
         cancelButtonColor: "#d9534f",
         allowOutsideClick: false,
         allowEscapeKey: false,
         reverseButtons: true
      }).then(function(result) {
         if (result.value) {
               $("#approver_change_schedule").unbind('submit').submit();
         } else if (result.dismiss === "cancel") {
               
         }
      });
   });

});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}} 