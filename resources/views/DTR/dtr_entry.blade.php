@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS --}}
@section('page_level_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title', 'DTR Entry')
{{-- BEGIN CONTENT --}}
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid" style="min-height: 700px">


        @include('Templates.alert_message')

        <form class="form-material" action="{{ url('/dtr-entry') }}" method="get">

            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Date From</label>
                                <input type="date" class="form-control" id="date_from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d'); ?>"
                                    name="date_from" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Date To</label>
                                <input type="date" class="form-control" id="date_to" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d', strtotime(date('Y-m-d') . ' + 15 days')); ?>"
                                    name="date_to" required>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Employee</label>
                                <select id="id" name="id" class="form-control custom-select selectpicker"
                                    data-live-search="true" required>
                                    <option value="" selected disabled>Select Employee</option>
                                    @foreach ($employees as $row)
                                        @if (isset($_GET['id']))
                                            @if ($_GET['id'] == $row->UserID_Empl . md5($row->UserID_Empl))
                                                <option value="{{ $row->UserID_Empl . md5($row->UserID_Empl) }}" selected>
                                                    {{ $row->Name_Empl }}
                                                </option>
                                            @else
                                                <option value="{{ $row->UserID_Empl . md5($row->UserID_Empl) }}">
                                                    {{ $row->Name_Empl }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $row->UserID_Empl . md5($row->UserID_Empl) }}">
                                                {{ $row->Name_Empl }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label class="hide" style="visibility: hidden">Button</label>
                                @include('button_component.search_button', ['margin_top' => '16.5'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <hr />

        <form class="form-material" action="{{ url('/manual_entry_dtr') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-4">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Employee's DTR</div>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-sm btn-info" type="button">
                                <i class="fa fa-calendar"></i> Default Values
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table id="example23_"
                                class="display nowrap table table-sm table-hover table-striped table-bordered"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle " hidden>emp_no</th>
                                        <th class="text-center align-middle " hidden>emp_name</th>
                                        <th class="text-center align-middle " hidden>attendance_id</th>
                                        <th class="text-center align-middle " hidden>dtr_date</th>
                                        <th class="text-center align-middle " hidden>am_in</th>
                                        <th class="text-center align-middle " hidden>pm_out</th>
                                        <th class="text-center align-middle" hidden>MONTH</th>
                                        <th class="text-center align-middle" hidden>DAY</th>
                                        <th class="text-center align-middle" hidden>NAME OF DAY</th>
                                        <th class="">Date</th>
                                        <th class="text-center">In</th>
                                        <th class="text-center">Out</th>
                                        <th class="text-center">Late</th>
                                        <th class="text-center">Undertime</th>
                                        <th class="text-center">NP</th>
                                        <th class="text-center">Hrs. Break</th>
                                        <th class="text-center">Total hrs.</th>
                                        <th>Shift Code Schedule</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th class="text-center align-middle " hidden>emp_no</th>
                                    <th class="text-center align-middle " hidden>emp_name</th>
                                    <th class="text-center align-middle " hidden>attendance_id</th>
                                    <th class="text-center align-middle " hidden>dtr_date</th>
                                    <th class="text-center align-middle " hidden>am_in</th>
                                    <th class="text-center align-middle " hidden>pm_out</th>
                                    <th class="text-center align-middle" hidden>MONTH</th>
                                    <th class="text-center align-middle" hidden>DAY</th>
                                    <th class="text-center align-middle" hidden>NAME OF DAY</th>
                                    <th class="">Date</th>
                                    <th class="text-center">In</th>
                                    <th class="text-center">Out</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Undertime</th>
                                    <th class="text-center">NP</th>
                                    <th class="text-center">Hrs. Break</th>
                                    <th class="text-center">Total hrs.</th>
                                    <th>Shift Code Schedule</th>
                                    <th>Remarks</th>
                                </tfoot>
                                <tbody id="list_body" name="list">
                                    <?php $ctr = 0; ?>
                                    @if (isset($_GET['id']))
                                        @foreach ($attendance1 as $row)
                                            @php
                                                // $def_out = date('H:i', strtotime('04:00PM'));
                                                // if ($row['break_hours'] > 0) {
                                                //     $def_out = date('H:i', strtotime('04:00PM') + 60 * 60 * $row['break_hours']);
                                                // }

                                                if($row['shift_code_def'] == "RD" || $row['shift_code_def'] == ""){
                                                    $timeIn = $timeOut = null;
                                                }
                                                else{

                                                    $timeIn = date('H:i', strtotime($row['TimeStart']));
                                                    $timeOut = date('H:i', strtotime($row['TimeEnd']));
                                                    
                                                    if($row['in_am'] != "" || $row['in_am'] != null){
                                                        $timeIn =  date('H:i', strtotime($row['in_am']));
                                                    }
                                                    if($row['out_pm'] != "" || $row['out_pm'] != null){
                                                        $timeOut =  date('H:i', strtotime($row['out_pm']));
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td class="hidden" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'employee_number_' . $ctr }}" name="employee_number[]"
                                                            value="{{ $row['employee_number'] }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="hidden" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'employee_fullName_' . $ctr }}"
                                                            name="employee_fullName[]"
                                                            value="{{ $row['employee_fullName'] }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="hidden" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'SysPK_emp_attendance_' . $ctr }}"
                                                            name="SysPK_emp_attendance[]"
                                                            value="{{ $row['SysPK_emp_attendance'] }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="hidden" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'dtr_date_' . $ctr }}" name="dtr_date[]"
                                                            value="{{ $row['dtr_date'] }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="hidden" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'TimeStart_' . $ctr }}" name="TimeStart[]"
                                                            value="{{ $row['TimeStart'] }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="hidden" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'TimeEnd_' . $ctr }}" name="TimeEnd[]"
                                                            value="{{ $row['TimeEnd'] }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 130px" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'month_' . $ctr }}" name="month"
                                                            value="{{ date('F', strtotime($row['dtr_date'])) }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 30px" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" type="text"
                                                            id="{{ 'day_' . $ctr }}" name="day"
                                                            value="{{ date('d', strtotime($row['dtr_date'])) }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-left" style="width: 130px" hidden>
                                                    <div class="form-group">
                                                        <input class="mat-input text-left" type="text"
                                                            id="{{ 'day_word_' . $ctr }}" name="day_word"
                                                            value="{{ date('l', strtotime($row['dtr_date'])) }}" readonly>
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-left" style="width: 130px">
                                                    {{ date('M d, Y', strtotime($row['dtr_date'])) }}
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 50px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-left" type="time"
                                                            id="{{ 'in_am_' . $ctr }}" name="in_am[]"
                                                            {{-- value="{{ $row['in_am'] ? date('H:i', strtotime($row['in_am'])) : date('H:i', strtotime('08:00AM')) }}" --}}
                                                            value="{{ $timeIn }}"
                                                            {{-- onchange="ComputeTime({{ $ctr }})" --}} />
                                                    </div>

                                                </td>
                                                <td class="pt-3-half text-center" style="width: 50px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-left" type="time"
                                                            id="{{ 'out_pm_' . $ctr }}" name="out_pm[]"
                                                            {{-- value="{{ $row['out_pm'] ? date('H:i', strtotime($row['out_pm'])) : $def_out }}" --}}
                                                            value="{{ $timeOut }}"
                                                            {{-- onchange="ComputeTime({{ $ctr }})" --}} />
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 70px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" step="0.01"
                                                            type="number" id="{{ 'late_' . $ctr }}" name="late[]"
                                                            value="{{ $row['late'] }}" />
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 100px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" step="0.01"
                                                            type="number" id="{{ 'undertime_' . $ctr }}"
                                                            name="undertime[]" value="{{ $row['undertime'] }}" />
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 100px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" step="0.01"
                                                            type="number" id="{{ 'np_hours_' . $ctr }}"
                                                            name="np_hours[]" value="{{ $row['np_hours'] }}" />
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 100px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" step="0.01"
                                                            type="number" id="{{ 'break_' . $ctr }}" name="break[]"
                                                            value="{{ $row['break_hours'] }}" />
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" style="width: 75px">
                                                    <div class="form-group">
                                                        <input class="mat-input text-center" step="0.01"
                                                            type="number" id="{{ 'total_hours_worked_' . $ctr }}"
                                                            name="total_hours_worked[]"
                                                            value="{{ $row['total_hours_worked'] }}" />
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center" >
                                                    <div class="form-group">
                                                        @if ($row['shift_id'] !== 0)
                                                            @if ($row['allow_update'] == 1)
                                                                <i class="fa-solid fa-pen-to-square shift_view_modal mr-2"
                                                                    title="Update" style="cursor: pointer"
                                                                    data-shift_code="{{ $row['shift_sched'] }}"
                                                                    data-shift_code_id="{{ $row['shift_id'] }}"
                                                                    data-shift_monitoring_id="{{ $row['sm_id'] }}"
                                                                    data-shift_brk_hrs="{{ $row['break_hours'] }}"
                                                                    data-shift_status="{{ $row['shift_monitoring_status'] == 1 ? 'Approved' : 'Pending' }}"
                                                                    data-shift_date="{{ $row['dtr_date'] ? date('m/d/Y', strtotime($row['dtr_date'])) : '' }}"></i>
                                                            @else
                                                                <i class="fa-solid fa-ellipsis" title="No action"></i>
                                                            @endif
                                                        @endif
                                                        <input class="mat-input text-left" id='shift_code_id[]'
                                                            name='shift_code_id[]' type="text"
                                                            value="{{ $row['shift_id'] }}" readonly hidden />
                                                        <input class="mat-input text-left" type="text"
                                                            value="{{ $row['shift_sched'] }}" readonly />
                                                        @if($row['is_active'] === 0)
                                                            <span class="badge badge-danger text-sm" title="The shift code has been deleted. Please report to your admin or update shift code.">Shift code has been deleted</span>
                                                         @endif
                                                    </div>
                                                </td>
                                                <td class="pt-3-half text-center">
                                                    <div class="form-group">
                                                        <input class="mat-input text-left" type="text" name="att_type[]"
                                                            value="{{ $row['att_type'] }}" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $ctr = $ctr + 1; ?>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center">NO DATA FOUND</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row pt-3 no-gutters align-items-center">
                        <div class="col text-right">
                            <button class="btn btn-sm btn-dark" type="submit">
                                <i class="fa-regular fa-floppy-disk"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <hr />

    </div>

    {{-- SHIFT VIEW MODAL --}}
    <div class="modal fade" id="shift_view_modal" tabindex="-1" aria-labelledby="shift_view_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shift_view_modalLabel">Change Schedule</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <form id="approver_change_schedule" action="{{ route('approver_change_schedule') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <center>
                            <h5 class="modal-title" id="shift_date_modal"></h5>
                        </center>
                        {{-- <a class="btn btn-link text-info" id="change_sched_id">Change Schedule</a> <i class="fa fa-times text-danger" aria-hidden="true" style="cursor: pointer" title="Cancel"></i> --}}
                        {{-- <input type="date" class="form-control" name="shift_date" id="change_date_to" value="" required/> --}}
                        <label class="control-label mt-2">Shift Code</label>
                        <select id="shift_code" name="shift_code" class="form-control custom-select">
                            {{-- <option value="" id="def_shift_code"></option> --}}
                            @foreach ($shift_codes as $i => $sc)
                                <option value="{{ $sc->id }}">{{ $sc->shift_code }} - {{ $sc->Description }}
                                </option>
                            @endforeach
                        </select>
                        <label class="control-label mt-2">Reason</label>
                        <textarea id="remarks_reason_text" class="form-control" name="remarks" placeholder="Type in your message"
                            rows="5" maxlength="100" required></textarea>
                        <h6 class="pull-right mt-1" id="count_message"></h6>

                        <input type="hidden" name="shift_monitoring_id" id="shift_monitoring_id" />

                        <br>
                        <table class="display nowrap table table-sm table-hover table-bordered" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th class="">Shift Code</th>
                                    <th class="text-center">No. Hrs Break</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> <small id="shift_code_modal"></small></td>
                                    <td class="text-center"> <small id="shift_brk_hrs_modal"></small></td>
                                    <td class="text-center"> <small id="shift_status_modal"></small></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="submit"><i class="fa-solid fa-floppy-disk"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END SHIFT VIEW MODAL --}}

    <!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
    <script src="{{ asset('uidesign/vendor/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('uidesign/js/custom/custom_mat.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')

    <script>
        function ComputeTime(index) {
            var number_of_hours = 0;
            var row_val = $('#list_body').find('tr');

            var start = "00:00";
            var end = "00:00";
            var timestart = "00:00";
            var timeend = "00:00";
            try {
                start = $(row_val[index]).find('input[name="in_am[]"]').val();
                end = $(row_val[index]).find('input[name="out_pm[]"]').val();
                timestart = $(row_val[index]).find('input[name="TimeStart[]"]').val();
                timeend = $(row_val[index]).find('input[name="TimeEnd[]"]').val();
            } catch (e) {

            }

            //get late hours
            try {
                var first_date = "01/01/2019 " + timestart + ":00"
                var second_date = "01/01/2019 " + start + ":00"
                if (Date.parse(first_date) < Date.parse(second_date)) {
                    get_diff(index, timestart, start, 'input[name="late[]"]');
                } else
                    $(row_val[index]).find('input[name="late[]"]').val(number_of_hours);
            } catch (e) {}
            //get undertime hours
            try {
                var first_date = "01/01/2019 " + timeend + ":00"
                var second_date = "01/01/2019 " + end + ":00"
                if (Date.parse(first_date) > Date.parse(second_date)) {
                    get_diff(index, end, timeend, 'input[name="undertime[]"]');
                } else
                    $(row_val[index]).find('input[name="undertime[]"]').val(number_of_hours);
            } catch (e) {}

            //get working hours
            get_diff(index, start, end, 'input[name="total_hours_worked[]"]');
        }

        function get_diff(index, start_time, end_time, cell_name) {
            var number_of_hours = 0;
            var row_val = $('#list_body').find('tr');

            var start = "00:00";
            var end = "00:00";
            try {
                start = start_time;
                end = end_time;
            } catch (e) {

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
                if (hours < 0) {
                    hours = hours + 24;
                }
                if (minutes == 0) {
                    number_of_hours = hours;
                } else
                    number_of_hours = parseFloat((hours + minutes)).toFixed(2);
            } catch (e) {

            }
            if (number_of_hours > 5) {
                number_of_hours = parseFloat(number_of_hours) - parseFloat(1);
            }
            if (cell_name == 'input[name="total_hours_worked[]"]') {
                let break_hrs = 0;
                break_hrs = $(row_val[index]).find('input[name="break[]"]').val();
                if (number_of_hours > 10) {
                    $(row_val[index]).find(cell_name).val(10);
                } else {
                    number_of_hours = number_of_hours - break_hrs;
                    $(row_val[index]).find(cell_name).val(number_of_hours);
                }
            } else {
                number_of_hours = number_of_hours - break_hrs;
                $(row_val[index]).find(cell_name).val(number_of_hours);
            }

        }

        $(document).ready(function(){

            $('.shift_view_modal').click(function() {

                $('#shift_date_modal').text(formatDateAbaca(new Date($(this).data('shift_date'))));
                $('#shift_code_modal').text($(this).data('shift_code'));
                $('#shift_brk_hrs_modal').text($(this).data('shift_brk_hrs'));
                $('#shift_status_modal').text($(this).data('shift_status'));
                $('#shift_monitoring_id').val($(this).data('shift_monitoring_id'));
                // $('#shift_date_id').val($(this).data('shift_date'));
                $("#shift_code").val($(this).data('shift_code_id')).change();

                $('#shift_view_modal').modal('show');
            });

            $('#sidebarToggle').click();

        });

        
    </script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}
