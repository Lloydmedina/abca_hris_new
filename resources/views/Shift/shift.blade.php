@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Employees Shift')
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
         <form action="{{ route('shift_monitoring') }}" method="get">
            {{-- @csrf --}}
            <div class="row">
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
                              <option value="0">All</option>
                              @foreach($outlets as $row)
                                 <option value="{{ $row->outlet_id }}" <?php echo ($row->outlet_id == @$_GET['outlet']) ? 'selected':'' ?>>{{ $row->outlet }}</option>
                              @endforeach
                        </select>
                  </div>
               </div>

               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">From: <i class="text-small text-danger">*</i></label>
                     <input type="date" class="form-control" id="date_from" value="<?php echo @$_GET['date_from'] ?? $date_from; ?>" name="date_from" required>
                  </div>
               </div>

               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">To: <i class="text-small text-danger">*</i></label>
                     <input type="date" class="form-control" id="date_to" value="<?php echo @$_GET['date_to'] ?? $date_to; ?>" name="date_to" required>
                  </div>
               </div>

               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     {{-- <label class="control-label">&nbsp;</label> --}}
                     <label class="hide" style="visibility: hidden">Search Button</label>
                     @include('button_component.search_button', ['margin_top' => "1"])
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
               <h4 class="card-title">Employees Shift <small>({{ count($shift) }})</small></h4>
            </div>
            <div class="col-lg-4 col-sm-12">
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
            </div>
            <div class="col-lg-4 col-sm-12 text-lg-right">
               <a href="{{ route('shift_entry') }}" class="btn btn-sm btn-dark">
                  <i class="fa fa-plus-circle"></i> Add Shift
               </a>
               
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Department</th>
                     <th class="">Outlet</th>
                     <th class="">Employee Name</th>
                     <th class="">Date</th>
                     <th class="">Shift Code</th>
                     <th class="text-center">No. Hrs Break</th>
                     <th class="text-center">Status</th>
                     <th colspan="2" class="text-center" style="min-width: 100px;">Action</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
               @if(count($shift))
               @php
                     $index = 0;
               @endphp
                  @foreach($shift as $row)
                     @if(isset($row->id))
                        <tr id="{{ $row->id.md5($row->id) }}">
                           <td>{{ $row->department }}</td>
                           <td>{{ $row->outlet }}</td>
                           <td>{{$row->emp_name}}</td>
                           <td>{{ date('M d, Y', strtotime($row->shift_date)) }}</td>
                           <td>
                              {{ $row->shift_code}} ({{ $row->Description }})
                              @if($row->is_active === 0)
                                 <span class="badge badge-danger text-sm" title="The shift code has been deleted. Please report to your admin or update the shift code.">Shift code has been deleted</span>
                              @endif
                           </td>
                           <td class="text-center">{{ $row->no_hr_break}}</td>
                           <td class="text-center">{{ ($row->s_m_status == 1) ? "Approved" : "Not approve" }}</td>
                           <td class="text-center">
                              <div class="w-100">
                                 <a href="#update_entry_shift_modal" title="Update shift schedule" data-toggle="modal" data-target="#update_entry_shift_modal" onclick='setShiftEmployee({{ $index }},"{{ $row->emp_id }}","{{ $row->emp_name }}","{{ $row->department }}", "{{ route("get_emp_shift") }}" )'>
                                    <span class="text-dark" style="cursor: pointer;">
                                       <span class="fa-solid fa-pen-to-square"></span>
                                    </span>
                                 </a>
                              </div>
                          </td>
                          <td class="text-center">
                              <div class="w-100">
                                 <a href="javascript:(0)" data-id="{{ $row->id.md5($row->id) }}" class="text-danger deleteShift_" title="Delete Shift">
                                    <span class="fa fa-trash"></span>
                                 </a>
                              </div>
                          </td>
                        </tr>
                     @endif
                     <?php $index = $index +1; ?>
                  @endforeach
               @else
                  <tr><td class="text-center" colspan="12">No record found</td></tr>
               @endif
               </tbody>
            </table>
            {{ $shift->appends(request()->query())->links() }}
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
            <h4 class="modal-title">Upload Shift</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/upload_emp_shift') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Choose File</label>
                        <input type="file" name="shift" class="form-control">
                      </div>
                    </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button id="btn_upload" type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Upload</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END ENTRY MODAL --}}

