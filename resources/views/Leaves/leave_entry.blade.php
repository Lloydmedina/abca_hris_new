@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>
#text_area {
    resize: none;
    margin-botton:10px;
}
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Leave Form')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">
            <form id="save_leaves" action="{{ route('save_leaves') }}" method="POST">
                @csrf
                <div class="row mb-sm-3">
                    <div class="col-12 text-center mb-5">
                        <h3>Leave Request</h3>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Leave type: <small class="text-danger">*</small></label>
                            <select class="form-control w-100" id="leave_type_id" name="leave_type_id" required>
                                <option value="" selected disabled>Select Leave Type</option>
                                @foreach($leave_type as $row)
                                    @if (old('leave_type_id') == $row->leave_number)
                                        <option data-default_days="{{$row->default_days}}" value="{{ $row->leave_number }}" selected>{{ $row->leave_type }}</option>
                                    @else
                                        @if($row->leave_number == 5 && $checkBdayLeave && $isEmployeeDateHireEqualOrMorethan180Days)
                                            <option value="" title="{{ $checkBdayLeaveMessage }}" disabled>{{ $row->leave_type }} - (N/A)</option>
                                        @else
                                            @if($row->withPay == 1 && !$isEmployeeDateHireEqualOrMorethan180Days)
                                                <option value="" title="N/A" disabled>{{ $row->leave_type }} - (N/A)</option>
                                            @else
                                                <option data-default_days="{{$row->default_days}}" value="{{ $row->leave_number }}" >{{ $row->leave_type }}</option>
                                            @endif
                                            
                                        @endif
                                    @endif
                                @endforeach
                                </select>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <input class="mt-lg-5" style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="0" id="is_half_day" name="is_half_day" {{ (old('is_half_day') == 1) ? ' checked' : '' }} /> <span>Half day</span>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                </div>
                {{-- for half day --}}
                <div class="row d-none " id="half_day_row">
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Select date: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected" value="{{ old('date_selected', date('Y-m-d')) }}" name="date_selected">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">No. of hours</label>
                            <input type="text" id="total_hours" name="total_hours" value="{{ old('total_hours') }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Time from: <small class="text-danger">*</small></label>
                            <input type="time" id="time_from" name="time_from" value="{{ old('time_from', date('HH:mm')) }}" onchange="get_diff()" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Time to</label>
                            <input type="time" id="time_to" name="time_to" value="{{ old('time_to', date('HH:mm')) }}" onchange="get_diff()" class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-sm-12"></div>
                </div>


                {{-- For whole day --}}
                <div class="row " id="whole_day_row">
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date from: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected_from" value="{{ old('date_selected_from', date('Y-m-d')) }}" name="date_selected_from">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date to: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected_to" value="{{ old('date_selected_to') }}" name="date_selected_to">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">No. of day/s</label>
                            <input type="text" id="no_of_days" name="no_of_days" value="{{ old('no_of_days') }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>

                    
                </div>

                @if(in_array(session('user')->employee_type_id, array(1,2)))
                    <center>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#employeeTable">Select employee</button>
                    </center>
                @endif

                
                @if(in_array(session('user')->employee_type_id, array(1,2)))
                    {{-- Employee list absenteeism --}}
                    <div class="card d-none" id="emp_tbl_list">
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="" class="table table-sm table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_employee_selected">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" id="sub_btn_save_leave" class="btn btn-dark mt-0 mb-2"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                            <button type="button" class="dummy_button btn btn-dark mt-0 mb-2 disabled d-none" style="cursor: not-allowed;"><i class="fa-solid fa-floppy-disk"></i> Saving please wait</button>
                            <button type="submit" id="btn_save_leave" class="btn btn-dark mt-0 mb-2 d-none">Save</button>
                        </div>
                    </div>
                @endif

                @if(!in_array(session('user')->employee_type_id, array(1,2)))
                    {{-- For normal employee --}}
                    <div class="row">
                        <div class="col-lg-3 col-sm-12"></div>
                        <div class="col-lg-6">

                            <div class="form-group">
                                <label class="control-label">Reason <small class="text-danger">*</small></label>
                                <textarea class="form-control" id="text_area" name="remarks[]" placeholder="Type in your message" rows="5" maxlength="100" required></textarea>
                        <h6 class="pull-right mt-1" id="count_message"></h6>
                            </div>
                            <input type="text" name="employee_id[]" value="{{ session('user')->emp_id }}" hidden>
                            <input type="text" name="employee_number[]" value="{{ session('employee')->UserID_Empl }}" hidden>
                                
                            <div class="text-center">
                                <button type="button" id="sub_btn_save_leave" class="btn btn-dark mt-0 mb-2"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                                <button type="button" class="dummy_button btn btn-dark mt-0 mb-2 disabled d-none" style="cursor: not-allowed;"><i class="fa-solid fa-floppy-disk"></i> Saving please wait</button>
                                <button type="submit" id="btn_save_leave" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
                            </div>

                        </div>
                        <div class="col-lg-3 col-sm-12"></div>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <hr>

    <!-- Modal -->
    <div class="modal fade" id="employeeTable" tabindex="-1" role="dialog" aria-labelledby="employeeTableLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 80% !important;" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeTableLabel">Employee list</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="selected_employee_form" action="{{ url('absenteeism') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="myTable" class="table table-sm table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th class="text-center">
                                                &nbsp;&nbsp; <input type="checkbox" style="top: .8rem;width: 1rem;height: 1rem;" value="1" name="checked_all" id="checked_all"></input>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($employees) > 0)
                                            @foreach($employees as $employee)
                                            <tr>
                                                <td>{{ $employee->Name_Empl }}</td>
                                                <td>{{ $employee->Position_Empl }}</td>
                                                <td>{{ $employee->Department_Empl }}</td>
                                                <td align="center">
                                                    <input type="checkbox" class="checked" style="top: .8rem;width: 1rem;height: 1rem;" value="{{ $employee->SysPK_Empl }}" name="checked[]"></input>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn_modal_close" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Select</button>
                </div>
            </form>
            
            </div>
        </div>
    </div>

</div>

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

    $("#save_leaves").submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: "Would you like to submit your leave?",
            // text: "Would you like to request a schedule change?",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-floppy-disk fa-xs text-dark"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, submit!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
        }).then(function(result) {
            
            if (result.value) {

                Swal.fire({
                    width: "350",
                    title: 'Saving...',
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
                    $('#sub_btn_save_leave').addClass('d-none');
                    $('.dummy_button').removeClass('d-none');
                    $("#save_leaves").unbind('submit').submit();
                });
                
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });

    let text_max = 100;
    $('#count_message').html(text_max + ' remaining');
    $('#text_area').keyup(function() {
        var text_length = $('#text_area').val().length;
        var text_remaining = text_max - text_length;
        $('#count_message').html(text_remaining + ' remaining');
    }); 

    let is_hlf_dy = "<?php echo old('is_half_day')?>";


    if(is_hlf_dy == ''){

        $('#is_half_day').val(0);
        $('#half_day_row').addClass('d-none');
        $('#whole_day_row').removeClass('d-none');

        }else{

        $('#is_half_day').val(1);
        $('#whole_day_row').addClass('d-none');
        $('#half_day_row').removeClass('d-none');
    }

    // delete Leave

    $(document).on('click', '.deleteLeave', function(e) {
        
        let id = $(this).attr('id');
        let url = base_url + 'delete_leave';
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
                        $('#'+id).hide();
                    }
                },
                error: function(result){
                    console.log(result);
                }

            });
        }

    });

    // check all

    // Selected date validation
    $(document).on('click', '#sub_btn_save_leave', function(e) {

        // let date_selected = $('#date_selected').val();
        // let myDate = new Date(date_selected);
        // let today = new Date();
        
        // if ( myDate.getTime() > today.getTime() ) { 
        //     alert('The chosen date must be a future.');
        // }
        // else{
        //     // Trigger form submit button
        //     // offBeforeunload();
        //     $('#btn_save_leave').click();
        // }
        $('#btn_save_leave').click();

    });

    $(document).on('change', '#date_selected_from, #date_selected_to', function(){

        let date_from = $('#date_selected_from').val();
        let date_to = $('#date_selected_to').val();

        if(date_to && date_from){
            
            $('#no_of_days').val(calculateFiledLeaveDays(date_from, date_to));
        }

    });

    // Check if the checkbox hallday was checked
    $(document).on('change', '#is_half_day', function(e){

        let is_half_day = $('#is_half_day').val();

        if(is_half_day == 1){

            $('#is_half_day').val(0);
            $('#half_day_row').addClass('d-none');
            $('#whole_day_row').removeClass('d-none');

        }else{

            $('#is_half_day').val(1);
            $('#whole_day_row').addClass('d-none');
            $('#half_day_row').removeClass('d-none');
        }

        
    });


    let leave_tp_id = "<?php echo old('leave_type_id')?>";

    if(leave_tp_id == 5){

        $('#is_half_day').attr('disabled', true).prop('checked', false);
        $( "#date_selected_to" ).prop( "disabled", true );

        $('#is_half_day').val(0);
        $('#half_day_row').addClass('d-none');
        $('#whole_day_row').removeClass('d-none');

        $('#date_selected_to').val($('#date_selected_from').val());
        $('#no_of_days').val(1);


    }
    else{
        $('#is_half_day').removeAttr("disabled");
        $('#date_selected_to').removeAttr("disabled");

        $('#date_selected_to').val('');
        $('#no_of_days').val('');
    }

    $(document).on('change', '#leave_type_id, #date_selected_from', function(e){

        let selectedOption = $('#leave_type_id option:selected');
        let default_days = selectedOption.data('default_days');
        let leave_type = this.value;

        let currentSelectedDateFrom = new Date($('#date_selected_from').val());
        let additionalDays = (default_days > 1) ? default_days - 1 : default_days;
        currentSelectedDateFrom.setDate(currentSelectedDateFrom.getDate() + additionalDays);
        let formattedDate = currentSelectedDateFrom.toISOString().split('T')[0];

        if(leave_type == 5){

            $('#is_half_day').attr('disabled', true).prop('checked', false);
            $( "#date_selected_to" ).prop( "disabled", true );

            $('#is_half_day').val(0);
            $('#half_day_row').addClass('d-none');
            $('#whole_day_row').removeClass('d-none');

            // $('#date_selected_to').val($('#date_selected_from').val());
            // $('#no_of_days').val(1);

        }
        else{
            $('#is_half_day').removeAttr("disabled");
            $('#date_selected_to').removeAttr("disabled");

            // $('#date_selected_to').val('');
            // $('#no_of_days').val('');
        }

        // if(default_days == 1){
        //     $('#date_selected_to').val($('#date_selected_from').val());
        // }
        // else{

        // }

        $('#date_selected_to').val(formattedDate);
        $('#no_of_days').val(default_days);
    });



    // $(document).on('change', '#date_selected_from', function(e){
    //     let leave_type = $('#leave_type_id').val();

    //     if(leave_type == 5){
    //         $('#date_selected_to').val(this.value);
    //         $( "#date_selected_to" ).prop( "disabled", true );
    //         $('#no_of_days').val(1);
    //     }

    // });

});


