<?php

?>

@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
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
            <form action="{{ url('/upload_dtr_summary') }}" method="post" enctype="multipart/form-data">
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
                        <button type="submit" id="btn_choose_file" class="btn btn-md btn-primary mt-3">Import File <i class="fa fa-file" aria-hidden="true"></i></button>
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

    <hr>

</div>


<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/js/custom/custom_mat.js') }}"></script>
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
    @if(session('attendance'))
    $('#list').DataTable();
    @endif

});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}