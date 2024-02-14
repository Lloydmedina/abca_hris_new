@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">

@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Taxdue List')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

   @include('Templates.alert_message')

   <form class="form-material" action="{{ url('/taxdue_list') }}" method="get">
      <div class="card border-left-primary shadow h-100 py-2">
         <div class="card-body">

            <div class="row">
               
               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                     <label class="form-control-label">Year</label>
                     <input type="number" min="1" max="9999" maxlength="4" class="form-control" name="yr" value="{{ $yr }}" >
                  </div>
               </div>

               <div class="col-lg-8 col-sm-12 text-lg-right">
                  <div class="form-group">
                     <label class="hide" style="visibility: hidden">Search</label>
                     @include('button_component.search_button', ['margin_top' => "4"])
                  </div>
                  
               </div>
            </div>

            
         </div>
      </div>
   </form>

   <hr>

   <div class="row pt-3">
      <div class="col-12">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">

               <div class="row no-gutters align-items-center">
                  <div class="col mr-4">
                     <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Employee's Taxdue</div>
                     <hr>
                  </div>
               </div>

               <div class="table-responsive m-t-40">
                  <table id="taxdue_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                     <thead>
                        <tr>
                           <th class="align-middle text-center">Employee</th>
                           <th class="align-middle text-center">Basic Pay</th>
                           <th class="align-middle text-center">OT Pay</th>
                           <th class="align-middle text-center">13th Month<br>Pay</th>
                           <th class="align-middle text-center">Non Taxable<br>Allowance</th>
                           <th class="align-middle text-center">SSS</th>
                           <th class="align-middle text-center">PhilHealth</th>
                           <th class="align-middle text-center">PAG-IBIG</th>
                           <th class="align-middle text-center">Gross Taxable<br>Compensation</th>
                           <th class="align-middle text-center">Withholding Tax</th>
                           <th class="align-middle text-center">Dependent</th>
                           <th class="align-middle text-center">Personal Exeption</th>
                           <th class="align-middle text-center">Dependent Exeption</th>
                           <th class="align-middle text-center">Net Taxable<br>Compensation</th>
                           <th class="align-middle text-center">Tax Due</th>
                           <th class="align-middle text-center">Tax Payable</th>
                           <th class="align-middle text-center">Tax Refund</th>
                           <th class="align-middle text-center">Category</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if($list)
                           @foreach($list as $data)
                              <tr>
                                 <td class="align-middle text-left">{{ $data->Employee }}</td>
                                 <td class="align-middle text-right">{{ $data->BasicPay}}</td>
                                 <td class="align-middle text-right">{{ $data->OTPay }}</td>
                                 <td class="align-middle text-right">{{ $data->Month13thPay }}</td>
                                 <td class="align-middle text-right">{{ $data->NonTaxableAllowance }}</td>
                                 <td class="align-middle text-right">{{ $data->sss }}</td>
                                 <td class="align-middle text-right">{{ $data->philhealth }}</td>
                                 <td class="align-middle text-right">{{ $data->pag_ibig }}</td>
                                 <td class="align-middle text-right">{{ $data->GrossTaxableCompensation }}</td>
                                 <td class="align-middle text-right">{{ $data->WithholdingTax }}</td>
                                 <td class="align-middle text-center">{{ $data->dependent }}</td>
                                 <td class="align-middle text-right">{{ $data->PersonalExeption }}</td>
                                 <td class="align-middle text-right">{{ $data->DependentExeption }}</td>
                                 <td class="align-middle text-right">{{ $data->NetTaxableCompensation }}</td>
                                 <td class="align-middle text-right">{{ $data->TaxDue }}</td>
                                 <td class="align-middle text-right">{{ $data->TaxPayable }}</td>
                                 <td class="align-middle text-right">{{ $data->TaxRefund }}</td>
                                 <td class="align-middle text-left">{{ $data->Category }}</td>
                              </tr>
                           @endforeach
                        @endif
                     </tbody>
                  </table>
               </div>
            </div>
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
<script src="{{ asset('uidesign/js/custom/custom_mat.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<!-- <script src="{{ asset('uidesign/js/custom/department.js') }}"></script> -->
<script>
   $( document ).ready(function() {
         $('#taxdue_table').DataTable({
             dom: 'Bfrtip',
             buttons: [
                 'copy', 'csv', 'excel', 'pdf', 'print'
             ]
         });
         $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');
   });



   function print_payroll(){
        var myWindow = window.open("{{ url('/print_payroll') }}", "myWindow", 'width=1500,height=800');
   }

   function ShowModal(index){
      clearFields();
      var row_val = $('#list_body').find('tr');

      $('#entry_modal').modal('show');

   }
   function clearFields(){

   }
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}