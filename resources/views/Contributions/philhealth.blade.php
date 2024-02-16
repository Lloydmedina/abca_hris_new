@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','PHILHEALTH Contributions')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
          <form action="{{ route('philhealth-contributions') }}" method="get">
              <div class="row">

                <div class="col-lg-4 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Company</label>
                        <select id="company" name="company" class="form-control custom-select" >
                            <option value="" selected disabled>Select Company</option>
                            @foreach($companies as $row)
                                @if($company_id)
                                    @if($company_id == $row->company_id )
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
                </div>

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
                    <h4 class="card-title">PHILHEALTH Contributions</h4>
                </div>
                <div class="col-lg-6 col-sm-12 text-lg-right">
                    
                </div>
            </div>

            <div class="table-responsive m-t-40">
                <table id="ph_contri_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                   <thead>
                      <tr>
                         <th class="">Philhealth Number</th>
                         <th class="">Employee</th>
                         <th class="text-right">Employee Share</th>
                         <th class="text-right">Employer Share</th>
                         <th class="text-right">Basic</th>
                         <th class="text-right">Total</th>
                      </tr>
                      <tfoot>
                        @php
                            $total_PH_EmployeeShare = $total_PH_EmployerShare = $total_Basic = $total_total = 0;
                        @endphp
                        @if(count($philhealthContributions) > 0 )
                            
                            @foreach($philhealthContributions as $row)

                                @php
                                    $total_PH_EmployeeShare += $row->PH_EmployeeShare;
                                    $total_PH_EmployerShare += $row->PH_EmployerShare;
                                    $total_Basic += $row->Basic;
                                    $total_total += $row->total;
                                @endphp

                            @endforeach
                        @endif
                         <th class="">Total</th>
                         <th class=""></th>
                         <th class="text-right">{{ number_format($total_PH_EmployeeShare, 2) }}</th>
                         <th class="text-right">{{ number_format($total_PH_EmployerShare, 2) }}</th>
                         <th class="text-right">{{ number_format($total_Basic, 2) }}</th>
                         <th class="text-right">{{ number_format($total_total, 2) }}</th>
                      </tfoot>
                   </thead>
                   <tbody id="list_body" name="list">
                    @if(count($philhealthContributions) > 0 )
                        @foreach($philhealthContributions as $row)
                            <tr>
                                <td>{{ $row->philhealth_no }}</td>
                                <td>{{ $row->emp_name }}</td>
                                <td class="text-right">{{ number_format($row->PH_EmployeeShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->PH_EmployerShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->Basic, 2) }}</td>
                                <td class="text-right">{{ number_format($row->total, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                           <td colspan="13" class="text-center">No record found.</td>
                        </tr>
                    @endif
                   </tbody>
                </table>
             </div>


        </div>
   </div>

   <hr>

</div>



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
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')

<script>
   $( document ).ready(function() {


   });
    
    $('#ph_contri_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        "displayLength": 50,
        order: [[1, 'asc']]
    });

    $('.buttons-csv, .buttons-excel, .buttons-pdf').addClass('btn btn-sm btn-dark mr-1');
    // $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-dark mr-1');
    </script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}