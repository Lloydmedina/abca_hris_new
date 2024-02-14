@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Trainings')
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
         <form action="" method="get">
            <div class="row">
               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">From: <i class="text-small text-danger">*</i></label>
                     <input type="date" class="form-control" id="date_from" value="<?php echo @$_GET['date_from'] ?? $date_from; ?>" name="date_from" required>
                  </div>
               </div>

               <div class="col-lg-4 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">To: <i class="text-small text-danger">*</i></label>
                     <input type="date" class="form-control" id="date_to" value="<?php echo @$_GET['date_to'] ?? $date_to; ?>" name="date_to" required>
                  </div>
               </div>

               <div class="col-lg-4 col-sm-12 text-lg-right">
                  <div class="form-group">
                     <label class="hide" style="visibility: hidden">Search Button</label>
                     @include('button_component.search_button', ['margin_top' => "8.5"])
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
            <div class="col-lg-6 col-sm-12">
               <h4 class="card-title">Trainings <small>({{ count($trainigs) }})</small></h4>
            </div>
            {{-- <div class="col-lg-6 col-sm-12">
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
            </div> --}}
            <div class="col-lg-6 col-sm-12 text-lg-right">
               
                  <a href="{{ url('/my-training-calendar') }}" title="Training Calendar">
                        <button class="btn btn-sm btn-link"><i class="fa fa-calendar" aria-hidden="true"></i> Training Calendar</button>
                  </a>
               
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Training Name</th>
                     <th class="">Date/Time</th>
                     <th class="">Trainer/s</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
               @if(count($trainigs))
               @php
                     $index = 0;
               @endphp
                  @foreach($trainigs as $row)

                     @php
                        $tr_status = "Upcoming";
                        $tr_status_class = "badge-primary";
                        if(strtotime($row->tr_date) == strtotime(date('Y-m-d'))){
                           $tr_status = "Ongoing...";
                           $tr_status_class = "badge-warning";
                        }
                        elseif(strtotime($row->tr_date) < strtotime(date('Y-m-d'))){
                           $tr_status = "Finished";
                           $tr_status_class = "badge-success";
                        }
                     @endphp

                     <tr id="{{ $row->tr_id.md5($row->tr_id) }}">
                        <td><a href='{{ url("/my-training/$row->tr_id/result?v=1")}}'>{{ $row->tr_name }}</a></td>
                        <td>{{ date('M d, Y', strtotime($row->tr_date)) }} {{ $row->tr_time }} <span class="ml-3 badge {{$tr_status_class}}">{{ $tr_status }}</span></td>
                        <td>{{ $row->trainers }}</td>
                     </tr>
                     <?php $index = $index +1; ?>
                  @endforeach
               @else
                  <tr><td class="text-center" colspan="12">No record found</td></tr>
               @endif
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <hr>

</div>

{{-- TRAINING DELETE MODAL --}}
<div class="modal fade" id="training_delete_modal" tabindex="-1" aria-labelledby="training_delete_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="training_delete_modalLabel">Are you sure?</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body text-center">
            <p>Are you sure you want to remove this training? <br>
            You CAN NOT view this training in your list anymore if you remove.</p>
   
            <h5 class="modal-title" id="training_delete_title_modal"></h5>
         </div>
         <div class="modal-footer">
            <a href="" id="btn-delete-training-button" class="btn btn-danger" type="button">Yes, Remove</a>
            <button class="btn btn-success" type="button" data-dismiss="modal">No, Don't Remove</button>
         </div>
      </div>
   </div>
</div>
{{-- END TRAINING DELETE MODAL --}}


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

   // Trigger click submit search button
   $('#btn-search').click(function(){
      $('#btn-search-button').click();
   });

   // Delete training
   $('.training_delete_modal').click(function(){
		$('#training_delete_title_modal').text($(this).data('title'));
		$('#btn-delete-training-button').attr('href', $(this).data('delete_url'));
		$('#training_delete_modal').modal({backdrop: 'static', keyboard: true});
   });
});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}