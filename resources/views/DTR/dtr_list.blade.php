@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','DTR List')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

   @include('Templates.alert_message')

   <form class="form-material" action="{{ url('/dtr_list') }}" method="get">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-lg-4 col-sm-12">
                  @if(session('user')->employee_type_id != 5)
                     <div class="form-group">
                        <label class="control-label">Deparment</label>
                           <select id="company_selected" name="company_selected" class="form-control custom-select" >
                              @if( isset($_GET['company_selected'])) 
                              @if($_GET['company_selected'] == 0)
                                 <option value="0" selected>All</option>
                              @else
                                 <option value="0">All</option>
                              @endif
                              @else
                              <option value="0">All</option>
                              @endif
                              @foreach($company as $row)
                                 @if( isset($_GET['date_from']))
                                    @if(  $_GET['company_selected'] == $row->company_id )
                                    <option value="{{ $row->company_id }}" selected>{{ $row->company }}</option>
                                    @else
                                    <option value="{{ $row->company_id }}">{{ $row->company }}</option>
                                    @endif
                                 @else
                                    <option value="{{ $row->company_id }}">{{ $row->company }}</option>
                                 @endif
                                    
                              @endforeach
                           </select>
                     </div>
                  @else
                     <input type="hidden" value="{{ session('employee')->dept_id }}" name="deparment" />
                  @endif
               </div>
               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Date From</label>
                     <input type="date" class="form-control" id="date_from" value="<?php echo (isset($_GET['date_from'])) ? $_GET['date_from'] : date('Y-m-d') ?>" name="date_from" required>
                  </div>
               </div>
               <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                     <label class="control-label">Date To</label>
                     <input type="date" class="form-control" id="date_to" value="<?php echo (isset($_GET['date_to'])) ? $_GET['date_to'] : date('Y-m-d',strtotime(date('Y-m-d').' + 15 days')) ?>" name="date_to" required>
                  </div>
               </div>
               <div class="col-lg-2 col-sm-12">
                  <div class="form-group">
                     <label class="control-label" style="visibility: hidden">Button</label>
                     @include('button_component.search_button', ['margin_top' => "16.5"])
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>

   <hr>
   <form class="form-material" action="{{ url('/save_dtr') }}" method="post">
      @csrf
      <div class="card">
         <div class="card-body">
            <div class="table-responsive m-t-40">
               <table id="dtr_list" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                     <tr>
                           <th class="text-center align-middle" rowspan="2">
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              ID
                           </th>
                           <th class="text-center align-middle" rowspan="2" style="width: 300px">
                              Name
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              Date
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              Time In
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              Time out
                           </th>
                           <th class="text-center" colspan="3">
                              Normal
                           </th>
                           <th class="text-center" colspan="2">
                              Holiday
                           </th>
                           <th class="text-center" colspan="2">
                              Sunday
                           </th>
                           <th class="text-center">
                              Leave
                           </th>
                           <th class="text-center">
                              Late
                           </th>
                           <th class="text-center">
                              Undertime
                           </th>
                           <th class="text-center">
                              Absenteeism
                           </th>
                           <th class="text-center" colspan="3">
                              Night Diff
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              Remarks
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              Sched In
                           </th>
                           <th class="text-center align-middle" rowspan="2">
                              Sched Out
                           </th>
                           <th class="text-center align-middle" rowspan="2" hidden>
                              np
                           </th>
                           <th class="text-center align-middle" rowspan="2" hidden>
                              ot
                           </th>
                           <th class="text-center align-middle" rowspan="2" hidden>
                              total_hrs
                           </th>
                     </tr>
                     <tr>
                           
                           <th class="text-center">
                              Hour
                           </th>
                           <th class="text-center">
                              Day
                           </th>
                           <th class="text-center">
                              Overtime
                           </th>
                           <th class="text-center">
                              Hour
                           </th>
                           <th class="text-center">
                              Overtime
                           </th>
                           <th class="text-center">
                              Hour
                           </th>
                           <th class="text-center">
                              Overtime
                           </th>
                           <th class="text-center">
                              Hour
                           </th>
                           <th class="text-center">
                              Minute
                           </th>
                           <th class="text-center">
                              Hour
                           </th>
                           <th class="text-center">
                              Hour
                           </th>
                           <th class="text-center">
                              Normal
                           </th>
                           <th class="text-center">
                              Sun/Special
                           </th>
                           <th class="text-center">
                              Holiday
                           </th>
                     </tr>
                  </thead>
                  <tbody id="list_body" name="list">
                     
                     @if($dtr_list)
                        
                        {{-- Normal user --}}
                        @if(session('user')->employee_type_id == 5)
                           @foreach($dtr_list as $list)
                              @if(session('user')->emp_id == $list->emp_id)
                                 <tr>
                                    <td></td>
                                    <td>{{$list->employee_number}}</td>
                                    <td>{{$list->emp_name}}</td>
                                    <td>{{date('M d, Y', strtotime($list->dtr_date))}}</td>
                                    <td>{{$list->in_am}}</td>
                                    <td>{{$list->out_pm}}</td>
                                    <td>{{$list->normal_hour}}</td>
                                    <td>{{$list->normal_day}}</td>
                                    <td>{{$list->normal_ot}}</td>
                                    <td>{{$list->holiday_hour}}</td>
                                    <td>{{$list->holiday_ot}}</td>
                                    <td>{{$list->sunday_hour}}</td>
                                    <td>{{$list->sunday_ot}}</td>
                                    <td>{{$list->leave}}</td>
                                    <td>{{$list->late}}</td>
                                    <td>{{$list->undertime}}</td>
                                    <td>{{$list->absent}}</td>
                                    <td>{{$list->night_sunday}}</td>
                                    <td>{{$list->night_special}}</td>
                                    <td>{{$list->night_regular}}</td>
                                    <td>{{$list->att_remarks}}</td>
                                    <td>{{$list->sched_in}}</td>
                                    <td>{{$list->sched_out}}</td>
                                    <td></td>
                                    <td></td>
                                 </tr>
                              @endif
                           @endforeach
                        {{-- sub admin or higher position --}}
                        @else
                           <?php $ctr = 0;  ?>
                           @foreach($dtr_list as $list)
                              <tr>
                                 <td class="align-middle text-center">
                                    <a href="" data-toggle="modal" data-target="#entry_modal" onclick="ShowModal({{$ctr}} )">
                                       <span class="fas fa-fw fa-keyboard"></span> Edit
                                    </a>
                                    
                                 </td>
                                 <td class="align-middle">{{$list->employee_number}}</td>
                                 <td class="align-middle">{{$list->emp_name}}</td>
                                 <td class="align-middle">{{date('m/d/Y', strtotime($list->dtr_date))}}</td>
                                 <td class="align-middle text-center">{{$list->in_am}}</td>
                                 <td class="align-middle text-center">{{$list->out_pm}}</td>
                                 <td class="align-middle text-center">{{$list->normal_hour}}</td>
                                 <td class="align-middle text-center">{{$list->normal_day}}</td>
                                 <td class="align-middle text-center">{{$list->normal_ot}}</td>
                                 <td class="align-middle text-center">{{$list->holiday_hour}}</td>
                                 <td class="align-middle text-center">{{$list->holiday_ot}}</td>
                                 <td class="align-middle text-center">{{$list->sunday_hour}}</td>
                                 <td class="align-middle text-center">{{$list->sunday_ot}}</td>
                                 <td class="align-middle text-center">{{$list->leave}}</td>
                                 <td class="align-middle text-center">{{$list->late}}</td>
                                 <td class="align-middle text-center">{{$list->undertime}}</td>
                                 <td class="align-middle text-center">{{$list->absent}}</td>
                                 <td class="align-middle text-center">{{$list->night_sunday}}</td>
                                 <td class="align-middle text-center">{{$list->night_special}}</td>
                                 <td class="align-middle text-center">{{$list->night_regular}}</td>
                                 <td class="align-middle text-center">{{$list->att_remarks}}</td>
                                 <td class="align-middle text-center">{{$list->sched_in}}</td>
                                 <td class="align-middle text-center">{{$list->sched_out}}</td>
                                 <td class="align-middle" name="np" hidden>{{$list->np_hours}}</td>
                                 <td class="align-middle" name="ot" hidden>{{$list->ot_hours}}</td>
                                 <td class="align-middle" hidden>{{$list->total_hours_worked}}</td>
                              </tr>
                              <?php $ctr = $ctr + 1; ?>
                           @endforeach
                        @endif
                     @else
                        <tr>
                           <td class="text-center" colspan="24">
                              No Data
                           </td>
                        </tr>
                     @endif
                  </tbody>
               </table>
            </div>
            {{-- @if(session('user')->employee_type_id != 5) --}}
               <div class="row pt-3 no-gutters align-items-center">
                  <div class="col text-right">
                     <button class="btn btn-sm btn-dark" type="button" onclick="print_dtr_list()">
                        <i class="fa-solid fa-print"></i> Print
                     </button>
                  </div>
               </div>
            {{-- @endif --}}
         </div>
      </div>
   </form>

   <hr>
