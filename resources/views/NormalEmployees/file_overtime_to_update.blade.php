

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
@section('title','Overtime Form')
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
            <form id="file_overtime_update" action="{{ route('file_overtime_update') }}" method="POST">
                @csrf
                <input type="text" name="id" value="{{ $overtime->OT_ID }}" hidden>
                @isset($approver_update)
                    <input type="text" name="approver_update" value="1" hidden>
                @endisset
                {{-- <input type="text" name="employee_id" value="{{ session('user')->emp_id }}" hidden>
                <input type="text" name="employee_number" value="{{ session('employee')->UserID_Empl }}" hidden> --}}
                <div class="row">
                    <div class="col-lg-12 col-sm-12 text-center mb-5">
                        <h3>Update Overtime Request</h3>
                        @isset($employee)
                            <b>{{ $employee->Name_Empl }}</b>
                        @endisset
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Select date: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected" value="{{ date('Y-m-d', strtotime($overtime->dtr_date)) }}" name="date_selected" {{ ($checkApplication) ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Total no. of hours:</label>
                            <input type="text" id="total_hours" name="total_hours" value="{{ $overtime->OT_Hours }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>

                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Time from: <small class="text-danger">*</small></label>
                            <input type="time" id="time_from" name="time_from" value="{{ date('H:i', strtotime($overtime->Time_Start)) }}" onchange="get_diff()" class="form-control" {{ ($checkApplication) ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Time to: <small class="text-danger">*</small></label>
                            <input type="time" id="time_to" name="time_to" value="{{ date('H:i', strtotime($overtime->Time_End)) }}" onchange="get_diff()" class="form-control" {{ ($checkApplication) ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>

                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-6 col-sm-12">
                        <label class="control-label">Remarks <small class="text-danger">*</small></label>
                        <textarea class="form-control" id="text_area" name="remarks" placeholder="Type in your message" rows="5" maxlength="100" {{ ($checkApplication) ? 'readonly' : '' }} required>{{$overtime->remarks}}</textarea>
                        <h6 class="pull-right mt-1" id="count_message"></h6>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="text-center">
                            @if($checkApplication)
                                <button type="button" class="btn btn-dark mt-0 mb-2 disabled"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                            @else
                                <button type="button" id="sub_btn_save_overtime" class="btn btn-dark mt-0 mb-2"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                                <button type="submit" id="btn_save_overtime" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
                            @endif
                        </div>
                    </div>
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
    function offBeforeunload() {
        $(window).off('beforeunload');
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

    $(document).ready(function(){

        $("#file_overtime_update").submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: "Would you like to update the overtime?",
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
                        $("#file_overtime_update").unbind('submit').submit();
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

        $(document).on('click', '#sub_btn_save_overtime', function(e) {

            let date_selected = $('#date_selected').val();
            let myDate = new Date(date_selected);
            let today = new Date();

            if ( myDate > today ) { 
                alert('You cannot enter a date in the future!');
            }
            else{
                offBeforeunload();
                // Trigger form submit button
                $('#btn_save_overtime').click();
            }

        });

    });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}