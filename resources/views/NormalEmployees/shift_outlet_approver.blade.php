@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Shift Schedule')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')

   <div class="alert_message_js alert text-info fade show d-none" role="alert">
      <span id="alert_message_js"></span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
   </div>

   <div class="card">
      <div class="card-body">
         <form action="{{ route('shift_outlet') }}" method="get">

            <div class="row">

               <div class="col-lg-4 col-sm-12">
                   <div class="form-group">
                       <label class="control-label">Month</label>
                       <select id="month" name="month" class="form-control custom-select">
                           @php
                                 $monthSelected = $_GET['month'] ?? date('n');
                                 $yearSelected = $_GET['year'] ?? date('Y');
                           @endphp
                               @foreach($months as $i => $month)
                                 <option value="{{ $i }}" <?php echo ($i == $monthSelected) ? 'selected':'' ?>>{{ $month }}</option>
                               @endforeach
                       </select>
                   </div>
               </div>

               <div class="col-lg-4 col-sm-12">
                 <div class="form-group">
                   <label class="control-label">Year</label>
                   <select id="year" name="year" class="form-control custom-select">
                           @foreach($years as $i => $year)
                           <option value="{{ $i }}" <?php echo ($i == $yearSelected) ? 'selected':'' ?>>{{ $year }}</option>
                           @endforeach
                   </select>
                 </div>
               </div>

               <div class="col-lg-4 col-sm-12">
                 <div class="form-group">
                     <label class="hide" style="visibility: hidden">Search Button</label>
                     @include('button_component.search_button', ['margin_top' => "8.2"])
                     {{-- <button type="button" class="btn btn-primary mt-auto w-100" id="btn-search-biometrics"><i class="fa fa-search" aria-hidden="true"></i> Search</button> --}}
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
               <h4 class="card-title">Employees with Shift</h4>
            </div>
            <div class="col-lg-4 col-sm-12">
            @if(session('is_approver'))
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
            @endif
         </div>
            <div class="col-lg-4 col-sm-12 text-lg-right">
            {{-- Condition --}}
               @if(session('is_approver'))
                  @php
                     $emp_id_numbers = array();
                     // STORE THE EMP ID UNDER APPROVER
                     // foreach (session('approvers') as $value) $emp_id_numbers[] = $value->emp_id;
                     $approvers_re_name = DB::table('approvers')
                           ->where('approver_1_emp_id', session('employee')->UserID_Empl)
                           ->orWhere('approver_2_emp_id', session('employee')->UserID_Empl)
                           ->get()->toArray();

                     foreach ($approvers_re_name as $value) $emp_id_numbers[] = $value->emp_id;
                     // $total_pending_change_shcedule = DB::table('emp_pass_slip')
                     // ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
                     // ->whereIn('employees.UserID_Empl', $emp_id_numbers)
                     // ->where('emp_pass_slip.is_deleted', 0)
                     // ->where('emp_pass_slip.ps_code', 2)
                     // ->where('emp_pass_slip.ps_status', 0)
                     // ->count();
                     $total_pending_change_shcedule = DB::table('emp_pass_slip')
                                 ->join('employees', 'emp_pass_slip.emp_id', '=', 'employees.SysPK_Empl')
                                 ->whereIn('employees.UserID_Empl', $emp_id_numbers)
                                 ->where('emp_pass_slip.is_deleted', 0)
                                 ->where('emp_pass_slip.ps_code', 2)
                                 // ->where('emp_pass_slip.ps_status', 0)
                                 ->where(function ($query) {
                                    $query->where('emp_pass_slip.ps_status', 0)
                                          ->orWhere('emp_pass_slip.ps_status',3); // partially approved
                                 })->count();
                  @endphp
                  {{-- @if($total_pending_change_shcedule > 0)
                     <a href="{{ route('change_schedule_request') }}" class="btn btn-link text-danger" id="change_sched_id">Change Schedule Request
                        <span class="ml-2 badge badge-danger">{{ $total_pending_change_shcedule }}</span>
                     </a>
                  @endif --}}
                  <a href="{{ route('change_schedule_request') }}" class="btn btn-link" id="change_sched_id">Change Schedule Request
                     <span class="ml-2 badge badge-danger">{{ $total_pending_change_shcedule }}</span>
                  </a>
                  {{-- <a href="{{ route('shift_entry') }}" class="btn btn-sm btn-dark">
                     <i class="fa fa-plus-circle"></i> Add Shift
                  </a> --}}
                  {{-- <a href="{{ route('shift_entry_v2') }}" class="btn btn-sm btn-dark">
                     <i class="fa fa-plus-circle"></i> Add Shift
                  </a> --}}


                  <div class="btn-group" role="group">
                     <button id="btnAddShift" type="button" class="btn btn-sm btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus-circle"></i> Add Shift
                     </button>
                     <div class="dropdown-menu" aria-labelledby="btnAddShift">
                        <a class="dropdown-item" href="{{ route('shift_entry') }}">Format 1</a>
                        <a class="dropdown-item" href="{{ route('shift_entry_v2') }}">Format 2</a>
                     </div>
                  </div>


               @endif
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                  @if(session('is_approver'))
                     <th class="">Department</th>
                     <th class="">Outlet</th>
                     <th class="">Employee</th>
                  @endif
                     {{-- <th class="">Date</th> --}}
                     {{-- <th class="">Shift Code</th> --}}
                     {{-- <th class="text-center">No. Hrs Break</th> --}}
                     {{-- <th>Status</th> --}}
                     <th class="text-center">No. of Shift Plotted</th>
                     <th class="text-center" style="min-width: 100px;">Action</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
               @if(count($shift))
                  <?php $index = 0; ?>
                  @foreach($shift as $row)
                     {{-- @if(isset($row->id)) --}}
                        <tr></tr>
                        @if(session('is_approver'))
                           <td>{{ $row->department }}</td>
                           <td>{{ $row->outlet }}</td>
                           <td>{{ strtoupper($row->Name_Empl) }} <span class="float-right {{ ( strtoupper($row->Status_Empl) == 'RESIGNED' || strtoupper($row->Status_Empl) == 'AWOL' || strtoupper($row->Status_Empl) == 'TERMINATED') ? 'text-danger' : 'text-dark' }}"><small>{{ strtoupper($row->Status_Empl ?? 'N/A') }}</small></span></td>
                        @endif
                           {{-- <td>{{ date('M d, Y', strtotime($row->shift_date)) }}</td> --}}
                           {{-- <td>{{ $row->shift_code}} ({{ $row->Description }})</td> --}}
                           {{-- <td class="text-center">{{ $row->no_hr_break}}</td> --}}
                           {{-- <td>{{ strtoupper($row->Status_Empl ?? 'N/A') }}</td> --}}
                           <td class="text-center">{{ $row->total_shift_date_plotted }}</td>
                           <td class="text-center">
                              @if($row->total_shift_date_plotted > 0)
                                 {{-- <a href="#update_entry_shift_modal" title="Update shift schedule" data-toggle="modal" data-target="#update_entry_shift_modal" onclick='displayEmployeeShift_2("{{ $row->Name_Empl }}","{{ $row->department }}","{{ $row->emp_id }}","{{ $monthSelected }}","{{ $yearSelected }}","{{ route("get_emp_shift_2") }}")'>
                                    <span class="text-dark" style="cursor: pointer;">
                                       <span class="fa-solid fa-pen-to-square"></span>
                                    </span>
                                 </a> --}}

                                 <a href='{{url("update-staff-shift?")}}emp_id={{$row->emp_id}}&month={{$monthSelected}}&year={{$yearSelected}}' title="Update shift schedule">
                                    <span class="text-dark" style="cursor: pointer;">
                                       <span class="fa-solid fa-pen-to-square"></span>
                                    </span>
                                 </a>
                              @else
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              @endif
                           </td>
                           {{-- <td class="text-center">
                              <a href="javascript:(0)" data-id="{{ $row->id.md5($row->id) }}" class="text-danger deleteShift_" title="Delete Shift">
                                 <span class="fa fa-trash"></span>
                              </a>
                           </td> --}}
                        </tr>
                     {{-- @endif --}}
                     <?php $index = $index + 1; ?>
                  @endforeach
               @else
                  <tr><td class="text-center" colspan="8">No record found</td></tr>
               @endif
               </tbody>
            </table>
            {{-- {{ $shift->appends(request()->query())->links() }} --}}
         </div>
      </div>
   </div>

   <hr>

