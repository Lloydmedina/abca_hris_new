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
@section('title','My Time Pass Slip')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('my_time_pass') }}" method="get">
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

                    {{-- <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <input style="cursor:pointer" type="submit" class="form-control btn-primary" id="date_selected" name="btn_search" required>
                        </div>
                    </div> --}}
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-sm-12">
                    <h4 class="card-title">My Time Pass Slip</h4>
                </div>
                <div class="col-lg-4 col-sm-12 text-center mb-2">
                    <a class="btn btn-sm text-info " href="{{ url('/employee?id=').session('user')->emp_id.md5(1) }}&approver=1" target="_blank" title="See Approvers">Approvers <i class="fa-solid fa-restroom"></i></a>
                </div>
                <div class="col-lg-4 col-sm-12 text-lg-right">
                    {{-- <a href="{{ route('file_time_pass') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle">
                        </i> File Time Pass Slip 
                    </a> --}}

                    <button type="button" class="btn  btn-sm btn-dark" data-toggle="modal" data-target="#pass_slip_modal_notice">
                        File Time Pass Slip 
                    </button>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th title="System Lock" class="text-center">SL</th>
                            <th class="">Date & Time</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Approver 1</th>
                            <th class="text-center">Approver 2</th>
                            <th class="text-center">Status</th>
                            <th>Created At</th>
                            <th colspan="3" class="text-center" style="min-width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($timePass))
                            @foreach($timePass as $row)
                                @php
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
                                @endphp
                                <tr id="{{ $row->id.md5($row->id) }}">
                                    <td>
                                        @if($row->is_lock == 1)
                                            <center><i class="fa-solid fa-lock text-dark" title="This application is currently locked and cannot be updated/removed at this time. Sep 20, 2023"></i></center>
                                        @endif
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($row->ps_date_from ))}} {{ date('H:i', strtotime($row->time ))}}</td>
                                    <td class="text-center"> {{ ($row->time_type) == 0 ? 'Time-In' : 'Time-Out' }}</td>
                                    <td class="text-center"><i class="{{ $approver_1 }}"></i> <small>{{ ($row->app1_approved_on) ? date('M d, Y - H:i', strtotime($row->app1_approved_on)) : '' }}</small></td>
                                    <td class="text-center"><i class="{{ $approver_2 }}"></i> <small>{{ ($row->app2_approved_on) ? date('M d, Y - H:i', strtotime($row->app2_approved_on)) : '' }}</td>
                                    <td class="text-center">
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
                                        <div class="w-100">
                                            <span href="javascript:(0)" style="cursor: pointer"
                                                data-id="{{ $row->id.md5($row->id) }}"
                                                data-ps_status="{{$ps_status}}"
                                                data-date_approved="{{$row->date_approved ? date('M d, Y', strtotime($row->date_approved )) : 'N/A'}}"
                                                data-time_type="{{ ($row->time_type) == 0 ? 'Time-In' : 'Time-Out' }}"
                                                data-ps_date="{{ date('M d, Y', strtotime($row->ps_date_from )).' '.date('H:i:s', strtotime($row->time ))}}"
                                                data-ps_remarks="{{$row->Remarks}}"
                                                data-status_remarks="{{$row->status_remarks ? $row->status_remarks : 'N/A'}}"
                                                class="text-info req_ps_view_modal"
                                                title="View"
                                                >
                                                <i class="fa-solid fa-eye"></i>
                                            </span>
                                        </div>
                                    </td>
                                    {{-- @if($row->ps_status != 1)
                                        <td class="text-center">
                                            <div class="w-100">
                                                @if($row->is_lock == 1)
                                                    <i class="fa-solid fa-pen-to-square disabled" title="This application is currently locked and cannot be updated/removed at this time. {{ date('M d, Y', strtotime($row->lock_date )) }}"></i>
                                                @else
                                                    <a href="{{ url('/update-file-time-pass?id=') }}{{ $row->id.md5($row->id) }}" title="Update" class="text-dark" style="cursor: pointer;">
                                                        <span class="fa-solid fa-pen-to-square"></span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100">
                                                @if($row->is_lock == 1)
                                                    <i class="fa-solid fa-trash-can disabled" title="This application is currently locked and cannot be updated/removed at this time. {{ date('M d, Y', strtotime($row->lock_date )) }}"></i>
                                                @else
                                                    <span href="javascript:(0)" style="cursor: pointer" data-id="{{ $row->id.md5($row->id) }}" class="text-danger deleteTimePass" title="Delete">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </span>
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
                                    @endif --}}
                                    {{-- <td class="text-center">
                                        @if($row->is_lock == 1)
                                            <i class="fa-solid fa-lock text-dark" title="This application is currently locked and cannot be updated/removed at this time. {{ date('M d, Y', strtotime($row->lock_date )) }}"></i>
                                        @else
                                            <i class="fa-solid fa-lock-open text-muted"></i>
                                        @endif
                                    </td> --}}
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


{{-- pass slip note --}}
<div class="modal fade" id="pass_slip_modal_notice" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pass_slip_modal_noticeLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pass_slip_modal_noticeLabel">NOTICE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <p>
            Starting from November 15, 2023, you will no longer be able to file a pass slip using this system. Please revert to the manual process and submit it to your approver.
        </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
        </div>
      </div>
    </div>
</div>

{{-- REQUEST PASS SLIP VIEW MODAL --}}
<div class="modal fade" id="req_ps_view_modal" tabindex="-1" aria-labelledby="req_ps_view_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="req_ps_view_modalLabel">Time Pass Slip Request</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <div class="modal-body">
                <div class="container bootstrap snippets bootdey">
                <div class="panel-body inf-content">
                    <div class="row">
                        <div class="col-12">
                            <center><h5 class="mt-4"><strong id="ps_status" title="Status"></strong></h5></center>
                            <br>
                            <div class="table-responsive">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>        
                                        <td><strong>Date & Time</strong></td>
                                        <td class="text-primary" id="ps_date"></td>
                                    </tr>
                                    <tr>        
                                        <td><strong>Type</strong></td>
                                        <td class="text-primary" id="time_type"></td>
                                    </tr>
                                    <tr>        
                                        <td><strong>Date Approved</strong></td>
                                        <td class="text-primary" id="date_approved">
                                            8
                                        </td>
                                    </tr>
                                    <tr>        
                                        {{-- <td><strong>Reason</strong></td> --}}
                                        <td colspan="2">
                                            <h6>Reason from the Employee</h6>
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
 {{-- END REQUEST PASS SLIP VIEW MODAL --}}


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

    $('#date_from, #date_to').change(function() {
        let date = $('#date_from').val();
        if(date)
            $("#date_to").attr("required", true);
        else
            $("#date_to").removeAttr("required");
    });

    // View details
    $(document).on('click', '.req_ps_view_modal', function(e) {
        $('#ps_status').text($(this).data('ps_status'));
        $('#time_type').text($(this).data('time_type'));
        $('#ps_date').text($(this).data('ps_date'));
        $('#date_approved').text($(this).data('date_approved'));
        $('#ps_remarks').text($(this).data('ps_remarks'));
        $('#status_remarks').text($(this).data('status_remarks'));
        $('#req_ps_view_modal').modal('show');
    });

    $(document).on('click', '.deleteTimePass', function(e) {
         
        let id = $(this).data('id');
        let url = "{{ url('delete_time_pass') }}";
        // let btn_confirm = confirm("Delete Pass slip?");

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

});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}