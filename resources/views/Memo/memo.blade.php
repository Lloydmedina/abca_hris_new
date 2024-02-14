@php
   $checkIfHasMemoPriv = false;
   if(session('other_links')) foreach (session('other_links') as $value) if($value->id == 25) $checkIfHasMemoPriv = true;
@endphp
@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<style>
#memo_description_modal {
   white-space: pre-line;
}
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Memo')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
          <form action="{{ route('memo') }}" method="get">
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
               <h4 class="card-title">Memo</h4>
            </div>
            <div class="col-lg-6 col-sm-12 text-lg-right">
               @if($checkIfHasMemoPriv)
                  <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
               @endif
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Outlet</th>
                     <th>Date</th>
                     <th>Title</th>
                     <th>Description</th>
                     <th class="text-center" title="Mark as read"><i class="fa-solid fa-envelopes-bulk"></i></th>
                     @if($checkIfHasMemoPriv)
                        <th>Noticed by</th>
                     @endif
                     <th>Created by</th>
                     <th colspan="3" class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="list_body_" name="list">

                  @if(count($memo))
                     @foreach($memo as $row)

                     @php
                        $noticed_by_ids = explode(",", $row->noticed_by);


                        $outlet_ids = explode(",", $row->outlet_id);

                        // Unique
                        $noticed_by_ids = array_unique($noticed_by_ids);

                        // checker for already mark as read
                        $is_noticed = 1;
                     @endphp
                     <tr>
                        <td>
                           @if($row->outlet_id == 0)
                              ALL OUTLETS
                           @elseif(count($outlet_ids) == 1)
                              @foreach ($outlet as $item)
                                 @if ($item->outlet_id == $row->outlet_id)
                                       {{ $item->outlet }}
                                 @endif
                              @endforeach
                           @else
                              
                              <a
                                 href="javascript:void(0)"
                                 title="View outlets"
                                 data-memo_id="{{ $row->memo_id ?? ''}}"
                                 data-title="{{ $row->memo_title ?? ''}}"
                                 data-url="{{ url('/memo/outlets') }}"
                                 class="memo_view_modal_outlets"
                                 >{{ count($outlet_ids) }} OUTLETS</a>
                           
                           @endif
                           {{-- {{ $row->outlet ?? "ALL OUTLETS" }} --}}
                        </td>
                        <td>{{ date('M d, Y', strtotime($row->memo_date)) }}</td>
                        <td>{{ $row->memo_title }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($row->description , 50,'...')}}</td>
                        <td class="text-center">

                           @if($row->created_by != session('user')->id)
                              @if(in_array(session('user')->id, $noticed_by_ids))
                                 <i class="fa-regular fa-envelope-open" title="Read"></i>
                              @elseif(session('employee') && (in_array(session('employee')->outlet_id, $outlet_ids) || $row->outlet_id == 0) )
                                 {{-- <a href="{{ url('/memo-noticed?memo_id='.$row->memo_id) }}" title="Mark as read">
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
                        @if($checkIfHasMemoPriv)
                           <td>
                              @if($row->noticed_by && $row->created_by == session('user')->id)
                                 <a
                                    href="javascript:void(0)"
                                    title="View all employees name"
                                    data-memo_id="{{ $row->memo_id ?? ''}}"
                                    data-title="{{ $row->memo_title ?? ''}}"
                                    data-url="{{ url('/memo/noticed-by-employees') }}"
                                    class="memo_view_modal_noticed_by"
                                    ><small>{{ count($noticed_by_ids) }} Employee(s)</small></a>
                              @endif
                           </td>
                        @endif
                        <td><b>{{ $row->username }}</b></td>
                        <td class="text-center">
                           <div class="w-100">
                              @if($row->image_path)
                                 <a target="_blank" href="{{ url('/memo/download/'.$row->image_path.'') }}" class="text-dark" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>
                              @else
                                 <i class="fa-solid fa-ellipsis" title="No action"></i>
                              @endif
                           </div>
                       </td>
                       <td class="text-center">
                           <div class="w-100">
                              <a href="javascript:(0)" 
                                 data-id="{{ $row->memo_id ?? ''}}"
                                 data-title="{{ $row->memo_title ?? ''}}"
                                 data-date="{{ date('M d, Y', strtotime($row->memo_date)) ?? ''}}"
                                 data-desc="{{ $row->description ?? ''}}"
                                 data-file="{{ url('/memo/download/'.$row->image_path.'') ?? ''}}"
                                 data-memo_notice_url="{{ url('/memo-noticed?memo_id='.$row->memo_id) }}"
                                 data-image_path="{{ $row->image_path ?? ''}}"
                                 data-is_noticed ="{{ $is_noticed }}"
                                 class="text-info memo_view_modal" title="View">
                                 <i class="fa-solid fa-eye"></i>
                              </a>
                           </div>
                       </td>
                        <td class="text-center">
                           <div class="w-100">
                              @if($row->created_by == session('user')->id)
                                 <a href="javascript:(0)" 
                                    data-delete_url="{{ url('/memo/delete/'.$row->memo_id.'') }}"
                                    data-title="{{ $row->memo_title ?? ''}}"
                                    data-date="{{ date('M d, Y', strtotime($row->memo_date)) ?? ''}}"
                                    class="text-danger memo_delete_modal" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
            <h4 class="modal-title">New Memo</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_memo') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">

                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Outlet <i class="text-small text-danger">*</i> <input type="checkbox" style="top: .8rem;width: 1rem;height: 1rem;" value="0" name="select_all" id="select_all"> All outlets</label>
                              {{-- <select id="department" name="outlet" class="form-control custom-select" required> --}}
                              <select id="selected_outlet" class="selectpicker" multiple data-live-search="true" data-width="100%" name="outlet[]" required>
                                    {{-- <option value="" disabled>Select Outlet</option> --}}
                                    {{-- <option value="0">All</option> --}}
                                    @foreach($outlet as $row)
                                       <option value="{{ $row->outlet_id }}" <?php echo ($row->outlet_id == @$_GET['outlet']) ? 'selected':'' ?>>{{ $row->outlet }}</option>
                                    @endforeach
                              </select>
                              <input type="text" id="total_outlets" value="{{ count($outlet) }}" hidden>
                        </div>
                     </div>

                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date <i class="fa fa-question fa-xs" aria-hidden="true" title="The memo will appear on the date you specified."></i></label>
                           <input type="date" class="form-control" id="memo_date" value="{{ date('Y-m-d') }}" name="memo_date" required>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row ">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Title <i class="text-small text-danger">*</i></label>
                           <input type="text" class="form-control" id="memo_title" name="memo_title" required>
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

{{-- MEMO VIEW MODAL --}}
<div class="modal fade" id="memo_view_modal" tabindex="-1" aria-labelledby="memo_view_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-lg">
       <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="memo_view_modalLabel">Memo</h5>
               <button type="button" class="close text-danger memo_view_modal_close" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <div class="modal-body" style="margin: 2rem;">
               <h6 class="modal-title" id="memo_title_modal"></h6> <br>
               <h6 class="modal-title" id="memo_date_modal"></h6> <br>
               <p id="memo_description_modal"></p> <br>
               
               <a class="btn btn-sm btn-primary text-white" target="_blank" href="#" id="memo_attachment_file_modal">Download attachment file</a>
            </div>
            <div class="modal-footer">
               <a id="btn_memo_notice" href="">
                  <button class="btn btn-dark" type="button" >Mark as read</button>
               </a>
               <button class="btn btn-danger memo_view_modal_close" type="button" data-dismiss="modal">Close</button>
            </div>
       </div>
   </div>
</div>
{{-- END MEMO VIEW MODAL --}}

{{-- MEMO DELETE MODAL --}}
<div class="modal fade" id="memo_delete_modal" tabindex="-1" aria-labelledby="memo_delete_modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="memo_delete_modalLabel">Are you sure?</h5>
               <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
           </div>
           <div class="modal-body text-center">
               <p>Are you sure you want to delete this memo? <br>
                  You CAN NOT view this memo in your list anymore if you delete.</p>

                  <h5 class="modal-title" id="memo_delete_title_modal"></h5>
            </div>
           <div class="modal-footer">
               <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
               <a href="" id="btn-delete-memo-button" class="btn btn-dark" type="button">Yes, Delete</a>
               
           </div>
       </div>
   </div>
</div>
{{-- END MEMO DELETE MODAL --}}

{{-- MODAL NOTICED BY --}}
<div class="modal fade" id="memo_view_modal_noticed_by" tabindex="-1" aria-labelledby="memo_view_modal_noticed_byLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="memo_view_modal_noticed_byLabel">Noticed By</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body">

            <div class="table-responsive">
               <h6 class="modal-title" id="memo_title_modal_noticed"></h6> <br>
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
               <table class="table table-sm mb-0">
                 <thead>
                   <tr>
                     <th class="border-0 text-uppercase font-medium">#</th>
                     <th class="border-0 text-uppercase font-medium">ID</th>
                     <th class="border-0 text-uppercase font-medium">Name</th>
                   </tr>
                 </thead>
                 <tbody id="noticed_by_employees_tbody">

                 </tbody>
               </table>
            </div>
            
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
         </div>
   </div>
   </div>
</div>
{{-- END MODAL NOTICED BY --}}


{{-- MODAL OUTLETS BY --}}
<div class="modal fade" id="memo_view_modal_outlets" tabindex="-1" aria-labelledby="memo_view_modal_outletsLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="memo_view_modal_outletsLabel">Outlets</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <div class="modal-body">

            <div class="table-responsive">
               <h6 class="modal-title" id="memo_title_modal_outlet"></h6>
               <table class="table table-sm mb-0">
                 <thead>
                   <tr>
                     <th class="border-0 text-uppercase font-medium">#</th>
                     <th class="border-0 text-uppercase font-medium">OUTLET</th>
                   </tr>
                 </thead>
                 <tbody id="outlets_employees_tbody">

                 </tbody>
               </table>
            </div>
            
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
         </div>
   </div>
   </div>
</div>
{{-- END MODAL NOTICED BY --}}

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
   $( document ).ready(function() {

      // View memo modal
      $('.memo_view_modal').click(function(){

         let esc = false;
         $('#memo_title_modal').text($(this).data('title'));
         $('#memo_date_modal').text($(this).data('date'));
         $('#memo_description_modal').text($(this).data('desc'));

         $(".memo_view_modal_close").hide();

         if($(this).data('is_noticed') == 1){
            esc = true
            $("#btn_memo_notice").attr("href", "#").hide();
            $(".memo_view_modal_close").show();
         }
         else{
            $('#btn_memo_notice').attr("href", $(this).data('memo_notice_url')).show();;
         }
         

         if($(this).data('image_path'))
            $("#memo_attachment_file_modal").attr("href", $(this).data('file')).show();
         else
            $("#memo_attachment_file_modal").attr("href", "#").hide();

         $('#memo_view_modal').modal({
                        backdrop: 'static',
                        keyboard: esc, 
                        show: true
         });
      });

      $('.memo_delete_modal').click(function(){
         $('#memo_delete_title_modal').text($(this).data('title'));
         // $('#memo_delete_date_modal').text($(this).data('date'));
         $('#btn-delete-memo-button').attr('href', $(this).data('delete_url'));
         $('#memo_delete_modal').modal({backdrop: 'static', keyboard: true});
      });

      $('.memo_view_modal_noticed_by').click(function(){

         let url = $(this).data('url');
         let id = $(this).data('memo_id');
         let title = $(this).data('title');

         $('#memo_view_modal_noticed_by').modal({backdrop: 'static', keyboard: true});
         $('#memo_title_modal_noticed').text("Memo Title: Loading...")

         $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
               $('#noticed_by_employees_tbody').empty().html('<tr><td colspan="3" class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>');
               $('#myInputSearch').attr("disabled", true);
            },
            success: function(result) {

               if(result.code == 0){

                  Swal.fire({
                     icon: "warning",
                     title: "Oops!",
                     text: result.message,
                     showConfirmButton: true
                  }).then((result) => {
                     if (result.isConfirmed) {
                        location.reload();
                     }
                  });

               }
               else if(result.code == 1){

                  let employees =  result.employees;
                  let list = "";
                  let count = 1;

                  employees.forEach(function(employee) {
                     list +="<tr>";
                     list += '<td>'+count+')</td>';
                     list += '<td>'+employee.username+'</td>';
                     list += '<td>'+employee.Name_Empl+'</td>';
                     list +="</tr>";
                     count++;
                  });

                  $('#noticed_by_employees_tbody').empty().html(list);
                  $('#myInputSearch').attr("disabled", false);
                  $('#memo_title_modal_noticed').text("Memo Title: "+title)
               }

            },
            error: function(result){
                  console.log(result);
            }

         });

      });

      $('#selected_outlet').on('change',function(e){
         let selected_outlet = $(e.target).val();
         let total_outlets = $('#total_outlets').val();

         if(selected_outlet.length == total_outlets){
            $('#select_all').prop('checked', true);
         }
         else {
            $('#select_all').prop('checked', false);
         }

      });

      $('#select_all').click(function(){
        if($(this).is(":checked")){
         $('#selected_outlet').selectpicker('selectAll');
        }
        else{
         $('#selected_outlet').selectpicker('deselectAll');
        }
      });


      $('.memo_view_modal_outlets').click(function(){

         let url = $(this).data('url');
         let id = $(this).data('memo_id');
         let title = $(this).data('title');

         $('#memo_view_modal_outlets').modal({backdrop: 'static', keyboard: true});
         $('#memo_title_modal_outlet').text("Memo Title: Loading...")
         
         $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
               $('#outlets_employees_tbody').empty().html('<tr><td colspan="3" class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>');
            },
            success: function(result) {

               if(result.code == 0){

                  Swal.fire({
                     icon: "warning",
                     title: "Oops!",
                     text: result.message,
                     showConfirmButton: true
                  }).then((result) => {
                     if (result.isConfirmed) {
                        location.reload();
                     }
                  });

               }
               else if(result.code == 1){

                  let outlets =  result.outlets;
                  let list = "";
                  let count = 1;

                  outlets.forEach(function(outlet) {
                     list +="<tr>";
                     list += '<td>'+count+')</td>';
                     list += '<td>'+outlet.outlet+'</td>';
                     list +="</tr>";
                     count++;
                  });

                  $('#outlets_employees_tbody').empty().html(list);
                  $('#memo_title_modal_outlet').text("Memo Title: "+title)
               }

            },
            error: function(result){
                  console.log(result);
            }

         });

      });

   });

   function searchNames() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInputSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("noticed_by_employees_tbody");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2   ];
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