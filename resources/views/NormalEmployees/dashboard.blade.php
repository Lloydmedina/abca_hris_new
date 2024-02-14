@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/css/custom/payslip_modal.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Dashboard')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')
    
   <!-- Content Row -->
   <div class="row">
      
      <!-- COMPANY MEMO -->
      <div class="col-xl-4 col-md-4 mb-4">
         <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Company Memo</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-comments fa-2x text-gray-300"></i> <br>
                     <a href="{{ url('memo') }}" title="View Company Memo">View</a>
                  </div>
               </div>
            </div>
         </div>
      </div>

      {{-- PAYROLL --}}
      <div class="col-xl-4 col-md-4 mb-4">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Payroll</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">Jan. 1-15-2023</div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-credit-card fa-2x text-gray-300"></i> <br>
                     <a href="{{ url('payslip') }}" title="View Payslip">View</a>
                  </div>
               </div>
            </div>
         </div>
      </div>

      {{-- PAYROLL --}}
      <div class="col-xl-4 col-md-4 mb-4">
         <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Leave</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">Jan. 1-2023 <small class="text-success">Approved</small></div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-window-restore fa-2x text-gray-300"></i> <br>
                     <a href="{{ url('leave') }}" title="View Leave">View</a>
                  </div>
               </div>
            </div>
         </div>
      </div>


   </div>
   <!-- Content Row -->

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