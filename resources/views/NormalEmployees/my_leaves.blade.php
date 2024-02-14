@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>
   .inf-content{
   border:1px solid #DDDDDD;
   -webkit-border-radius:10px;
   -moz-border-radius:10px;
   border-radius:10px;
   box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);
}			                                                      
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','My Leaves')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')


   <div class="alert_message_js alert text-info fade show d-none" role="alert">
      <span id="alert_message_js"></span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
      </button>
   </div>


   <div class="card">
      <div class="card-body">
         <form action="{{ route('my_leave') }}" method="get">
            <div class="row">
               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                      <label class="control-label">From:</label>
                      <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from">
                  </div>
              </div>

              <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                      <label class="control-label">To:</label>
                      <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to">
                  </div>
              </div>

              <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                      <label for="ps_status_select">Status</label>
                      <select class="form-control" id="ps_status_select" name="status">
                      <option value="4" {{ request()->input('status') === '4' ? 'selected' : '' }}>All</option>
                      <option value="0" {{ request()->input('status') === '0' ? 'selected' : '' }}>Pending</option>
                      <option value="3" {{ request()->input('status') === '3' ? 'selected' : '' }}>Partially Approved</option>
                      <option value="1" {{ request()->input('status') === '1' ? 'selected' : '' }}>Approved</option>
                      <option value="2" {{ request()->input('status') === '2' ? 'selected' : '' }}>Rejected</option>
                      </select>
                  </div>
              </div>

              <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                      <label class="hide" style="visibility: hidden">Search Button</label>
                      @include('button_component.search_button', ['margin_top' => "11.5"])
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
               <h4 class="card-title">My Leaves</h4>
            </div>
            <div class="col-lg-4 col-sm-12 text-center mb-2">
               <a class="btn btn-sm text-info " href="{{ url('/employee?id=').session('user')->emp_id.md5(1) }}&approver=1" target="_blank" title="See Approvers">Approvers <i class="fa-solid fa-restroom"></i></a>
           </div>
            <div class="col-lg-4 col-sm-12 text-lg-right">
               <a href="{{ route('file_leave') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i> File Leave</a>
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="text-center" title="System Lock">SL</th>
                     <th class="">Leave Date</th>
                     <th class="">Leave Type</th>
                     <th class="text-center">Approver 1</th>
                     <th class="text-center">Approver 2</th>
                     <th class="text-center">Status</th>
                     <th class="">Created At</th>
                     <th colspan="3" class="text-center" style="min-width: 100px;">Action</th>
                  </tr>
               </thead>
               <tbody>
               @if(count($leaves))
                  @foreach($leaves as $row)
                     @php
                        // if (strtolower($row->gender) == 'male'){
                        //    $defCover = 'public/default/cover/Male.png';
                        //    $defProfile = 'public/default/profile/Male.jpg';
                        // }else {
                        //    $defCover = 'public/default/cover/Female.png';
                        //    $defProfile = 'public/default/profile/Female.jpg';
                        // }
                        // $profilePic = $row->picture_path ? $row->picture_path : $defProfile;

                        $icon_pending = "fa-solid fa-ellipsis";
                        $icon_appoved = "fa-solid fa-check text-dark";
                        $icon_rejected = "fa-solid fa-xmark text-danger";

                        $approvers = array(null,null); // set null for the 2 approvers
                        $approver_1 = $approver_2 = $icon_pending; // set pending for the 2 approvers
                        if($row->approved_by){
                              $approvers = explode (",", $row->approved_by);
                              if(count($approvers) == 1) $approvers[1] = null; // check if one array then set the second array to null
                        }
                        
                        if($row->is_approved == 1) $approver_1 = $approver_2 = $icon_appoved; // approved
                        elseif($row->is_approved == 3){ // Partially approved
                              $approver_1 = $icon_appoved; // approved
                              $approver_2 = $icon_pending; // pending
                        }
                        elseif($row->is_approved == 2){ // rejected
                              if($approvers[0] == null && $approvers[1] == null){
                                 $approver_1 = $icon_rejected;
                                 $approver_2 = $icon_pending;
                              }
                              elseif($approvers[0] != null){
                                 $approver_1 = $icon_appoved;
                                 $approver_2 = $icon_rejected;
                              }
                        }

                        $is_lock = 0;
                        if($systemLockstatus){
                           foreach ($systemLockstatus as $value) {
                              if (strtotime($value->lock_date) >= strtotime($row->leave_date_from) && strtotime($value->lock_date) <= strtotime($row->leave_date_to)) {
                                 // The target date range is within the current range in the array
                                 $is_lock = 1;
                                 $lock_date = $value->lock_date;
                                 break; // Exit the loop since we found a match
                              }
                           }
                        }

                     @endphp
                  
                     <tr id="{{ $row->leave_app_id.md5($row->leave_app_id) }}">
                        <td class="text-center">
                           @if($is_lock == 1)
                              <center><i class="fa-solid fa-lock text-dark" title="This application is currently locked and cannot be updated/removed at this time. Sep 20, 2023"></i></center>
                           @endif
                        </td>
                        <td>{{ date('M d, Y', strtotime($row->leave_date_from))}} <b>-</b> {{ date('M d, Y', strtotime($row->leave_date_to))}}</td>
                        <td>{{ ucwords(strtolower($row->leave_type)) }} @if($row->withPay == 1) (W/ Pay) @endif</td>
                        <td class="text-center"><i class="{{ $approver_1 }}"></i> <small>{{ ($row->app1_approved_on) ? date('M d, Y - H:i', strtotime($row->app1_approved_on)) : '' }}</small></td>
                        <td class="text-center"><i class="{{ $approver_2 }}"></i> <small>{{ ($row->app2_approved_on) ? date('M d, Y - H:i', strtotime($row->app2_approved_on)) : '' }}</td>
                        <td class="text-center" id="status_{{ $row->leave_app_id.md5($row->leave_app_id) }}">
                           @if($row->is_approved == 0)
                              @php
                                 $leave_status = 'Pending...';
                              @endphp
                              <span>Pending...</span>
                           @elseif($row->is_approved == 1)
                              @php
                                 $leave_status = 'Approved';
                              @endphp
                              <span class="text-dark">Approved</span>
                           @elseif($row->is_approved == 2)
                              @php
                                 $leave_status = 'Rejected';
                              @endphp
                              <span class="text-danger">Rejected</span>
                           @elseif($row->is_approved == 3)
                              @php
                                 $leave_status = 'Partially Approved';
                              @endphp
                              <span class="text-primary">Partially Approved</span>
                           @endif
                        </td>
                        <td>{{ date('M d, Y H:i', strtotime($row->created_at ))}}</td>
                        <td class="text-center">
                           <div class="w-100">
                              <a href="javascript:(0)" 
                                 data-id="{{ $row->leave_app_id.md5($row->leave_app_id) }}"
                                 data-leave_status="{{$leave_status}}"
                                 data-leave_type="{{ ucwords(strtolower($row->leave_type)) }} {{ ($row->withPay == 1) ? 'With Pay' : '' }}"
                                 data-leave_date="{{ date('M d, Y', strtotime($row->leave_date_from ))}} - {{ date('M d, Y', strtotime($row->leave_date_to ))}}"
                                 data-leave_total_days="{{ ($row->no_of_days) ? $row->no_of_days : 'N/A' }}"
                                
                                 data-leave_total_hours="{{($row->total_hours) ? $row->total_hours : 'N/A'}}"
                                 data-leave_reason="{{$row->remarks}}"
                                 data-reason_remarks="{{$row->reason_remarks}}"
                                 class="text-info req_leave_view_modal"
                                 title="View"
                                 >
                                 <i class="fa-solid fa-eye"></i>
                              </a>
                           </div>
                        </td>
                        @if($row->is_approved != 1)
                           <td class="text-center">
                              <div class="w-100">
                                 @if($is_lock == 1)
                                    <i class="fa-solid fa-pen-to-square disabled" title="This application is currently locked and cannot be updated/removed at this time. {{ date('M d, Y', strtotime($lock_date )) }}"></i>
                                 @else
                                    <a href="{{ url('/update-file-leave?id=') }}{{ $row->leave_app_id.md5($row->leave_app_id) }}" title="Update" class="text-dark" style="cursor: pointer;">
                                       <span class="fa-solid fa-pen-to-square"></span>
                                    </a>
                                 @endif
                              </div>
                           </td>
                           <td class="text-center">
                              <div class="w-100">
                                 @if($is_lock == 1)
                                    <i class="fa fa-trash disabled" title="This application is currently locked and cannot be updated/removed at this time. {{ date('M d, Y', strtotime($lock_date )) }}"></i>
                                 @else
                                    <a href="javascript:(0)" data-id="{{ $row->leave_app_id.md5($row->leave_app_id) }}" class="text-danger deleteLeave" title="Delete">
                                       <span class="fa fa-trash"></span>
                                    </a>
                                 @endif
                              </div>
                           </td>
                        @else
                           <td class="text-center">
                              <div class="w-100">
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              </div>
                           </td>
                           <td class="text-center">
                              <div class="w-100">
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              </div>
                           </td>
                        @endif
                     </tr>
                  
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

