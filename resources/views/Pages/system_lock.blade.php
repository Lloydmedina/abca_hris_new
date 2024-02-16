@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')

@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','System Lock')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('system-lock') }}" method="get">
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

                    </div>

                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="hide" style="visibility: hidden">Search Button</label>
                            @include('button_component.search_button', ['margin_top' => "11.5"])
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
                <div class="col-lg-4 col-sm-12">
                    <h4 class="card-title">System Lock</h4>
                </div>
                <div class="col-lg-4 col-sm-12 text-center mb-2">
                   
                </div>
                <div class="col-lg-4 col-sm-12 text-lg-right">
                    {{-- <a href="{{ route('file_time_pass') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle">
                        </i> File Time Pass Slip 
                    </a> --}}
                </div>
            </div>
            <div class="table-responsive mt-3">
                <form id="save_system_lock" action="{{ route('save-system-lock') }}" method="POST">
                    @csrf
                    <input type="date" class="form-control" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from" required hidden>
                    <input type="date" class="form-control" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to" required hidden>
                    <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th title="System Lock" class="text-center">SL</th>
                                <th class="">Date</th>
                                <th class="text-center">Status</th>
                                <th class="">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($systemLockMerge))

                                @foreach($systemLockMerge as $row)

                                    <tr>
                                        <td>
                                            @if($row['is_lock'] == 1)
                                                <center><i class="fa-solid fa-lock text-dark" title="This application is currently locked and cannot be updated/removed at this time."></i></center>
                                            @endif
                                        </td>
                                        <td>{{ date('M d, Y', strtotime($row['date'])) }} </td>
                                        <td class="text-center">

                                            <div class="form-check">
                                                <input name="lock[]" value="{{$row['date']}}" style="width: 17px;height: 17px;" class="form-check-input" type="checkbox" {{ ($row['is_lock'] == 1) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            @if($row['date_created'])
                                                {{ date('M d, Y', strtotime($row['date_created'])) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td class="text-center" colspan="4">No record found</td></tr>
                            @endif

                        </tbody>
                    </table>
                    <div class="text-right">
                        {{-- <button type="submit" class="btn btn-sm btn-dark"><i class="fa-solid fa-floppy-disk"></i> Save</button> --}}

                        <button type="button" id="sub_btn_save_system_lock" class="btn btn-dark mt-0 mb-2"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                        <button type="button" class="dummy_button btn btn-dark mt-0 mb-2 disabled d-none" style="cursor: not-allowed;"><i class="fa-solid fa-floppy-disk"></i> Saving please wait</button>
                        <button type="submit" id="btn_save_system_lock" class="btn btn-dark mt-0 mb-2 d-none">Save</button>
                    </div>
                </form>
            </div>
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
{{-- <script src="{{ asset('uidesign/js/custom/overtime.js') }}"></script> --}}
<script>
$(document).ready(function(){

    $(document).on('click', '#sub_btn_save_system_lock', function(e) {
        $('#btn_save_system_lock').click();
    });

    $("#save_system_lock").submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: "Would you like to save?",
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
                    $('#sub_btn_save_system_lock').addClass('d-none');
                    $('.dummy_button').removeClass('d-none');
                    $("#save_system_lock").unbind('submit').submit();
                });
                
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });

});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}