{{-- UPDATE SHIFT MODAL --}}
<div id="update_entry_shift_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Employee Shift Details</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
        <form id="update_employee_form" action="{{ url('update_emp_shift') }}" method="post">
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
                                    <th>Date</th>
                                    <th>Shift Code</th>
                                    <th>Break Hr</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
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
                  <button type="submit" class="btn btn-sm btn-dark ml-2"><i class="fa-solid fa-floppy-disk"></i> Update</button>
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
{{-- <script src="{{ asset('uidesign/js/custom/shift.js') }}"></script> --}}
<script>

$(document).ready(function(){

   $(document).on('click', '.deleteShift_', function(e) {
        
        let id = $(this).data('id');
        let url = "{{ route('delete_shift') }}";
        let btn_confirm = confirm("Delete Shift?");

        if (btn_confirm) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {id:id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                  if(result.code == 1){
                     $('.alert_message_js').removeClass('d-none').addClass('alert-success');;
                     $('#alert_message_js').text(result.message);
                     $('#'+id).hide();
                     
                  }
                },
                error: function(result){
                    console.log(result);
                }

            });
        }

   });
   //trigger click file upload
    $('#btn_choose_file').click(function(){
        $('#file_import').click();
    });
    //trigger click upload submit
    $('#file_import').change(function(){
        $('#file_import').val(base_url);
        $('#btn_upload').click();
    });
   // Trigger click submit search button
   $('#btn-search-shift-monitoring').click(function(){
      $('#btn-search-button').click();
   });
});

function setShiftEmployee(index,emp_id,emp_name,department){
   
   $('#d_from').val($('#date_from').val());
   $('#d_to').val($('#date_to').val());
   $('#s_emp').val( emp_name );
   $('#s_dept').val( department );
   var shift_list = "";
   var a = emp_id;
   // DisplayEmployeeShift(emp_id,$('#date_from').val(),$('#date_to').val()); old
   DisplayEmployeeShift_new(emp_id,$('#date_from').val(),$('#date_to').val()); // new
}

function DisplayEmployeeShift_new(emp_id,date_from,date_to){
    let url = "{{ route('get_emp_shift') }}";
    let str = "";
    let checkedIndentifier = '';
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    $.ajax({
        url: url,
        type: 'GET',
        data: {emp_id : emp_id,date_from: date_from, date_to: date_to},
        beforeSend: function(){
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
            $.each(result[0], function (i) {
               
               let d = new Date(result[0][i].shift_date);

                checkedIndentifier = '';
                if(result[0][i].status == 1) checkedIndentifier = 'checked';

                if(i == 0){
                  $('#d_from_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());
                }
                else{
                  $('#d_to_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());
                }

                str += '<tr>\
                           <td>'+ monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear() +'</td>\
                           <td><select id="u_shift_code[]" name="u_shift_code[]" class="form-control custom-select" value="'+ result[0][i].shift_code_id+'" required>';
                           str +='<option value="" selected disabled>Select shift code</option>';
                $.each(result[1], function (g) {
                    if(result[1][g].id == result[0][i].shift_code_id)str += '<option value="'+ result[1][g].id +'" selected>'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                    else str += '<option value="'+ result[1][g].id +'">'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                    
                    // str += '<option value="'+ result[1][i].id +'">'+result[1][i].shift_code+'</option>';
                    
                });
                        //    <td><input class="form-control" type="text" name="u_shift_code[]" value="'+result[0][i].shift_code+'"></td></td>
                str += '</select><td><input type="number" name="u_break_hr[]" value="'+result[0][i].no_hr_break+'" any></td>\
                            <td><input class="form-control" type="text" name="u_remarks[]" value="'+result[0][i].remarks+'"></td>\
                            <td hidden><input type="text" name="u_shift_id[]" value="'+result[0][i].id+'"></td>\
                            <td class="text-center"><div class="form-check"><input name="u_status['+result[0][i].id+']" style="width: 20px;height: 20px;" class="form-check-input" value="'+result[0][i].id+'" type="checkbox" '+checkedIndentifier+'></div></td>\
                        </tr>';
            });

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