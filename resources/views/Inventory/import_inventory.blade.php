<?php
require('App/XLSXReader/XLSXReader.php');
?>

@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Import Inventory')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

    @include('Templates.alert_message')

    <div class="card">
    <div class="card-body">
        <h4 class="card-title">Company Inventory</h4>
        <form action="{{ route('import-inventory') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-md-2">Upload file</label>
                <div class="col-md-10">
                    <input class="form-control d-none" id="file_import" type="file" name="inventory" required>
                    <button type="submit" id="btn_choose_file" class="btn btn-sm btn-primary">Choose file</button>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" id="btn_upload" class="d-none btn btn-sm btn-primary">Upload</button>
            </div>
        </form>
    </div>
    </div>

    <hr>
    <?php $dir = 'storage/uploads/inventory/'; ?>
    @if(isset($_GET['imported_file']) && file_exists($dir.$_GET['imported_file']))
        <div class="card">
            <div class="card-body ">
                <h6 class="card-title text-bold">Inventory <small>imported.</small></h6>
                <table id="myTable" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ITEM ID</th>
                            <th>ITEM DESCRIPTION</th>
                            <th>ITEM TYPE</th>
                            <th>STOCKING</th>
                            <th class="text-right">QTY ON HAND</th>
                            <th class="text-right">UNIT COST</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $imported_file = $dir.$_GET['imported_file'];
                        $xlsx = new XLSXReader($imported_file);
                        $sheetNames = $xlsx->getSheetNames();
                        $sheet = array();
                        $ctr = 0;

                        if (strpos($xlsx->getSheetNames()[1], 'ENVIRO') == true) {
                            $sheet = $xlsx->getSheet($sheetNames[1])->getData();
                        }
                        @endphp
                        
                        @foreach($sheet as $row)

                        @if( $ctr != 0 && $row[0] )
                            <tr style="width: 50%;">
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>{{ $row[3] }}</td>
                            <td class="text-right">{{ $row[4] }}</td>
                            <td class="text-right">{{ $row[5] }}</td>
                            @php $Item_ID = $row[0] @endphp
                            </tr>
                        @endif
                        <?php $ctr++; ?>
                        @endforeach

                    </tbody>
                </table>
                <form action="{{ route('save_inventory') }}" method="post">
                    @csrf
                    <input type="hidden" name="imported_file" value="<?php echo (isset($_GET['imported_file'])) ? $dir.$_GET['imported_file'] : '' ?>">
                    <input type="hidden" name="file_path" value="{{ (isset($_GET['imported_file'])) ? $_GET['imported_file'] : ''}}">
                    {{-- GAMIT KAY NI ANG LOG_IF BASIHANAN NI --}}
                    <input type="hidden" name="Item_ID" value="<?php echo (isset($Item_ID)) ? $Item_ID : '' ?>">
                    @if(!session('save_data') && $sheet)
                        <div class="text-center">
                            <button type="submit" class="mt-2 btn btn-sm btn-primary">Save to database</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <hr>
    @endif

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