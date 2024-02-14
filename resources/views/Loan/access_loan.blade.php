@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"> --}}
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Access Loan Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <form action="{{ route('access_loan') }}" method="get">
            <div class="row">
               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                      <label class="control-label">From:</label>
                      <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from">
                  </div>
              </div>

              <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                      <label class="control-label">To:</label>
                      <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to">
                  </div>
              </div>

               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                     <label class="hide" style="visibility: hidden">Search</label>
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
            <div class="col-6">
               <h4 class="card-title">Access Loan Setup</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="loan_list" class="example23 display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Seq #</th>
                     <th class="">Date</th>
                     <th class="">Employee</th>
                     <th class="text-right">Loan Amount</th>
                     <th class="">Deduction History</th>
                     <th class="text-right">Total Deduction</th>
                     <th class="text-right">Current Balance</th>
                     <th class="">Date From</th>
                     <th class="">Date To</th>
                     <th class="">Remarks</th>
                     <th class="">Issued By</th>
                     <th colspan="2" class="text-center">Action</th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <th class="">Seq #</th>
                     <th class="">Date</th>
                     <th class="">Employee</th>
                     <th class="text-right">Loan Amount</th>
                     <th class="">Deduction History</th>
                     <th class="text-right">Total Deduction</th>
                     <th class="text-right">Current Balance</th>
                     <th class="">Date From</th>
                     <th class="">Date To</th>
                     <th class="">Remarks</th>
                     <th class="">Issued By</th>
                     <th colspan="2" class="text-center">Action</th>
                  </tr>  
               </tfoot>
               <tbody id="list_body" name="list">
                  @if(count($loan) > 0)
                     <?php $ctr = count($loan) ?>
                     @foreach($loan as $row)
                        <tr>
                           <td class="">
                              {{ $ctr }}
                           </td>
                           <td class="">
                              {{ date('M d, Y',strtotime($row->loan_date)) }}
                           </td>
                           <td class="">
                              {{ $row->emp_name }}
                           </td>
                           <td class="text-right">
                              {{ number_format($row->loan_amount ,2) }}
                           </td>
                           <td class="text-center">
                              
                           </td>
                           <td class="text-right">
                              
                           </td>
                           <td class="text-right">
                              
                           </td>
                           <td class="">
                              {{ date('M d, Y',strtotime($row->date_from)) }}
                           </td>
                           <td class="">
                              {{ date('M d, Y',strtotime($row->date_to)) }}
                           </td>
                           <td class="">
                                 {{ $row->remarks }}
                           </td>
                           <td class="">
                                 {{ $row->issued_by }}
                           </td>
                           <td class="text-center">
                              <div class="w-100">
                                 <a href="javascript:(0)" class="text-dark update_access_loan" data-amount_deduction="{{ $row->amount_deduction }}" data-remarks="{{ $row->remarks }}" data-date_to="{{ $row->date_to }}" data-date_from="{{ $row->date_from }}" data-loan_id="{{ $row->loan_id }}" data-emp_name="{{ $row->emp_name }}" data-loan_date="{{ $row->loan_date }}" data-loan_amount="{{ $row->loan_amount }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                 </a>
                              </div>
                           </td>
                           <td class="text-center">
                              <div class="w-100">
                                 <a href="{{ url('/delete_access_loan?id=').$row->loan_id }}" onclick="return confirm('Delete Loan')" class="text-danger">
                                    <i class="fa-solid fa-trash-can"></i>
                                 </a>
                              </div>
                           </td>
                        </tr>
                        <?php $ctr-- ?>
                     @endforeach
                  @else
                        <tr>
                           <td colspan="13" class="text-center">No record found.</td>
                        </tr>
                  @endif
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <hr>

</div>


