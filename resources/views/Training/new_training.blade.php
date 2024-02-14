

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
@section('title','Add New Training')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <a href="{{ url('/trainings') }}" title="Go to list">
                    <button class="btn btn-sm btn-link"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                </a>
            </div>  
            <div class="col-4 text-center">
                <h3>New Training</h3>
            </div>
            <div class="col-4"></div>
        </div>
        <form id="" action="{{ route('add_new_traning') }}" method="POST">
            @csrf
            {{-- <input type="text" name="emp_id" value="{{ session('user')->emp_id }}" hidden>
            <input type="text" name="employee_number" value="{{ session('employee')->UserID_Empl }}" hidden> --}}
            <div class="row">
                
                <div class="col-3"></div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label">Select date <small class="text-danger">*</small></label>
                        <input type="date" class="form-control" id="tr_date" value="{{ old('tr_date') }}" name="tr_date" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label">Time <small class="text-danger">*</small></label>
                        <input type="time" id="tr_time" name="tr_time" value="{{ old('tr_time', date('h:i:A')) }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-3"></div>

                <div class="col-3"></div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Display Name</label>
                        <input type="input" id="tr_display_title" name="tr_display_title" value="{{ old('tr_display_title')}}" class="form-control">
                    </div>
                </div>
                <div class="col-3"></div>

                <div class="col-3"></div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Training Name <small class="text-danger">*</small></label>
                        <input type="input" id="tr_name" name="tr_name" value="{{ old('tr_name')}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-3"></div>

                <div class="col-3"></div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Trainer/s <small class="text-danger">*</small></label>
                        <input type="input" id="trainers" name="trainers" value="{{ old('trainers')}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-3"></div>

                <div class="col-3"></div>
                <div class="col-6">
                    <label class="control-label">Description <small class="text-danger">*</small></label>
                    <textarea class="form-control" id="text_area" name="tr_description" placeholder="Type in your message" rows="5" maxlength="1000" required>{{ old('tr_description') }}</textarea>
                    <h6 class="pull-right mt-1" id="count_message"></h6>
                </div>
                <div class="col-3"></div>

                

                <div class="col-12">
                    <div class="text-center">
                        <button type="button" id="sub_btn_save" class="btn btn-primary mt-0 mb-2">Add Training</button>
                        <button type="submit" id="btn_save_trig" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
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

    $(document).ready(function(){

        let text_max = 1000;
        $('#count_message').html(text_max + ' remaining');
        $('#text_area').keyup(function() {
            var text_length = $('#text_area').val().length;
            var text_remaining = text_max - text_length;
            $('#count_message').html(text_remaining + ' remaining');
        }); 

        $(document).on('click', '#sub_btn_save', function(e) {

            offBeforeunload();
                // Trigger form submit button
            $('#btn_save_trig').click();

            // let tr_date = $('#tr_date').val();
            // let myDate = new Date(tr_date);
            // let today = new Date();
            
            // if ( myDate >= today ) { 
            //     offBeforeunload();
            //     // Trigger form submit button
            //     $('#btn_save_trig').click();
            // }
            // else{
            //     alert('The chosen date is invalid.');
            // }

        });

        $(document).on('keydown', 'input[pattern]', function(e){
            let input = $(this);
            let oldVal = input.val();
            let regex = new RegExp(input.attr('pattern'), 'g');

            setTimeout(function(){
                let newVal = input.val();
                if(!regex.test(newVal)){
                input.val(oldVal); 
                }
            }, 1);
        });

    });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}