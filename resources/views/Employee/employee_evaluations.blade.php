@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','Employee Evaluations')
	
	{{-- BEGIN CONTENT --}}
	@section('content')
		<!-- Begin Page Content -->
		<div class="container-fluid">
		   <!-- Page Heading -->
		   
		   <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                   <h4 class="card-title">Evaluations</h4>
                                </div>
                                <div class="col-6 text-right">
                                   <a href="{{ url('/employee-evaluation-1') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i> Evaluate Employee</a>
                                </div>
                             </div>
                            <div class="table-responsive m-t-40">
                                <table id="myTable" class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Seq #</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Evaluated By</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($evaluations))
                                            <?php $ctr = count($evaluations) ?>
                                            @foreach($evaluations as $row)
                                                <tr>
                                                    <td>{{$ctr}}</td>
                                                    <td>
                                                        <a href="{{ url('/employee-evaluation?eval_batch=').$row->eval_batch_id }}" target="_blank">
                                                            {{$row->Name_Empl}}
                                                        </a>
                                                    </td>
                                                    <td>{{ $row->Position_Empl }}</td>
                                                    <td>{{ $row->Department_Empl }}</td>
                                                    <td>{{ $row->evaluated_by }}</td>
                                                    <td>{{ date('M d, Y', strtotime($row->date_created)) }}</td>
                                                </tr>
                                                <?php $ctr-- ?>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End PAge Content -->
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