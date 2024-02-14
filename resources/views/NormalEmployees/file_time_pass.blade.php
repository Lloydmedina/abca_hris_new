@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<style>
    #text_area {
    resize: none;
    margin-botton:10px;
}
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Time Pass Slip Form')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">
            <form id="file_time_pass_request" action="{{ route('file_time_pass_request') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h3>Time Pass Slip Request</h3>
                    </div>

                    @if(session('is_approver'))
                        <div class="col-lg-3 col-sm-12"></div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Staff <small class="text-danger">*</small></label> <i style="cursor: help" class="fa-solid fa-circle-question fa-sm" title="You are assigned to these staff as Approver 1."></i>
                                <select name="emp_id" class="border form-control custom-select selectpicker" data-live-search="true" required>
                                    <option selected disabled value="">Select Staff</option>
                                    @foreach($staffs as $row)
                                        <option value="{{ $row->SysPK_Empl }}" {{ (app('request')->input('emp_id') == $row->SysPK_Empl) ? ' selected' : '' }}>{{ $row->Name_Empl }}</option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12"></div>
                    @endif

                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Select date: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected" value="{{ old('date_selected', date('Y-m-d')) }}" name="date_selected" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Select Time: <small class="text-danger">*</small></label>
                            <input type="time" class="form-control" id="time_selected" value="{{ old('time_selected', date('Y-m-d')) }}" name="time_selected" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label for="exampleFormControltime_type">Select log type: <small class="text-danger">*</small></label>
                            <select name="time_type" class="form-control" id="exampleFormControltime_type" required>
                                <option selected disabled value="">Select</option>
                                <option value="0" {{ old('time_type') == '0' ? 'selected' : '' }}>Time-In</option>
                                <option value="1" {{ old('time_type') == '1' ? 'selected' : '' }}>Time-Out</option>
                            </select>
                            </div>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-6 col-sm-12">
                        <label class="control-label">Remarks <small class="text-danger">*</small></label>
                        <textarea class="form-control" id="text_area" name="remarks" placeholder="Type in your message" rows="5" maxlength="100" required>{{ old('remarks') }}</textarea>
                        <h6 class="pull-right mt-1" id="count_message"></h6>
                    </div>
                    <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-12">
                        <div class="text-center">
                            <button type="button" id="sub_btn_save_pass_time" class="btn btn-dark mt-0 mb-2"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                            <button type="submit" id="btn_save_pass_time" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
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

        $("#file_time_pass_request").submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: "Would you like to submit your pass slip?",
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
                        $("#file_time_pass_request").unbind('submit').submit();
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

        $(document).on('click', '#sub_btn_save_pass_time', function(e) {

            let date_selected = $('#date_selected').val();
            let myDate = new Date(date_selected);
            let today = new Date();
            
            if ( myDate > today ) { 
                alert('The chosen date must be less than or equal to the current date.');
            }
            else{
                offBeforeunload();
                // Trigger form submit button
                $('#btn_save_pass_time').click();
            }

        });

    });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}