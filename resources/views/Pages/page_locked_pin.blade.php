@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/css/custom/settings.css') }}" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

@endsection
{{-- END PAGE LEVEL CSS --}}
@if($current_module)
    @section('title','Locked - '. $current_module->module)
@else
    @section('title','404')
@endif
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @if($current_module)

        @include('Templates.alert_message')

        <div class="row">
            <div class="col-lg-4 col-sm-12"></div>
            <div class="col-lg-4 col-sm-12">
                <div class="card mt-5 shadow-sm">
                    
                    <div class="card-body text-center">
                        <center><h3><i class="fa-solid fa-lock" id="key_logo"></i></h3></center>
                        <div class="form-group">
                            <label class="control-label">Enter PIN</label>
                            <br>
                            <p><small class="text-muted">A PIN is required to unlock the content. Please make sure to enter the correct PIN to gain access.</small></p>
                            <input type="text" id="pin_required" name="pin" value="" class="form-control" required />
                            <br>
                        </div>
                        <span class="text-danger" id="header_err_pin_level"></span>
                        <span class="text-primary" id="header_success_pin_level"></span>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-sm-12"></div>
        </div>

    @else
        @include('errors.404');
    @endif


</div>
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/vendor/pincodeinput/bootstrap-pincode-input.js') }}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')

<script>

    $(document).ready(function(){


        // setTimeout(() => {
        //     $('.card').effect( "shake", {times:4}, 700 );
        // }, 600);

        $("#pin_required").pincodeInput({ 
            hidedigits: true, 
            inputs: 6, 
            inputclass: "form-control-md",
            change: function(){

            },
            complete: function(input){
                checkPin(input);
                // $('.btn_submit_pin').click();
            }
        });

        function checkPin(pin){

            $('#header_success_pin_level').text('');
            $('#header_err_pin_level').text('');
            
            // do ajax
            $.ajax({
                url: "{{ route('page_unlock') }}",
                type: 'POST',
                data: {pin:pin, route_name: "{{ Request::get('m') }}"},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function(){
                    $('#key_logo').effect( "bounce", {times:4}, 700 );
                },
                success: function(result) {

                    if(result.code == 1){
                        $('#header_success_pin_level').text(result.message);

                        $('#key_logo').removeClass('fa-lock').addClass('fa-unlock text-primary').effect( "bounce", {times:4}, 700 );

                        setTimeout(() => {
                            window.location.reload(true);
                        }, 2000);
                    }
                    else if(result.code == 0){
                        $('#header_err_pin_level').text(result.message);
                        $('#pin_required').pincodeInput().data('plugin_pincodeInput').clear();
                        $('#pin_required').pincodeInput().data('plugin_pincodeInput').focus();
                        $('.card').effect( "shake", {times:2}, 300 );
                    }
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