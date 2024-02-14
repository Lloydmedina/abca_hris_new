@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Trip Details Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Trip Details List</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New Trip</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="text-center">Trip No</th>
                     <th class="text-center">Travel Description</th>
                     <th class="text-center">Passenger Names</th>
                     <th class="text-center">Destination</th>
                     <th class="text-center">Departed on</th>
                     <th class="text-center">Odometer</th>
                     <th class="text-center">Arrived on</th>
                     <th class="text-center">Odometer</th>
                     <th class="text-center">Remarks</th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <th class="text-center">Trip No</th>
                     <th class="text-center">Travel Description</th>
                     <th class="text-center">Passenger Names</th>
                     <th class="text-center">Destination</th>
                     <th class="text-center">Departed on</th>
                     <th class="text-center">Odometer</th>
                     <th class="text-center">Arrived on</th>
                     <th class="text-center">Odometer</th>
                     <th class="text-center">Remarks</th>
                  </tr>
               </tfoot>
               <tbody id="list_body" name="list">
                  @foreach($list as $row)
                  <tr>
                     <td class="text-left">
                        {{ $row->trip_number }}
                     </td>
                     <td class="text-left">
                        {{ $row->travel_details }}
                     </td>
                     <td class="text-left">
                        {{ $row->passenger_names }}
                     </td>
                     <td class="text-left">
                        {{ $row->destination }}
                     </td>
                     <td class="text-left">
                        {{ $row->et_departure }}
                     </td>
                     <td class="text-left">
                        {{ $row->departure_odometer }}
                     </td>
                     <td class="text-left">
                        {{ $row->et_arrival }}
                     </td>
                     <td class="text-left">
                        {{ $row->arrival_odometer }}
                     </td>
                     <td class="text-left">
                        {{ $row->remarks }}
                     </td>                            
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
            <h4 class="modal-title">New Trip Details</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_new_trip') }}" method="post">
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Trip Number</label>
                           <input type="number" id="trip_number" name="trip_number" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Destination</label>
                           <input class="form-control text-right" type="text" id="destination" name="destination">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group has-danger">
                           <label class="control-label">Travel Details</label>
                           <input type="text" id="travel_details" name="travel_details"  class="form-control" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Passenger Names</label>
                           <input type="text" id="passenger_names" name="passenger_names"  class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Departed on</label>
                           <input type="text" id="et_departure" name="et_departure" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Arrived on"</label>
                           <input type="text" id="et_arrival" name="et_arrival" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Departure Odometer Reading</label>
                           <input type="text" id="departure_odometer" name="departure_odometer" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Arrival Odometer Reading</label>
                           <input type="text" id="arrival_odometer" name="arrival_odometer" class="form-control"  required>
                        </div>
                     </div>
                     <!--/span-->
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
                  @csrf
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Add</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END UPDATE MODAL --}}

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