{{-- ENTRY MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">New Access Loan</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_access_loan') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date</label>
                           <input type="date" id="loan_date" value="{{ date('Y-m-d') }}" name="loan_date"  class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee</label>
                           <select id="emp_code" name="emp_code" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Employee</option>
                                 @foreach($employees as $row)
                                    <option value="{{ $row->UserID_Empl }}">
                                       {{ $row->Name_Empl }}
                                    </option>
                                 @endforeach    
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date From</label>
                           <input type="date" id="date_from" name="date_from" class="form-control" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date To</label>
                           <input type="date"  name="date_to" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label float-right">Loan Amount</label>
                           <input type="number" id="loan_amount" name="loan_amount"  class="form-control text-right" step="any" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label float-right">Loan Deduction</label>
                           <input type="number" id="amount_deduction" name="amount_deduction"  class="form-control text-right" step="any" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea  name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2"> <i class="fa-solid fa-floppy-disk"></i> Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END ENTRY MODAL --}}

{{-- UPDATE MODAL --}}
<div id="update_access_loan" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelUpdate" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-success text-white">
            <h4 class="modal-title">Update Access Loan</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_access_loan') }}" method="post">
            @csrf
            <input type="hidden" id="loan_id_up" name="loan_id" required/>
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date</label>
                           <input type="date" id="loan_date_up" name="loan_date"  class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Employee</label>
                           <input type="text" id="emp_name_up" name="employee"  class="form-control" readonly>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date From</label>
                           <input type="date" id="date_from_up" name="date_from" class="form-control" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date To</label>
                           <input type="date" id="date_to_up" name="date_to" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label float-right">Loan Amount</label>
                           <input type="number" id="loan_amount_up" name="loan_amount"  class="form-control text-right" step="any" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label float-right">Loan Deduction</label>
                           <input type="number" id="amount_deduction_up" name="amount_deduction"  class="form-control text-right" step="any" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="remarks_up" name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2"> <i class="fa-solid fa-floppy-disk"></i> Update</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END UPDATE MODAL --}}

{{-- View Deduction MODAL --}}
<div id="view_deduction_sss_loan" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelView" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Deduction History</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body">
            <h4 id="emp_name_deduction"></h4>
            <div class="table-responsive m-t-40">
               <table id="deduction_list" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                     <tr>
                        <th class="">Seq #</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-right">Current Deduction</th>
                        <th class="text-right">Current Bal.</th>
                        <th class="text-right">Remaining Bal.</th>
                        <th class="">Date</th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                        <th class="">Seq #</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-right">Current Deduction</th>
                        <th class="text-right">Current Bal.</th>
                        <th class="text-right">Remaining Bal.</th>
                        <th class="">Date</th>
                     </tr>  
                  </tfoot>
                  <tbody id="deduction_history_tbl">

                  </tbody>
               </table>
            </div>
         </div>
         <div class="modal-footer">
            <div class="form-actions">
               <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </div>
      </div>
   </div>
</div>
{{-- END View Deduction MODAL --}}

{{-- add deduction MODAL --}}
<div id="add_deduction_sss_loan" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Deduction Entry</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_deduction_loan') }}" method="post">
            @csrf
            <input type="hidden" name="sss_loan_id" id="emp_sss_loan_id" />
            <input type="hidden" name="type" value="sss" />
            <div class="modal-body">
               <h4 id="emp_name_to_deduct"></h4>
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date</label>
                           <input type="date" value="{{ date('Y-m-d') }}" name="date_deducted"  class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label float-right">Amount Deduction</label>
                           <input type="number" name="current_deduction"  class="form-control text-right" step="any" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Add</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END addcdeduction MODAL --}}

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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script> --}}
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/sss_loan.js') }}"></script>
<script>
   $(document).ready(function() {
      
      $(document).on('click', '.update_access_loan', function(e) {

         let loan_id = $(this).data('loan_id'); 
         let amount_deduction = $(this).data('amount_deduction'); 
         let remarks = $(this).data('remarks'); 
         let date_to = $(this).data('date_to'); 
         let date_from = $(this).data('date_from'); 
         let emp_name = $(this).data('emp_name'); 
         let loan_date = $(this).data('loan_date'); 
         let loan_amount = $(this).data('loan_amount');

         $('#loan_id_up').val(loan_id);
         $('#loan_date_up').val(loan_date);
         $('#emp_name_up').val(emp_name);
         $('#date_to_up').val(date_to);
         $('#date_from_up').val(date_from);
         $('#loan_amount_up').val(loan_amount);
         $('#amount_deduction_up').val(amount_deduction);
         $('#remarks_up').val(remarks);

         $('#update_access_loan').modal({
            backdrop: 'static',
            keyboard: true
         });

      });
   });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}