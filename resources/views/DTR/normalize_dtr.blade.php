@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Normalize DTR')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

   @include('Templates.alert_message')

   <form class="form-material" action="{{ url('/add_normalize_dtr') }}" method="post">
   @csrf
      <div class="row">
         <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2">
               <div class="card-body">
                  <div class="row mx-1">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date From</label>
                           <input type="date" class="form-control" id="date_from" value="<?php echo (isset($_GET['date_from'])) ? $_GET['date_from'] : date('Y-m-d') ?>" name="date_from" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date To</label>
                           <input type="date" class="form-control" id="date_to" value="<?php echo (isset($_GET['date_to'])) ? $_GET['date_to'] : date('Y-m-d',strtotime(date('Y-m-d').' + 15 days')) ?>" name="date_to" required>
                        </div>
                     </div>
                  </div>
                  <div class="col text-right">
                     <button class="btn btn-sm btn-info ml-5" type="submit">
                     <i class="fa fa-search">&nbsp;Normalize</i>
                     </button>
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
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/employees_list.js') }}"></script>
<script>
   $( document ).ready(function() {
      
   });

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}