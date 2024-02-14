@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
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
            <div class="row">
                @php
                    $date1_ts = request()->get('date_from') ?? date('Y-m-d');
                    $date2_ts = request()->get('date_to') ?? date('Y-m-d', strtotime($datePlus6days));
                @endphp
                <div class="col-lg-2 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Date from:</label>
                        <input type="date" class="form-control" id="date_selected_from" value="{{ old('date_selected_from', $date1_ts) }}" name="date_from" required onchange="SetEmpShiftDates()">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Date to:</label>
                        <input type="date" class="form-control" id="date_selected_to" value="{{ old('date_selected_to', $date2_ts) }}" name="date_to" required onchange="SetEmpShiftDates()">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">No. of days:</label>
                        <input type="text" id="no_of_days" name="no_of_days" value="{{ old('no_of_days') }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12 text-sm-center">
                            <br>
                            <button type="button" class="btn btn-md btn-dark mt-2" data-toggle="modal" data-target="#employeeTable" data-backdrop="static" data-keyboard="false">Select employee</button>
                        </div>
                        <div class="col-lg-4 col-sm-12 text-center">
                            <br><p class="mt-3">OR</p>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <form action="{{ route('import_shift') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                    
                                <div class="form-group row">
                                {{-- <label class="col-form-label col-md-2">Upload file</label> --}}
                                    <div class="col-md-10 mt-3 ">
                                        <input class="form-control d-none" id="file_import" type="file" name="file_import" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                                        <button type="submit" id="btn_choose_file" class="btn btn-md btn-dark mt-3">Upload file</button>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="btn_upload" class="d-none btn btn-sm btn-dark">Upload</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- @if(session('user')->employee_type_id != 5) --}}
                {{-- <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#employeeTable">Select employee</button> --}}
            {{-- @endif --}}

            <hr />
            <input type="text" name="shift_code_e" id = "shift_code_e"  hidden>
            {{-- Employee list absenteeism --}}
            @if (request()->get('date_from') && request()->get('date_to') && session('new_shift_created'))
                <div id="emp_tbl_list">
                    
                    
                    {{-- {{session('new_shift_created')}} --}}
                    <a href="{{ route('clear_shift_session') }}" class="btn btn-sm btn-danger text-white mb-2" style="float: right">Clear</a>
                    <div class="table-responsive m-t-40">
                        <table id="" class="table table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Name</th>
                                    @php
                                        $date1_ts = strtotime(request()->get('date_from'));
                                        $date2_ts = strtotime(request()->get('date_to'));
                                        $diff = $date2_ts - $date1_ts;
                                        $daysCount = $diff / 86400;
                                    @endphp
                                    {{-- <th>{{ date('Y-m-d',strtotime(request()->get('date_from'))) }}</th> --}}
                                    @for ($x = 0; $x <= $daysCount; $x++)
                                        <th>
                                            {{date('M d, Y', strtotime("+$x day", strtotime(request()->get('date_from'))))}}
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            
                            <tbody id="tbody_employee_selected">
                                @if (session('new_shift_created'))
                                    <?php $index = 0; ?>
                                    @foreach($dataListShift as $row => $item)
                                        <tr>
                                            <td><small>{{ preg_replace('/[0-9]+/', '', $row) }}</small></td>
                                            <td><small>{{ $item[0]->emp_name }}</small></td>
                                            @foreach ($item as $data)
                                            <td>
                                                <a href="#entry_modal" data-toggle="modal" data-target="#entry_modal" onclick="setShiftEmployee({{ $index }},'{{ $data->emp_id }}','{{ $data->emp_name }}','{{ $data->department }}' )"> {{ date('H:i', strtotime($data->time_start)) }} - {{ date('H:i', strtotime($data->time_end)) }} </a>
                                            </td>
                                            @endforeach
                                        </tr>
                                        <?php $index = $index +1; ?>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    
                    <!-- <div class="text-center">
                        <button type="button" id="sub_btn_save_leave" class="btn btn-primary mt-0 mb-2">Save</button>
                        <button type="submit" id="btn_save_leave" class="btn btn-primary mt-0 mb-2 d-none">Save</button>
                    </div> -->
                </div>
            @endif
        </div>
    </div>

    <hr>

</div>

 <!-- Modal -->
<div class="modal fade" id="employeeTable" tabindex="-1" role="dialog" aria-labelledby="employeeTableLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80% !important;" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="employeeTableLabel">Search Employee</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="save_emp_shift" action="{{ url('save_emp_shift') }}" method="post">
            @csrf
            <input type="date" name="date_from_s" id="date_from_s" hidden>
            <input type="date" name="date_to_s" id="date_to_s" hidden>
            <div class="modal-body">
                <div id="alert-required-message" class="alert alert-danger" role="alert" hidden></div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input emp_status_all" name="emp_status" type="checkbox" id="inlineCheckbox_ALL" value="ALL" >
                                    <label class="form-check-label mr-3" for="inlineCheckbox_ALL">ALL</label> |
                                </div>
                                @foreach($empStatus as $row)
                                    <div class="form-check form-check-inline mb-3">
                                        <input class="form-check-input emp_status" name="emp_status" type="checkbox" id="inlineCheckbox_{{ $row->Status_Empl }}" value="{{ $row->Status_Empl }}"  {{ ($row->Status_Empl == 'REGULAR') ? 'checked' : '' }}>
                                        <label class="form-check-label mr-3" for="inlineCheckbox_"{{ $row->Status_Empl }}>{{ $row->Status_Empl }}</label> |
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Department <i class="text-small text-danger">*</i></label>
                                    <select id="Department_Empl" name="Department_Empl" class="form-control custom-select">
                                        <option value="" disabled="" selected="">Select Department</option>
                                        {{-- <option value="all" selected>All Departments</option> --}}
                                        @foreach($department as $row)
                                            <option value="{{ $row->SysPK_Dept }}">{{ $row->Name_Dept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Company</label>
                                    <select id="company_id" name="company_id" class="form-control custom-select">
                                        {{-- <option value="" disabled="" selected="">Select Outlet</option> --}}
                                        <option value="all" selected>All Companies</option>
                                        @foreach($companies as $row)
                                            <option value="{{ $row->company_id }}">{{ $row->company }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Outlet</label>
                                    <select id="outlet_id" name="outlet_id" class="form-control custom-select">
                                        {{-- <option value="" disabled="" selected="">Select Outlet</option> --}}
                                        <option value="all" selected>All Outlets</option>
                                        @foreach($outlets as $row)
                                            <option value="{{ $row->outlet_id }}">{{ $row->outlet }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Employee Status</label>
                                    <select id="Status_Empl" name="Status_Empl" class="form-control custom-select">
                                        <option value="" disabled selected>Select Department</option>
                                        @foreach($empStatus as $row)
                                            <option value="{{ $row->Status_Empl }}" {{ ($row->Status_Empl == 'REGULAR') ? 'selected' : '' }}>{{ $row->Status_Empl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            {{-- <div class="col-md-5">
                                <div class="form-group">
                                    <label for="emp_name">Employee's Name</label>
                                    <input type="text" class="form-control" id="emp_name" placeholder="Employee's Name">
                                </div>
                            </div> --}}
                            <div class="col-md-3 col-sm-12">
                                <label class="hide" style="visibility: hidden">Search Button</label>
                                <button type="button" class="btn btn-dark mt-auto w-100" id="btn-search-employee"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row" id="shift-code-row" hidden>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Shift Code <i class="text-small text-danger">*</i></label>
                                    <select id="shift_code" name="shift_code" class="form-control custom-select" required>
                                        <option value="" disabled selected>Select Shift Code</option>
                                        @foreach($shift_code as $row)
                                            <option value="{{ $row->id }}">{{ $row->shift_code }} ({{ $row->Description }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Search Name</label>
                                    <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
                                </div>
                            </div>
                        </div>
                        <div id="loading-employee" class="text-center mt-4" hidden>
                            <i class="fa fa-spinner fa-spin spin text-info" aria-hidden="true"></i>
                        </div>
                        <div id="employee-table-results" class="table-responsive m-t-40" hidden>
                            <table id="emp_table" name="emp_table" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th class="text-center">
                                            <input type="checkbox" style="top: .8rem;width: 1rem;height: 1rem;" value="1" name="checked_all" id="checked_all"></input>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="searched-employee-results">
                                    
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" id="btn_modal_close" data-dismiss="modal">Close</button> --}}
                <button id="btn-save-button-selected-employee" type="button" class="btn btn-dark" hidden>Save</button>
                <button id="btn-save-button-selected-employee-2" type="submit" class="btn btn-dark" hidden>Save</button>
            </div>
        </form>
        
        </div>
    </div>
</div>

<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Employee Shift Details</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
        <form id="update_emp_shift" action="{{ url('update_emp_shift') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Date From</label>
                                <input type="text" id="d_from_display" name="" value="<?php echo date('M d, Y', strtotime(@$_GET['date_from'])); ?>"  class="form-control" readonly>
                                <input type="date" id="d_from" name="d_from"  class="form-control" hidden>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Date To</label>
                                <input type="text" id="d_to_display" name="" value="<?php echo date('M d, Y', strtotime(@$_GET['date_to'])); ?>"  class="form-control" readonly>
                                <input type="date" id="d_to" name="d_to"  class="form-control" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Employee</label>
                                <input type="text" id="s_emp" name="s_emp"  class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Department</label>
                                <input type="text" id="s_dept" name="s_dept"  class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive m-t-40">
                            <table id="emp_shift_table" name="emp_shift_table" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <th style="min-width: 150px;">Date</th>
                                    <th>Shift Code</th>
                                    <th>Break Hr</th>
                                    <th style="min-width: 200px;">Created At</th>
                                    <th>Status</th>
                                    <th><i class="fa-solid fa-lock text-dark"></i></th>
                                </thead>
                                <tbody id="emp_shift_body" name="emp_shift_body">
                                </tbody>
                            </table>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2"> <i class="fa-solid fa-floppy-disk"></i> Update</button>
               </div>
            </div>
        </form>
      </div>
   </div>
</div>

<div class="modal fade" id="employeeShiftTable" tabindex="-1" role="dialog" aria-labelledby="employeeShiftTableLabel" aria-hidden="true" >
    <div class="modal-dialog" style="max-width: 90% !important;" role="document">
        <form id="save_import_shift" action="{{ route('save_import_shift') }}" method="POST">
            @csrf
            <input type="text" name="save_import_shift" value="1" hidden>
            {{-- <input type="text" name="from_page" value="1" hidden> --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeShiftTableLabel">Import Employee Shift</h5>
                    <button type="button" class="close" onClick="window.location.reload(true)" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body" id="shift_emp_list">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-dark btn_save_shift d-none"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
{{-- <script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script> --}}
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/shift_entry.js') }}"></script>
<script>
  
    $(document).ready(function(){

        $("#update_emp_shift").submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: "Would you like to update the shift schedule?",
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
                        $("#update_emp_shift").unbind('submit').submit();
                    });

                } else if (result.dismiss === "cancel") {
                    
                }
            });
        });

        $("#save_import_shift").submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: "Would you like to submit the imported shift schedule?",
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
                        $("#save_import_shift").unbind('submit').submit();
                    });
                    
                } else if (result.dismiss === "cancel") {
                    
                }
            });
        });

        $("#save_emp_shift").submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: "Would you like to submit the shift schedule?",
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
                        $("#save_emp_shift").unbind('submit').submit();
                    });
                    
                } else if (result.dismiss === "cancel") {
                    
                }
            });
        });

        //trigger click file upload
        $('#btn_choose_file').click(function(){
            $('#file_import').click();
        });

        //trigger click upload submit
        $('#file_import').change(function(){
            
            $('#employeeShiftTable').modal({backdrop: 'static', keyboard: false}, 'show'); 

            var formData = new FormData();
            formData.append('file_import', $('#file_import')[0].files[0]);
            // formData.append('csrfmiddlewaretoken', "{{ 'csrf_token' }}");
            $.ajax({
                url : "{{route('import_shift')}}",
                type : 'POST',
                data : formData,
                dataType: 'json',
                headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                beforeSend: function(){
                    $('#shift_emp_list').empty().html('<div class="text-center"><i class="fa-solid fa-spinner fa-spin fa-lg text-dark"></i></i></div>');
                },
                success : function(data) {

                    // data = JSON.parse(data);
                    // console.log(data)
                    if(data.success == true){
                        $('.btn_save_shift').removeClass('d-none');
                    }

                    $('#shift_emp_list').empty().html(data.html);
                },
                error: function(){
                    $('#shift_emp_list').empty().html('<div class="text-center text-danger">Error occured. Please try again</div>');
                }
            });
            
            // Open modal
            
            // $('#btn_upload').click();
        });

        $(".emp_status_all").click(function(){
            $('.emp_status').not(this).prop('checked', this.checked);
        });

        // $('#emp_table').DataTable();
        $('#date_selected_to').trigger("change");

        
        // @if( session('no_of_days') )
        //     const date = new Date('{{ session('date_from') }}');
        //         let dayName = 0;
        //         var no_days = {{ session('no_of_days') }};
        //         for (let cnt=0; cnt < no_days; cnt++)
        //         {
        //             let i = cnt + 1;
        //             var td_id = "date_" + String(i);
        //             dayName =  date.getDate() + cnt;
        //             var display_date =  date.getMonth() + 1 + "/"+  dayName + "/"+ date.getFullYear()
        //             document.getElementById(td_id).innerHTML = display_date;
        //         }
        // @endif

        @if (session('date_from'))
            $('#date_selected_from').val('{{ session('date_from') }}');
            $('#date_selected_to').val('{{ session('date_to') }}');
        @endif
        
        $("#btn-search-employee").click(function() {

            // console.log("Clicked");
            let dept_id = $('#Department_Empl').val();
            let outlet_id = $('#outlet_id').val();
            let company_id = $('#company_id').val();
            // let emp_name = $('#emp_name').val();
            // let Status_Empl = $('#Status_Empl').val();

            // Uncheck check all box
            $('#checked_all').prop('checked', false);
            

            let arr_emp_status = {};
            $.each($("input[name='emp_status']:checked"), function(i, e){
                arr_emp_status[i] = $(this).attr('value');
            });

            if(!dept_id){
                // $('#alert-required-message').empty().append('<strong>Required!</strong> Select department.').removeAttr('hidden');
                Swal.fire({
                    icon: "warning",
                    title: "Required!",
                    text: "Select department.",
                    showConfirmButton: true
                });
            }
            else if(!company_id){
                // $('#alert-required-message').empty().append('<strong>Required!</strong> Select company.').removeAttr('hidden');
                Swal.fire({
                    icon: "warning",
                    title: "Required!",
                    text: "Select company.",
                    showConfirmButton: true
                });
            }
            else if(!outlet_id){
                // $('#alert-required-message').empty().append('<strong>Required!</strong> Select outlet.').removeAttr('hidden');
                Swal.fire({
                    icon: "warning",
                    title: "Required!",
                    text: "Select outlet.",
                    showConfirmButton: true
                });
            }
            else{
                $("#btn-search-employee").attr('disabled','disabled');
                $('#alert-required-message').empty().attr('hidden','hidden');
                $.ajax({
                    url:"{{ url('/search_employee_2') }}",
                    method: "GET",
                    data: {
                        // emp_name: emp_name, 
                        dept_id: dept_id,
                        company_id: company_id,
                        outlet_id: outlet_id,
                        arr_emp_status: arr_emp_status
                    },
                    beforeSend: function() {
                        $('#loading-employee').removeAttr('hidden');
                        $('#employee-table-results').attr('hidden','hidden');
                    },
                    success: function(res) {
                        $("#btn-search-employee").removeAttr("disabled");
                        $('#loading-employee').attr('hidden', 'hidden');
                        $('#employee-table-results').removeAttr('hidden');

                        if(res != 0 && res != '0'){
                            $('#searched-employee-results').empty().append(res);
                            $('#shift-code-row').removeAttr('hidden');
                            $('#btn-save-button-selected-employee').removeAttr('hidden');
                        }
                        else{
                            $('#searched-employee-results').empty().append('<tr><td class="text-center" colspan="12">No record found</td></tr>');
                            $('#shift-code-row').attr('hidden','hidden');
                            $('#btn-save-button-selected-employee').attr('hidden','hidden');
                        }
                    }
                });
            }
        });

        $('#btn-save-button-selected-employee').click(function () {
            let checked = $('.employee-checkbox').find('input[type=checkbox]:checked').length;
            let shift_code = $('#shift_code').val();
            if(checked > 0){
                if(shift_code > 0){
                    $('#btn-save-button-selected-employee-2').click();
                }
                else{
                    // $('#alert-required-message').empty().append('<strong>Required!</strong> Select shift code.').removeAttr('hidden');
                    Swal.fire({
                        icon: "warning",
                        title: "Required!",
                        text: "Select shift code.",
                        showConfirmButton: true
                    });
                }
            }
            else{
                // $('#alert-required-message').empty().append('<strong>Required!</strong> Choose at least one employee.').removeAttr('hidden');
                Swal.fire({
                    icon: "warning",
                    title: "Required!",
                    text: "Choose at least one employee.",
                    showConfirmButton: true
                });
            }
        });
        

    });

    function getEmployeeList(){
        $("#emp_table").DataTable().clear();
        $("#emp_table").DataTable().destroy();
        $emp_list = <?php echo json_encode($employees); ?>;
        var table_rows = "";
        var dept_id = $("#Department_Empl option:selected").text();
        Object.keys($emp_list).forEach(function(key) {
            if($emp_list[key]["Department_Empl"] ==dept_id )
            {
                table_rows = table_rows + "<tr>\
                                        <td>"+$emp_list[key]["Name_Empl"]+"</td>\
                                        <td>"+$emp_list[key]["Position_Empl"]+"</td>\
                                        <td>"+$emp_list[key]["Department_Empl"]+"</td>\
                                        <td align='center'>\
                                            <input type='checkbox' class='checked' style='top: .8rem;width: 1rem;height: 1rem;' value='"+ $emp_list[key]["SysPK_Empl"] +"' name='checked[]'></input>\
                                        </td>\
                                        </tr>";
            }
            // console.log(key, $emp_list[key]["Name_Empl"]);

        });
        $('#emp_table > tbody:last-child').append(table_rows);
        $("#emp_table").DataTable();
    }
    
    function setShiftEmployee(index,emp_id,emp_name,department){
        
        $('#d_from').val($('#date_selected_from').val());
        $('#d_to').val($('#date_selected_to').val());
        $('#s_emp').val( emp_name );
        $('#s_dept').val( department );
        var shift_list = "";
        var a = emp_id;
        // DisplayEmployeeShift(emp_id,$('#d_from').val(),$('#d_to').val()); old
        DisplayEmployeeShift_new(emp_id,$('#d_from').val(),$('#d_to').val()); // new
    }

    function DisplayEmployeeShift_new(emp_id,date_from,date_to){
        let url = "{{ route('get_emp_shift') }}";
        let str = "";
        let checkedIndentifier = '';
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        $.ajax({
            url: url,
            type: 'GET',
            data: {emp_id : emp_id,date_from: date_from, date_to: date_to},
            beforeSend: function(){
                // $('#emp_shift_body').empty().html('<tr><td colspan="10"><center><i class="fas fa-spinner fa-spin text-info m-auto" aria-hidden="true"></i></center></td></tr>');
                Swal.fire({
                    width: "350",
                    title: 'Fetching...',
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
                });
            },
            success: function(result) {

                let bnt_update_checker = false;

                $.each(result[0], function (i) {

                let d = new Date(result[0][i].shift_date);

                checkedIndentifier = '';

                if(result[0][i].status == 1) checkedIndentifier = 'checked';
                
                if(i == 0) $('#d_from_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());
                else $('#d_to_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());

                if(result[0][i].allow_update == 1){

                    bnt_update_checker = true;

                    str += '<tr>\
                                <td>'+ monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear()+'</td>\
                                <td><select id="u_shift_code[]" name="u_shift_code[]" class="form-control custom-select" value="'+ result[0][i].shift_code_id+'" required>';
                                str +='<option value="" selected disabled>Select shift code</option>';
                            $.each(result[1], function (g) {
                                if(result[1][g].id == result[0][i].shift_code_id)str += '<option value="'+ result[1][g].id +'" selected>'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                                else str += '<option value="'+ result[1][g].id +'">'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                            });

                    str += '</select></td>';

                    new_remarks = "";
                    if(result[0][i].remarks) new_remarks = result[0][i].remarks;

                    str += '<td><input type="number" name="u_break_hr[]" value="'+result[0][i].no_hr_break+'" any></td>\
                                <td>'+result[0][i].created_at+'</td>\
                                <td hidden><input type="text" name="u_shift_id[]" value="'+result[0][i].id+'"></td>\
                                <td hidden><input class="form-control" type="text" name="u_remarks[]" value="'+new_remarks+'"></td>\
                                <td class="text-center"><div class="form-check"><input name="u_status['+result[0][i].id+']" style="width: 20px;height: 20px;" class="form-check-input" value="'+result[0][i].id+'" type="checkbox" '+checkedIndentifier+'></div></td>\
                                <td>...</td>\
                            </tr>';
                }
                else{
                    
                    str += '<tr style="border: 2px solid black;" title="This item is currently locked and cannot be updated at this time. If you have any questions or concerns, please feel free to reach out for assistance. Thank you for your understanding.">\
                                <td>'+ monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear()+'</td>\
                                <td><span class="ml-2">'+result[0][i].shift_code+' - '+result[0][i].Description+'</span></td>';

                    new_remarks = "";
                    if(result[0][i].remarks) new_remarks = result[0][i].remarks;

                    str += '<td><span class="ml-2">'+result[0][i].no_hr_break+'</span></td>\
                                <td>'+result[0][i].created_at+'</td>\
                                <td class="text-center"><div class="form-check"><input style="width: 20px;height: 20px;" class="form-check-input" onClick="return false;" type="checkbox" '+checkedIndentifier+'></div></td>\
                                <td><i class="fa-solid fa-lock text-dark"></i></td>\
                            </tr>';
                }
                });

                if(bnt_update_checker) $('.btn_update_shift').removeClass('d-none');
                else $('.btn_update_shift').addClass('d-none');

                // set empty
                $('#emp_shift_body').empty();

                // append data
                $('#emp_shift_table').append(str);

                Swal.close();
            },
            error: function() {
                console.log('Oppss something went wrong');
            }
        });
    }

    function SetEmpShiftDates(){
        $('#date_from_s').val( $('#date_selected_from').val() );
        $('#date_to_s').val( $('#date_selected_to').val() );
    }
    
    // Filter names
    function searchNames() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInputSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("searched-employee-results");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }
        }
    }
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}