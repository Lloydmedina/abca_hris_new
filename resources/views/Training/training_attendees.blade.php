@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Training Attendees')
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

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-1">
                    <a href="{{ url('/trainings') }}" title="Go to list">
                        <button class="btn btn-sm btn-link"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                    </a>
                </div>
                <div class="col-3">
                    
                    <b>Training Name</b>
                    <div>{{ $training->tr_name }}</div>
                </div>

                <div class="col-3">
                    <b>Date/Time</b>
                    <div>{{ date('M d, Y', strtotime($training->tr_date)) }} {{ $training->tr_time }}</div>
                </div>

                <div class="col-3">
                    <b>Trainer/s</b>
                    <div>{{ $training->trainers }}</div>
                </div>

                <div class="col-2 text-center">
                    <b>Total Number of Attendees</b>
                    <div>{{ count($attendees) }}</div>
                </div>

                <div class="col-1"></div>
                <div class="col-11">
                    <div class="mt-3">{{ $training->tr_description }}</div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <h4 class="card-title">Attendees</h4>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
                </div>
                <div class="col-4 text-right">
                    <a href="javascript:(0)" class="btn btn-sm btn-dark" id="btn_add_attendees">
                        <i class="fa fa-plus-circle"></i> Add New
                    </a>
                </div>
            </div>
            <div class="table-responsive m-t-40">
                <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="">Employee ID</th>
                            <th class="">Name</th>
                            <th class="">Brand</th>
                            <th class="">Outlet</th>
                            <th class="">Result</th>
                            <th class="">Certification</th>
                            <th class="">Validity</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="myTbody">
                    @if(count($attendees))
                    @php
                        $index = 0;
                    @endphp
                    @foreach($attendees as $row)

                        <tr id="{{ $row->att_id.md5($row->att_id) }}">
                            <td>{{ $row->UserID_Empl }}</td>
                            <td>{{ $row->Name_Empl }}</td>
                            <td>{{ $row->company }}</td>
                            <td>{{ $row->outlet }}</td>
                            <td>{{ $row->result }}</td>
                            <td>{{ $row->certification }}</td>
                            <td>{{ $row->validity }}</td>
                            <td class="text-center">
                                <a 
                                    data-delete_url="{{ url('/attendee/delete/'.$row->att_id.'') }}"
                                    data-title="{{ $row->Name_Empl ?? ''}}"
                                    href="javascript:(0)" class="mr-1 text-danger attendee_delete_modal" title="Remove Attendee">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>

                                <a 
                                    data-update_url="{{ url('/attendee/update/') }}"
                                    data-emp_id="{{ $row->UserID_Empl ?? ''}}"
                                    data-id="{{ $row->att_id ?? ''}}"
                                    data-name="{{ $row->Name_Empl ?? ''}}"
                                    data-result="{{ $row->result ?? ''}}"
                                    data-certification="{{ $row->certification ?? ''}}"
                                    data-validity="{{ $row->validity ?? ''}}"
                                    href="javascript:(0)" class="ml-1 text-dark attendee_update_modal" title="Update">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $index = $index +1; ?>
                    @endforeach
                    @else
                    <tr><td class="text-center" colspan="12">No record found</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

</div>

{{-- ADD / REMOVE ATTENDEES MODAL --}}
<div class="modal fade" id="add_attendees_modal" tabindex="-1" aria-labelledby="add_attendees_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_attendees_modalLabel">New Attendees</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('add_attendees') }}" method="POST">
                    @csrf
                    <input type="text" value="{{ $training->tr_id }}" name="tr_id" hidden>
                    <div class="form-group">
                        <label for="sel1">Select Attendees:</label>
                        <br>
                        <select class="selectpicker" multiple data-live-search="true" data-width="100%" name="attendees[]">
                            @foreach ( $employees as $employee )
                                <option value="{{ $employee->SysPK_Empl }}">{{ $employee->Name_Empl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn d-none btn_add_submit" type="submit"><i class="fa-solid fa-plus"></i> Add</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark" type="button" id="btn_save_attendees"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </div>
        </div>
    </div>
</div>
{{-- END ADD / REMOVE ATTENDEES MODAL --}}

{{-- ATTENDEE DELETE MODAL --}}
<div class="modal fade" id="attendee_delete_modal" tabindex="-1" aria-labelledby="attendee_delete_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="attendee_delete_modalLabel">Are you sure?</h5>
             <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
          </div>
          <div class="modal-body text-center">
             <p>Are you sure you want to remove this attendee? <br>
             You CAN NOT view this attendee in your list anymore if you remove.</p>
    
             <h5 class="modal-title" id="attende_name_delete_title_modal"></h5>
          </div>
          <div class="modal-footer">
             <a href="" id="btn-delete-attendee-button" class="btn btn-dark" type="button">Yes, Remove</a>
             <button class="btn btn-danger" type="button" data-dismiss="modal">No, Don't Remove</button>
          </div>
       </div>
    </div>
</div>
{{-- END ATTENDEE DELETE MODAL --}}

{{-- ATTENDEE UPDATE MODAL --}}
<div class="modal fade" id="attendee_update_modal" tabindex="-1" aria-labelledby="attendee_update_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="attendee_update_modalLabel">Update Attendee?</h5>
             <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
          </div>
            <form id="action-update-attendee" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="text" name="id" id="attende_id_update_modal" hidden>
                    <div class="form-group">
                        <label class="control-label">Employee ID</label>
                        <input type="input" id="attende_emp_id_update_modal" value="" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Employee Name</label>
                        <input type="input" id="attende_name_update_modal" value="" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Result</label>
                        <input type="input" id="tr_result" name="result" value="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Certification</label>
                        <input type="input" id="tr_certification" name="certification" value="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Validity</label>
                        <input type="input" id="tr_validity" name="validity" value="" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" type="submit"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                </div>
            </form>
       </div>
    </div>
</div>
{{-- END ATTENDEE UPDATE MODAL --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
{{-- <script src="{{ asset('uidesign/js/custom/shift.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function(){
        // $('select').selectpicker();
        // Attendees
        $('#btn_add_attendees').click(function(){
            $('#add_attendees_modal').modal({backdrop: 'static', keyboard: true});
        });

        $('#btn_save_attendees').click(function(){
            $('.btn_add_submit').click();
        });

        // Delete attendee
        $('.attendee_delete_modal').click(function(){
            $('#attende_name_delete_title_modal').text($(this).data('title'));
            $('#btn-delete-attendee-button').attr('href', $(this).data('delete_url'));
            $('#attendee_delete_modal').modal({backdrop: 'static', keyboard: true});
        });

        // update attendee
        $('.attendee_update_modal').click(function(){
            $('#attende_name_update_modal').val($(this).data('name'));
            $('#tr_result').val($(this).data('result'));
            $('#tr_certification').val($(this).data('certification'));
            $('#tr_validity').val($(this).data('validity'));
            $('#attende_emp_id_update_modal').val($(this).data('emp_id'));
            $('#attende_id_update_modal').val($(this).data('id'));
            $('#action-update-attendee').attr('action', $(this).data('update_url'));
            $('#attendee_update_modal').modal({backdrop: 'static', keyboard: true});
        });

    });

    function searchNames() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInputSearch");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTbody");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
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