// function calculateFiledLeaveDays(startDate, endDate) {
//   const oneDay = 24 * 60 * 60 * 1000; // Number of milliseconds in a day
//   const start = new Date(startDate);
//   const end = new Date(endDate);

//   let totalDays = 0;
//   let currentDate = new Date(start);

//   while (currentDate <= end) {
//     const dayOfWeek = currentDate.getDay();
//     if (dayOfWeek !== 0 && dayOfWeek !== 6) { // Exclude weekends
//       totalDays++;
//     }
//     currentDate.setTime(currentDate.getTime() + oneDay);
//   }

//   return totalDays;
// }

function calculateFiledLeaveDays(startDate, endDate) {
  const oneDay = 24 * 60 * 60 * 1000; // Number of milliseconds in a day
  const start = new Date(startDate);
  const end = new Date(endDate);

  const timeDifference = Math.abs(end - start);
  const totalDays = Math.floor(timeDifference / oneDay) + 1; // Including both start and end days

  return totalDays;
}



function countSundays(startDate, endDate){
    var sundays = 0;
        for (var i = startDate; i <= endDate; i.setDate(i.getDate()+1)){
            if (i.getDay() == 0) sundays++;
        }
    return sundays;
    }

    function get_diff() {
    var number_of_hours = 0;

    var start ="00:00";
    var end ="00:00";

    try {
    start = $('#time_from').val();
    end = $('#time_to').val();
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
        $('#total_hours').val(0);
    }else{
        $('#total_hours').val(number_of_hours);
    }
}
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}