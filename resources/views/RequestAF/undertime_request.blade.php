<?php
// require('App/XLSXReader/XLSXReader.php');
?>

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
@section('title','Undertime Request')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    {{-- <div class="alert_message_js alert text-info fade show d-none" role="alert">
        <span id="alert_message_js"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}

    <div class="card">
        <div class="card-body">
            <form action="{{ route('undertime_request') }}" method="get">
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

                    <div class="col-lg-1 col-sm-12">
                        <div class="form-group">
                            <label for="ps_display_by_select">Display</label>
                            <select class="form-control" id="ps_display_by_select" name="display_by">
                            <option value="10" {{ request()->input('display_by') === '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request()->input('display_by') === '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request()->input('display_by') === '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request()->input('display_by') === '100' ? 'selected' : '' }}>100</option>
                            <option value="all" {{ request()->input('display_by') === 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="hide" style="visibility: hidden">Search Button</label>
                            @include('button_component.search_button', ['margin_top' => "0.5"])
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
            <div class="col-lg-6 col-sm-12">
                <h4 class="card-title">Undertime Request</h4>
            </div>
            <div class="col-lg-6 col-sm-12 text-lg-right">
                {{-- <a href="{{ route('file_time_pass') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle">
                    </i> Add Pass Slip 
                </a> --}}
            </div>
            </div>
            <div class="table-responsive m-t-40">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        {{-- <th class="">Emp.ID</th> --}}
                        <th class="">Employee Name</th>
                        <th class="">Date</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Total Hrs </th>
                        <th class="text-center">Approver 1</th>
                        <th class="text-center">Approver 2</th>
                        <th class="text-center">Status</th>
                        <th>Created At</th>
                        <th colspan="3" class="text-center" style="min-width: 100px;">Action</th>
                        <th class="text-center"><i class="fa-solid fa-pen-nib"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($undertime))
                        @foreach($undertime as $row)
                            @php
                                if (strtolower($row->gender) == 'male'){
                                    $defCover = 'public/default/cover/Male.png';
                                    $defProfile = 'public/default/profile/Male.jpg';
                                }else{
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
                                
                                if($row->ps_status == 1) $approver_1 = $approver_2 = $icon_appoved; // approved
                                elseif($row->ps_status == 3){ // Partially approved
                                    $approver_1 = $icon_appoved; // approved
                                    $approver_2 = $icon_pending; // pending
                                }
                                elseif($row->ps_status == 2){ // rejected
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
                            <tr id="{{ $row->id.md5($row->id) }}">
                                {{-- <td>{{ $row->UserID_Empl }}</td> --}}
                                <td title="{{ $row->UserID_Empl }}"><b>{{ ucwords(strtolower($row->Name_Empl)) }}</b></td>
                                <td>{{ date('M d, Y', strtotime($row->ps_date_from ))}}</td>
                                <td class="text-center">{{ date('H:i ', strtotime($row->time ))}} - {{ date('H:i ', strtotime($row->time_end ))}}</td>
                                <td class="text-center">{{ $row->total_time }}</td>
                                <td class="text-center">{{ $approver_1_name }} <br><i class="{{ $approver_1 }}"></i> <small>{{ ($row->app1_approved_on) ? date('M d, Y - H:i', strtotime($row->app1_approved_on)) : '' }}</small></td>
                                <td class="text-center">{{ $approver_2_name }} <br><i class="{{ $approver_2 }}"></i> <small>{{ ($row->app2_approved_on) ? date('M d, Y - H:i', strtotime($row->app2_approved_on)) : '' }}</td>                           
                                <td class="text-center" id="status_{{ $row->id.md5($row->id) }}">
                                    @if($row->ps_status == 0)
                                        @php
                                            $ps_status = 'Pending...';
                                        @endphp
                                        <span>Pending...</span>
                                    @elseif($row->ps_status == 1)
                                        @php
                                            $ps_status = 'Approved';
                                        @endphp
                                        <span class="text-dark">Approved</span>
                                    @elseif($row->ps_status == 2)
                                        @php
                                            $ps_status = 'Rejected';
                                        @endphp
                                        <span class="text-danger">Rejected</span>
                                    @elseif($row->ps_status == 3)
                                        @php
                                            $ps_status = 'Partially Approved';
                                        @endphp
                                        <span class="text-primary">Partially Approved</span>
                                    @endif
                                </td>
                                <td>{{ date('M d, Y H:i', strtotime($row->created_at ))}}</td>
                                <td class="text-center">
                                    <div class="w-100 py-3">
                                        <a href="javascript:(0)" 
                                            data-id="{{ $row->id.md5($row->id) }}"
                                            data-ps_status="{{$ps_status}}"
                                            data-employee_id_number="{{$row->UserID_Empl}}"
                                            data-employee_name="{{ ucwords(strtolower($row->Name_Empl)) }}"
                                            data-date_approved="{{$row->date_approved ? date('M d, Y', strtotime($row->date_approved )) : 'N/A'}}"
                                            data-time_type="Undertime"
                                            data-ps_date="{{ date('M d, Y', strtotime($row->ps_date_from ))}}"
                                            data-ps_time="{{ date('H:i ', strtotime($row->time ))}} - {{ date('H:i ', strtotime($row->time_end ))}}"
                                            data-ps_remarks="{{$row->Remarks}}"
                                            data-status_remarks="{{$row->status_remarks ? $row->status_remarks : 'N/A'}}"
                                            data-ps_img="{{ $profilePic }}"
                                            data-ps_total_hours="{{($row->total_time) ? $row->total_time : 'N/A'}}"
                                            class="text-info req_ps_view_modal"
                                            title="View"
                                            >
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                                @if(session('is_approver') && $row->ps_status != 2)

                                    @if(session('employee')->UserID_Empl == $row->approver_2_emp_id && in_array($row->ps_status, [0], null) )
                                        <td class="text-center">
                                            <div class="w-100 py-3">
                                                <i class="fa fa-question-circle text-danger" aria-hidden="true" title="Approver 1 should approve first."></i>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100 py-3">
                                                <i class="fa-solid fa-ellipsis" title="No action"></i>
                                            </div>
                                        </td>
                                    @else
                                        @if(($row->ps_status == 3 && session('employee')->UserID_Empl != $row->approved_by) || $row->approved_by == 0)
                                            <td class="text-center">
                                                <div class="w-100 py-3">
                                                    <a href="javascript:(0)" data-id="{{ $row->id.md5($row->id) }}" class="ml-1 text-dark approveUndertime span_{{ $row->id.md5($row->id) }}" title="Approve">
                                                        <i class="fa-solid fa-check"></i>
                                                    </a>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="w-100 py-3">
                                                    <a href="javascript:(0)" data-id="{{ $row->id.md5($row->id) }}" class="ml-1 text-danger reject_view_modal span_{{ $row->id.md5($row->id) }}" title="Reject">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <div class="w-100 py-3">
                                                    <i class="fa-solid fa-ellipsis" title="No action"></i>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="w-100 py-3">
                                                    <i class="fa-solid fa-ellipsis" title="No action"></i>
                                                </div>
                                            </td>
                                        @endif
                                    @endif
                                
                                @else
                                    <td class="text-center">
                                        <div class="w-100 py-3">
                                            <i class="fa-solid fa-ellipsis" title="No action"></i>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="w-100 py-3">
                                            <i class="fa-solid fa-ellipsis" title="No action"></i>
                                        </div>
                                    </td>
                                @endif
                                
                                <td class="text-center">
                                    <div class="w-100 py-3">
                                        <a href="{{ url('/update-file-undertime-approver?id=') }}{{ $row->id.md5($row->id) }}&emp_id={{ $row->emp_id }}" title="Update" class="mx-1 text-dark" style="cursor: pointer;">
                                            <span class="fa-solid fa-pen-to-square"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td class="text-center" colspan="13">No record found</td></tr>
                    @endif
                </tbody>
            </table>
            <div class="float-left"> {{ $paginationLinks }} </div>
            </div>
        </div>
    </div>

    <hr>

</div>
{{-- REQUEST UNDERTIME VIEW MODAL --}}
<div class="modal fade" id="req_ps_view_modal" tabindex="-1" aria-labelledby="req_ps_view_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="req_ps_view_modalLabel">Undertime Request</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <div class="modal-body">
                <div class="container bootstrap snippets bootdey">
                <div class="panel-body inf-content">
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <img alt="" style="width:300px;" title="Photo" id="ps_img" class="mt-3 mb-3 img-circle img-thumbnail" src="https://bootdey.com/img/Content/avatar/avatar7.png" data-original-title="Usuario"> 
                        </div>
                        <div class="col-4"></div>
                        <div class="col-12">
                            <center>
                                <strong id="ps_status" title="Status">Partially Approved</strong> <br>
                                <span id="date_approved" class="text-primary"></span>
                            </center>
                            <br>
                            <div class="table-responsive">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>        
                                        <td><strong>Date</strong></td>
                                        <td class="text-primary" id="ps_date"></td>
                                    </tr>
                                    <tr>        
                                        <td><strong>Time</strong></td>
                                        <td class="text-primary" id="ps_time">
                                            
                                        </td>
                                    </tr>
                                    <tr>        
                                        <td><strong>Total Hours</strong></td>
                                        <td class="text-primary" id="ps_total_hours">
                                            
                                        </td>
                                    </tr>
                                    <tr>        
                                        <td><strong>Type</strong></td>
                                        <td class="text-primary" id="time_type"></td>
                                    </tr>
                                    
                                    <tr>        
                                        {{-- <td><strong>Reason</strong></td> --}}
                                        <td colspan="2">
                                            <h6>Remarks from the Employee</h6>
                                            <p class="text-nuted" id="ps_remarks"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>        
                                        {{-- <td><strong>Reason</strong></td> --}}
                                        <td colspan="2">
                                            <h6>Remarks from the Approver</h6>
                                            <p class="text-nuted" id="status_remarks"></p>
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
 {{-- END REQUEST UNDERTIME VIEW MODAL --}}

 {{-- REJECTION VIEW MODAL --}}
<div class="modal fade" id="reject_view_modal" tabindex="-1" aria-labelledby="reject_view_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reject_view_modalLabel">Reject Request?</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <div class="modal-body">
                <input type="text" id="emp_pass_req_id" hidden>
                <label class="control-label">Reason</label>
                <textarea class="form-control" id="remarks_reason_text" name="remarks" placeholder="Type in your message" rows="5" maxlength="100" required></textarea>
                <h6 class="pull-right mt-1" id="count_message"></h6>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark rejectUndertime" type="button">Reject</button>
            </div>
        </div>
    </div>
</div>
 {{-- END REJECTION VIEW MODAL --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
{{-- <script src="{{ asset('uidesign/js/custom/overtime.js') }}"></script> --}}
<script>
$(document).ready(function(){

    let text_max = 100;
    $('#count_message').html(text_max + ' remaining');
    $('#remarks_reason_text').keyup(function() {
        var text_length = $('#remarks_reason_text').val().length;
        var text_remaining = text_max - text_length;
        $('#count_message').html(text_remaining + ' remaining');
    });
    
    $('#date_from').change(function() {
        let date = $(this).val();
        if(date)
            $("#date_to").attr("required", true);
        else
            $("#date_to").removeAttr("required");
    });

    // View details
    $(document).on('click', '.req_ps_view_modal', function(e) {
         
         $('#ps_img').attr('src', $(this).data('ps_img'));
         $('#ps_status').text($(this).data('ps_status'));
         $('#employee_id_number').text($(this).data('employee_id_number'));
         $('#employee_name').text($(this).data('employee_name'));
         $('#time_type').text($(this).data('time_type'));
         $('#ps_date').text($(this).data('ps_date'));
         $('#ps_time').text($(this).data('ps_time'));
         $('#date_approved').text($(this).data('date_approved'));
         $('#ps_remarks').text($(this).data('ps_remarks'));
         $('#status_remarks').text($(this).data('status_remarks'));
         $('#ps_total_hours').text($(this).data('ps_total_hours'));
         
         $('#req_ps_view_modal').modal('show');
    });

    $(document).on('click', '.approveUndertime', function(e) {
         
        let id = $(this).data('id');
        let url = "{{ url('approve_undertime') }}";
        
        Swal.fire({
            title: "Would you like to approve the undertime?",
            // text: "Would you like to request a schedule change?",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-check fa-xs text-dark"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, approve!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
        }).then(function(result) {

            if (result.value) {

                Swal.fire({
                    width: "350",
                    title: 'Approving...',
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
                    // $("#file_time_pass_update").unbind('submit').submit();
                    if(id && url) approveItem(id,url);
                    
                });

            } else if (result.dismiss === "cancel") {
                
            }
        });
    });

    function approveItem(id ="", url =""){
        $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {

                let title = "";

                if(result.code == 0){

                    Swal.fire({
                        icon: "warning",
                        title: "Oops!",
                        text: result.message,
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else{

                    if(result.code == 3){
                        title = "Partially Approved";
                        $('#status_'+id).empty().append('<span class="text-primary">'+title+'</span>');
                        // $('.span_'+id).hide();
                    }
                    else if(result.code == 1){
                        title = "Approved";
                        $('#status_'+id).empty().append('<span class="text-success">'+title+'</span>');
                        // $('.span_'+id).hide();
                    }
                    else if(result.code == 2){
                        title = "Rejected";
                        $('#status_'+id).empty().append('<span class="text-danger">'+title+'</span>');
                        // $('.span_'+id).hide();
                    }

                    $('.span_'+id).empty().replaceWith('<i class="fa-solid fa-ellipsis" title="No action"></i>');

                    Swal.fire({ 
                        width: "350", position: "top-right", 
                        icon: "success", title: title, 
                        text: result.message, 
                        showConfirmButton: false, timer: 2000
                    });

                }
                
            },
            error: function(result){
                console.log(result);
            }
        });
    }


    $(document).on('click', '.rejectUndertime', function(e) {
        
        let id = $('#emp_pass_req_id').val();
        let reason_remarks = $('#remarks_reason_text').val();
        let url = "{{ url('reject_undertime') }}";
        
        Swal.fire({
            title: "Would you like to reject the undertime?",
            // text: "You won't be able to revert this!",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-xmark fa-xs text-danger"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, reject!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
        }).then(function(result) {

            if (result.value) {

                Swal.fire({
                    width: "350",
                    title: 'Rejecting...',
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
                    if(id && url) rejectItem(id,url,reason_remarks);
                });
                
            } else if (result.dismiss === "cancel") {

            }
        });
    });

    function rejectItem(id="", url="", reason_remarks=""){
        $.ajax({
            url: url,
            type: 'POST',
            data: {id:id, reason_remarks:reason_remarks},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {

                if(result.code == 1){
                    $('#status_'+id).empty().append('<span class="text-danger">Rejected</span>');
                    // $('.span_'+id).hide();
                    $('.span_'+id).empty().replaceWith('<i class="fa-solid fa-ellipsis" title="No action"></i>');
                    $('#reject_view_modal').modal('toggle');

                    Swal.fire({ 
                        width: "350", position: "top-right", 
                        icon: "success", title: "Rejected", 
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

            },
            error: function(result){
                console.log(result);
            }

        });
    }

    // View memo modal
    $('.reject_view_modal').click(function(e){
        let id = $(this).data('id');
        $('#emp_pass_req_id').val(id);
        $('#reject_view_modal').modal('show');
    });

});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}