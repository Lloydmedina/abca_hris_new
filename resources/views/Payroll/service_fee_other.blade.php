@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Service Charge')
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
         <form action="{{ url('/service-charge') }}" method="get">
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
               <h4 class="card-title">List of Service Charge</h4>
            </div>
            <div class="col-lg-4 col-sm-12">
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
            </div>
            <div class="col-lg-3 col-sm-12 text-lg-right">
               <button class="btn btn-sm btn-dark mb-2" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
            <div class="col-lg-1 col-sm-12 text-lg-right">
               <form action="{{ url('/upload_service_fee_other') }}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group row">
                     <input class="form-control d-none" id="file_import" type="file" name="file_import" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                     <button id="btn_choose_file" class="btn btn-sm btn-success" type="button"><i class="fa-solid fa-circle-arrow-up"></i> Upload</button>
                     <button type="submit" id="btn_upload" class="d-none btn btn-sm btn-primary">Upload</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Effective Date</th>
                     {{-- <th class="">Emp Code</th> --}}
                     <th class="">Employee</th>
                     <th class="text-right">Service Fee</th>
                     <th class="">Remarks</th>
                     <th class="">Created At</th>
                     <th colspan="2" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
                  @if(count($list))
                     @foreach($list as $row)
                        <tr id="{{ $row->payroll_other_id.md5($row->payroll_other_id) }}">
                           <td class="">
                              {{ date('M d, Y',strtotime($row->payroll_date)) }}
                           </td>
                           <td class="">
                              {{ $row->emp_name }}
                           </td>
                           <td class="text-right">
                              {{ number_format($row->service_fee,2) }}
                           </td>
                           <td class="">
                              {{ $row->remarks}}
                           </td>
                           <td>{{ date('M d, Y H:i', strtotime($row->created_at ))}}</td>
                           <td class="text-center">
                                 <span class="text-dark update_service_charge" style="cursor: pointer;"
                                 data-payroll_other_id="{{ $row->payroll_other_id.md5($row->payroll_other_id) }}"
                                 data-payroll_date="{{ date('Y-m-d',strtotime($row->payroll_date)) }}"
                                 data-emp_code="{{ $row->emp_code }}"
                                 data-service_fee="{{ $row->service_fee }}"
                                 data-remarks="{{ $row->remarks }}"
                                 >
                                    <span class="fa-solid fa-pen-to-square"></span>
                                 </span>
                           </td>
                           <td class="text-center">
                              <a href="javascript:(0)" data-id="{{ $row->payroll_other_id.md5($row->payroll_other_id) }}" class="ml-1 text-danger deleteatd_" title="Delete Service Charge">
                                 <span class="fa fa-trash"></span>
                              </a>
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

   <hr>

</div>

{{-- ENTRY MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Service Charge</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_service_fee_other_deduction') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Effective Date <i class="text-small text-danger">*</i></label>
                           <input type="date" id="payroll_date" name="payroll_date"  class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-12">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee <i class="text-small text-danger">*</i></label>
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
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Service Charge <i class="text-small text-danger">*</i></label>
                           <input type="number" id="service_fee" name="service_fee"  step="any" class="form-control" required>
                           <input type="text" name="service_type" id="service_type" value="service_charge" hidden>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks <i class="text-small text-danger">*</i></label>
                           <textarea id="" name="remarks" class="form-control" rows="3" required></textarea>
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

{{-- ENTRY MODAL --}}
<div id="update_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Update Service Charge</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_service_fee_other_deduction') }}" method="post">
            @csrf
            <input type="text" name="payroll_other_id" id="update_payroll_other_id" readonly hidden>
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Effective Date <i class="text-small text-danger">*</i></label>
                           <input type="date" id="update_payroll_date" name="payroll_date"  class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-12">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee <i class="text-small text-danger">*</i></label>
                           <select id="update_emp_code" name="emp_code" class="border form-control custom-select selectpicker" data-live-search="true" required>
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
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Service Charge <i class="text-small text-danger">*</i></label>
                           <input type="number" id="update_service_fee" name="service_fee"  step="any" class="form-control" required>
                           <input type="text" name="service_type" value="service_charge" hidden>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks <i class="text-small text-danger">*</i></label>
                           <textarea id="update_remarks" name="remarks" class="form-control" rows="3" required></textarea>
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
{{-- END ENTRY MODAL --}}

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
      

      //trigger click file upload
      $('#btn_choose_file').click(function(){
         $('#file_import').click();
      });

      //trigger click upload submit
      $('#file_import').change(function(){
         $('#btn_upload').click();
      });

      $(document).on('click', '.deleteatd_', function(e) {
        
         let id = $(this).data('id');
         let url = "{{ route('delete_service_charge_other_deduction') }}";

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

      $(document).on('click', '.update_service_charge', function(e) {

         let payroll_other_id = $(this).data('payroll_other_id');
         let payroll_date = $(this).data('payroll_date');
         let emp_code = $(this).data('emp_code');
         let service_fee = $(this).data('service_fee');
         let remarks = $(this).data('remarks');

         $('#update_payroll_other_id').val(payroll_other_id);
         $('#update_service_fee').val(service_fee);
         $('#update_payroll_date').val(payroll_date);
         $('#update_remarks').val(remarks);
         $('#update_emp_code').val(emp_code).change();

         $('#update_modal').modal('show');

      });

   });
   // $('#example23').DataTable({
   //    dom: 'Bfrtip',
   //    buttons: [
   //       'copy', 'csv', 'excel', 'pdf', 'print'
   //    ],
   //    order: [[5, 'desc']],
   // });
   // $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');

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