</div>

{{-- UPDATE SHIFT MODAL --}}
<div id="update_entry_shift_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Employee Shift Schedules</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form id="update_emp_shift" action="{{ url('update_emp_shift') }}" method="post">
            @csrf
            <input type="text" value="shift-monitoring" name="from_page" hidden>
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="control-label">Date From</label>
                              <input type="text" id="d_from_display" name="" value="<?php echo date('M d, Y', strtotime(@$_GET['date_from'])); ?>"  class="form-control" readonly>
                              <input type="date" id="d_from" name="d_from" value="<?php echo @$_GET['date_from']; ?>"  class="form-control" hidden>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="control-label">Date To</label>
                              <input type="text" id="d_to_display" name="" value="<?php echo date('M d, Y', strtotime(@$_GET['date_to'])); ?>"  class="form-control" readonly>
                              <input type="date" id="d_to" name="d_to" value="<?php echo @$_GET['date_to']; ?>" class="form-control" hidden>
                           </div>
                        </div>
                  </div>
                  <div class="row p-t-20">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="control-label">Employee</label>
                              <input type="text" id="s_emp" name="s_emp"  class="form-control" readonly>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="control-label">Department</label>
                              <input type="text" id="s_dept" name="s_dept"  class="form-control" readonly>
                           </div>
                        </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive m-t-40">
                           <table id="emp_shift_table" name="emp_shift_table" class="table table-sm table-bordered table-striped table-hover">
                              <thead>
                                    <th style="min-width: 150px;">Date</th>
                                    <th>Shift Code</th>
                                    <th>Break Hr</th>
                                    {{-- <th>Remarks</th> --}}
                                    <th style="min-width: 200px;">Created At</th>
                                    <th>Status</th>
                                    <th><i class="fa-solid fa-lock text-dark"></i></th>
                              </thead>
                              <tbody id="emp_shift_body" name="emp_shift_body">
                                 
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2 btn_update_shift"> <i class="fa-solid fa-floppy-disk"></i> Update</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END UPDATE SHIFT MODAL --}}

   <!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
{{-- <script src="{{ asset('uidesign/js/custom/shift_outlet_approver.js') }}"></script> --}}
<script>

