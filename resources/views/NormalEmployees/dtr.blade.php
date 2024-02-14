@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
{{-- <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/css/custom/payslip_modal.css') }}" rel="stylesheet"> --}}
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','My DTR')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">

            <form action="{{ route('my_dtr') }}" method="get">
               {{-- @csrf --}}
               <div class="row">
                  
                  <div class="col-lg-4 col-sm-12">
                     <div class="form-group">
                        <label class="control-label">From: <i class="text-small text-danger">*</i></label>
                         <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from" required>
                     </div>
                 </div>

                 <div class="col-lg-4 col-sm-12">
                     <div class="form-group">
                        <label class="control-label">To: <i class="text-small text-danger">*</i></label>
                         <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to" required>
                     </div>
                 </div>

                  <div class="col-lg-4 col-sm-12">
                     <div class="form-group">
                        <label class="hide" style="visibility: hidden">Search Button</label>
                        @include('button_component.search_button', ['margin_top' => "8.5"])
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
            <div class="col-lg-4 col-sm-12">
               <h4 class="card-title">My DTR <small>({{ count($attendance1) }})</small></h4>
            </div>
            <div class="col-lg-4 col-sm-12">
            {{-- <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names.."> --}}
            </div>
            <div class="col-lg-4 col-sm-12 text-right">
                  
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Date</th>
                     <th class="text-center">In</th>
                     <th class="text-center">Out</th>
                     <th class="text-center">OT</th>
                     <th class="text-center">NP</th>
                     <th class="text-center">Hrs. Late</th>
                     <th class="text-center">Undertime</th>
                     <th class="text-center">Hrs. Break</th>
                     <th class="text-center">Total hrs.</th>
                     <th>Shift Code Schedule</th>
                     <th>Remarks</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
                  @if(count($attendance1))
                     <?php $ctr = 0; ?>
                     @foreach($attendance1 as $row)
                        <tr>
                           <td>{{date('M d, Y',strtotime($row['dtr_date']))}}</td>
                           <td class="text-center">{{ $row['in_am'] ? date('H:i',strtotime($row['in_am'])) : '' }}</td>
                           <td class="text-center">{{ $row['out_pm'] ? date('H:i',strtotime($row['out_pm'])) : '' }}</td>
                           <td class="text-center">{{ $row['ot_hours'] == 0 ? "" : $row['ot_hours'] }}</td>
                           <td class="text-center">{{ $row['np_hours'] == 0 ? "" : $row['np_hours']}}</td>
                           <td class="text-center">{{ $row['late'] ==0 ? "" : $row['late']}}</td>
                           <td class="text-center">{{ $row['undertime'] ==0 ? "" : $row['undertime']}}</td>
                           <td class="text-center">{{ $row['break_hours'] ==0 ? "" : $row['break_hours']}}</td>
                           <td class="text-center">{{ $row['total_hours_worked'] ==0 ? "" : $row['total_hours_worked']}}</td>
                           <td>
                              {{ $row['shift_sched'] }}
                              @if($row['is_active'] === 0)
                                 <span class="badge badge-danger text-sm" title="The shift code has been deleted. Please report to your admin.">Shift code has been deleted</span>
                              @endif
                           </td>
                           <td>{{ ucwords(strtolower($row['att_type'])) }}</td>
                        </tr>
                        <?php $ctr = $ctr + 1; ?>
                     @endforeach
                  @else
                     <tr><td class="text-center" colspan="10">No record found</td></tr>
                  @endif
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

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
{{-- <script src="{{ asset('uidesign/js/custom/shift.js') }}"></script> --}}
<script>

$(document).ready(function(){
   
});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}} 