</div>
<!-- /.container-fluid -->
{{-- UPDATE MODAL --}}
<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">UPDATE MODAL</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">ID</label>
                           <input type="text" id="employee_number" name="employee_number" class="form-control" required readonly>
                        </div>
                     </div>
                     <!--/span-->
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Name</label>
                           <input class="form-control" type="text" id="emp_name" name="emp_name" readonly>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Sched Time In</label>
                           <input type="time" id="sched_time_in" name="sched_time_in"  class="form-control" readonly>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Sched Time Out</label>
                           <input type="time" id="sched_time_out" name="sched_time_out"  class="form-control" readonly>
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Date</label>
                           <input type="date" id="dtr_date" name="dtr_date"  class="form-control" required readonly>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group has-success">
                           <label class="control-label">Total Hours</label>
                           <input type="number" id="total_hours_worked" name="total_hours_worked"  class="form-control" readonly>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Time In</label>
                           <input type="time" id="in_am" name="in_am" onchange="ComputeTime()"  class="form-control">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Time Out</label>
                           <input type="time" id="out_pm" name="out_pm"  onchange="ComputeTime()" class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>
                  
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Late</label>
                           <input type="number" id="late" min="-9999999" name="late"  class="form-control">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group has-danger">
                           <label class="control-label">Undertime</label>
                           <input type="number" id="undertime" min="-9999999" name="undertime"  class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group has-warning">
                           <label class="control-label">Overtime</label>
                           <input type="number" id="ot_hours" min="-9999999" name="ot_hours"  class="form-control">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group has-warning">
                           <label class="control-label">Night Premium</label>
                           <input type="number" id="np_hours" min="-9999999" name="np_hours"  class="form-control">
                        </div>
                     </div>
                     <!--/span-->
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-warning">
                           <label class="control-label">Remakrs</label>
                           <input type="text" id="att_remarks" name="att_remarks"  class="form-control">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="button" class="btn btn-sm btn-primary ml-2" onclick="UpdateDTR()"> <i class="fa fa-plus-circle"></i> UPDATE</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END UPDATE MODAL --}}
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

  $( document ).ready(function() {
      $('#dtr_list').DataTable();
  });

  function print_dtr_list(){
          var myWindow = window.open("{{ url('/print_dtr_list') }}", "myWindow", 'width=1500,height=800');
  }
  function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }
  @if(session('user')->employee_type_id == 1)
    function ShowModal(index){
      clearFields();
      var row_val = $('#list_body').find('tr');

      document.getElementById("employee_number").value =  $('#dtr_list').DataTable().cell(index,1).data();
      document.getElementById("emp_name").value = $('#dtr_list').DataTable().cell(index,2).data();
      document.getElementById("dtr_date").value = formatDate($('#dtr_list').DataTable().cell(index,3).data());
      document.getElementById("sched_time_in").value = $('#dtr_list').DataTable().cell(index,21).data();
      document.getElementById("sched_time_out").value = $('#dtr_list').DataTable().cell(index,22).data();
      document.getElementById("total_hours_worked").value = $('#dtr_list').DataTable().cell(index,25).data();
      document.getElementById("in_am").value = $('#dtr_list').DataTable().cell(index,4).data();
      document.getElementById("out_pm").value = $('#dtr_list').DataTable().cell(index,5).data();
      document.getElementById("late").value = $('#dtr_list').DataTable().cell(index,14).data();
      document.getElementById("undertime").value = $('#dtr_list').DataTable().cell(index,15).data();
      document.getElementById("ot_hours").value = $('#dtr_list').DataTable().cell(index,24).data();
      document.getElementById("np_hours").value = $('#dtr_list').DataTable().cell(index,23).data();
      document.getElementById("att_remarks").value = $('#dtr_list').DataTable().cell(index,20).data();

     

    }

    function clearFields(){
      document.getElementById("employee_number").value = "";
      document.getElementById("emp_name").value = "";
      document.getElementById("sched_time_in").value = "";
      document.getElementById("sched_time_out").value = "";
      document.getElementById("dtr_date").value = "";
      document.getElementById("total_hours_worked").value = 0;
      document.getElementById("in_am").value = "";
      document.getElementById("out_pm").value = "";
      document.getElementById("late").value = 0;
      document.getElementById("undertime").value = 0;
      document.getElementById("ot_hours").value = 0;
      document.getElementById("np_hours").value = 0;
      document.getElementById("att_remarks").value = "";
    }

    function ComputeTime() {
        var number_of_hours = 0;
        var row_val = $('#list_body').find('tr');

        var start ="00:00";
        var end ="00:00";
        var timestart = "00:00";
        var timeend = "00:00";
        try {
           start = document.getElementById("in_am").value;
           end = document.getElementById("out_pm").value;
           timestart = document.getElementById("sched_time_in").value;
           timeend = document.getElementById("sched_time_out").value;
        } catch(e) {

        }

        //get late hours
        try {
           var first_date = "01/01/2019 " + timestart + ":00"
           var second_date = "01/01/2019 " + start + ":00"
           if(Date.parse(first_date) < Date.parse(second_date)){
              get_diff(0,timestart,start,'late');
           }
           else
              document.getElementById('late').value = number_of_hours;
        } catch(e){}
        //get undertime hours
        try {
           var first_date = "01/01/2019 " + timeend + ":00"
           var second_date = "01/01/2019 " + end + ":00"
           if(Date.parse(first_date) > Date.parse(second_date)){
              get_diff(0,end,timeend,'undertime');
           }
           else
              document.getElementById('undertime').value = number_of_hours;
        } catch(e){}
        
        //get working hours
        get_diff(0,start,end,'total_hours_worked');
     }

     function get_diff(index,start_time,end_time,cell_name) {
        var number_of_hours = 0;
        var row_val = $('#list_body').find('tr');

        var start ="00:00";
        var end ="00:00";
        try {
           start = start_time;
           end = end_time;
        } catch(e) {

        }

        try {
           start = start.split(":");
            end = end.split(":");
            var startDate = new Date(0, 0, 0, start[0], start[1], 0);
            var endDate = new Date(0, 0, 0, end[0], end[1], 0);
            var diff = endDate.getTime() - startDate.getTime();
            var hours = Math.floor(diff / 1000 / 60 / 60);
            diff -= hours * 1000 * 60 * 60;
            var minutes = Math.floor(diff / 1000 / 60);
            // if(hours!=0){
            //   hours = hours - 1;
            // }
            
            minutes = minutes / 60;

            // If using time pickers with 24 hours format, add the below line get exact hours
            if (hours < 0){
               hours = hours + 24;
            }
           if (minutes==0) {
              number_of_hours = hours;
           }
           else
            number_of_hours = parseFloat( (hours + minutes) ).toFixed(2);
        } catch(e) {
           
        }
        if (number_of_hours > 5) {
           number_of_hours = parseFloat(number_of_hours) - parseFloat(1);
        }
        if(cell_name=='total_hours_worked'){
           if(number_of_hours > 8){
              document.getElementById("total_hours_worked").value = 8;
           }
           else{
              document.getElementById("total_hours_worked").value = number_of_hours;
           }
        }
        else{
            document.getElementById(cell_name).value = number_of_hours;
        }
        
     }

     function UpdateDTR(){
      $.ajax({
           type:'POST',
           url:"{{ url('/update_dtr') }}",
           headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           data:{
                  employee_number:document.getElementById("employee_number").value, 
                  emp_name:document.getElementById("emp_name").value, 
                  sched_time_in:document.getElementById("emp_name").value,
                  sched_time_out:document.getElementById("sched_time_out").value,
                  dtr_date:document.getElementById("dtr_date").value,
                  total_hours_worked:document.getElementById("total_hours_worked").value,
                  in_am:document.getElementById("in_am").value,
                  out_pm:document.getElementById("out_pm").value,
                  late:document.getElementById("late").value,
                  undertime:document.getElementById("undertime").value,
                  ot_hours:document.getElementById("ot_hours").value,
                  np_hours:document.getElementById("np_hours").value,
                  att_remarks:document.getElementById("att_remarks").value
                },
           success:function(data){

              alert(data.success);
              $('#btnSearch').click();
           }

        });
     }
  @endif

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}