@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/css/custom/payslip_modal.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Shift Schedule')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

   @include('Templates.alert_message')
    
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-body">
   
                  <form action="{{ route('shift') }}" method="get">
                     {{-- @csrf --}}
                     <div class="row">
                        @php $colValue = "col-4"; @endphp
                        {{-- @if(count($outletApprover))
                           @php $colValue = "col-2"; @endphp
                           <div class="col-3">
                              <div class="form-group">
                                 <label class="control-label">Department</label>
                                    <select id="department" name="department" class="form-control custom-select">
                                          <option value="0">All</option>
                                          @foreach($department as $row)
                                             <option value="{{ $row->SysPK_Dept }}"  //echo ($row->SysPK_Dept == @$_GET['department']) ? 'selected':'' ?>>{{ $row->Name_Dept }}</option>
                                          @endforeach
                                    </select>
                              </div>
                           </div>
      
                           <div class="col-3">
                              <div class="form-group">
                                 <label class="control-label">Outlet</label>
                                    <select id="outlet" name="outlet" class="form-control custom-select">
                                          @if(count($outlets) > 1)
                                             <option value="0">All</option>
                                          @endif
                                          @foreach($outlets as $row)
                                             <option value="{{ $row->outlet_id }}"  //echo ($row->outlet_id == @$_GET['outlet']) ? 'selected':'' ?>>{{ $row->outlet }}</option>
                                          @endforeach
                                    </select>
                              </div>
                           </div>
                        @endif --}}

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
                              {{-- <label class="control-label">&nbsp;</label> --}}
                              <input style="cursor:pointer" type="submit" class="form-control btn-primary" id="btn-search-button" value="SEARCH" name="btn_search" hidden>
                              <label class="hide" style="visibility: hidden">Search Button</label>
                              <button type="button" class="btn btn-primary mt-auto w-100" id="btn-search-shift-monitoring"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
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
                     <div class="col-4">
                        <h4 class="card-title">Shift <small>({{ count($shift) }})</small></h4>
                     </div>
                     <div class="col-4">
                     {{-- @if(count($outletApprover))
                        <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
                     @endif --}}
                  </div>
                     <div class="col-4 text-right">
                     {{-- Condition --}}
                        {{-- @if(count($outletApprover))
                           <a href="{{ route('shift_entry') }}" class="btn btn-sm btn-primary">
                              <i class="fa fa-plus-circle"></i> Add Shift
                           </a>
                        @endif --}}
                     </div>
                  </div>
                  <div class="table-responsive m-t-40">
                     <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                           <tr>
                           {{-- @if(count($outletApprover))
                              <th class="">Department</th>
                              <th class="">Outlet</th>
                              <th class="">Employee Name</th>
                           @endif --}}
                              <th class="">Date</th>
                              <th class="">Shift Code</th>
                              <th class="text-center">No. Hrs Break</th>
                              <th class="text-center">Status</th>
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
                                 {{-- @if(count($outletApprover))
                                    <td>{{ $row->department }}</td>
                                    <td>{{ $row->outlet }}</td>
                                    <td><a href="#update_entry_shift_modal" data-toggle="modal" data-target="#update_entry_shift_modal" onclick="setShiftEmployee({{ $index }},'{{ $row->emp_id }}','{{ $row->emp_name }}','{{ $row->department }}' )">{{$row->emp_name}}</a></td>
                                 @endif --}}
                                    <td>{{ $row->shift_date}}</td>
                                    <td>{{ $row->shift_code}} ({{ $row->Description }})</td>
                                    <td class="text-center">{{ $row->no_hr_break}}</td>
                                    <td class="text-center">{{ ($row->status == 1) ? "Approved" : "Not approve" }}</td>
                                 </tr>
                              @endif
                              <?php $index = $index +1; ?>
                           @endforeach
                        @else
                           <tr><td class="text-center" colspan="7">No record found</td></tr>
                        @endif
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         <hr>
      </div>
   </div>

   <hr>

</div>

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
                                <input type="date" id="d_from" name="d_from" value="<?php echo @$_GET['date_from']; ?>"  class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Date To</label>
                                <input type="date" id="d_to" name="d_to" value="<?php echo @$_GET['date_to']; ?>" class="form-control" readonly>
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
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Update</button>
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
<script src="{{ asset('uidesign/js/custom/shift.js') }}"></script>
<script>

$(document).ready(function(){
    // Trigger click submit search button
   $('#btn-search-shift-monitoring').click(function(){
      $('#btn-search-button').click();
   });
});

function setShiftEmployee(index,emp_id,emp_name,department){
   @if (session('date_from'))
      $('#d_from').val('{{ session('date_from') }}');
      $('#d_to').val('{{ session('date_to') }}');
   @endif
   
   $('#s_emp').val( emp_name );
   $('#s_dept').val( department );
   var shift_list = "";
   var a = emp_id;
   DisplayEmployeeShift(emp_id,$('#date_from').val(),$('#date_to').val());
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