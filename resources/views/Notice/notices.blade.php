@php
   $checkIfHasNoticePriv = false;
   if(session('other_links')) foreach (session('other_links') as $value) if($value->id == 49) $checkIfHasNoticePriv = true;
@endphp
@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<style>
#notice_description_modal {
   white-space: pre-line;
}
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Notices')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
          <form action="{{ route('notices') }}" method="get">
              <div class="row">
                  <div class="col-lg-3 col-sm-12">
                      <div class="form-group">
                          <label class="control-label">From</label>
                          <input type="date" class="form-control" id="date_from" value="{{ request()->input('date_from') ?? $date_from }}" name="date_from">
                      </div>
                  </div>

                  <div class="col-lg-3 col-sm-12">
                      <div class="form-group">
                          <label class="control-label">To</label>
                          <input type="date" class="form-control" id="date_to" value="{{ request()->input('date_to') ?? $date_to }}" name="date_to">
                      </div>
                  </div>

                  <div class="col-lg-3 col-sm-12">
                     {{-- <div class="form-group">
                        <label for="ps_status_select">Status</label>
                        <select class="form-control" id="ps_status_select" name="status">
                        <option value="4" {{ request()->input('status') === '4' ? 'selected' : '' }}>All</option>
                        <option value="0" {{ request()->input('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="3" {{ request()->input('status') === '3' ? 'selected' : '' }}>Partially Approved</option>
                        <option value="1" {{ request()->input('status') === '1' ? 'selected' : '' }}>Approved</option>
                        <option value="2" {{ request()->input('status') === '2' ? 'selected' : '' }}>Rejected</option>
                        </select>
                     </div> --}}
                  </div>

                  <div class="col-lg-1 col-sm-12">
                     <div class="form-group">
                        <label for="ps_display_by_select">Display</label>
                        <select class="form-control" id="ps_display_by_select" name="display_by">
                        <option value="10" {{ request()->input('display_by') === '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request()->input('display_by') === '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request()->input('display_by') === '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request()->input('display_by') === '100' ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request()->input('display_by') === 'all' ? 'selected' : '' }}>All</option>
                        </select>
                     </div>
                  </div>

                  <div class="col-lg-2 col-sm-12">
                      <div class="form-group">
                          <label class="hide" style="visibility: hidden">Search Button</label>
                          @include('button_component.search_button', ['margin_top' => "0.5"])
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
               <h4 class="card-title">Notices</h4>
            </div>
            <div class="col-lg-6 col-sm-12 text-lg-right">
               @if($checkIfHasNoticePriv)
                  <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
               @endif
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Employee/s</th>
                     <th>Date</th>
                     <th>Title</th>
                     <th>Description</th>
                     <th class="text-center" title="Mark as read"><i class="fa-solid fa-envelopes-bulk"></i></th>
                     <th>Noticed At</th>
                     <th>Created by</th>
                     <th colspan="3" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="" name="list">

                  @if(count($notices))
                     @foreach($notices as $row)

                     @php
                        $noticed_by_ids = explode(",", $row->noticed_by);
                        $emp_ids = explode(",", $row->emp_ids);

                        // checker for already mark as read
                        $is_noticed = 1;
                     @endphp
                     <tr>
                        <td>
                            @foreach ($assigned_employees[$row->notice_id] as $names)
                                <span title="{{ $names['employee_id'] }}">{{ $names['name'] }}</span> <br>
                            @endforeach
                        </td>
                        <td>{{ date('M d, Y', strtotime($row->notice_date)) }}</td>
                        <td>{{ $row->notice_title }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($row->description , 50,'...')}}</td>
                        <td class="text-center">

                           @if($row->created_by != session('user')->id)
                              @if(in_array(session('user')->id, $noticed_by_ids))
                                 <i class="fa-regular fa-envelope-open" title="Read"></i>
                              @elseif(session('employee') && in_array(session('employee')->SysPK_Empl, $emp_ids) )
                                 {{-- <a href="{{ url('/notice-noticed?notice_id='.$row->notice_id) }}" title="Mark as read">
                                    <i class="fa-regular fa-square"></i>
                                 </a> --}}
                                 @php
                                    $is_noticed = 0;
                                 @endphp
                                 <i class="fa-solid fa-envelope" title="Unread"></i>
                              @else
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              @endif
                           @else
                              <i class="fa-solid fa-ellipsis" title="No action"></i>
                           @endif
                           
                        </td>
                        <td>
                           @if($row->noticed_by_date)
                              {{ date('M d, Y H:i', strtotime($row->noticed_by_date)) }}
                           @endif
                        </td>
                        <td><b>{{ $row->username }}</b></td>
                        <td class="text-center">
                           <div class="w-100">
                              @if($row->image_path)
                                 <a target="_blank" href="{{ url('/notice/download/'.$row->image_path.'') }}" class="text-dark" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>
                              @else
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              @endif
                           </div>
                       </td>
                       <td class="text-center">
                           <div class="w-100">
                              <a href="javascript:(0)" 
                                 data-id="{{ $row->notice_id ?? ''}}"
                                 data-title="{{ $row->notice_title ?? ''}}"
                                 data-date="{{ date('M d, Y', strtotime($row->notice_date)) ?? ''}}"
                                 data-desc="{{ $row->description ?? ''}}"
                                 data-file="{{ url('/notice/download/'.$row->image_path.'') ?? ''}}"
                                 data-notice_notice_url="{{ url('/notice-noticed?notice_id='.$row->notice_id) }}"
                                 data-image_path="{{ $row->image_path ?? ''}}"
                                 data-is_noticed ="{{ $is_noticed }}"
                                 class="text-info notice_view_modal" title="View">
                                 <i class="fa-solid fa-eye"></i>
                              </a>
                           </div>
                       </td>
                        <td class="text-center">
                           <div class="w-100">
                              @if($row->created_by == session('user')->id)
                                 <a href="javascript:(0)" 
                                    data-delete_url="{{ url('/notice/delete/'.$row->notice_id.'') }}"
                                    data-title="{{ $row->notice_title ?? ''}}"
                                    data-date="{{ date('M d, Y', strtotime($row->notice_date)) ?? ''}}"
                                    class="text-danger notice_delete_modal" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                              @else
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              @endif
                           </div>
                        </td>
                     </tr>
                     @endforeach
                  @else
                     <tr><td class="text-center" colspan="10">No record found</td></tr>
                  @endif
               </tbody>
            </table>
            <div class="float-left"> {{ $paginationLinks }} </div>
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
            <h4 class="modal-title">New Notice</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_notice') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">

                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Employees <i class="text-small text-danger">*</i></label>
                              <select id="emp_ids" name="emp_ids[]" class="border form-control custom-select selectpicker" data-live-search="true" required>
                                    <option value="" selected disabled>Select Employee</option>
                                    @foreach($employees as $row)
                                       <option value="{{ $row->SysPK_Empl }}">{{ $row->Name_Empl }}</option>
                                    @endforeach
                              </select>
                        </div>
                     </div>

                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date <i class="fa fa-question fa-xs" aria-hidden="true" title="The notice will appear on the date you specified."></i></label>
                           <input type="date" class="form-control" id="notice_date" value="{{ date('Y-m-d') }}" name="notice_date" required>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row ">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Title <i class="text-small text-danger">*</i></label>
                           <input type="text" class="form-control" id="notice_title" name="notice_title" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group has-danger">
                           <label class="control-label">Description</label>
                           <textarea id="description" name="description" class="form-control" rows="6"></textarea>
                        </div>
                     </div>
                  </div>
                  
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="image_path">Attacth File</label>
                           <input type="file" class="form-control-file {{ $errors->has('image_path') ? 'is-invalid' : '' }}" id="image_path" name="image_path" aria-describedby="fileHelp" accept="image/png, image/jpeg,.xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                           <small id="fileHelp" class="form-text text-muted">(PDF, DOC, DOCX, PNG, JPEG, JPG).</small>
                           <div class="invalid-feedback">
                               {{ $errors->first('image_path') }}
                           </div>
                       </div>
                     </div>
                     
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-dark ml-2"> <i class="fa-solid fa-floppy-disk"></i> Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END ENTRY MODAL --}}

{{-- notice VIEW MODAL --}}
<div class="modal fade" id="notice_view_modal" tabindex="-1" aria-labelledby="notice_view_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-lg">
       <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="notice_view_modalLabel">Notice</h5>
               <button type="button" class="close text-danger notice_view_modal_close" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <div class="modal-body" style="margin: 2rem;">
               <h6 class="modal-title" id="notice_title_modal"></h6> <br>
               <h6 class="modal-title" id="notice_date_modal"></h6> <br>
               <p id="notice_description_modal"></p> <br>
               
               <a class="btn btn-sm btn-primary text-white" target="_blank" href="#" id="notice_attachment_file_modal">Download attachment file</a>
            </div>
            <div class="modal-footer">
               <a id="btn_notice_notice" href="">
                  <button class="btn btn-dark" type="button" >Acknowledge</button>
               </a>
               <button class="btn btn-danger notice_view_modal_close" type="button" data-dismiss="modal">Close</button>
            </div>
       </div>
   </div>
</div>
{{-- END notice VIEW MODAL --}}

{{-- notice DELETE MODAL --}}
<div class="modal fade" id="notice_delete_modal" tabindex="-1" aria-labelledby="notice_delete_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="notice_delete_modalLabel">Are you sure?</h5>
               <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
           </div>
           <div class="modal-body text-center">
               <p>Are you sure you want to delete this notice? <br>
                  You CAN NOT view this notice in your list anymore if you delete.</p>

                  <h5 class="modal-title" id="notice_delete_title_modal"></h5>
            </div>
           <div class="modal-footer">
               <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
               <a href="" id="btn-delete-notice-button" class="btn btn-dark" type="button">Yes, Delete</a>
               
           </div>
       </div>
   </div>
</div>
{{-- END notice DELETE MODAL --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script>
   $( document ).ready(function() {

      // View notice modal
      $('.notice_view_modal').click(function(){

         let esc = false;
         $('#notice_title_modal').text($(this).data('title'));
         $('#notice_date_modal').text($(this).data('date'));
         $('#notice_description_modal').text($(this).data('desc'));

         $(".notice_view_modal_close").hide();

         if($(this).data('is_noticed') == 1){
            esc = true
            $("#btn_notice_notice").attr("href", "#").hide();
            $(".notice_view_modal_close").show();
         }
         else{
            $('#btn_notice_notice').attr("href", $(this).data('notice_notice_url')).show();;
         }
         

         if($(this).data('image_path'))
            $("#notice_attachment_file_modal").attr("href", $(this).data('file')).show();
         else
            $("#notice_attachment_file_modal").attr("href", "#").hide();

         $('#notice_view_modal').modal({
                        backdrop: 'static',
                        keyboard: esc, 
                        show: true
         });
      });

      $('.notice_delete_modal').click(function(){
         $('#notice_delete_title_modal').text($(this).data('title'));
         // $('#notice_delete_date_modal').text($(this).data('date'));
         $('#btn-delete-notice-button').attr('href', $(this).data('delete_url'));
         $('#notice_delete_modal').modal({backdrop: 'static', keyboard: true});
      });
      
      
   });

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}