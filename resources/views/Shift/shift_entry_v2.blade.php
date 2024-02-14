@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')

<style>
    
    select.btn-mini {
        height: auto;
        line-height: 10px;
    }


    table {
    border-collapse: collapse;
    width: 200%;
    /* table-layout:fixed */
    }

    thead tr > th {
    background:#ffffff;
    position: sticky;
    padding:.5rem;
    z-index:2;
    top: 0;
    }

    thead tr > :nth-child(4) {
    z-index:3;
    left:0;
    top:0;
    }

    td {overflow:hidden; white-space:nowrap}

    tbody tr > :nth-child(4) {
    background:#f4f4f4;
    position: sticky;
    z-index:1;
    left:0;
}

</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Add employee shift')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('shift_entry_v2') }}" method="get">
                <div class="row">
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date from: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected_from" value="{{ old('date_selected_from', request()->get('date_from')) }}" name="date_from" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date to: <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_selected_to" value="{{ old('date_selected_to', request()->get('date_to')) }}" name="date_to" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Company</label>
                            <select id="company_id" name="company_id" class="form-control custom-select">
                                {{-- <option value="" disabled="" selected="">Select Outlet</option> --}}
                                <option value="all">All</option>
                                @foreach($companies as $row)
                                    <option value="{{ $row->company_id }}" {{ (request()->get('company_id') == $row->company_id) ? "selected" : "" }}>{{ $row->company }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">

                        <label class="control-label">Department</label>
                        <select id="dept_id" name="dept_id" class="form-control custom-select">
                            <option value="all">All</option>
                            @foreach($departments as $row)
                                <option value="{{ $row->SysPK_Dept }}" {{ (request()->get('dept_id') == $row->SysPK_Dept) ? "selected" : "" }}>{{ $row->Name_Dept }}</option>
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            <label class="hide" style="visibility: hidden">Search Button</label>
                            @include('button_component.search_button', ['margin_top' => "-1"])
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
                    <h4 class="card-title">New Shift</h4>
                </div>
                <div class="col-lg-4 col-sm-12 text-center mb-2">
                </div>
                <div class="col-lg-4 col-sm-12 text-lg-right">
                    
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="text-center">
                                <input class="checked_emp_all" title="Deselect all" type="checkbox" style="top: .8rem;width: 1rem;height: 1rem;" checked />
                            </td>
                            <th>No</th>
                            <th>EmpID</th>
                            <th>Employee</th>
                            {{-- <th>Status</th> --}}
                            {{-- <td>Approver 1</td> --}}
                            {{-- <td>Approver 2</td> --}}
                            @if($date_from && $date_to)
                                @php
                                    $date1_ts = strtotime(request()->get('date_from'));
                                    $date2_ts = strtotime(request()->get('date_to'));
                                    $diff = $date2_ts - $date1_ts;
                                    $daysCount = $diff / 86400;
                                @endphp
                                {{-- <th>{{ date('Y-m-d',strtotime(request()->get('date_from'))) }}</th> --}}
                                @for ($x = 0; $x <= $daysCount; $x++)
                                    <th class="text-center">
                                        {{date('M d, Y', strtotime("+$x day", strtotime(request()->get('date_from'))))}}
                                    </th>
                                @endfor
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $ctr = 0;
                        @endphp
                        @if(count($employees))

                            <form id="save_emp_shift_v2" action="{{ url('save_emp_shift_v2') }}" method="post">
                                @csrf
                                <input type="date" class="form-control" value="{{ request()->get('date_from') }}" name="date_from" required hidden>
                                <input type="date" class="form-control" value="{{ request()->get('date_to') }}" name="date_to" required hidden>
                                @foreach($employees as $row)
                                    @php
                                        $emp_id = $row->SysPK_Empl;
                                    @endphp
                                    <tr id="{{ $row->SysPK_Empl.md5($row->SysPK_Empl) }}">
                                        <td class="text-center">
                                            <input class="checked_emp" name="checked[]" value="{{ $row->SysPK_Empl }}" type="checkbox" style="top: .8rem;width: 1rem;height: 1rem;" checked />
                                        </td>
                                        <td><small><b>{{ ++$ctr }}</b></small></td>
                                        <td><small><b>{{ $row->UserID_Empl }}</b></small></td>
                                        <td><small><b>{{ $row->Name_Empl }}</b></small></td>

                                        @for ($x = 0; $x <= $daysCount; $x++)
                                            <td>
                                                <select name="shift_code[{{ $emp_id }}][]" class="btn btn-sm btn-mini checked_emp_select_all checked_emp_select_{{$emp_id}}" required>
                                                    <option value="" disabled>Shift Code</option>
                                                    @foreach($shift_codes as $row)
                                                        <option value="{{ $row->id }}" {{ ($row->shift_code == "RD") ? "selected" : "" }}>{{ $row->shift_code }} ({{ $row->Description }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                                <button type="submit" class="d-none" id="btn_submit_shift">Submit</button>
                            </form>

                        @else
                            <tr><td class="text-center" colspan="100">No record found</td></tr>
                        @endif
                    </tbody>
                </table>
                
            </div>
            @if($ctr > 0)
                <button type="button" id="btn_submit_shift_trigger" class="btn btn-dark float-right mt-3">Save</button>
                <button type="button" style="cursor: not-allowed;" id="btn_submit_shift_dummy" class="btn btn-dark float-right mt-3 d-none disabled">Saving please wait</button>
            @endif
            <input type="text" value="{{ $ctr }}" class="total_records" hidden>
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
{{-- <script src="{{ asset('uidesign/js/custom/shift_entry.js') }}"></script> --}}
<script>
  
    $(document).ready(function(){

        $('#btn_submit_shift_trigger').click(function() {
            $('#btn_submit_shift').click();
        });


        $("#save_emp_shift_v2").submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: "Would you like to submit employees shift?",
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
                    $('#btn_submit_shift_trigger').addClass('d-none');
                    $('#btn_submit_shift_dummy').removeClass('d-none');
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
                        $("#save_emp_shift_v2").unbind('submit').submit();
                    });
                    
                } else if (result.dismiss === "cancel") {
                    
                }
            });
        });

        $('.checked_emp').change(function() {
            let isCheck = $(this).val();
            let total_records = $('.total_records').val();

            if($(this).is(':checked')){
                // show
                $('.checked_emp_select_'+isCheck).show();
                // show save button
                $('#btn_submit_shift_trigger').show();
            }
            else{
                // hide
                $('.checked_emp_select_'+isCheck).hide();
            }
            
            if($('.checked_emp:checked').length > 0){
                // console.log('At least one checkbox is checked');

                // check if the all checkbox checked
                if(total_records == $('.checked_emp:checked').length){
                    $('.checked_emp_all').not(this).prop('checked', true);
                }

            } else {
                // console.log('No checkboxes are checked');
                $('.checked_emp_all').not(this).prop('checked', false);
                // Hide button
                $('#btn_submit_shift_trigger').hide();
            }
        });


        $(".checked_emp_all").change(function() {
            if($(this).is(':checked')) {
                //Do stuff
                $('.checked_emp').not(this).prop('checked', true);
                $(this).attr("title", "Deselect all");
                $('.checked_emp_select_all').show();
                // show save button
                $('#btn_submit_shift_trigger').show();
            }
            else{
                $('.checked_emp').not(this).prop('checked', false);
                $(this).attr("title", "Select all");
                $('.checked_emp_select_all').hide();

                // Hide button
                $('#btn_submit_shift_trigger').hide();
            }
        });

        $('#sidebarToggle').click();

    });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}