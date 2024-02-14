@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Employees')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
          @php
              $arr_emp_status = request()->input('emp_status') ?? [];
              // dd($arr_emp_status);
          @endphp
          <form action="{{ route('employees') }}" method="get">
              {{-- @csrf --}}
              <div class="row">
              <div class="col-lg-12 col-sm-12">
                  <div class="form-check form-check-inline mb-3">
                      <input class="form-check-input emp_status_all" name="emp_status[]" type="checkbox" id="inlineCheckbox_ALL" value="ALL" {{ (in_array("ALL", $arr_emp_status)) ? 'checked' : '' }}>
                      <label class="form-check-label mr-3" for="inlineCheckbox_ALL">ALL</label> |
                  </div>
                  @foreach($empStatus as $row)
                      <div class="form-check form-check-inline mb-3">
                          <input class="form-check-input emp_status" name="emp_status[]" type="checkbox" id="inlineCheckbox_{{ $row->Status_Empl }}" value="{{ $row->Status_Empl }}"  {{ (in_array($row->Status_Empl, $arr_emp_status)) ? 'checked' : '' }}>
                          <label class="form-check-label mr-3" for="inlineCheckbox_"{{ $row->Status_Empl }}>{{ $row->Status_Empl }}</label> |
                      </div>
                  @endforeach
              </div>
                  <div class="col-lg-4 col-sm-12">
                      <div class="form-group">
                      <label class="control-label">Department</label>
                          <select id="department" name="department" class="form-control custom-select">
                                  <option value="0">All</option>
                                  @foreach($department as $row)
                                      <option value="{{ $row->SysPK_Dept }}" {{ ($row->SysPK_Dept == request()->get('department')) ? 'selected':'' }}>{{ $row->Name_Dept }}</option>
                                  @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-4 col-sm-12">
                      <div class="form-group">
                      <label class="control-label">Outlet</label>
                          <select id="outlet" name="outlet" class="form-control custom-select">
                                  <option value="0">All</option>
                                  @foreach($outlets as $row)
                                  <option value="{{ $row->outlet_id }}" {{ ($row->outlet_id == request()->get('outlet')) ? 'selected':'' }}> {{ $row->outlet }}</option>
                                  @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-2 col-sm-12"></div>
                  <div class="col-lg-2 col-sm-12">
                      <div class="form-group">
                          {{-- <input style="cursor:pointer" type="submit" class="form-control btn-primary" id="btn-search-button" value="SEARCH" name="btn_search" hidden> --}}
                          <label class="hide" style="visibility: hidden">Search Button</label>
                          @include('button_component.search_button', ['margin_top' => "-1.5"])
                          {{-- <button type="button" class="btn btn-primary mt-auto w-100" id="btn-search-employees"><i class="fa fa-search" aria-hidden="true"></i> Search</button> --}}
                      </div>
                  </div>
              </div>
          </form>
      </div>
  </div>

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Exit Interview Listing</h4>
            </div>
            <!-- <div class="col-6 text-right">
               <a href="{{ url('/add_employee_exit') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Add New</a>
            </div> -->
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                  <th class="">Exit Status</th>   
                     <th class="">Code</th>
                     <th class="">Name</th>
                     <th class="">Company</th>
                     <th class="">Outlet</th>
                     <th class="">Department</th>
                     <th class="">Position</th>
                     <th class="">Status</th>
                     
                  </tr>
                  <tfoot>
                  <th class="">Exit Status</th>
                     <th class="">Code</th>
                     <th class="">Name</th>
                     <th class="">Company</th>
                     <th class="">Outlet</th>
                     <th class="">Department</th>
                     <th class="">Position</th>
                     <th class="">Status</th>
                     
                  </tfoot>
               </thead>
               <tbody id="list_body" name="list">
                  @foreach($employees as $row)
                     <tr>
                        <td style='text-align : center'>
                        <a href="{{ url('/add_employee_exit?id='.$row->SysPK_Empl.md5( $row->SysPK_Empl) ) }}" class="btn btn-sm btn-primary">
                        Apply?</a>
                        </td>
                        <td>{{ $row->UserID_Empl }}</td>
                        <td>
                              {{ ucwords(strtolower($row->Name_Empl)) }}
                        </td>
                        <td>{{ $row->company }}</td>
                        <td>{{ $row->outlet }}</td>
                        <td>{{ $row->Department_Empl }}</td>
                        <td>{{ $row->Position_Empl }}</td>
                        <td>{{ $row->Status_Empl }}</td>
                     </tr>
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
<script src="{{ asset('uidesign/js/custom/employees_list.js') }}"></script>
<script>
   $(document).ready(function(){

       $(".emp_status_all").click(function(){
           $('.emp_status').not(this).prop('checked', this.checked);
       });

       // $('#btn-search-employees').click(function(){
       //     $('#btn-search-button').click();
       // });
   });

   function searchNames() {
       // Declare variables
       var input, filter, div, h5, name, i, txtValue;
       input = document.getElementById("myInputSearch");
       filter = input.value.toUpperCase();
       div = document.getElementById("myEmpList");
       divC = document.getElementsByClassName("myEmpList");
       h5 = div.getElementsByTagName("h5");

       // Loop through all table rows, and hide those who don't match the search query
       for (i = 0; i < h5.length; i++) {
           name = h5[i];
           if (name) {
               txtValue = name.textContent || name.innerText;
               if (txtValue.toUpperCase().indexOf(filter) > -1) {
                   divC[i].style.display = "";
               } else {
                   divC[i].style.display = "none";
               }
           }
       }
   }
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}