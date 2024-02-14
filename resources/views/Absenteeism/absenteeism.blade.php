
@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Absenteeism')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">

         <form action="{{ route('absenteeism') }}" method="get">
            @csrf
            <div class="row">
               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">From:</label>
                     <input type="date" class="form-control" id="date_from" value="<?php echo @$_GET['date_from']; ?>" name="date_from" required>
                  </div>
               </div>

               <div class="col-lg-4 col-sm-12">
                     <div class="form-group">
                        <label class="control-label">To:</label>
                        <input type="date" class="form-control" id="date_to" value="<?php echo @$_GET['date_to']; ?>" name="date_to" required>
                     </div>
               </div>

               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">&nbsp;</label>
                     <input style="cursor:pointer" type="submit" class="form-control btn-primary" id="date_selected" value="SEARCH" name="btn_search" required>
                  </div>
               
               </div>
            </div>
            
         </form>
      </div>
   </div>

   <hr>

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Employees Absenteeism</h4>
            </div>
            <div class="col-6 text-right">
               <a href="{{ url('/add-absenteeism') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Add Absenteeism</a>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Date</th>
                     <th class="">Code</th>
                     <th class="">Name</th>
                     <th class="">Position</th>
                     <th class="">Time Start</th>
                     <th class="">Time End</th>
                     <th class="">Total Hours</th>
                     <th class="">Remarks</th>
                     <th class="text-center">Action</th>
                  </tr>
               <tfoot>
                  <th class="">Date</th>
                  <th class="">Code</th>
                  <th class="">Name</th>
                  <th class="">Position</th>
                  <th class="">Time Start</th>
                  <th class="">Time End</th>
                  <th class="">Total Hours</th>
                  <th class="">Remarks</th>
                  <th class="text-center">Action</th>
               </tfoot>
               </thead>
               <tbody>
                  @foreach($absenteeism as $row)
                  @if(isset($row->AB_ID))
                  <tr id="{{ $row->AB_ID.md5($row->AB_ID) }}">
                     <td>{{ date('M d, Y', strtotime($row->dtr_date)) }}</td>
                     <td>{{ $row->employee_number }}</td>
                     <td>
                        <a href="{{ url('employee?id='.$row->SysPK_Empl.md5($row->SysPK_Empl) ) }}" class="text-info" title="View Information">
                        {{ ucwords(strtolower($row->Name_Empl)) }}
                        </a>
                     </td>
                     <td>{{ $row->Position_Empl }}</td>
                     <td>{{ $row->Time_Start }}</td>
                     <td>{{ $row->Time_End }}</td>
                     <td>{{ $row->AB_Hours }}</td>
                     <td>{{ $row->remarks }}</td>
                     <td class="text-center">
                        <a href="javascript:(0)" id="{{ $row->AB_ID.md5($row->AB_ID) }}" class="text-danger deleteAB">
                        <span class="far fa-trash-alt"></span> Delete
                        </a>
                     </td>
                  </tr>
                  @endif
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>

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
<script src="{{ asset('uidesign/js/custom/absenteeism.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}