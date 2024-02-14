@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS --}}
@section('page_level_css')
    <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title', 'Outlet Setup')

{{-- BEGIN CONTENT --}}
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        @include('Templates.alert_message')

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h4 class="card-title">Outlets <small>({{ count($outlets) }})</small></h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-sm btn-primary m-l-15 float-right"
                            data-form_url="{{ url('/add_outlet') }}" id="add_outlet_button">
                            <i class="fa fa-plus-circle"></i>
                            Add New
                        </button>
                    </div>
                </div>
                <div class="table-responsive m-t-40">
                    <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Outlet Name</th>
                                <th>Remarks</th>
                                <th class="text-center" style="width: 200px;">Approver/Outlet Head</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="list_body_" name="list">
                            @if (count($outlets))
                                @foreach ($outlets as $outlet)
                                    <tr>
                                        <td>{{ $outlet->outlet }}</td>
                                        <td>{{ $outlet->remarks }}</td>
                                        <td class="text-center">
                                            @php
                                                $approver_emp_ids = $outlet->approver_emp_id ? explode(',', $outlet->approver_emp_id) : [];
                                            @endphp

                                            @if (count($approver_emp_ids))
                                                <p id="org_total_approver_{{ $outlet->outlet_id }}"
                                                    class="m-auto badge badge-info p-2">{{ count($approver_emp_ids) }}</p>
                                            @else
                                                <p id="org_total_approver_{{ $outlet->outlet_id }}"
                                                    class="m-auto badge badge-danger p-2">{{ count($approver_emp_ids) }}</p>
                                            @endif
                                            -
                                            <button type="button m-auto" data-outlet_id="{{ $outlet->outlet_id }}"
                                                data-outlet="{{ $outlet->outlet }}"
                                                data-approver_emp_id="{{ $outlet->approver_emp_id }}"
                                                class="btn btn-sm btn-primary add_remove_approver">
                                                View
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <span data-outlet_id="{{ $outlet->outlet_id }}"
                                                data-outlet="{{ $outlet->outlet }}" data-remarks="{{ $outlet->remarks }}"
                                                class="mr-1 text-primary update_outlet_button" style="cursor: pointer;"
                                                title="Update outlet">
                                                <span class="fa-solid fa-pen-to-square"></span>
                                            </span>
                                            |
                                            <span data-delete_url="{{ url('/outlet/delete/' . $outlet->outlet_id . '') }}"
                                                data-title="{{ $outlet->outlet ?? '' }}"
                                                class="ml-1 text-danger outlet_delete_modal" style="cursor: pointer;"
                                                title="Delete outlet">
                                                <span class="fa-solid fa-trash-can"></span>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">No record found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <hr>

    </div>
    <!-- /.container-fluid -->

    {{-- ENTRY MODAL --}}
    <div id="outlet_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        style=" padding-right: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" id="modal_outlet_title"></h4>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <form class="form-material" id="form_outlet_id" action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="outlet_id" id="outlet_id_to_update" hidden>
                                        <label class="control-label">Outlet <i class="text-small text-danger">*</i></label>
                                        <input type="text" id="outlet" name="outlet" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Company Group <i
                                                class="text-small text-danger">*</i></label>
                                        <select class="border form-control custom-select selectpicker"
                                            data-live-search="true" required name="company_group_id" id="company_group_id"
                                            autofocus>
                                            @foreach ($company_group as $row)
                                                <option value="{{ $row->company_group_id }}">{{ $row->company_group }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->

                            </div>

                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Remarks</label>
                                        <textarea id="remarks" name="remarks" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                            <div class="modal-footer">
                                <div class="form-actions m-auto">
                                    <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i
                                            class="fa fa-times"></i> Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-primary ml-2"
                                        id="modal_outlet_button"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END ENTRY MODAL --}}

    {{-- OUTLET DELETE MODAL --}}
    <div class="modal fade" id="outlet_delete_modal" tabindex="-1" aria-labelledby="outlet_delete_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="outlet_delete_modalLabel">Are you sure?</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete this outlet? <br>
                        You CAN NOT view this outlet in your list anymore if you delete.</p>

                    <h5 class="modal-title" id="outlet_delete_title_modal"></h5>
                </div>
                <div class="modal-footer">
                    <a href="" id="btn-delete-outlet-button" class="btn btn-danger" type="button">Yes,
                        Delete</a>
                    <button class="btn btn-success" type="button" data-dismiss="modal">No, Don't Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END OUTLET DELETE MODAL --}}

    {{-- ADD / REMOVE APPROVER MODAL --}}
    <div class="modal fade" id="add_remove_approver_modal" tabindex="-1"
        aria-labelledby="add_remove_approver_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_remove_approver_modalLabel">Approver Setting</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="outlet_title_modal"></h5>
                    <input type="input" value="" id="selected_outlet_id" hidden>
                    <br />
                    <div id="outlet_approver_table" style="overflow-y: scroll; max-height:300px;">

                    </div>

                    <hr>
                    <h5>Add Approver</h5>
                    <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()"
                        placeholder="Search for names..">
                    <div style="overflow-y: scroll; max-height:300px;">
                        <table class="table table-sm" style="margin-top: -5px" id="emp_to_add" hidden>
                            <thead class="mb-2" style="position: sticky; inset-block-start: 0; background-color: white">
                                <tr>
                                    <th colspan="2">Select Names</th>
                                </tr>
                            </thead>
                            <tbody id="myTbody2">
                                @foreach ($employees as $employee)
                                    <tr class="not_approver_emp_id_class"
                                        id="not_approver_emp_id_{{ $employee->SysPK_Empl }}">
                                        <td><a href="" data-emp_id={{ $employee->SysPK_Empl }}
                                                class="select_emp">Select</a></td>
                                        <td class="text-left">{{ $employee->Name_Empl }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END ADD / REMOVE APPROVER MODAL --}}

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}

