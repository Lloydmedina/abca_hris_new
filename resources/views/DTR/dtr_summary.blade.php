@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS --}}
@section('page_level_css')
    <link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title', 'DTR Summary')
{{-- BEGIN CONTENT --}}
@section('content')
    <!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

    @include('Templates.alert_message')

    <form class="form-material" action="{{ url('/dtr_summary') }}" method="get">

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Company</label>
                            <select id="company_id" name="company_id" class="form-control custom-select" >
                                <option value="{{ 0 }}" selected>All</option>
                                @foreach($company as $row)
                                    @if($company_id)
                                    @if($company_id == $row->company_id )
                                        <option value="{{ $row->company_id }}" selected>{{ $row->company }}</option>
                                    @else
                                        <option value="{{ $row->company_id }}">{{ $row->company }}</option>
                                    @endif
                                    @else
                                    <option value="{{ $row->company_id }}">{{ $row->company }}</option>
                                    @endif
                                    
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date From</label>
                            <input type="date" class="form-control" id="date_from"
                                value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d'); ?>" name="date_from" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date To</label>
                            <input type="date" class="form-control" id="date_to"
                                value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d', strtotime(date('Y-m-d') . ' + 15 days')); ?>" name="date_to" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <label class="hide" style="visibility: hidden">Search</label>
                        @include('button_component.search_button', ['margin_top' => "16.5"])
                    </div>
                </div>
            </div>
        </div>

    </form>

    <hr>

    <form class="form-material" action="{{ url('/save_dtr') }}" method="post">
        @csrf
        <div class="row pt-3">
            <div class="col-12">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="table-responsive m-t-40">
                            <table id="dtr_list"
                                class="display nowrap table table-sm table-hover table-striped table-bordered"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">
                                            ID
                                        </th>
                                        <th class="text-center align-middle">
                                            Name
                                        </th>
                                        <th class="text-center align-middle">
                                            Late
                                        </th>
                                        <th class="text-center align-middle">
                                            Undertime
                                        </th>
                                        <th class="text-center align-middle">
                                            Absent
                                        </th>
                                        <th class="text-center align-middle">
                                            OT
                                        </th>
                                        <th class="text-center align-middle">
                                            NP
                                        </th>
                                        <th class="text-center align-middle">
                                            Holiday<br>Hours
                                        </th>
                                        <th class="text-center align-middle">
                                            RD
                                        </th>
                                        <th class="text-center align-middle">
                                            RDOT
                                        </th>
                                        <th class="text-center align-middle">
                                            Total<br>Hours
                                        </th>
                                        <th class="text-center align-middle">
                                            Total<br>Days
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="list_body" name="list">
                                    @if ($dtr_list)
                                            @foreach ($dtr_list as $list)
                                                    <tr>
                                                        <td class="align-middle text-center">{{ $list->employee_number }}</td>
                                                        <td class="align-middle text-near">{{ $list->employee_fullName }}</td>
                                                        <td class="align-middle text-center">{{ $list->late }}</td>
                                                        <td class="align-middle text-center">{{ $list->undertime }}</td>
                                                        <td class="align-middle text-center">{{ $list->absent }}</td>
                                                        <td class="align-middle text-center">{{ $list->ot_hours }}</td>
                                                        <td class="align-middle text-center">{{ $list->np_hours }}</td>
                                                        <td class="align-middle text-center">{{ $list->holiday_hours }}</td>
                                                        <td class="align-middle text-center">{{ $list->RD }}</td>
                                                        <td class="align-middle text-center">{{ $list->RDOT }}</td>
                                                        <td class="align-middle text-center">{{ $list->total_hours_worked }}</td>
                                                        <td class="align-middle text-center">{{ $list->DutyDays }}</td>
                                                    </tr>
                                            @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="row pt-3 no-gutters align-items-center">
                            <div class="col text-right">
                               <button class="btn btn-sm btn-dark" type="button" onclick="print_dtr_list()">
                                  <i class="fa-solid fa-print"></i> Print
                               </button>
                            </div>
                         </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    <hr>

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
    <script>
        $(document).ready(function() {
            $('#dtr_list').DataTable();
        });
        $('#dtr_list').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
            'btn btn-sm btn-primary mr-1');

        function print_dtr_list() {
            var myWindow = window.open("{{ url('/print_dtr_list') }}", "myWindow", 'width=1500,height=800');
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }
        @if (session('user')->employee_type_id == 1)
            function ShowModal(index) {
                clearFields();
                var row_val = $('#list_body').find('tr');
                document.getElementById("dtr_sum_id").value = $('#dtr_list').DataTable().cell(index, 1).data()
                document.getElementById("emp_no").value = $('#dtr_list').DataTable().cell(index, 2).data();
                document.getElementById("emp_name").value = $('#dtr_list').DataTable().cell(index, 3).data();
                document.getElementById("total_hours").value = $('#dtr_list').DataTable().cell(index, 4).data();
                document.getElementById("meal_allowance").value = $('#dtr_list').DataTable().cell(index, 5).data();
                document.getElementById("ot").value = $('#dtr_list').DataTable().cell(index, 6).data();
                document.getElementById("rd").value = $('#dtr_list').DataTable().cell(index, 7).data();
                document.getElementById("rdot").value = $('#dtr_list').DataTable().cell(index, 8).data();
                document.getElementById("np").value = $('#dtr_list').DataTable().cell(index, 9).data();
                document.getElementById("absent").value = $('#dtr_list').DataTable().cell(index, 10).data();
                document.getElementById("late").value = $('#dtr_list').DataTable().cell(index, 11).data();
                document.getElementById("ut").value = $('#dtr_list').DataTable().cell(index, 12).data();
                document.getElementById("lh").value = $('#dtr_list').DataTable().cell(index, 13).data();
                document.getElementById("lhot").value = $('#dtr_list').DataTable().cell(index, 14).data();
                document.getElementById("lhrd").value = $('#dtr_list').DataTable().cell(index, 15).data();
                document.getElementById("lhrdot").value = $('#dtr_list').DataTable().cell(index, 16).data();
                document.getElementById("sh").value = $('#dtr_list').DataTable().cell(index, 17).data();
                document.getElementById("shot").value = $('#dtr_list').DataTable().cell(index, 18).data();
                document.getElementById("shrd").value = $('#dtr_list').DataTable().cell(index, 19).data();
                document.getElementById("shrdot").value = $('#dtr_list').DataTable().cell(index, 20).data();
                document.getElementById("sh2").value = $('#dtr_list').DataTable().cell(index, 21).data();
                document.getElementById("shot2").value = $('#dtr_list').DataTable().cell(index, 22).data();
                document.getElementById("shrd2").value = $('#dtr_list').DataTable().cell(index, 23).data();
                document.getElementById("shrdot2").value = $('#dtr_list').DataTable().cell(index, 24).data();
                document.getElementById("lh2").value = $('#dtr_list').DataTable().cell(index, 25).data();
                document.getElementById("lhot2").value = $('#dtr_list').DataTable().cell(index, 26).data();
                document.getElementById("lhrd2").value = $('#dtr_list').DataTable().cell(index, 27).data();
                document.getElementById("lhrdot2").value = $('#dtr_list').DataTable().cell(index, 28).data();
                document.getElementById("lhsh").value = $('#dtr_list').DataTable().cell(index, 29).data();
                document.getElementById("lhshot").value = $('#dtr_list').DataTable().cell(index, 30).data();
                document.getElementById("lhshrd").value = $('#dtr_list').DataTable().cell(index, 31).data();
                document.getElementById("lhshrdot").value = $('#dtr_list').DataTable().cell(index, 32).data();
                document.getElementById("vl_wp").value = $('#dtr_list').DataTable().cell(index, 33).data();
                document.getElementById("sl_wp").value = $('#dtr_list').DataTable().cell(index, 34).data();
                document.getElementById("vl_wop").value = $('#dtr_list').DataTable().cell(index, 35).data();
                document.getElementById("sl_wop").value = $('#dtr_list').DataTable().cell(index, 36).data();
                document.getElementById("spl").value = $('#dtr_list').DataTable().cell(index, 37).data();
                document.getElementById("bl").value = $('#dtr_list').DataTable().cell(index, 38).data();
                document.getElementById("ml").value = $('#dtr_list').DataTable().cell(index, 39).data();
                document.getElementById("pl").value = $('#dtr_list').DataTable().cell(index, 40).data();
            }

            function clearFields() {
                document.getElementById("dtr_sum_id").value = 0;
                document.getElementById("emp_no").value = "";
                document.getElementById("emp_name").value = "";
                document.getElementById("total_hours").value = 0;
                document.getElementById("meal_allowance").value = 0;
                document.getElementById("ot").value = 0;
                document.getElementById("rd").value = 0;
                document.getElementById("rdot").value = 0;
                document.getElementById("np").value = 0;
                document.getElementById("absent").value = 0;
                document.getElementById("late").value = 0;
                document.getElementById("ut").value = 0;
                document.getElementById("lh").value = 0;
                document.getElementById("lhot").value = 0;
                document.getElementById("lhrd").value = 0;
                document.getElementById("lhrdot").value = 0;
                document.getElementById("sh").value = 0;
                document.getElementById("shot").value = 0;
                document.getElementById("shrd").value = 0;
                document.getElementById("shrdot").value = 0;
                document.getElementById("sh2").value = 0;
                document.getElementById("shot2").value = 0;
                document.getElementById("shrd2").value = 0;
                document.getElementById("shrdot2").value = 0;
                document.getElementById("lh2").value = 0;
                document.getElementById("lhot2").value = 0;
                document.getElementById("lhrd2").value = 0;
                document.getElementById("lhrdot2").value = 0;
                document.getElementById("lhsh").value = 0;
                document.getElementById("lhshot").value = 0;
                document.getElementById("lhshrd").value = 0;
                document.getElementById("lhshrdot").value = 0;
                document.getElementById("vl_wp").value = 0;
                document.getElementById("sl_wp").value = 0;
                document.getElementById("vl_wop").value = 0;
                document.getElementById("sl_wop").value = 0;
                document.getElementById("spl").value = 0;
                document.getElementById("bl").value = 0;
                document.getElementById("ml").value = 0;
                document.getElementById("pl").value = 0;
            }


            function UpdateDTR() {
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/update_dtr_summary') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: document.getElementById("dtr_sum_id").value,
                        emp_name: document.getElementById("emp_name").value,
                        total_hours: document.getElementById("total_hours").value,
                        meal_allowance: document.getElementById("meal_allowance").value,
                        ot: document.getElementById("ot").value,
                        rd: document.getElementById("rd").value,
                        rdot: document.getElementById("rdot").value,
                        np: document.getElementById("np").value,
                        absent: document.getElementById("absent").value,
                        late: document.getElementById("late").value,
                        ut: document.getElementById("ut").value,
                        lh: document.getElementById("lh").value,
                        lhot: document.getElementById("lhot").value,
                        lhrd: document.getElementById("lhrd").value,
                        lhrdot: document.getElementById("lhrdot").value,
                        sh: document.getElementById("sh").value,
                        shot: document.getElementById("shot").value,
                        shrd: document.getElementById("shrd").value,
                        shrdot: document.getElementById("shrdot").value,
                        sh2: document.getElementById("sh2").value,
                        shot2: document.getElementById("shot2").value,
                        shrd2: document.getElementById("shrd2").value,
                        shrdot2: document.getElementById("shrdot2").value,
                        lh2: document.getElementById("lh2").value,
                        lhot2: document.getElementById("lhot2").value,
                        lhrd2: document.getElementById("lhrd2").value,
                        lhrdot2: document.getElementById("lhrdot2").value,
                        lhsh: document.getElementById("lhsh").value,
                        lhshot: document.getElementById("lhshot").value,
                        lhshrd: document.getElementById("lhshrd").value,
                        lhshrdot: document.getElementById("lhshrdot").value,
                        vl_wp: document.getElementById("vl_wp").value,
                        sl_wp: document.getElementById("sl_wp").value,
                        vl_wop: document.getElementById("vl_wop").value,
                        sl_wop: document.getElementById("sl_wop").value,
                        spl: document.getElementById("spl").value,
                        bl: document.getElementById("bl").value,
                        ml: document.getElementById("ml").value,
                        pl: document.getElementById("pl").value
                    },
                    success: function(data) {

                        alert(data.success);
                        $('#btnSearch').click();
                    }

                });
            }
        @endif
    </script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}
