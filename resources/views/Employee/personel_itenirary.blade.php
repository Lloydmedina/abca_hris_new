@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Personnel Itinerary Setup')

{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Personnel Itinerary List</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> 
                  @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
                     Add New
                  @else
                     File PI
                  @endif
               </button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Date From</th>
                     <th>Date To</th>
                     <th>Name</th>
                     <th>Location</th>
                     <th>Remarks</th>
                     <th>Status</th>
                     @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
                        <th class="text-center">Action</th>
                     @endif
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <th>Date From</th>
                     <th>Date To</th>
                     <th>Name</th>
                     <th>Location</th>
                     <th>Remarks</th>
                     <th>Status</th>
                     @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
                        <th class="text-center">Action</th>
                     @endif
                  </tr>
               </tfoot>
               <tbody id="list_body" name="list">
                  @foreach($list as $row)
                  <tr>
                     <td>
                        {{ date('M d, Y', strtotime($row->date_from)) }}
                     </td>
                     <td>
                        {{ date('M d, Y', strtotime($row->date_to)) }}
                     </td>
                     <td>
                        {{ $row->emp_name }}
                     </td>
                     <td>
                        {{ $row->location }}
                     </td>
                     <td>
                        {{ $row->remarks }}
                     </td>
                     <td>
                        @if($row->status == 'PENDING')
                              <span>Pending...</span>
                        @elseif($row->status == 'APPROVED')
                           <span class="text-dark">Approved</span>
                        @else
                           <span class="text-danger">Rejected</span>
                        @endif
                     </td>
                     {{-- Para sa regular employee --}}
                     @if(session('user')->employee_type_id != 5 && session('user')->employee_type_id != 6)
                        <td class="text-center">
                           @if($row->status == 'PENDING')
                              <a href="{{ url('/approve_pi?id=').$row->pi_id.md5($row->pi_id) }}" onclick="return confirm('Approve Personel Itinerary')" class="mr-1 text-success approveLeave">
                                 <i class="fa-solid fa-check"></i> Approve
                              </a>
                              <a href="{{ url('/reject_pi?id=').$row->pi_id.md5($row->pi_id) }}" onclick="return confirm('Reject Personel Itinerary')" class="ml-1 text-danger rejectLeave">
                                 <i class="fa-solid fa-xmark"></i> Reject
                              </a>
                           @else
                              N/A
                           @endif
                        </td>
                     @endif
                  </tr>
                  @endforeach
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
            <h4 class="modal-title">New Personel Itinerary</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ route('add_personel_itenirary') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date From</label>
                           <input type="date" class="form-control" id="date_from" value="{{ date('Y-m-d') }}" name="date_from" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date To</label>
                           <input type="date" class="form-control" id="date_to" name="date_to" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee</label>
                           <select id="Employee" name="Employee" {{ (session('user')->employee_type_id == 5 || session('user')->employee_type_id == 6) ? 'disabled' : '' }} }}  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option selected disabled value="">Select Employee</option>
                              @foreach($employees as $row)
                                 @if(session('user')->employee_type_id == 5 || session('user')->employee_type_id == 6)
                                    <option value="{{ $row->SysPK_Empl }}" {{ (session('employee')->SysPK_Empl == $row->SysPK_Empl) ? 'selected' : '' }}>{{ $row->Name_Empl }}</option>
                                 @else
                                    <option value="{{ $row->SysPK_Empl }}">{{ $row->Name_Empl }}</option>
                                 @endif
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Location</label>
                           <input type="text" id="location" name="location"  class="form-control" required>
                        </div>
                     </div>

                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Travel Type</label>
                           <select id="travel_type" name="travel_type" class="form-control custom-select" required="">
                              <option value="Business Travel" @if (old('travel_type') == "Business Travel") {{ 'selected' }} @endif>Business Travel</option>
                              <option value="Special Travel" @if (old('travel_type') == "Special Travel") {{ 'selected' }} @endif>Special Travel</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <textarea id="" name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> 
                     @if(session('user')->employee_type_id == 5)
                        File PI
                     @else
                        Add New
                     @endif
                  </button>
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