@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"> 
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','ATD Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="alert_message_js alert text-info fade show d-none" role="alert">
      <span id="alert_message_js"></span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
   </div>

   <div class="card">
      <div class="card-body">
            <form action="{{ route('atd') }}" method="get">
               <div class="row">
                     @php $colValue = "col-lg-2 col-sm-12"; @endphp
                     <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                           <label class="control-label">Department</label>
                              <select id="department" name="department" class="form-control custom-select">
                                    <option value="0">All</option>
                                    @foreach($department as $row)
                                       <option value="{{ $row->SysPK_Dept }}" <?php echo ($row->SysPK_Dept == @$_GET['department']) ? 'selected':'' ?>>{{ $row->Name_Dept }}</option>
                                    @endforeach
                              </select>
                        </div>
                     </div>

                     <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                           <label class="control-label">Outlet</label>
                              <select id="outlet" name="outlet" class="form-control custom-select">
                                    @if(count($outlets) > 1)
                                       <option value="0">All</option>
                                    @endif
                                    @foreach($outlets as $row)
                                       <option value="{{ $row->outlet_id }}" <?php echo ($row->outlet_id == @$_GET['outlet']) ? 'selected':'' ?>>{{ $row->outlet }}</option>
                                    @endforeach
                              </select>
                        </div>
                     </div>
                  

                  <div class="{{ $colValue }}">
                     <div class="form-group">
                        <label class="control-label">From: <i class="text-small text-danger">*</i></label>
                        <input type="date" class="form-control" id="date_from" value="<?php echo @$_GET['date_from'] ?? $date_from; ?>" name="date_from" required>
                     </div>
                  </div>

                  <div class="{{ $colValue }}">
                     <div class="form-group">
                        <label class="control-label">To: <i class="text-small text-danger">*</i></label>
                        <input type="date" class="form-control" id="date_to" value="<?php echo @$_GET['date_to'] ?? $date_to; ?>" name="date_to" required>
                     </div>
                  </div>

                  <div class="{{ $colValue }}">
                     <div class="form-group">
                        <label class="hide" style="visibility: hidden">Search</label>
                        @include('button_component.search_button', ['margin_top' => "16.5"])
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
               <h4 class="card-title">ATD</h4>
            </div>
            <div class="col-lg-4 col-sm-12">
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
            </div>
            <div class="col-lg-4 col-sm-12 text-lg-right">
               <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="example23__" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     
                     <th class="">Effective Date</th>
                     <th class="">Employee</th>
                     <th class="text-right">Amount</th>
                     <th class="text-right">Deduction</th>
                     <th class="">Remarks</th>
                     <th class="">Created At</th>
                     <th colspan="2" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
                  
                  @if(count($atd))
                     @foreach($atd as $row)
                        <tr id="{{ $row->atd_id.md5($row->atd_id) }}">
                           <td class="">
                              {{ date('M d, Y',strtotime($row->date_from)) }} to {{ date('M d, Y',strtotime($row->date_to)) }}
                           </td>
                           <td class="">
                              {{ $row->emp_name }}
                           </td>
                           <td class="text-right">
                              {{ number_format($row->atd_amount,2) }}
                           </td>
                           <td class="text-right">
                              {{ number_format($row->amount_deduction,2) }}
                           </td>
                           <td class="">
                              {{ $row->remarks}}
                           </td>
                           <td class="">
                              {{ date('M d, Y',strtotime($row->atd_date)) }}
                           </td>
                           <td class="text-center">
                              <div class="w-100">
                                 <span class="text-dark update_atd" style="cursor: pointer;"
                                 data-atd_id="{{ $row->atd_id.md5($row->atd_id) }}"
                                 data-date_from="{{ date('Y-m-d',strtotime($row->date_from)) }}"
                                 data-date_to="{{ date('Y-m-d',strtotime($row->date_to)) }}"
                                 data-atd_date="{{ date('Y-m-d',strtotime($row->atd_date)) }}"
                                 data-emp_id="{{ $row->emp_id }}"
                                 data-atd_amount="{{ $row->atd_amount }}"
                                 data-amount_deduction="{{ $row->amount_deduction }}"
                                 data-remarks="{{ $row->remarks }}"
                                 >
                                    <span class="fa-solid fa-pen-to-square"></span>
                                 </span>
                              </div>
                           </td>
                           <td class="text-center">
                              <div class="w-100">
                                 <a href="javascript:(0)" data-id="{{ $row->atd_id.md5($row->atd_id) }}" class="ml-1 text-danger deleteatd_" title="Delete ATD">
                                    <span class="fa fa-trash"></span>
                                 </a>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                  @else
                        <tr><td class="text-center" colspan="9">No record found</td></tr>
                  @endif
                  
               </tbody>
            </table>
         </div>
      </div>
   </div>

