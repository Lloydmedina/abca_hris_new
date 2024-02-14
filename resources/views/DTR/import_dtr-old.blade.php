@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Import DTR')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

    @include('Templates.alert_message')

    <div class="card">
    <div class="card-body">
        <h4 class="card-title">Employee Attendance</h4>
        <form action="{{ route('import_dtr') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mx-1">
                <div class="col-md-4">
                    <div class="form-group">
                    <label class="control-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" value="<?php echo (isset($_GET['date_from'])) ? $_GET['date_from'] : '' ?>" name="date_from" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    <label class="control-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" value="<?php echo (isset($_GET['date_to'])) ? $_GET['date_to'] : '' ?>" name="date_to" required>
                    </div>
                </div>

                <div class="col-md-4">
                <div class="form-group row">
                <div class="col-md-10 mt-3">
                    <input class="form-control d-none" id="file_import" type="file" name="dtr" required>
                    <button type="submit" id="btn_choose_file" class="btn btn-sm btn-primary mt-3">Choose file</button>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" id="btn_upload" class="d-none btn btn-sm btn-primary">Upload</button>
            </div>
                </div>
            </div>
            
        </form>
    </div>
    </div>

    <?php $dir = 'storage/uploads/dtr/'; ?>
    @if(isset($_GET['imported_file']) && file_exists($dir.$_GET['imported_file']))
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-bold">Employee DTR <small>imported.</small></h6>
                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>EMPLOYEE ID</th>
                                <th>DATE</th>
                                <th>LOG TYPE</th>
                                <th>TIME IN/OUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $imported_file = $dir.$_GET['imported_file'];
                            $file = fopen($imported_file,"r");
                            $ctr = 0;
                            $data_att = null;
                            
                            while(! feof($file))
                            {
                                    $new_file = trim(fgets($file));
                                    $str = $new_file;
                                    $delimiter = ' ';
                                    $dtr = explode(' ', $str);
                                    if (count($dtr)==1) {
                                        continue;
                                    }
                                    $get_date_time = strrev(substr(strrev($dtr[0]), 0,10)).' '.substr($dtr[1], 0,8);//REVERSE FIRST BEFORE WE GET THE STRING
                                    $date = strrev(substr(strrev($dtr[0]), 0,10));
                                    $time = substr($dtr[1], 0,8);
                                    $employee_id = strrev(substr(strrev($dtr[0]), 10,10));//REVERSE FIRST BEFORE WE GET THE STRING
                                    $log_id = substr($dtr[1], 11,1);//REVERSE FIRST BEFORE WE GET THE STRING

                                    
                                    //INSERT TO ARRAY BASE ON THE DATE SELECTED
                                    if (strtotime($date) >= strtotime($_GET['date_from']) && strtotime($date) <= strtotime($_GET['date_to'])) {
                                            $data_att[] = array(
                                            'log_id' => $log_id,
                                            'employee_id' => $employee_id,
                                            'date' => $date,
                                            'time' => $time
                                        );
                                    
                                    }
                                    $ctr++;
                            }
                            
                            $cnt = 0;
                            if($data_att)
                            {
                                foreach ($data_att as $row) 
                                {
                                        $cnt = $cnt + 1;
                                        $log_type = $row['log_id'] == "1" ? "OUT" : "IN";
                                    echo '<tr style="width:30%">';
                                            echo '<td>'.$cnt.'</td>';
                                        echo '<td>'.$row['employee_id'].'</td>';
                                        echo '<td>'.strtoupper(date('m/d/Y', strtotime($row['date']))).'</td>';
                                        echo '<td>'.$log_type.'</td>';
                                        echo '<td>'.strtoupper(date('H:i', strtotime($row['time']))).'</td>';
                                    echo '</tr>';
                                    $log_id = $row['log_id'];
                                }
                            }
                            @endphp
                        </tbody>
                    </table>
                    <form action="{{ route('import_dtr_dat') }}" method="post">
                        @csrf
                        <input type="hidden" name="imported_file" value="<?php echo (isset($_GET['imported_file'])) ? $dir.$_GET['imported_file'] : '' ?>">
                        <input type="hidden" name="file_path" value="{{ (isset($_GET['imported_file'])) ? $_GET['imported_file'] : ''}}">
                        <input type="hidden" class="form-control" id="from_date" value="<?php echo (isset($_GET['date_from'])) ? $_GET['date_from'] : '' ?>" name="from_date" required>
                        <input type="hidden" class="form-control" id="to_date" value="<?php echo (isset($_GET['date_to'])) ? $_GET['date_to'] : '' ?>" name="to_date" required>
                        {{-- GAMIT KAY NI ANG LOG_IF BASIHANAN NI --}}
                        <input type="hidden" name="log_id" value="<?php echo (isset($log_id)) ? $log_id : '' ?>">
                        @if(!session('save_data') && $data_att)
                            <div class="text-center">
                                <button type="submit" class="mt-2 btn btn-sm btn-primary">Save to database</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

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
<script type="text/javascript">
let base_url = window.location.origin + '/' + window.location.pathname.split('/')[1] + '/';
if(window.location.origin != "http://localhost"){
    base_url = window.location.origin + '/';
}
$(document).ready(function(){
   //trigger click file upload
    $('#btn_choose_file').click(function(){
        $('#file_import').click();
    });
    //trigger click upload submit
    $('#file_import').change(function(){
        $('#btn_upload').click();
    });
});

$(function() {
    $('#myTable').DataTable();
    $(function() {
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
    });
});
$('#example23').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});
$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}