{{-- REQUEST LEAVE VIEW MODAL --}}
<div class="modal fade" id="req_leave_view_modal" tabindex="-1" aria-labelledby="req_leave_view_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="req_leave_view_modalLabel">Leave Request</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body">
            <div class="container bootstrap snippets bootdey">
               <div class="panel-body inf-content">
                  <div class="row">
                     <div class="col-12">
                        <center><h5 class="mt-4"><strong id="leave_status" title="Status"></strong></h5></center>
                        <br>
                        <div class="table-responsive">
                        <table class="table table-user-information">
                              <tbody>
                                 {{-- <tr>        
                                    <td><strong>Employee ID #</strong></td>
                                    <td class="text-primary" id="employee_id_number">123456789</td>
                                 </tr>
                                 <tr>    
                                    <td><strong>Name</strong></td>
                                    <td class="text-primary" id="employee_name">Bootdey</td>
                                 </tr> --}}
                                 <tr>        
                                    <td><strong>Leave Date</strong></td>
                                    <td class="text-primary" id="leave_date"></td>
                                 </tr>
                                 <tr>        
                                    <td><strong>Leave Type</strong></td>
                                    <td class="text-primary" id="leave_type"></td>
                                 </tr>
                                 <tr>        
                                    <td><strong>Total Days</strong></td>
                                    <td class="text-primary" id="leave_total_days"></td>
                                 </tr>
                                 
                                 <tr>        
                                    <td><strong>Total Hours</strong></td>
                                    <td class="text-primary" id="leave_total_hours">
                                          
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="2" title="Reason from the Employee">
                                       <h6>Reason from the Employee</h6>
                                       <p class="text-nuted" id="leave_reason"></p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="2" title="Remarks from the Approver">
                                       <h6>Remarks from the Approver</h6>
                                       <p class="text-nuted" id="reason_remarks"></p>
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
         <div class="modal-footer">
            {{-- <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button> --}}
         </div>
      </div>
   </div>