</div>

{{-- ENTRY MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">New ATD</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_atd') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date <i class="text-small text-danger">*</i></label>
                           <input type="date" name="atd_date" value="{{ date('Y-m-d') }}" class="form-control" required readonly>
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee <i class="text-small text-danger">*</i></label>
                           <select name="emp_code" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Employee</option>
                              @foreach($employees as $row)
                                 <option value="{{ $row->UserID_Empl }}">
                                    {{ $row->Name_Empl }}
                                 </option>
                              @endforeach    
                           </select>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date From</label>
                           <input type="date" name="date_from" class="form-control" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date To</label>
                           <input type="date" name="date_to" class="form-control" required>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Amount</label>
                           <input type="number" name="atd_amount"  class="form-control" required step="any">
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Deduction</label>
                           <input type="number" name="amount_deduction"  class="form-control" required step="any">
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     
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
<div id="update_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Update ATD</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_atd') }}" method="post">
            @csrf
            <input type="text" name="atd_id" id="update_atd_id" readonly hidden>
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date <i class="text-small text-danger">*</i></label>
                           <input type="date" id="update_atd_date" name="atd_date" value="{{ date('Y-m-d') }}" class="form-control" required readonly>
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee <i class="text-small text-danger">*</i></label>
                           <select id="update_emp_id" name="emp_id" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Employee</option>
                              @foreach($employees as $row)
                                 <option value="{{ $row->SysPK_Empl }}">
                                    {{ $row->Name_Empl }}
                                 </option>
                              @endforeach    
                           </select>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date From</label>
                           <input type="date" id="update_date_from" name="date_from" class="form-control" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date To</label>
                           <input type="date" id="update_date_to" name="date_to" class="form-control" required>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Amount</label>
                           <input type="number" id="update_atd_amount" name="atd_amount"  class="form-control" required step="any">
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Deduction</label>
                           <input type="number" id="update_amount_deduction" name="amount_deduction"  class="form-control" required step="any">
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="update_remarks" name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     
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


{{-- UPDATE MODAL --}}

{{-- END UPDATE MODAL --}}
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script>
   $(document).ready(function(){

      $(document).on('click', '.deleteatd_', function(e) {
         
         let id = $(this).data('id');
         let url = "{{ route('delete_atd') }}";

         Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-trash-can fa-xs text-danger"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, delete!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
         }).then(function(result) {

            if (result.value) {

                  Swal.fire({
                     width: "350",
                     title: 'Deleting...',
                     icon: "question",
                     customClass: {
                        icon: 'no-border'
                     },
                     showClass: {
                        backdrop: 'swal2-noanimation', // disable backdrop animation
                        popup: '',                     // disable popup animation
                        icon: ''                       // disable icon animation
                     },
                     iconHtml: '<i class="fa-solid fa-spinner fa-spin-pulse fa-xs"></i>',
                     allowEscapeKey: false,
                     allowOutsideClick: false,
                     showCancelButton: false,
                     showConfirmButton: false,
                     timer: 1500,
                  }).then(function(){
                     if(id && url) deleteItem(id,url);
                  });
                  
            } else if (result.dismiss === "cancel") {

            }
         });

      });

      function deleteItem(id ="", url =""){

         $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {

               if(result.code == 1){

                  $('#'+id).hide();

                  Swal.fire({ 
                     width: "350", position: "top-right", 
                     icon: "success", title: "Deleted", 
                     text: result.message, 
                     showConfirmButton: false, timer: 2000
                  });
               }
               else if(result.code == 0){

                     Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: result.message,
                        showConfirmButton: true
                     });
               }

               // swal.close();
            },
            error: function(result){
               console.log(result);
            }

         });

      }

      $(document).on('click', '.update_atd', function(e) {

         let atd_id = $(this).data('atd_id');
         let date_from = $(this).data('date_from');
         let date_to = $(this).data('date_to');
         let atd_date = $(this).data('atd_date');
         let emp_id = $(this).data('emp_id');
         let atd_amount = $(this).data('atd_amount');
         let amount_deduction = $(this).data('amount_deduction');
         let remarks = $(this).data('remarks');

         $('#update_atd_id').val(atd_id);
         $('#update_atd_date').val(atd_date);
         $('#update_date_from').val(date_from);
         $('#update_date_to').val(date_to);
         $('#update_atd_amount').val(atd_amount);
         $('#update_amount_deduction').val(amount_deduction);
         $('#update_remarks').val(remarks);
         $('#update_emp_id').val(emp_id).change();

         $('#update_modal').modal('show');

      });

   });

   function searchNames() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInputSearch");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTbody");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
         td = tr[i].getElementsByTagName("td")[1];
         if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
            } else {
            tr[i].style.display = "none";
            }
         }
      }
   }
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}