@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Cash Advance Setup')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="alert_message_js alert text-info fade show d-none" role="alert">
      <span id="alert_message_js"></span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
   </div>

   <div class="card">
      <div class="card-body">
         <form action="{{ route('cash_advance') }}" method="get">
            <div class="row">
               @php $colValue = "col-lg-2 col-sm-12"; @endphp
               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Department</label>
                        <select id="department" name="department" class="form-control custom-select">
                              <option value="0">All</option>
                              @foreach($department as $row)
                                 <option value="{{ $row->SysPK_Dept }}" <?php echo ($row->SysPK_Dept == @$_GET['department']) ? 'selected':'' ?>>{{ $row->Name_Dept }}</option>
                              @endforeach
                        </select>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Outlet</label>
                        <select id="outlet" name="outlet" class="form-control custom-select">
                              @if(count($outlets) > 1)
                                 <option value="0">All</option>
                              @endif
                              @foreach($outlets as $row)
                                 <option value="{{ $row->outlet_id }}" <?php echo ($row->outlet_id == @$_GET['outlet']) ? 'selected':'' ?>>{{ $row->outlet }}</option>
                              @endforeach
                        </select>
                  </div>
               </div>
            
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
                     <label style="visibility: hidden">Search Button</label>
                     @include('button_component.search_button', ['margin_top' => "1"])
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
               <h4 class="card-title">Cash Advance</h4>
            </div>
            <div class="col-lg-6 col-sm-12 text-right">
               <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#entry_modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
         </div>
         <div class="table-responsive mt-3">
            <table id="example23__" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th class="">Created At</th>
                     <th class="">Employee</th>
                     <th class="">CA Amount</th>
                     <th class="">Deduction History</th>
                     <th class="">Total Deduction</th>
                     <th class="">Current Balance</th>
                     <th class="">Effective Date</th>
                     <th class="">Remarks</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="list_body" name="list">

                  @if(count($ca))
                     <?php $index = 0; ?>
                     @foreach($ca as $row)
                        <tr id="{{ $row->ca_id.md5($row->ca_id) }}">
                           <td class="">
                              {{ date('M d, Y',strtotime($row->ca_date)) }}
                           </td>
                           <td class="">
                              {{ $row->emp_name }}
                           </td>
                           <td class="">
                              {{ $row->ca_amount }}
                           </td>
                           <td class="">
                              
                           </td>
                           <td class="">
                              
                           </td>
                           <td class="">
                              
                           </td>
                           <td class="">
                              {{ date('M d, Y',strtotime($row->date_from)) }} to {{ date('M d, Y',strtotime($row->date_to)) }}
                           </td>
                           <td class="">
                              {{ $row->remarks}}
                           </td>
                           <td class="text-center">
                              <a href="javascript:(0)" data-id="{{ $row->ca_id.md5($row->ca_id) }}" class="ml-1 text-danger deleteCA_" title="Delete Cash Advance">
                                 <i class="fa-solid fa-trash-can"></i>
                              </a>
                           </td>
                        </tr>
                     @endforeach
                     <?php $index = $index + 1; ?>
                  @else
                     <tr><td class="text-center" colspan="9">No record found</td></tr>
                  @endif
                  
               </tbody>
            </table>
         </div>
      </div>
   </div>
   
</div>


{{-- ENTRY MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">New Cash Advance</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="{{ url('/add_cash_advance') }}" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Date</label>
                           <input type="date" id="ca_date" name="ca_date"  class="form-control" required>
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Employee</label>
                           <select id="emp_code" name="emp_code" class="border form-control custom-select selectpicker" data-live-search="true" required>
                              
                              @foreach($employees as $row)
                                 <option value="{{ $row->UserID_Empl }}">
                                    {{ $row->Name_Empl }}
                                 </option>
                              @endforeach    
                           </select>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date From</label>
                           <input type="date" id="date_from" name="date_from" class="form-control" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Effective Date To</label>
                           <input type="date" id="" name="date_to" class="form-control" required>
                        </div>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">CA Amount</label>
                           <input type="number" id="" name="ca_amount"  class="form-control" required step="any">
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">CA Deduction</label>
                           <input type="number" id="" name="amount_deduction"  class="form-control" required step="any">
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


{{-- UPDATE MODAL --}}

{{-- END UPDATE MODAL --}}
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
<script src="{{ asset('uidesign/js/custom/department.js') }}"></script>
<script>
   $(document).ready(function(){
   // Trigger click submit search button
   // $('#btn-search-ca').click(function(){
   //    $('#btn-search-button').click();
   // });

   $(document).on('click', '.deleteCA_', function(e) {
        
        let id = $(this).data('id');
        let url = "{{ route('delete_ca') }}";
        let btn_confirm = confirm("Delete Cash Advance?");

        if (btn_confirm) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {id:id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                  if(result.code == 1){
                     $('.alert_message_js').removeClass('d-none').addClass('alert-success');;
                     $('#alert_message_js').text(result.message);
                     $('#'+id).hide();
                     
                  }
                },
                error: function(result){
                    console.log(result);
                }

            });
        }

   });
});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}