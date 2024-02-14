@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Holiday Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-6">
               <h4 class="card-title">Holiday List</h4>
            </div>
            <div class="col-6 text-right">
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <!-- <th class="text-center">holiday_id</th> -->
                     <th class="text-center">Date</th>
                     <th class="text-center">Description</th>
                     <th class="text-center">Type</th>
                     <th class="text-center">Remarks</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <!-- <tfoot>
                  <tr>
                     <th class="text-center">Date</th>
                     <th class="text-center">Description</th>
                     <th class="text-center">Type</th>
                     <th class="text-center">Remarks</th>
                     <th class="text-center">Action</th>
                  </tr>
               </tfoot> -->
               <tbody id="list_body" name="list">
                  @foreach($list as $row)
                  <tr>
                    <!--  <td class="text-left">
                        {{ $row->holiday_id }}
                     </td> -->

                     <td class="text-left">
                        {{ date('M d, Y', strtotime($row->holiday_date)) }}
                     </td>
                     <td class="text-left">
                        {{ $row->description }}
                     </td>
                     <td class="text-left">
                        {{ $row->holiday_type }}
                     </td>
                     <td class="text-left">
                        {{ $row->remarks }}
                     
                     <td class="text-center">
                        <div class="w-100">
                           <span class="text-dark update_holiday" title="Update Holiday" style="cursor: pointer;" 
                              data-holiday_id="{{ $row->holiday_id }}" 
                              data-holiday_date="{{ $row->holiday_date }}"
                              data-description="{{ $row->description }}"
                              data-holiday_type="{{ $row->holiday_type }}"
                              data-remarks="{{ $row->remarks }}"
                             
                           >
                              <span class="fa-solid fa-pen-to-square"></span>
                           </span>
                        </div>
                     </td>
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
            <h4 class="modal-title">New Holiday</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_holiday_setup') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Holiday Date</label>
                           <input type="date" id="holiday_date" name="holiday_date" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Holiday Type</label>
                           <select class="form-control" name="holiday_type" required="">
                              <option value="regular">Regular</option>
                              <option value="special">Special</option>
                           </select>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Description</label>
                           <input class="form-control" type="text" id="description" name="description">
                           
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
{{-- END ENTRY MODAL --}}


{{-- UPDATE MODAL --}}
<div id="update_holiday_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelUpdate" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Update Holiday</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/update_holiday') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <!-- <label class="control-label">Holiday ID</label> -->
                           <input class="form-control" type="text" id="u_holiday_id" name="holiday_id" hidden="">
                        </div>
                     </div>
                  </div>
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <!-- <input type="text" name="id" id="holiday_id_to_update" hidden> -->
                           <label class="control-label">Holiday Date</label>
                           <input type="date" id="u_holiday_date" name="holiday_date" class="form-control" required>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Holiday Type</label>
                           <select class="form-control" id="u_holiday_type" name="holiday_type" required="">
                              <option value="regular">Regular</option>
                              <option value="special">Special</option>
                           </select>
                           
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Description</label>
                           <input class="form-control" type="text" id="u_description" name="description">
                           
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Remarks</label>
                           <input type="text" id="u_remarks" name="remarks" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Update</button>
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
<script src="{{ asset('uidesign/js/custom/department.js') }}"></script> --}}

<script>
   $(document).ready(function() {

      // update outlet modal
      $('.update_holiday').click(function(){
         $('#u_holiday_id').val($(this).data('holiday_id'));
         $('#u_holiday_date').val($(this).data('holiday_date'));
         $('#u_holiday_type').val($(this).data('holiday_type')); 
         $('#u_description').val($(this).data('description')); 
         $('#u_remarks').val($(this).data('remarks')); 
         


         $('#update_holiday_modal').modal({backdrop: 'static', keyboard: true});
      });

     
   });


</script>

@endsection
{{-- END PAGE LEVEL SCRIPT --}}