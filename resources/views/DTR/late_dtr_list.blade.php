@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Late Employee List')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

   @include('Templates.alert_message')

   <form class="form-material" action="{{ url('/late_dtr_list') }}" method="get">

      <div class="card">
         <div class="card-body">

            <div class="row mx-1">
               <div class="col-lg-5 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Date From</label>
                     <input type="date" class="form-control" id="date_from" value="<?php echo (isset($_GET['date_from'])) ? $_GET['date_from'] : date('Y-m-d') ?>" name="date_from" required>
                  </div>
               </div>
               <div class="col-lg-5 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Date To</label>
                     <input type="date" class="form-control" id="date_to" value="<?php echo (isset($_GET['date_to'])) ? $_GET['date_to'] : date('Y-m-d',strtotime(date('Y-m-d').' + 15 days')) ?>" name="date_to" required>
                  </div>
               </div>
            
               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="hide" style="visibility: hidden">Search</label>
                     @include('button_component.search_button', ['margin_top' => "16.5"])
                  </div>
               </div>

            </div>
         </div>
      </div>

   </form>

   <hr>

   <form class="form-material" action="" method="post">
      @csrf

      <div class="card">
         <div class="card-body">
            
            <div class="table-responsive m-t-40">
               <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                     <tr>
                           <th class="text-center align-middle">
                              ID
                           </th>
                           <th class="text-center align-middle" style="width: 300px">
                              Name
                           </th>
                           <th class="text-center align-middle">
                              Date
                           </th>
                           <th class="text-center align-middle">
                              Time In
                           </th>
                           <th class="text-center align-middle">
                              Time out
                           </th>
                           <th class="text-center align-middle">
                              Late
                           </th>
                           <th class="text-center align-middle">
                              Undertime
                           </th>
                     </tr>
                  </thead>
                  <tbody id="list_body" name="list">
                     @if($dtr_list)
                        @foreach($dtr_list as $list)
                           <tr>
                           <td>
                              {{$list->employee_number}}
                           </td>
                           <td>
                              {{$list->employee_fullName}}
                           </td>
                           <td>
                              {{date('M d, Y', strtotime($list->dtr_date))}}
                           </td>
                           <td class="text-center align-middle">
                              {{$list->in_am}}
                           </td>
                           <td class="text-center align-middle">
                              {{$list->out_pm}}
                           </td>
                           <td class="text-center align-middle">
                              {{round($list->late,2)}}
                           </td>
                           <td class="text-center align-middle">
                              {{round($list->undertime,2)}}
                           </td>
                           </tr>
                        @endforeach
                        @else
                        <tr>
                           <td class="text-center" colspan="20">
                              No Data
                           </td>
                        </tr>
                     @endif
                  </tbody>
               </table>
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