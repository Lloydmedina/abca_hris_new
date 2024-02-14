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

    @if($checkApplication)
        <div class="alert alert-warning text-dark fade show" role="alert">
            {{ $checkApplication }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form id="file_leave_update" action="{{ route('file_leave_update') }}" method="POST">
                @csrf
                <input type="text" name="id" value="{{ $leave->leave_app_id }}" hidden>
                @isset($approver_update)
                    <input type="text" name="approver_update" value="1" hidden>
                @endisset
                <div class="row mb-sm-3">
                    <div class="col-12 text-center mb-5">
                        <h3>Update Leave Request</h3>
                        @isset($employee)
                            <b>{{ $employee->Name_Empl }}</b>
                        @endisset
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Leave type: <small class="text-danger">*</small></label>
                            <select class="form-control w-100" id="leave_type_id" name="leave_type_id" {{ ($checkApplication) ? 'disabled' : '' }} required>
                                <option value="" selected disabled>Select Leave Type</option>
                                @foreach($leave_type as $row)
                                    @if ($leave->leave_type_id == $row->leave_number)
                                        <option value="{{ $row->leave_number }}" selected>{{ $row->leave_type }}</option>
                                    @else
                                        <option value="{{ $row->leave_number }}">{{ $row->leave_type }}</option>
                                    @endif
                                @endforeach
                                </select>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <input class="mt-lg-5" {{ ($leave->is_half_day == 1) ? ' checked ' : '' }} style="width: 18px; /*Desired width*/height: 18px; /*Desired height*/" type="checkbox" value="{{ $leave->is_half_day }}" id="is_half_day" name="is_half_day" {{ (old('is_half_day') == 1) ? ' checked' : '' }} {{ ($checkApplication) ? 'disabled' : '' }} /> <span>Half day</span>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                </div>

                {{-- for half day --}}
                <div class="row  {{ ($leave->is_half_day == 1) ? '' : 'd-none' }}" id="half_day_row">
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Select date: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected" value="{{ date('Y-m-d', strtotime($leave->leave_date_from)) }}" name="date_selected" {{ ($checkApplication) ? 'readonly' : '' }}>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">No. of hours:</label>
                            <input type="text" id="total_hours" name="total_hours" value="{{ $leave->total_hours }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Time from: <small class="text-danger">*</small></label>
                            <input type="time" id="time_from" name="time_from" value="{{ date('H:i', strtotime($leave->time_from)) }}" onchange="get_diff()" class="form-control" {{ ($checkApplication) ? 'readonly' : '' }}>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Time to:</label>
                            <input type="time" id="time_to" name="time_to" value="{{ date('H:i', strtotime($leave->time_to)) }}" onchange="get_diff()" class="form-control" {{ ($checkApplication) ? 'readonly' : '' }}>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-sm-12"></div>
                </div>

                {{-- For whole day --}}
                <div class="row {{ ($leave->is_half_day == 0) ? '' : 'd-none' }}" id="whole_day_row">
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date from: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected_from" value="{{ date('Y-m-d', strtotime($leave->leave_date_from)) }}" name="date_selected_from" {{ ($checkApplication) ? 'readonly' : '' }}>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date to: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected_to" value="{{ date('Y-m-d', strtotime($leave->leave_date_to)) }}" name="date_selected_to" {{ ($checkApplication) ? 'readonly' : '' }}>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">No. of days:</label>
                            <input type="text" id="no_of_days" name="no_of_days" value="{{ $leave->no_of_days }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-6">

                        <div class="form-group">
                            <label class="control-label">Reason <small class="text-danger">*</small></label>
                            <textarea class="form-control" id="text_area" name="remarks" placeholder="Type in your message" rows="5" maxlength="100" {{ ($checkApplication) ? 'readonly' : '' }} required>{{ $leave->remarks }}</textarea>
                            <h6 class="pull-right mt-1" id="count_message"></h6>
                        </div>
                            
                        <div class="text-center">
                            @if($checkApplication)
                                <button type="button" class="btn btn-dark mt-0 mb-2 disabled"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                            @else
                                <button type="button" id="sub_btn_save_leave" class="btn btn-dark mt-0 mb-2"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                                <button type="button" class="dummy_button btn btn-dark mt-0 mb-2 disabled d-none" style="cursor: not-allowed;"><i class="fa-solid fa-floppy-disk"></i> Updating please wait</button>
                                <button type="submit" id="btn_save_leave" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
                            @endif
                        </div>

                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                </div>

            </form>
        </div>
    </div>
    <hr>

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

    $("#file_leave_update").submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: "Would you like to update the leave?",
            // text: "Would you like to request a schedule change?",
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
                    $('#sub_btn_save_leave').addClass('d-none');
                    $('.dummy_button').removeClass('d-none');
                    $("#file_leave_update").unbind('submit').submit();
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

});

    function calculateFiledLeaveDays(startDate, endDate) {
        const oneDay = 24 * 60 * 60 * 1000; // Number of milliseconds in a day
        const start = new Date(startDate);
        const end = new Date(endDate);

        const timeDifference = Math.abs(end - start);
        const totalDays = Math.floor(timeDifference / oneDay) + 1; // Including both start and end days

        return totalDays;
    }

    function get_no_days(date_from, date_to){

        var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date(date_from);
        var secondDate = new Date(date_to);
        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay))) + 1;
        var countSun = countSundays(firstDate, secondDate);

        return diffDays - countSun;
        // return diffDays;
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