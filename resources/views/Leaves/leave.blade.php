@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>
    .inf-content{
    border:1px solid #DDDDDD;
    -webkit-border-radius:10px;
    -moz-border-radius:10px;
    border-radius:10px;
    box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
}			                                                      
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title',"Leaves")
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
         <form action="{{ route('leave') }}" method="get">
            {{-- @csrf --}}
            <div class="row">
               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">From:</label>
                     <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from" required>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">To:</label>
                     <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to" required>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Status</label>
                     <select id="status" name="status" class="form-control custom-select">
                           @php
                              $statusSelected = request()->input('status') ?? "";
                           @endphp
                           <option value="" <?php echo ($statusSelected == "") ? 'selected':'' ?>>All</option>
                           <option value="0" <?php echo ($statusSelected === '0') ? 'selected':'' ?>>Pending...</option>
                           <option value="3" <?php echo ($statusSelected == 3) ? 'selected':'' ?>>Partially approved</option>
                           <option value="1" <?php echo ($statusSelected == 1) ? 'selected':'' ?>>Approved</option>
                           <option value="2" <?php echo ($statusSelected == 2) ? 'selected':'' ?>>Rejected</option>
                           <option value="4" <?php echo ($statusSelected == 4) ? 'selected':'' ?>>Deleted</option>
                     </select>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">&nbsp;</label>
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
            <div class="col-6">
               <h4 class="card-title">Leaves</h4>
            </div>
            <div class="col-6 text-right">
            @if(in_array(session('user')->employee_type_id, [1,2]) || session('other_links'))
               <a href="{{ route('leave_entry') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i> Add Leave</a>
            @else
               <a href="{{ route('file_leave') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i> File Leave</a>
            @endif
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Emp.ID</th>
                     <th class="">Name</th>
                     <th class="">Leave Date</th>
                     <th class="">Leave Type</th>
                     <th class="text-center">Approver 1</th>
                     <th class="text-center">Approver 2</th>
                     <th class="">Status</th>
                     <th class="">Created At</th>
                     @if(in_array(session('user')->employee_type_id, [1,2]) || session('other_links'))
                        <th class="text-center">Action</th>
                     @endif
                  </tr>
               </thead>
               <tbody>
               @if(count($leaves))
                  @foreach($leaves as $row)
                     @php
                        if (strtolower($row->gender) == 'male'){
                           $defCover = 'public/default/cover/Male.png';
                           $defProfile = 'public/default/profile/Male.jpg';
                        }else {
                           $defCover = 'public/default/cover/Female.png';
                           $defProfile = 'public/default/profile/Female.jpg';
                        }
                        $profilePic = $row->picture_path ? $row->picture_path : $defProfile;



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

                        $approver_1_name = $approver_2_name = "No approver found.";
                        // set approvers name
                        if(count($row->approvers)){
                              foreach($row->approvers as $ap){
                                 if($row->approver_1_emp_id == $ap->approver_emp_id){
                                    $approver_1_name = ucwords(strtolower($ap->approver_name));
                                 }
                                 elseif($row->approver_2_emp_id == $ap->approver_emp_id){
                                    $approver_2_name = ucwords(strtolower($ap->approver_name));
                                 }
                              }
                        }
                     @endphp
                  @if(isset($row->leave_app_id))
                     <tr id="{{ $row->leave_app_id.md5($row->leave_app_id) }}">
                        
                        <td>{{ $row->employee_number }}</td>
                        <td>
                           {{-- @if(session('user')->employee_type_id != 5) --}}
                           @if(session('user')->employee_type_id != 5 || session('other_links'))
                              <a href="{{ url('employee?id='.$row->SysPK_Empl.md5($row->SysPK_Empl) ) }}" class="text-info" title="View Information">
                                 {{ ucwords(strtolower($row->Name_Empl)) }}
                              </a>
                           @else
                              {{ ucwords(strtolower($row->Name_Empl)) }}
                           @endif
                        </td>
                        <td>{{ date('M d, Y', strtotime($row->leave_date_from ))}} <b>-</b> {{ date('M d, Y', strtotime($row->leave_date_to ))}}</td>
                        
                        <td>
                           {{ ucwords(strtolower($row->leave_type)) }}
                           @if($row->withPay == 1) (W/ Pay)@endif
                        </td>
                        <td class="text-center">{{ $approver_1_name }} <br><i class="{{ $approver_1 }}"></i> <small>{{ ($row->app1_approved_on) ? date('M d, Y', strtotime($row->app1_approved_on)) : '' }}</small></td>
                    <td class="text-center">{{ $approver_2_name }} <br><i class="{{ $approver_2 }}"></i> <small>{{ ($row->app2_approved_on) ? date('M d, Y', strtotime($row->app2_approved_on)) : '' }}</td>
                           <td id="status_{{ $row->leave_app_id.md5($row->leave_app_id) }}">
                              @if($row->is_deleted == 1)
                                 @php
                                    $leave_status = 'Deleted';
                                 @endphp
                                 <span class="text-danger">Deleted</span>
                              @elseif($row->is_approved == 0)
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
                           <td>{{ date('M d, Y', strtotime($row->created_at ))}}</td>
                        <td class="text-center">
                           <a href="javascript:(0)" 
                              data-id="{{ $row->leave_app_id.md5($row->leave_app_id) }}"
                              data-leave_status="{{$leave_status}}"
                              data-employee_id_number="{{$row->employee_number}}"
                              data-employee_name="{{ ucwords(strtolower($row->Name_Empl)) }}"
                              data-leave_type="{{ ucwords(strtolower($row->leave_type)) }} {{ ($row->withPay == 1) ? 'With Pay' : '' }}"
                              data-leave_date="{{ date('M d, Y', strtotime($row->leave_date_from ))}} - {{ date('M d, Y', strtotime($row->leave_date_to ))}}"
                              data-leave_total_days="{{ ($row->no_of_days) ? $row->no_of_days : 'N/A' }}"
                              data-leave_time="{{ ($row->time_from == '00:00:00') ? 'N/A' : date('H:i', strtotime($row->time_from)) .' - '. date('H:i', strtotime($row->time_to)) }}"
                              data-leave_total_hours="{{($row->total_hours) ? $row->total_hours : 'N/A'}}"
                              data-leave_reason="{{$row->remarks}}"
                              data-reason_remarks="{{$row->reason_remarks}}"
                              data-leave_img="{{ $profilePic }}"
                              class="mr-1 text-info req_leave_view_modal"
                              title="View"
                              >
                              <i class="fa-solid fa-eye"></i>
                           </a>
                           @if(in_array(session('user')->employee_type_id, [1,2]) && $row->is_deleted == 1)
                              <a href="javascript:(0)" data-id="{{ $row->leave_app_id.md5($row->leave_app_id) }}" class="ml-1 text-danger deleteLeave" title="Delete permanently">
                                 <span class="fa fa-trash"></span>
                              </a>
                           @endif
                        </td>
                     </tr>
                  @endif
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
               <h5 class="modal-title" id="req_leave_view_modalLabel">Employee's Leave</h5>
               <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
           </div>
           <div class="modal-body">
            <div class="container bootstrap snippets bootdey">
               <div class="panel-body inf-content">
                   <div class="row">
                     <div class="col-4">
                     </div>
                       <div class="col-4">
                           <img alt="" style="width:300px;" title="Photo" id="leave_img" class="mt-3 img-circle img-thumbnail" src="https://bootdey.com/img/Content/avatar/avatar7.png" data-original-title="Usuario"> 
                           <ul title="Ratings" class="list-inline ratings text-center">
                               <li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
                               <li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
                               <li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
                               <li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
                               <li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
                           </ul>
                       </div>
                       <div class="col-4">
                     </div>
                       <div class="col-12">
                           <center><strong id="leave_status" title="Status"></strong></center>
                           <br>
                           <div class="table-responsive">
                           <table class="table table-user-information">
                               <tbody>
                                   <tr>        
                                       <td><strong>Employee ID #</strong></td>
                                       <td class="text-primary" id="employee_id_number"></td>
                                   </tr>
                                   <tr>    
                                       <td><strong>Name</strong></td>
                                       <td class="text-primary" id="employee_name"></td>
                                   </tr>
                                   <tr>        
                                       <td><strong>Leave Type</strong></td>
                                       <td class="text-primary" id="leave_type"></td>
                                    </tr>
                                   <tr>        
                                       <td><strong>Leave Date</strong></td>
                                       <td class="text-primary" id="leave_date"></td>
                                   </tr>
                                   
                                   <tr>        
                                       <td><strong>Total Days</strong></td>
                                       <td class="text-primary" id="leave_total_days">1</td>
                                   </tr>
                                   <tr>        
                                       <td><strong>Leave Time</strong></td>
                                       <td class="text-primary" id="leave_time">
                                           
                                       </td>
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
               <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
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

      // cancel Leave
      $(document).on('click', '.deleteLeave', function(e) {
         
         let id = $(this).data('id');
         let url = "{{ url('delete_leave') }}";
         let btn_confirm = confirm("Delete Leave?");

         if (btn_confirm) {
               $.ajax({
                  url: url,
                  type: 'POST',
                  data: {id:id},
                  headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function(result) {
                     if(result.code == 1){
                        $('.alert_message_js').removeClass('d-none').addClass('alert-success');;
                        $('#alert_message_js').text(result.message);
                        $('#'+id).hide();
                     }
                  },
                  error: function(result){
                     console.log(result);
                  }

               });
         }

      });

      // View details
      $(document).on('click', '.req_leave_view_modal', function(e) {
         
         $('#leave_img').attr('src', $(this).data('leave_img'));
         $('#leave_status').text($(this).data('leave_status'));
         $('#employee_id_number').text($(this).data('employee_id_number'));
         $('#employee_name').text($(this).data('employee_name'));
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