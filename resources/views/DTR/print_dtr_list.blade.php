@extends('Templates.print_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Payroll')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

    @include('Templates.alert_message')

    <table cellpadding='0' cellspacing='0' style="padding-left: 60px">
      <tr>
         <td>
            <img src="{{ asset('public/img/abaca_logo.png') }}" alt="Enviro"  height="100" onload="fixPNG(this)" border="0">
         </td>
         <td  align="left">
            <p align="left" style="padding-left: 20px">
               Address:&nbsp;<br>
               Phone:&nbsp;<br>
               Email:&nbsp;<br>
               Website:&nbsp;
            </p>
         </td>
      </tr>
    </table>

    <hr>

    <table id="example23" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
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
                Time<br>In
            </th>
            <th class="text-center align-middle" rowspan="2">
                Time<br>out
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
                UT
            </th>
            <th class="text-center">
                Absent
            </th>
            <th class="text-center" colspan="3">
                Night Diff
            </th>
            <th class="text-center align-middle" rowspan="2">
                Remarks
            </th>
        </tr>
        <tr>
            
            <th class="text-center">
                hr
            </th>
            <th class="text-center">
                Day
            </th>
            <th class="text-center">
                OT
            </th>
            <th class="text-center">
                hr
            </th>
            <th class="text-center">
                OT
            </th>
            <th class="text-center">
                hr
            </th>
            <th class="text-center">
                OT
            </th>
            <th class="text-center">
                hr
            </th>
            <th class="text-center">
                Min
            </th>
            <th class="text-center">
                hr
            </th>
            <th class="text-center">
                hr
            </th>
            <th class="text-center">
                Sun
            </th>
            <th class="text-center">
                Spcl
            </th>
            <th class="text-center">
                Reg
            </th>
        </tr>
        </thead>
        <tbody id="list_body" name="list">
        @if(session('dtr_list_print'))
            {{-- Normal user --}}
            @if(session('user')->employee_type_id == 5)
                @foreach(session('dtr_list_print') as $list)
                    @if(session('user')->emp_id == $list->emp_id)
                    <tr>
                        <td>
                        {{$list->employee_number}}
                        </td>
                        <td>
                        {{$list->emp_name}}
                        </td>
                        <td>
                        {{$list->dtr_date}}
                        </td>
                        <td>
                        {{$list->in_am}}
                        </td>
                        <td>
                        {{$list->out_pm}}
                        </td>
                        <td>
                        {{$list->normal_hour}}
                        </td>
                        <td>
                        {{$list->normal_day}}
                        </td>
                        <td>
                        {{$list->normal_ot}}
                        </td>
                        <td>
                        {{$list->holiday_hour}}
                        </td>
                        <td>
                        {{$list->holiday_ot}}
                        </td>
                        <td>
                        {{$list->sunday_hour}}
                        </td>
                        <td>
                        {{$list->sunday_ot}}
                        </td>
                        <td>
                        {{$list->leave}}
                        </td>
                        <td>
                        {{$list->late}}
                        </td>
                        <td>
                        {{$list->undertime}}
                        </td>
                        <td>
                        {{$list->absent}}
                        </td>
                        <td>
                        {{$list->night_sunday}}
                        </td>
                        <td>
                        {{$list->night_special}}
                        </td>
                        <td>
                        {{$list->night_regular}}
                        </td>
                        <td>
                        
                        </td>
                    </tr>
                    @endif
                @endforeach
            {{-- sub admin or higher position --}}
            @else
                @foreach(session('dtr_list_print') as $list)
                    <tr>
                    <td>
                        {{$list->employee_number}}
                        </td>
                    <td>
                    {{$list->emp_name}}
                    </td>
                    <td>
                    {{$list->dtr_date}}
                    </td>
                    <td>
                    {{$list->in_am}}
                    </td>
                    <td>
                    {{$list->out_pm}}
                    </td>
                    <td>
                    {{$list->normal_hour}}
                    </td>
                    <td>
                    {{$list->normal_day}}
                    </td>
                    <td>
                    {{$list->normal_ot}}
                    </td>
                    <td>
                    {{$list->holiday_hour}}
                    </td>
                    <td>
                    {{$list->holiday_ot}}
                    </td>
                    <td>
                    {{$list->sunday_hour}}
                    </td>
                    <td>
                    {{$list->sunday_ot}}
                    </td>
                    <td>
                    {{$list->leave}}
                    </td>
                    <td>
                        {{$list->late}}
                    </td>
                    <td>
                    {{$list->undertime}}
                    </td>
                    <td>
                    {{$list->absent}}
                    </td>
                    <td>
                    {{$list->night_sunday}}
                    </td>
                    <td>
                    {{$list->night_special}}
                    </td>
                    <td>
                    {{$list->night_regular}}
                    </td>
                    <td>
                    
                    </td>
                    </tr>
                @endforeach
            @endif
        @else
            <tr>
                <td class="text-center" colspan="20">
                    No Data
                </td>
            </tr>
        @endif
        </tbody>
    </table>

    <hr>

</div>

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="{{ asset('uidesign/js/custom/custom_mat.js') }}"></script>
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
<!-- <script src="{{ asset('uidesign/js/custom/department.js') }}"></script> -->
<script>
   $( document ).ready(function() {
      var css = '@page { size: landscape; }',
          head = document.head || document.getElementsByTagName('head')[0],
          style = document.createElement('style');

      style.type = 'text/css';
      style.media = 'print';

      if (style.styleSheet){
        style.styleSheet.cssText = css;
      } else {
        style.appendChild(document.createTextNode(css));
      }

      head.appendChild(style);
   });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}