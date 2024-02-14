@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Employee Position Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header bg-info">
            <h4 class="m-b-0 text-white">Position Setup</h4>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                  <h4 class="card-title float-left">Position List</h4>
                  <button type="button" class="btn btn-sm btn-info m-l-15 float-right" data-toggle="modal" data-target="#entry_modal">
                  <i class="fa fa-plus-circle"></i>
                  Add New
                  </button>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <hr>
               </div>
            </div>
            <div class="row">
               <div class="table-responsive">
                  <table id="list" name="list" class="table table-sm table-bordered" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th class="">Code</th>
                           <th class="">Position</th>
                           <th class="">Daily Rate</th>
                           <th class="">Monthly Rate</th>
                           <th class="">COLA</th>
                           <th class="text-center">Action</th>
                        </tr>
                     </thead>
                     <tbody id="list_body" name="list">
                        @foreach($employees_rate as $row)
                        <tr>
                           <td>{{ $row->Position_Code }}</td>
                           <td>{{ $row->Position_Empl }}</td>
                           <td>{{ number_format($row->RatePerDay_Empl,2) }}</td>
                           <td>{{ number_format($row->BasicSalary_Empl,2) }}</td>
                           <td>{{ number_format($row->DoleRate_Empl,2) }}</td>
                           <td class="text-center">
                              <a href="#!" onclick="get_position_id('{{ $row->id.md5($row->id) }}', '{{$row->Position_Code}}','{{ $row->Position_Empl}}','{{ $row->RatePerDay_Empl }}','{{$row->BasicSalary_Empl}}', '{{ $row->DoleRate_Empl }}')" class="text-danger" data-toggle="modal" data-target="#update_modal" style="cursor: pointer;">
                              <span class="far fa-edit"></span>Edit
                              </a>
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>

   <hr>

</div>

{{-- BEGIN MODAL POSITION ENTRY --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-info text-white">
            <h4 class="modal-title">Position Entry</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_position') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Position Code</label>
                           <input type="text" id="" value="{{ old('Position_Code') }}" name="Position_Code" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Position</label>
                           <input type="text" id="" value="{{ old('Position_Empl') }}" name="Position_Empl" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Rate Option</label>
                           <select id="rate_selector" name="rate_option"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option value="1" selected>Monthly</option>
                              <option value="2">Daily</option>
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group" id="monthly_rate">
                           <label class="control-label">Monthly Rate</label>
                           <input type="number" id="" name="BasicSalary_Empl" value="{{ old('BasicSalary_Empl') }}" class="form-control">
                        </div>
                        <div class="form-group d-none" id="daily_rate">
                           <label class="control-label">Daily Rate</label>
                           <input type="number" id="" name="RatePerDay_Empl" value="{{ old('RatePerDay_Empl') }}" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">COLA</label>
                           <input type="number" id="" name="DoleRate_Empl" value="{{ old('DoleRate_Empl') }}" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="modal-footer">
                     <div class="form-actions m-auto">
                        <button type="reset" id="entry_reset_button" class="d-none">Reset</button>
                        <button type="button" id="entry_reset_id" class="btn btn-sm btn-danger mr-2"><i class="fa fa-undo"></i> Reset</button>
                        <button type="submit" class="btn btn-sm btn-info ml-2"> <i class="fa fa-plus-circle"></i> Add</button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END MODAL POSITION ENTRY --}}

{{-- BEGIN MODAL POSITION UPDATE --}}
<div id="update_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-success text-white">
            <h4 class="modal-title">Update Position</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_position') }}" method="post">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Position Code</label>
                           <input type="text" id="Position_Code" value="{{ old('Position_Code') }}" name="Position_Code" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Position</label>
                           <input type="text" id="Position_Empl" value="{{ old('Position_Empl') }}" name="Position_Empl" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Rate Option</label>
                           <select id="update_rate_selector" name="rate_option"  class="border form-control custom-select selectpicker" data-live-search="true" required>
                              <option value="1" selected>Monthly</option>
                              <option value="2">Daily</option>
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group" id="update_monthly_rate">
                           <label class="control-label">Monthly Rate</label>
                           <input type="number" id="BasicSalary_Empl" name="BasicSalary_Empl" value="{{ old('BasicSalary_Empl') }}" class="form-control">
                        </div>
                        <div class="form-group d-none" id="update_daily_rate">
                           <label class="control-label">Daily Rate</label>
                           <input type="number" id="RatePerDay_Empl" name="RatePerDay_Empl" value="{{ old('RatePerDay_Empl') }}" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">COLA</label>
                           <input type="number" id="DoleRate_Empl" name="DoleRate_Empl" value="{{ old('DoleRate_Empl') }}" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="modal-footer">
                     <div class="form-actions m-auto">
                        <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancel</button>
                        <button type="submit" class="btn btn-sm btn-info ml-2"> <i class="fa fa-plus-circle"></i> Update</button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END MODAL POSITION UPDATE --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('uidesign/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/position.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}