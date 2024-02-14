@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/css/custom/settings.css') }}" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Settings')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')
    
    <div class="alert_message_js alert text-info fade show d-none" role="alert">
        <span id="alert_message_js"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="row gutters-sm">
        <div class="col-md-4 d-none d-md-block">
            <div class="card">
                <div class="card-body">
                    <nav class="nav flex-column nav-pills nav-gap-y-1">
                        
                        <a href="#change_password"  data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded active">
                            <i class="fa-solid fa-wrench"></i>
                            Change Password
                        </a>
                        <a href="#security" id="security_a" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded">
                            <i class="fa-solid fa-shield-halved"></i>
                            Security
                        </a>

                        @if(!in_array(session('user')->employee_type_id, [1,2]))
                            <a href="#page_lock" data-toggle="tab" class="nav-item nav-link has-icon nav-link-faded">
                                <i class="fa-solid fa-lock"></i>
                                Page Lock
                            </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-bottom mb-3 d-flex d-md-none">
                    <ul class="nav nav-tabs card-header-tabs nav-gap-x-1" role="tablist">
                        <li class="nav-item">
                            <a href="#change_password" data-toggle="tab" class="nav-link has-icon active">
                                <i class="fa-solid fa-wrench"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#security" data-toggle="tab" class="nav-link has-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#page_lock" data-toggle="tab" class="nav-link has-icon">
                                <i class="fa-solid fa-lock"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active" id="change_password">
                        <h6>PASSWORD SETTINGS</h6>
                        <hr />
                        
                        <form id="change_pass_form" method="post" action="{{ route('change_pass') }}">
                            <span class="text-danger" id="header_err_pass_level"></span>
                            <span class="text-primary" id="header_success_pass_level"></span>
                            @csrf
                           
                            <div class="form-group">
                                <label class="control-label">Current Password</label>
                                <input type="password" id="current_password" name="current_password" value="" class="form-control" placeholder="***********" required />
                            </div>
                        
                            <div class="form-group">
                                <label class="control-label">New Password</label>
                                <input type="password" id="new_password" name="new_password" value="" class="form-control" placeholder="***********" required />
                            </div>
                        
                            <div class="form-group">
                                <label class="control-label">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" value="" class="form-control" placeholder="***********" required />
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="my_security_question">Security question <small class="text-danger">*</small></label>
                                <select name="question" class="form-control" id="my_security_question" required>
                                    <option selected disabled value="">Select question</option>
                                    @foreach ($my_question_and_answer as $row )
                                        <option value="{{ $row->id }}">{{ $row->question }}</option>
                                    @endforeach
                                </select>
                                <input type="text" id="my_security_asnwer" name="answer" class="form-control mt-3" placeholder="Your answer" required />
                            </div>
                                
                            <div class="text-center">
                                {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button> --}}
                                <button class="btn btn-danger mr-1" type="reset"><i class="fa-solid fa-eraser"></i> Cancel</button>
                                <button class="btn btn-success ml-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                            </div>
                        </form>
                        <hr>
                    </div>
                    
                    <div class="tab-pane" id="security">
                        <h6>SECURITY SETTINGS</h6>
                        <hr />
                        @if($my_pin)
                            {{-- for update pin --}}
                            <form id="set_pin_form" method="post" action="{{ route('change_pin') }}">
                            <input type="text" name="pin_id" value="{{ $my_pin->id }}" hidden>
                        @else
                            <form id="set_pin_form" method="post" action="{{ route('set_pin') }}">
                        @endif
                        
                            @csrf
                            <div class="form-group">
                                <label class="d-block">Two Factor Authentication</label>

                                @if($my_pin)
                                    <button class="btn btn-info" type="button" id="enable_pin_btn_2"><i class="fa-solid fa-wrench"></i> Change PIN</button>
                                @else
                                    <button class="btn btn-info" type="button" id="enable_pin_btn"><i class="fa-solid fa-wrench"></i> Enable PIN</button>
                                    <button class="btn btn-info d-none" id="change_pin_btn" type="button"><i class="fa-solid fa-wrench"></i> Change PIN</button>
                                @endif

                                <p class="small text-muted mt-2">A PIN (Personal Identification Number) in page lock is a numeric code used to secure and restrict access to a page or module. It serves as a password or authentication method to prevent unauthorized use or access.</p>
                                
                                <span class="text-danger" id="header_err_pin_level"></span>
                                <span class="text-primary" id="header_success_pin_level"></span>

                                <div class="row d-none" id="enable_pin_form">

                                    <div class="col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="my_security_question_2">Security question <small class="text-danger">*</small></label>
                                            <select name="question" class="form-control" id="my_security_question_2" required>
                                                <option selected disabled value="">Select question</option>
                                                @foreach ($my_question_and_answer as $row )
                                                    <option value="{{ $row->id }}">{{ $row->question }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" id="my_security_asnwer_2" name="answer" class="form-control mt-3" placeholder="Your answer" required />
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 col-sm-12 form_group_pin d-none">
                                        <div class="form-group">
                                            <label class="control-label">@if($my_pin) New @endif PIN</label>
                                            <input type="text" id="security_pin_1" name="security_pin_1" value="" class="form-control" required />
                                        </div>
    
                                    </div>

                                    <div class="col-lg-6 col-sm-12 form_group_pin d-none">
                                        <div class="form-group div_security_pin_2 d-none">
                                            <label class="control-label">Confirm @if($my_pin) New @endif PIN</label>
                                            <input type="text" id="security_pin_2" name="security_pin_2" value="" class="form-control" required />
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="text-left">
                                            <button class="btn btn-danger mt-2 mr-1 btn_cancel_pin" type="reset"><i class="fa-solid fa-eraser"></i> Cancel</button>

                                            <button class="btn btn-primary ml-2 d-none btn_submit_pin" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                
                                {{-- <div class="text-center">
                                    <button class="btn btn-danger mr-1" type="reset"><i class="fa-solid fa-eraser"></i> Cancel</button>
                                    <button class="btn btn-primary ml-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                                </div> --}}

                            </div>
                        </form>
                        <hr />
                    </div>
                    
                    @if(!in_array(session('user')->employee_type_id, [1,2]))
                        <div class="tab-pane" id="page_lock">
                            <h6>PAGE LOCK SETTINGS</h6>
                            <hr />
                            <form>
                                <div class="form-group">
                                    <label class="d-block mb-0">Secure Pages</label>
                                    <div class="small text-muted mb-3">The primary purpose of a page lock is to enhance privacy and prevent unauthorized individuals from accessing specific pages on your account.</div>
                                    
                                    <span class="text-danger" id="header_err_lock_level"></span>
                                    <span class="text-primary" id="header_success_lock_level"></span>

                                    @if($my_pin)

                                    @php
                                        $is_page_lock_access = session('page_lock_access') ?? null;
                                        $is_page_lock_access_class = $is_page_lock_access ? ' ' : 'd-none';
                                    @endphp

                                        @if(!$is_page_lock_access)
                                            <div class="row pin_required_row">
                                                <div class="col-md-3 col-sm-12"></div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="card mt-5 shadow-sm pin-card">
                            
                                                        <div class="card-body text-center">
                                                            <center><h3><i class="fa-solid fa-lock" id="key_logo"></i></h3></center>
                                                            <div class="form-group">
                                                                <label class="control-label">Enter PIN</label>
                                                                <br>
                                                                <p><small class="text-muted">A PIN is required to unlock the content. Please make sure to enter the correct PIN to gain access.</small></p>
                                                                <input type="text" id="pin_required" name="pin" value="" class="form-control" required />
                                                                <br>
                                                            </div>
                                                            <span class="text-danger" id="header_err_pin_level_2"></span>
                                                            <span class="text-primary" id="header_success_pin_level_2"></span>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-12"></div>
                                            </div>
                                        @endif
                                        
                                        <div class="row mt-3 page_items_row {{ $is_page_lock_access_class }}">

                                            @if(session('is_approver'))
                                                <div class="col-lg-6 col-sm-12">
                                            @else
                                                <div class="col-lg-12 col-sm-12">
                                            @endif
                                                    <ul class="list-group list-group-sm">
                                                        @foreach ($page_lock as $i => $row )

                                                            @if($row->is_approver == 0)
                                                                <li class="list-group-item has-icon">
                                                                    <div class="custom-control custom-control-nolabel custom-switch ml-auto">
                                                                        <input type="checkbox" class="custom-control-input lock_page" data-module_name="{{$row->module}}" value="{{ $row->id }}" id="custom_switch_{{ $row->id }}" @if($row->is_lock) checked="checked" @endif />
                                                                        <label style="cursor: pointer" class="custom-control-label" for="custom_switch_{{ $row->id }}"></label>
                                                                        <a title="Open" href="{{ route($row->route_name) }}" target="_blank">{!! $row->icon !!} {{ $row->module }}</a>
                                                                    </div>
                                                                </li>
                                                            @endif

                                                        @endforeach
                                                    </ul>
                                                </div>

                                            @if(session('is_approver'))
                                                <div class="col-lg-6 col-sm-12">
                                                    <ul class="list-group list-group-sm">
                                                        <li class="list-group-item has-icon">
                                                            <h6>Staff Management</h6>
                                                        </li>
                                                        @foreach ($page_lock as $i => $row )

                                                            @if($row->is_approver == 1)
                                                                <li class="list-group-item has-icon">
                                                                    <div class="custom-control custom-control-nolabel custom-switch ml-auto">
                                                                        <input type="checkbox" class="custom-control-input lock_page" data-module_name="{{$row->module}}" value="{{ $row->id }}" id="custom_switch_{{ $row->id }}" @if($row->is_lock) checked="checked" @endif />
                                                                        <label style="cursor: pointer" class="custom-control-label" for="custom_switch_{{ $row->id }}"></label>
                                                                        <a title="Open" href="{{ route($row->route_name) }}" target="_blank">{!! $row->icon !!} {{ $row->module }}</a>
                                                                    </div>
                                                                </li>
                                                            @endif

                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                        </div>
                                    @else
                                        <div class="alert alert-info" role="alert">
                                            Please note that you need to enable the PIN first in order to proceed.<button type="button" class="btn btn-link btn_enable_pin">Click here!</button>
                                        </div>
                                        
                                    @endif
                                    
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr>
  

</div>

<input type="text" id="my_question_and_answer" value="{{ count($my_question_and_answer) }}" hidden>
{{-- Security Question --}}
<div class="modal fade" id="security_question" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionModalLabel">Set your security question</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" id="security_question_x">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="set_question" method="post" action="{{ route('set_question') }}">
                <div class="modal-body">
                    {{-- <span class="text-danger" id="header_err_pass_level_"></span>
                    <span class="text-primary" id="header_success_pass_level_"></span> --}}
                    @csrf
                    <p class="font-size-sm text-secondary">
                        Security questions are a form of authentication used to verify a user's identity when accessing an account or resetting a password. These questions typically require the user to provide specific answers to predetermined questions that are known only to them. The purpose is to add an extra layer of security by confirming the user's identity through information that is not easily accessible to others. 
                    </p>

                    <b>Answer 3 Questions</b>

                    <input type="text" value="1" id="current_form_group" hidden>

                    <div class="mt-3">
                        <div class="form-group form_group_1">
                            <div class="form-group">
                                <label for="security_question_1">Question 1 <small class="text-danger">*</small></label>
                                <select name="questions[]" class="form-control security_question_select" id="security_question_1" required>
                                    <option disabled selected value="">Select question</option>
                                    @foreach ($security_questions as $row )
                                        <option class="question_{{ $row->id }}" value="{{ $row->id }}">{{ $row->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" id="security_answer_1" name="answers[]" class="form-control" placeholder="Answer 1" required />
                        </div>

                        <div class="form-group form_group_2 d-none">
                            <div class="form-group">
                                <label for="security_question_2">Question 2 <small class="text-danger">*</small></label>
                                <select name="questions[]" class="form-control security_question_select" id="security_question_2" required>
                                    <option disabled selected value="">Select question</option>
                                    @foreach ($security_questions as $row )
                                        <option class="question_{{ $row->id }}" value="{{ $row->id }}">{{ $row->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" id="security_answer_2" name="answers[]" class="form-control" placeholder="Answer 2" required />
                        </div>

                        <div class="form-group form_group_3 d-none">
                            <div class="form-group">
                                <label for="security_question_3">Question 3 <small class="text-danger">*</small></label>
                                <select name="questions[]" class="form-control security_question_select" id="security_question_3" required>
                                    <option disabled selected value="">Select question</option>
                                    @foreach ($security_questions as $row )
                                        <option class="question_{{ $row->id }}" value="{{ $row->id }}">{{ $row->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" id="security_answer_3" name="answers[]" class="form-control" placeholder="Answer 3" required />
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-6 text-right">
                            <i title="Back" style="cursor: pointer" class="back_button fa-solid fa-circle-chevron-left fa-xl mr-2 d-none"></i>
                        </div>
                        <div class="col-6 text-left">
                            <i title="Next" style="cursor: pointer" class="next_button fa-solid fa-circle-chevron-right fa-xl ml-2 d-none"></i>
                        </div>
                    </div>


                </div>
                <div class="modal-footer d-none div_footer">
                    {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button> --}}
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Security Question --}}


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

    let my_question_and_answer = $('#my_question_and_answer').val();
    
    if(my_question_and_answer === 0 || my_question_and_answer === '0'){
        // open modal to set up questions & answers
        $('#security_question').modal({
    		backdrop: 'static',
    		keyboard: false
		});
        $('#security_question_x').addClass("d-none");
    }

    $(document).on('click', '#enable_pin_btn, #enable_pin_btn_2', function(){
        $('#enable_pin_form').removeClass('d-none');
    });

    $(document).on('click', '.btn_cancel_pin', function(){
        $('#enable_pin_form').addClass('d-none');
        $('.form_group_pin').addClass('d-none');
    });

    // set pin
    $(document).on('change', '#my_security_question_2', function(){
        let my_security_question_2 = this.value;
        let my_security_asnwer_2 = $('#my_security_asnwer_2').val();

        if(my_security_question_2 && my_security_asnwer_2) $('.form_group_pin').removeClass('d-none');
        else $('.form_group_pin').addClass('d-none');
    });

    $(document).on('keyup', '#my_security_asnwer_2', function(){
        let my_security_asnwer_2 = this.value;
        let my_security_question_2 = $('#my_security_question_2').val();

        if(my_security_question_2 && my_security_asnwer_2) $('.form_group_pin').removeClass('d-none');
        else $('.form_group_pin').addClass('d-none');
    });




    // Remove item selction option if already selected
    $('.security_question_select').change(function() {
        $('.security_question_select option').show();
        $('.security_question_select').each(function(i, elt) {
            $('.security_question_select').not(this).find('option[value="'+$(elt).val()+'"]').hide();
        });
    });


    $(document).on('change', '#security_question_1', function(){
        let security_question_1 = this.value;
        let security_answer_1 = $('#security_answer_1').val();

        if(security_question_1 && security_answer_1) $('.next_button').removeClass('d-none');
        else $('.next_button').addClass('d-none');
    });

    $(document).on('keyup', '#security_answer_1', function(){
        let security_answer_1 = this.value;
        let security_question_1 = $('#security_question_1').val();

        if(security_question_1 && security_answer_1) $('.next_button').removeClass('d-none');
        else $('.next_button').addClass('d-none');
    });


    $(document).on('change', '#security_question_2', function(){
        let security_question_2 = this.value;
        let security_answer_2 = $('#security_answer_2').val();

        if(security_question_2 && security_answer_2) $('.next_button').removeClass('d-none');
        else $('.next_button').addClass('d-none');
    });

    $(document).on('keyup', '#security_answer_2', function(){
        let security_answer_2 = this.value;
        let security_question_2 = $('#security_question_2').val();

        if(security_question_2 && security_answer_2) $('.next_button').removeClass('d-none');
        else $('.next_button').addClass('d-none');
    });


    $(document).on('change', '#security_question_3', function(){
        let security_question_3 = this.value;
        let security_answer_3 = $('#security_answer_3').val();

        // show or hide footer
        if(security_question_3 && security_answer_3) $('.div_footer').removeClass('d-none');
        else $('.div_footer').addClass('d-none');
    });

    $(document).on('keyup', '#security_answer_3', function(){
        let security_answer_3 = this.value;
        let security_question_3 = $('#security_question_3').val();

        // show or hide footer
        if(security_question_3 && security_answer_3) $('.div_footer').removeClass('d-none');
        else $('.div_footer').addClass('d-none');
    });



    $(document).on('click', '.next_button', function(){
        let current_form_group = $('#current_form_group').val();

        if(current_form_group == 1 || current_form_group == '1'){
            // set 2
            $('#current_form_group').val(2);
            // hide form group 1
            $('.form_group_1').addClass('d-none');
            // show form group 2
            $('.form_group_2').removeClass('d-none');

            $('#security_answer_2').keyup();
            // show back button
            $('.back_button').removeClass('d-none');
        }
        else if(current_form_group == 2 || current_form_group == '2'){
            // set 3
            $('#current_form_group').val(3);
            // hide form group 2
            $('.form_group_2').addClass('d-none');
            // show form group 3
            $('.form_group_3').removeClass('d-none');

            // hide next button
            $(this).addClass('d-none');
            // show back button
            $('.back_button').removeClass('d-none');

            $('#security_answer_3').keyup();
        }
    });


    $(document).on('click', '.back_button', function(){
        let current_form_group = $('#current_form_group').val();

        // hide save button
        $('.div_footer').addClass('d-none');

        if(current_form_group == 3 || current_form_group == '3'){
            // set 2
            $('#current_form_group').val(2);
            // hide form group 3
            $('.form_group_3').addClass('d-none');
            // show form group 2
            $('.form_group_2').removeClass('d-none');

            // show next button
            $('.next_button').removeClass('d-none');
        }
        else if(current_form_group == 2 || current_form_group == '2'){
            // set 1
            $('#current_form_group').val(1);
            // hide form group 2
            $('.form_group_2').addClass('d-none');
            // show form group 1
            $('.form_group_1').removeClass('d-none');

            // hide next button
            $(this).addClass('d-none');
            // show back button
            $('.next_button').removeClass('d-none');
        }
    });


    $(document).on('submit', '#change_pass_form', function(e){

        e.preventDefault();
        let change_pass_form = $(this).serialize();
        // let url = base_url+'change_pass';
        let url = $(this).attr('action');
        let current_password = $('#current_password').val();
        let new_password = $('#new_password').val();
        let confirm_password = $('#confirm_password').val();

        $('#header_success_pass_level').text('');
        $('#header_err_pass_level').text('');

        if(new_password != confirm_password){
            // $('#header_err_pass_level').text('Password did not match');
            Swal.fire({
                icon: "warning",
                title: "Opps!",
                text: "Password did not match",
                showConfirmButton: true
            });
        }
        else if(new_password.length <=7 ){
            // $('#header_err_pass_level').text('Password at least 8 characters in length');
            Swal.fire({
                icon: "warning",
                title: "Opps!",
                text: "Password at least 8 characters in length",
                showConfirmButton: true
            });
        }
        else{

            Swal.fire({
                title: "Would you like to update your password?",
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

                    // do ajax
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: change_pass_form,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {

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
                                
                                if(result.code == 1){
                                    // $('#header_success_pass_level').text(result.message);

                                    Swal.fire({ 
                                        width: "350", position: "top-right", 
                                        icon: "success", title: "Password Updated", 
                                        text: result.message, 
                                        showConfirmButton: false, timer: 2000
                                    });

                                    $('#current_password').val('');
                                    $('#new_password').val('');
                                    $('#confirm_password').val('');
                                    $('#my_security_asnwer').val('');

                                    $("#my_security_question").val("");
                                }
                                else if(result.code == 0){
                                    // $('#header_err_pass_level').text(result.message);
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Oops!",
                                        text: result.message,
                                        showConfirmButton: true
                                    });
                                }

                            });
                        },
                        error: function(result){
                            console.log(result);
                        }

                    });


                } else if (result.dismiss === "cancel") {
                    
                }
            });

            
        }

    });


    $("#security_pin_1").pincodeInput({ 
        hidedigits: true, 
        inputs: 6, 
        inputclass: "form-control-md",
        change: function(input,value,inputnumber){
            let current_val = $("#security_pin_1").val().length;

            if(current_val != 6 || current_val != "6"){
                $('.div_security_pin_2').addClass('d-none');
                $('#security_pin_2').pincodeInput().data('plugin_pincodeInput').clear();
            }
        },
        complete: function(pin){
            $('.div_security_pin_2').removeClass('d-none');
        } 
    });

    $("#security_pin_2").pincodeInput({ 
        hidedigits: true, 
        inputs: 6, 
        inputclass: "form-control-md",
        complete: function(){
            // trigger submit
            $('.btn_submit_pin').click();
        }
    });


    $(document).on('submit', '#set_pin_form', function(e){

        e.preventDefault();
        let set_pin_form = $(this).serialize();
        let url = $(this).attr('action');

        let security_pin_1 = $('#security_pin_1').val();
        let security_pin_2 = $('#security_pin_2').val();

        $('#header_success_pin_level').text('');
        $('#header_err_pin_level').text('');

        if(security_pin_1 != security_pin_2){
            $('#header_err_pin_level').text('PIN did not match');
            $('#security_pin_1').pincodeInput().data('plugin_pincodeInput').clear();
            $('#security_pin_2').pincodeInput().data('plugin_pincodeInput').clear();
            $('#security_pin_1').pincodeInput().data('plugin_pincodeInput').focus();
        }
        else if(security_pin_1.length <=5 ){
            $('#header_err_pin_level').text('PIN 6 characters in length');
            $('#security_pin_1').pincodeInput().data('plugin_pincodeInput').clear();
            $('#security_pin_2').pincodeInput().data('plugin_pincodeInput').clear();
            $('#security_pin_1').pincodeInput().data('plugin_pincodeInput').focus();
        }
        else{
            // do ajax
            $.ajax({
                url: url,
                type: 'POST',
                data: set_pin_form,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {

                    if(result.code == 1){

                        $('#header_success_pin_level').text(result.message);

                        $('.btn_cancel_pin').click();//to reset
                        $('#enable_pin_btn').remove();
                        $('#change_pin_btn').removeClass('d-none');
                    }
                    else if(result.code == 0){
                        $('#header_err_pin_level').text(result.message);
                        $('#security_pin_1').pincodeInput().data('plugin_pincodeInput').clear();
                        $('#security_pin_2').pincodeInput().data('plugin_pincodeInput').clear();
                        $('#security_pin_1').pincodeInput().data('plugin_pincodeInput').focus();
                    }
                },
                error: function(result){
                    console.log(result);
                }

            });
        }

    });

    
    $(".lock_page").change(function() {

        let is_lock = this.checked ? 1 : 0;
        let employee_module_id = this.value;
        let module_name = $(this).data("module_name");
        let success_title = "";
        let swal_title = "";
        let swal_html_icon = "";
        let swal_icon = "";

        if(is_lock == 1){
            success_title = "Locked Page";
            swal_title = "Locking...";
            swal_icon = '<i class="fa-solid fa-lock-open fa-shake fa-xs"></i>';
            swal_html_icon = '<i class="fa-solid fa-lock fa-xs"></i>';
        }
        else{
            success_title = "Unlocked Page";
            swal_title = "Unlocking...";
            swal_icon = '<i class="fa-solid fa-lock fa-shake fa-xs"></i>';
            swal_html_icon = '<i class="fa-solid fa-lock-open fa-xs"></i>';
        }

        Swal.fire({
            width: "350",
            title: swal_title,
            icon: "question",
            customClass: {
                icon: 'no-border'
            },
            showClass: {
                backdrop: 'swal2-noanimation', // disable backdrop animation
                popup: '',                     // disable popup animation
                icon: ''                       // disable icon animation
            },
            iconHtml: swal_icon,
            allowEscapeKey: false,
            allowOutsideClick: false,
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
        }).then(function(){
            
             // do ajax
            $.ajax({
                url: "{{ route('page_lock') }}",
                type: 'POST',
                data: {is_lock: is_lock, employee_module_id: employee_module_id, module_name: module_name},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if(result.code == 1){
                        // $('#header_success_lock_level').text(result.message);

                        Swal.fire({ 
                            width: "350", position: "top-right", 
                            icon: "success", title: success_title,
                            text: result.message, iconHtml: swal_html_icon,
                            showConfirmButton: false, timer: 2000
                        });
                    }
                    else if(result.code == 0){
                        // $('#header_err_lock_level').text(result.message);
                        Swal.fire({
                            icon: "warning",
                            title: "Oops!",
                            text: result.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function(result){
                    
                }

            });

        });

       
        
    });

    $(document).on('click', '.btn_enable_pin', function(){
        $('#security_a').click();
    });


    $("#pin_required").pincodeInput({ 
        hidedigits: true, 
        inputs: 6, 
        inputclass: "form-control-md",
        change: function(){

        },
        complete: function(pin){
            
            $('#header_success_pin_level_2').text('');
            $('#header_err_pin_level_2').text('');
            
            // do ajax
            $.ajax({
                url: "{{ route('page_lock_access') }}",
                type: 'POST',
                data: {pin:pin},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function(){
                    $('#key_logo').effect( "bounce", {times:4}, 700 );
                },
                success: function(result) {

                    if(result.code == 1){
                        $('#header_success_pin_level_2').text(result.message);
                        $('#key_logo').removeClass('fa-lock').addClass('fa-unlock text-primary').effect( "bounce", {times:4}, 700 );

                        setTimeout(() => {
                            $('.pin_required_row').remove();
                            $('.page_items_row').removeClass('d-none');
                            
                        }, 2000);

                    }
                    else if(result.code == 0){
                        $('#header_err_pin_level_2').text(result.message);
                        $('#pin_required').pincodeInput().data('plugin_pincodeInput').clear();
                        $('#pin_required').pincodeInput().data('plugin_pincodeInput').focus();
                        $('.pin-card').effect( "shake", {times:2}, 300 );
                    }
                },
                error: function(result){
                    console.log(result);
                }

            });

        }
    });


    $("#set_question").submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: "Would you like to submit your security question?",
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
                    $("#set_question").unbind('submit').submit();
                });
                
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });

});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}