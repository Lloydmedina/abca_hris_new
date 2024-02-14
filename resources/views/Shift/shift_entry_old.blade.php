@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Shift Entry Form')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
    <div class="card-body">

        <form id="save_shift" action="{{ route('save_shift') }}" method="POST" novalidate>
            @csrf

            {{-- For whole day --}}
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label class="control-label">Break Time(hr):</label>
                        <input type="number" class="form-control text-right" id="no_hr_break"  name="no_hr_break" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="control-label">Time from:</label>
                        <input type="time" id="time_from" name="time_from" value="{{ old('time_from', date('HH:mm')) }}" onchange="get_diff()" class="form-control" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="control-label">Time to:</label>
                        <input type="time" id="time_to" name="time_to" value="{{ old('time_to', date('h:i:A')) }}" onchange="get_diff()" class="form-control" required>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label class="control-label">Date from:</label>
                        <input type="date" class="form-control" id="date_selected_from" value="{{ old('date_selected_from', date('Y-m-d')) }}" name="date_from" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="control-label">Date to:</label>
                        <input type="date" class="form-control" id="date_selected_to" value="{{ old('date_selected_to') }}" name="date_to" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="control-label">No. of days:</label>
                        <input type="text" id="no_of_days" name="no_of_days" value="{{ old('no_of_days') }}" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <!-- @if(session('user')->employee_type_id != 5)
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#employeeTable">Select employee</button>
            @endif -->

            
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#employeeTable">Select employee</button>
            

            <hr />
            
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
                                        <th>Reamrks</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_employee_selected">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" id="sub_btn_save_leave" class="btn btn-primary mt-0 mb-2">Save</button>
                        <button type="submit" id="btn_save_leave" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
                    </div>
                </div>

        </form>
    </div>
    </div>

    <hr>

 </div>

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

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/shift.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}