{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
    <script>
        $(document).ready(function() {

            // Add outlet modal
            $('#add_outlet_button').click(function() {
                $('#outlet_id_to_update').val("");
                $('#form_outlet_id').attr('action', "{{ url('/add_outlet') }}");
                $('#modal_outlet_title').text('Add Outlet');
                $('#modal_outlet_button').empty().append('<i class="fa fa-plus-circle"></i> Add');
                $('#outlet_modal').modal({
                    backdrop: 'static',
                    keyboard: true
                });
            });

            // update outlet modal
            $('.update_outlet_button').click(function() {
                // Set values
                $('#outlet_id_to_update').val($(this).data('outlet_id'));
                $('#outlet').val($(this).data('outlet'));
                $('#remarks').val($(this).data('remarks'));
				$('#company_group_id').val($(this).data('company_group_id'));

                $('#form_outlet_id').attr('action', "{{ url('/update_outlet') }}");
                $('#modal_outlet_title').text('Update Outlet');
                $('#modal_outlet_button').empty().append('<i class="fa fa-save"></i> Update');
                $('#outlet_modal').modal({
                    backdrop: 'static',
                    keyboard: true
                });
            });

            // Delete outlet
            $('.outlet_delete_modal').click(function() {
                $('#outlet_delete_title_modal').text($(this).data('title'));
                $('#btn-delete-outlet-button').attr('href', $(this).data('delete_url'));
                $('#outlet_delete_modal').modal({
                    backdrop: 'static',
                    keyboard: true
                });
            });

            // Approver Settings
            $('.add_remove_approver').click(function() {

                let outlet_id = $(this).data('outlet_id');
                let outlet = $(this).data('outlet');
                let approver_emp_id = $(this).data('approver_emp_id');

                $(".not_approver_emp_id_class").removeAttr("style");

                if (approver_emp_id) {

                    if (!$.isNumeric(approver_emp_id)) {
                        approver_emp_id = approver_emp_id.split(',');
                        // Hide names if already an approver
                        $.each(approver_emp_id, function(index, value) {
                            $('#not_approver_emp_id_' + value).css('visibility', 'collapse');
                        });
                    } else $('#not_approver_emp_id_' + approver_emp_id).css('visibility', 'collapse');


                }

                if (outlet_id) {

                    $.ajax({
                        url: "{{ url('/outlet_approver') }}",
                        method: "POST",
                        data: {
                            outlet_id: outlet_id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {

                        },
                        success: function(res) {

                            if (res != 0 && res != '0') {
                                $('#outlet_approver_table').empty().append(res);
                                $('#outlet_title_modal').text('Outlet: ' + outlet);
                                $('#selected_outlet_id').val(outlet_id);

                                $('#add_remove_approver_modal').modal({
                                    backdrop: 'static',
                                    keyboard: true
                                });
                            }
                        }
                    });

                }
            });

            $(".select_emp").click(function(e) {

                let outlet_id = $('#selected_outlet_id').val();
                let emp_id = $(this).data('emp_id');

                if (emp_id) {

                    $.ajax({
                        url: "{{ url('/add_outlet_approver') }}",
                        method: "POST",
                        data: {
                            outlet_id: outlet_id,
                            emp_id: emp_id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {

                        },
                        success: function(res) {

                            if (res != 0 && res != '0') {
                                $('#outlet_approver_table').empty().append(res);
                                $('#not_approver_emp_id_' + emp_id).css('visibility',
                                    'collapse');
                                $("#org_total_approver_" + outlet_id).text($('.total_approver')
                                    .text());

                                if ($('.total_approver').text() == '0' || $('.total_approver')
                                    .text() == 0) $("#org_total_approver_" + outlet_id)
                                    .removeClass('badge-info').addClass('badge-danger');
                                else $("#org_total_approver_" + outlet_id).removeClass(
                                    'badge-danger').addClass('badge-info');
                            }
                        }
                    });

                }
                return false;
            });



            $(document).on("click", ".remove_emp", function(e) {
                e.preventDefault();

                let outlet_id = $('#selected_outlet_id').val();
                let emp_id = $(this).data('emp_id');

                if (emp_id) {

                    $.ajax({
                        url: "{{ url('/remove_outlet_approver') }}",
                        method: "POST",
                        data: {
                            outlet_id: outlet_id,
                            emp_id: emp_id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {

                        },
                        success: function(res) {

                            if (res != 0 && res != '0') {
                                $('#outlet_approver_table').empty().append(res);
                                $('#not_approver_emp_id_' + emp_id).css('visibility', '');
                                $("#org_total_approver_" + outlet_id).text($('.total_approver')
                                    .text());

                                if ($('.total_approver').text() == '0' || $('.total_approver')
                                    .text() == 0) $("#org_total_approver_" + outlet_id)
                                    .removeClass('badge-info').addClass('badge-danger');
                                else $("#org_total_approver_" + outlet_id).removeClass(
                                    'badge-danger').addClass('badge-info');
                            }
                        }
                    });

                }

            });

        });



        function searchNames() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInputSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTbody2");
            tr = table.getElementsByTagName("tr");
            table_div = document.getElementById("emp_to_add");
            if (filter) table_div.removeAttribute("hidden");
            else table_div.setAttribute("hidden", "hidden");
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