</div>
{{-- END REQUEST LEAVE VIEW MODAL --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')


<script>
   $(document).ready(function(){

      $('#date_from, #date_to').change(function() {
        let date = $('#date_from').val();
        if(date)
            $("#date_to").attr("required", true);
        else
            $("#date_to").removeAttr("required");
      });

      // cancel Leave
      $(document).on('click', '.deleteLeave', function(e) {
         
         let id = $(this).data('id');
         let url = "{{ url('delete_leave') }}";

         Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-trash-can fa-xs text-danger"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, delete!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
         }).then(function(result) {

            if (result.value) {

               Swal.fire({
                  width: "350",
                  title: 'Deleting...',
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
                  if(id && url) deleteItem(id,url);
               });
               
            } else if (result.dismiss === "cancel") {
               
            }
         });

      });

      function deleteItem(id ="", url =""){

         $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {

               if(result.code == 1){

                     $('#'+id).hide();

                     Swal.fire({ 
                        width: "350", position: "top-right", 
                        icon: "success", title: "Deleted", 
                        text: result.message, 
                        showConfirmButton: false, timer: 2000
                     });
               }
               else if(result.code == 0){

                  Swal.fire({
                     icon: "error",
                     title: "Error!",
                     text: result.message,
                     showConfirmButton: true
                  });
               }

               // swal.close();
            },
            error: function(result){
               console.log(result);
            }

         });
      }

      // View details
      $(document).on('click', '.req_leave_view_modal', function(e) {
         
         // $('#leave_img').attr('src', $(this).data('leave_img'));
         $('#leave_status').text($(this).data('leave_status'));
         // $('#employee_id_number').text($(this).data('employee_id_number'));
         // $('#employee_name').text($(this).data('employee_name'));
         $('#leave_type').text($(this).data('leave_type'));
         $('#leave_date').text($(this).data('leave_date'));
         $('#leave_total_days').text($(this).data('leave_total_days'));
         $('#leave_time').text($(this).data('leave_time'));
         $('#leave_total_hours').text($(this).data('leave_total_hours'));
         $('#leave_reason').text($(this).data('leave_reason'));
         $('#reason_remarks').text($(this).data('reason_remarks'));
         $('#req_leave_view_modal').modal('show');
      });

   });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}