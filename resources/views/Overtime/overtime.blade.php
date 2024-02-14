<?php
// require('App/XLSXReader/XLSXReader.php');
?>

@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Overtime')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

  @include('Templates.alert_message')

  <div class="card">
      <div class="card-body">

            <form action="{{ route('overtime') }}" method="get">
              <div class="row">
                  <div class="col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label class="control-label">From:</label>
                      <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from" required>
                    </div>
                  </div>

                  <div class="col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label class="control-label">To:</label>
                      <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to" required>
                    </div>
                  </div>

                  <div class="col-lg-4 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <input style="cursor:pointer" type="submit" class="form-control btn-primary">
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
              <h4 class="card-title">Overtime</h4>
          </div>
          <div class="col-6 text-right">
            @if(session('user')->employee_type_id != 5 )
              <a href="{{ url('/add-overtime') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle">
                </i> Add Overtime 
              </a>
              @if(session('user')->employee_type_id == 1 )
                {{-- <a href="" class="btn btn-sm btn-success" data-toggle="modal" data-target="#OTMODAL"><i class="fa fa-check">
                  </i> Approve Overtime 
                </a> --}}
              @endif
            @endif
          </div>
        </div>
        <div class="table-responsive m-t-40">
          <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  {{-- <th class=""></th> --}}
                  <th class="">Emp.ID</th>
                  <th class="">Name</th>
                  {{-- <th class="">Position</th> --}}
                  {{-- <th class="">Time<br>Start</th>
                  <th class="">Time<br>End</th> --}}
                  <th class="">Overtime Date</th>
                  <th class="text-center">Time</th>
                  <th class="text-center">Total Hrs</th>
                  <th class="">Remarks</th>
                  <th class="text-center">Approver 1</th>
                  <th class="text-center">Approver 2</th>
                  <th class="">Status</th>
                  @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
                    <th class="text-center">Action</th>
                  @endif
                </tr>

              </thead>
              
              <tbody>
                @foreach($overtime as $row)
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

                <tr id="{{ $row->OT_ID.md5($row->OT_ID) }}">
                  {{-- @if($row->stat=="PENDING")
                    <td class="text-center">
                      <input type="checkbox" name="cb_{{ $row->OT_ID }}" id="cb_{{ $row->OT_ID }}" onChange="approveOT({{ $row->OT_ID }},'{{ $row->Name_Empl }}','{{ date("H:i",strtotime($row->Time_Start)) }}','{{ date("H:i",strtotime($row->Time_End)) }}','{{ $row->OT_Hours }}')">
                    </td>
                  @endif
                  @if($row->stat!="PENDING")
                    <td class="text-center">
                      &nbsp;
                    </td>
                  @endif --}}
                    
                    <td>{{ $row->employee_number }}</td>
                    <td>
                      <a href="{{ url('employee?id='.$row->SysPK_Empl.md5($row->SysPK_Empl) ) }}" class="text-info" title="View Information">
                        {{ ucwords(strtolower($row->Name_Empl)) }}
                      </a>
                    </td>
                    {{-- <td>{{ $row->Position_Empl }}</td> --}}
                    <td>{{ date('M d, Y', strtotime($row->dtr_date)) }}</td>
                    <td class="text-center">{{ date("H:i",strtotime($row->Time_Start)) }} - {{ date("H:i",strtotime($row->Time_End)) }}</td>
                    {{-- <td class="text-center">{{ date("H:i",strtotime($row->Time_End)) }}</td> --}}
                    <td class="text-center">{{ $row->OT_Hours }}</td>
                    <td>{{ $row->remarks }}</td>
                    <td class="text-center">{{ $approver_1_name }} <br><i class="{{ $approver_1 }}"></i> <small>{{ ($row->app1_approved_on) ? date('M d, Y', strtotime($row->app1_approved_on)) : '' }}</small></td>
                    <td class="text-center">{{ $approver_2_name }} <br><i class="{{ $approver_2 }}"></i> <small>{{ ($row->app2_approved_on) ? date('M d, Y', strtotime($row->app2_approved_on)) : '' }}</td>
                    <td>
                        @if($row->is_deleted == 1)
                          @php $ot_status = 'Deleted'; @endphp
                        @elseif($row->is_approved == 0)
                          @php $ot_status = 'Pending...'; @endphp
                          <span>Pending...</span>
                        @elseif($row->is_approved == 1)
                          @php
                            $ot_status = 'Approved';
                          @endphp
                        <span class="text-dark">Approved</span>
                        @elseif($row->is_approved == 2)
                          @php
                            $ot_status = 'Rejected';
                          @endphp
                        <span class="text-danger">Rejected</span>
                        @elseif($row->is_approved == 3)
                          @php
                            $ot_status = 'Partially Approved';
                          @endphp
                        <span class="text-primary">Partially Approved</span>
                      @endif

                    </td>
                    
                    <td class="text-center">
                      @if(in_array(session('user')->employee_type_id, [1,2]) && $row->is_deleted == 1)
                      <a href="javascript:(0)" id="{{ $row->OT_ID.md5($row->OT_ID) }}" class="text-danger deleteOT" title="Delete OT">
                        <span class="far fa-trash-alt"></span>
                      </a>
                      @endif
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

<!-- Start Modal -->
<div class="modal fade" id="OTMODAL" tabindex="-1" role="dialog" aria-labelledby="employeeTableLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 80% !important;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="employeeTableLabel">Are you Sure to Approve OT?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form form-material  action="{{ url('/approved_ot') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive m-t-40">
                <table id="modalTable" class="table table-sm table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th hidden></th>
                      <th>Name</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Hours</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="btn_modal_close" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Approve</button>
        </div>
      </form>   
    </div>
  </div>
</div>
<!-- End Modal -->

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
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/overtime.js') }}"></script>
<script>
  function approveOT(ot_id,emp_name,time_start,time_end,ot_hours){
    var cb_id = "#cb_"+ot_id;
    var row_id = "modal_ot_"+ot_id;
    var remove_id = "#modal_ot_"+ot_id;
    var ot_status = 0;
    if ($(cb_id).is(":checked")){
      ot_status = 1;
    }
    if (ot_status == 1) {
      var add_ot = "<tr id='"+row_id+"'>\
                      <td>\
                        <input type='text' id='ot_id[]' name='ot_id[]' value='"+ot_id+"'  hidden/>\
                        "+emp_name+"\
                      </td>\
                      <td>\
                        "+time_start+"\
                      </td>\
                      <td>\
                        "+time_end+"\
                      </td>\
                      <td>\
                        "+ot_hours+"\
                      </td>\
                  </tr>";

      $('#modalTable > tbody:last-child').append(add_ot);
    }
    else
      $(remove_id).remove();
  }

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}