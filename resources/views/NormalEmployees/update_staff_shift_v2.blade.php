@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>

</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Update Shift')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
        @php
            $monthSelected = $month ?? date('n');
            $yearSelected = $year ?? date('Y');
        @endphp

        <div class="row">

            <div class="col-lg-4 col-sm-12">
                <div class="form-group">
                        <label class="control-label">Employee</label>
                        <div><b>{{ $isEditableByApprover1->Name_Empl }}</b></div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-12">
                <div class="form-group">
                    <label class="control-label">Month</label>
                    @foreach($months as $i => $month)
                        @if($i == $monthSelected)
                            <div><b>{{ $month }}</b></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="col-lg-2 col-sm-12">
                <div class="form-group">
                        <label class="control-label">Year</label>
                    @foreach($years as $i => $year)
                        @if(($i == $yearSelected))
                            <div><b>{{ $year }}</b></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="col-lg-3 col-sm-12">
                <div class="form-group">
                    <label class="control-label">Status</label>
                    <br>
                    <a href="{{ request()->fullUrlWithQuery(['status' => !$status]) }}">
                        <b>{{ ($status != 1) ? "Hide" : "Show" }} inactive status</b>
                    </a>
                </div>
            </div>
        </div>
           
      </div>
   </div>

   <hr>

   <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <h4 class="card-title">Total Shift <small>{{ $totalShifts }}</small></h4>
            </div>
            <div class="col-lg-4 col-sm-12 text-center mb-2">
            </div>
            <div class="col-lg-4 col-sm-12 text-lg-right">
                @if(count($duplicateEntries))
                    <button class="btn btn-sm btn-dark fix_staff_shift_duplicate_entries"><i class="fa-solid fa-wrench"></i> Fix duplicate entries</button>
                    {{-- <a id="fix_staff_shift_duplicate_entries" class="d-none" href="{{ url('/fix-staff-shift') }}?emp_id={{ $emp_id }}&date_from={{ $date_from }}&date_to={{ $date_to }}">Fix duplicate entries</a> --}}

                    <form class="d-none" action="{{ url('/fix-staff-shift') }}" method="POST" id="fix_staff_shift_duplicate_entries">
                        @csrf
                        <input type="text" name="emp_id" value="{{ $emp_id }}">
                        <input type="date" name="date_from" value="{{ $date_from }}">
                        <input type="date" name="date_to" value="{{ $date_to }}">
                        <button id="click_fix_schedule" type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="text-center">Shift Code</th>
                        <th>Created At</th>
                        <th class="text-center">Status</th>
                        <th class="text-center"><i class="fa-solid fa-lock text-dark"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ctr = 0;
                    @endphp
                    @if(count($shifts))
                        <form id="update_emp_shift_v2" action="{{ url('update_emp_shift_v2') }}" method="post">
                            @csrf
                            @foreach ($shifts as $row)
                            <input type="text" name="u_shift_id[]" value="{{ $row->id }}" hidden>
                                @php
                                    $title = "";
                                    $borderStyle = "";

                                    if($row->allow_update == 0){
                                        $borderStyle = "border: 2px solid black;";
                                        $title = "This item is currently locked and cannot be updated at this time. If you have any questions or concerns, please feel free to reach out for assistance. Thank you for your understanding.";
                                    }
                                @endphp

                                <tr title="{{ $title }}" style="{{ $borderStyle }}">
                                    <td>{{ date('M d, Y', strtotime($row->shift_date)) }}</td>
                                    <td class="text-center">
                                        <select name="shift_code[]" class="btn btn-sm btn-mini" required>
                                            <option value="" disabled>Shift Code</option>
                                            @foreach($shift_codes as $row2)
                                                @if($row->allow_update == 1 )
                                                    <option value="{{ $row2->id }}" {{ ($row2->shift_code == $row->shift_code) ? "selected" : "" }}>{{ $row2->shift_code }} ({{ $row2->Description }})</option>
                                                @elseif($row2->shift_code == $row->shift_code)
                                                    <option value="{{ $row2->id }}" {{ ($row2->shift_code == $row->shift_code) ? "selected" : "" }}>{{ $row2->shift_code }} ({{ $row2->Description }})</option>
                                                @else
                                                    <option disabled value="{{ $row2->id }}">{{ $row2->shift_code }} ({{ $row2->Description }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ date('M d, Y H:i:s', strtotime($row->created_at)) }}</td>
                                    <td class="text-center">
                                        <input {{ ($row->allow_update == 0) ? "disabled" : "" }} style="width: 20px;height: 20px;" type="checkbox" name="status[{{$row->id}}]" {{ ($row->status == 1) ? "checked" : "" }} value="{{ $row->id }}">
                                    </td>
                                    <td class="text-center">
                                        @if($row->allow_update == 0)
                                            <i class="fa-solid fa-lock"></i>
                                        @else
                                            <i class="fa-solid fa-ellipsis"></i>
                                            @php
                                                $ctr++;
                                            @endphp
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <button type="submit" class="d-none" id="btn_submit_shift">Submit</button>
                        </form>
                    @else
                        <tr><td class="text-center" colspan="100">No record found. <br><a href="{{ url('/shift-entry-v2') }}">Add Shift</a></td></tr>
                    @endif
                </tbody>
            </table>
            <div class="float-left"> {{ $paginationLinks }} </div>
        </div>
        @if($isEditableByApprover1)
            @if($ctr > 0 && $totalShifts < 51 )
                <button type="button" id="btn_submit_shift_trigger" class="btn btn-dark float-right mt-3">Update</button>
            @endif
        @else
            <div class="alert alert-warning" role="alert">
                Unable to update this employee's shift.
            </div>
        @endif
    </div>
</div>

<hr>


</div>

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

    $('#btn_submit_shift_trigger').click(function() {
        $('#btn_submit_shift').click();
    });


    $("#update_emp_shift_v2").submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: "Would you like to update employee's shift?",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-floppy-disk fa-xs text-dark"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, update!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
        }).then(function(result) {
            
            if (result.value) {

                Swal.fire({
                    width: "350",
                    title: 'Updating...',
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
                    $("#update_emp_shift_v2").unbind('submit').submit();
                });
                
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });

    $('.fix_staff_shift_duplicate_entries').click(function(){
        $('#click_fix_schedule').click();
    });

    $("#fix_staff_shift_duplicate_entries").submit(function(e){
        
      e.preventDefault();
      Swal.fire({
         title: "Would you like to fix the duplicate schedule?",
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

            Swal.fire({
                width: "350",
                title: 'Fixing...',
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
                $("#fix_staff_shift_duplicate_entries").unbind('submit').submit();
            });

            } else if (result.dismiss === "cancel") {

            }
      });
    });

});


</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}