$(document).ready(function(){

   $("#update_emp_shift").submit(function(e){
      e.preventDefault();
      Swal.fire({
            title: "Would you like to update the shift schedule?",
            // text: "Would you like to request a schedule change?",
            icon: "question",
            iconHtml: '<i class="fa-solid fa-floppy-disk fa-xs text-dark"></i>',
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, update!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
      }).then(function(result) {

         if (result.value) {
            
            Swal.fire({
               width: "350",
               title: 'Updating...',
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
               $("#update_emp_shift").unbind('submit').submit();
            });

         } else if (result.dismiss === "cancel") {
            
         }
      });
   });

   $(document).on('click', '.deleteShift_', function(e) {
        
        let id = $(this).data('id');
        let url = "{{ route('delete_shift') }}";
        
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

});


function displayEmployeeShift_2(emp_name,emp_dept,emp_id,month,year,url){
   // let url = base_url+'ABACA/get_emp_shift';
   let str = "";
   let checkedIndentifier = '';
   const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
   ];

   $.ajax({
      url: url,
      type: 'POST',
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {emp_id : emp_id,month: month, year: year},
      beforeSend: function(){
         // $('#emp_shift_body').empty().html('<tr><td colspan="10"><center><i class="fas fa-spinner fa-spin text-info m-auto" aria-hidden="true"></i></center></td></tr>');
         Swal.fire({
               width: "350",
               title: 'Fetching...',
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
         });
      },
      success: function(result) {

            $('#s_emp').val(emp_name);
            $('#s_dept').val(emp_dept);
            
            let bnt_update_checker = false;

            $.each(result[0], function (i) {

               let d = new Date(result[0][i].shift_date);

               checkedIndentifier = '';

               if(result[0][i].status == 1) checkedIndentifier = 'checked';
               
               if(i == 0) $('#d_from_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());
               else $('#d_to_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());

               if(result[0][i].allow_update == 1){

                  bnt_update_checker = true;
                  
                  str += '<tr>\
                              <td>'+ monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear()+'</td>\
                              <td><select id="u_shift_code[]" name="u_shift_code[]" class="form-control custom-select" value="'+ result[0][i].shift_code_id+'" required>';
                              str +='<option value="" selected disabled>Select shift code</option>';
                              $.each(result[1], function (g) {
                              
                              if(result[1][g].id == result[0][i].shift_code_id)str += '<option value="'+ result[1][g].id +'" selected>'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                              else str += '<option value="'+ result[1][g].id +'">'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                           });

                  str += '</select></td>';

                  new_remarks = "";
                  if(result[0][i].remarks) new_remarks = result[0][i].remarks;

                  str += '<td><input type="number" name="u_break_hr[]" value="'+result[0][i].no_hr_break+'" any></td>\
                              <td>'+result[0][i].created_at+'</td>\
                              <td hidden><input type="text" name="u_shift_id[]" value="'+result[0][i].id+'"></td>\
                              <td hidden><input class="form-control" type="text" name="u_remarks[]" value="'+new_remarks+'"></td>\
                              <td class="text-center"><div class="form-check"><input name="u_status['+result[0][i].id+']" style="width: 20px;height: 20px;" class="form-check-input" value="'+result[0][i].id+'" type="checkbox" '+checkedIndentifier+'></div></td>\
                              <td>...</td>\
                           </tr>';
               }
               else{
                  
                  str += '<tr style="border: 2px solid black;" title="This item is currently locked and cannot be updated at this time. If you have any questions or concerns, please feel free to reach out for assistance. Thank you for your understanding.">\
                              <td>'+ monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear()+'</td>\
                              <td><span class="ml-2">'+result[0][i].shift_code+' - '+result[0][i].Description+'</span></td>';

                  new_remarks = "";
                  if(result[0][i].remarks) new_remarks = result[0][i].remarks;

                  str += '<td><span class="ml-2">'+result[0][i].no_hr_break+'</span></td>\
                              <td>'+result[0][i].created_at+'</td>\
                              <td class="text-center"><div class="form-check"><input style="width: 20px;height: 20px;" class="form-check-input" onClick="return false;" type="checkbox" '+checkedIndentifier+'></div></td>\
                              <td><i class="fa-solid fa-lock text-dark"></i></td>\
                           </tr>';
               }
            });

            if(bnt_update_checker) $('.btn_update_shift').removeClass('d-none');
            else $('.btn_update_shift').addClass('d-none');

            // set empty
            $('#emp_shift_body').empty();

            // append data
            $('#emp_shift_table').append(str);
            Swal.close();
      },
      error: function() {
            console.log('Oppss something went wrong');
      }
   });
 }

function searchNames() {
// Declare variables
var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("myInputSearch");
filter = input.value.toUpperCase();
table = document.getElementById("myTbody");
tr = table.getElementsByTagName("tr");

// Loop through all table rows, and hide those who don't match the search query
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td")[2];
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