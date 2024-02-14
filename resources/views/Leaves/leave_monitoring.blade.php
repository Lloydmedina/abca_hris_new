@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Leave Monitoring')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <form class="form-material" action="{{ url('/leave_monitoring') }}" method="get">
            <div class="row">
               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">
                        Date
                     </label>
                     <input class="form-control" type="date" name="yr" value="{{ date('Y-m-d') }}">
                  </div>
               </div>

               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">&nbsp;</label>
                     @include('button_component.search_button', ['margin_top' => "11.5"])
                  </div>
               </div>
               
            </div>
         </form>
      </div>
   </div>

   <hr>

   <div class="card">
      <div class="card-body">
         <div class="table-responsive mt-3">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="text-center align-middle"rowspan="2">
                        Emp ID
                     </th>
                     <th class="text-center align-middle" rowspan="2">
                        Name
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Leave<br>Available
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Jan
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Feb
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Mar
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Apr
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        May
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Jun
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Jul
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Aug
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Sept
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Oct
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Nov
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Dec
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Used<br>Leave
                     </th>
                     <th class="text-center align-middle" colspan="2">
                        Remaining<br>Leave
                     </th>
                     <th class="text-center align-middle" rowspan="2">
                        Total<br>Leave
                     </th>
                  </tr>
                  <tr>
                     <td class="text-center align-middle">
                        vl
                     </td>
                     <td class="text-center align-middle">
                        sl
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        1st
                     </td>
                     <td class="text-center align-middle">
                        2nd
                     </td>
                     <td class="text-center align-middle">
                        vl
                     </td>
                     <td class="text-center align-middle">
                        sl
                     </td>
                     <td class="text-center align-middle">
                        vl
                     </td>
                     <td class="text-center align-middle">
                        sl
                     </td>
                  </tr>
               </thead>

               <tbody id="list_body" name="list">
                  @if(!empty($list))
                     @foreach($list as $row)
                        <tr>
                           <td class="text-left align-middle">
                              {{ $row->emp_code }}
                           </td>
                           <td class="text-left align-middle">
                              {{ $row->emp_name }}
                           </td>
                           <td class="text-center align-middle">
                              <p contenteditable="true">
                                 {{ $row->vication_leave }}
                              </p>
                           </td>
                           <td class="text-center align-middle">
                              <p contenteditable="true">
                              {{ $row->sick_leave }}
                              </p>
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_jan ==  0 ? "" : $row->first_jan }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_jan ==  0 ? "" : $row->second_jan }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_feb ==  0 ? "" : $row->first_feb }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_feb ==  0 ? "" : $row->second_feb }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_mar ==  0 ? "" : $row->first_mar }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_mar ==  0 ? "" : $row->second_mar}}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_apr ==  0 ? "" : $row->first_apr }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_apr ==  0 ? "" : $row->second_apr }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_may ==  0 ? "" : $row->first_may }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_may ==  0 ? "" : $row->second_may }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_jun ==  0 ? "" : $row->first_jun }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_jun ==  0 ? "" : $row->second_jun }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_jul ==  0 ? "" : $row->first_jul }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_jul ==  0 ? "" : $row->second_jul }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_aug ==  0 ? "" : $row->first_aug }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_aug ==  0 ? "" : $row->second_aug }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_sep ==  0 ? "" : $row->first_sep  }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_sep ==  0 ? "" : $row->second_sep }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_oct ==  0 ? "" : $row->first_oct }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_oct ==  0 ? "" : $row->second_oct }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_nov ==  0 ? "" : $row->first_nov }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_nov ==  0 ? "" : $row->second_nov }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->first_dec ==  0 ? "" : $row->first_dec }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->second_dec ==  0 ? "" : $row->second_dec}}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->used_vication_leave }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->used_sick_leave }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->bal_vication_leave }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->bal_sick_leave }}
                           </td>
                           <td class="text-center align-middle">
                              {{ $row->total_leave_used }}
                           </td>
                        </tr>
                     @endforeach
                  @endif
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <hr>

</div>
<!-- /.container-fluid -->
{{-- ENTRY MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Update Leave Credits</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_cost_center') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Leave Type Code</label>
                           <input type="text" id="leave_code" name="leave_code" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Leave Type</label>
                           <input class="form-control text-right" type="text" id="leave_type" name="leave_type">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">With Pay</label>
                           <select class="form-control" required="">
                              <option value="1">YES</option>
                              <option value="0">NO</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <input type="text" id="remarks" name="remarks" class="form-control">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/department.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}