@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','1601C')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
   
   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
          <form action="{{ route('1601-c') }}" method="get">
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
                    <h4 class="card-title">1601C</h4>
                </div>
                <div class="col-lg-6 col-sm-12 text-lg-right">
                    
                </div>
            </div>

            <div class="table-responsive m-t-40">
                <table id="1601c_contri_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                   <thead>
                        <tr>
                            <th rowspan="2">TIN</th>
                            <th rowspan="2">Employee</th>
                            <th rowspan="2">Date of Birth</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Position</th>
                            <th rowspan="2">Area of Assignment</th>
                            <th colspan="2" class="text-center">Gross Compensation</th>
                            <th rowspan="2" class="text-center">13th Month Pay</th>
                            <th colspan="1" class="text-center">Others</th>
                            <th colspan="1" class="text-center">Meal</th>
                            <th colspan="1" class="text-center">Service Charge</th>
                            <th rowspan="2" class="text-center">Wtax</th>
                            <th colspan="3" class="text-center">HDFM Details</th>
                            <th colspan="5" class="text-center">SSS Details</th>
                            <th colspan="3" class="text-center">Philhealth Details</th>

                            <th rowspan="2" class="text-center">Other Deductions</th>
                            <th rowspan="2" class="text-center">Actual Compensation Received</th>
                        </tr>
                        <tr>
                            
                            <th class="text-right">Daily Rate</th>
                            <th class="text-right">Monthly Rate</th>
                            {{-- <th class="text-right">13th Month Pay</th> --}}
                            <th class="text-right">Overtime Pay</th>
                            <th class="text-right">Meal allowances</th>
                            <th class="text-right"></th>
                            <th class="text-center">HDMF Number</th>
                            <th class="text-right">Employer Share</th>
                            <th class="text-right">Employee Share</th>

                            <th class="text-center">SSS Number</th>
                            <th class="text-right">SSS Employer Share</th>
                            <th class="text-right">SSS Employee Share</th>
                            <th class="text-right">WISP Employer Share</th>
                            <th class="text-right">WISP Employee Share</th>
                            
                            <th class="text-center">Philhealth Number</th>
                            <th class="text-right">Philhealth Employer Share</th>
                            <th class="text-right">Philhealth Employee Share</th>

                        </tr>

                        <tfoot>
                        @php
                            $total_daily_rate = $total_monthly_rate = $total_13th_rate = $total_ot = $total_allowances = $total_sc = $total_wtax = $total_hdmf_emplyr = $total_hdmf_emp = $total_sss_emplyr = $total_sss_emp = $total_wisp_emplyr = $total_wisp_emp = $total_ph_emplyr = $total_ph_emp = $total_deductions = $total_actual_comp = 0;
                        @endphp
                        @if(count($_1601c) > 0 )
                            
                            @foreach($_1601c as $row)

                                @php
                                    $total_daily_rate += $row->DailyRate;
                                    $total_monthly_rate += $row->MonthlyRate;
                                    $total_13th_rate += $row->{'13thMonthPay'};
                                    $total_ot += $row->OvertimePay;
                                    $total_allowances += $row->MealAllowances;
                                    $total_sc += $row->ServiceCharge;
                                    $total_wtax += $row->WTAX;
                                    $total_hdmf_emplyr += $row->EmployerShare;
                                    $total_hdmf_emp += $row->EmployeeShare;
                                    $total_sss_emplyr += $row->SSS_EmployerShare;
                                    $total_sss_emp += $row->SSS_EmployeeShare;
                                    $total_wisp_emplyr += $row->wisp_EmployerShare;
                                    $total_wisp_emp += $row->wisp_EmployeeShare;
                                    $total_ph_emplyr += $row->PH_EmployerShare;
                                    $total_ph_emp += $row->PH_EmployeeShare;
                                    $total_deductions += $row->OtherDeductions;
                                    $total_actual_comp += $row->ActualCompensation;
                                @endphp

                            @endforeach
                        @endif

                            <th class="text-left">Total</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right">{{ number_format($total_daily_rate, 2) }}</th>
                            <th class="text-right">{{ number_format($total_monthly_rate, 2) }}</th>
                            <th class="text-right">{{ number_format($total_13th_rate, 2) }}</th>
                            <th class="text-right">{{ number_format($total_ot, 2) }}</th>
                            <th class="text-right">{{ number_format($total_allowances, 2) }}</th>
                            <th class="text-right">{{ number_format($total_sc, 2) }}</th>
                            <th class="text-right">{{ number_format($total_wtax, 2) }}</th>
                            <th class="text-right"></th>
                            <th class="text-right">{{ number_format($total_hdmf_emplyr, 2) }}</th>
                            <th class="text-right">{{ number_format($total_hdmf_emp, 2) }}</th>
                            <th class="text-right"></th>
                            <th class="text-right">{{ number_format($total_sss_emplyr, 2) }}</th>
                            <th class="text-right">{{ number_format($total_sss_emp, 2) }}</th>
                            <th class="text-right">{{ number_format($total_wisp_emplyr, 2) }}</th>
                            <th class="text-right">{{ number_format($total_wisp_emp, 2) }}</th>
                            <th class="text-right"></th>
                            <th class="text-right">{{ number_format($total_ph_emplyr, 2) }}</th>
                            <th class="text-right">{{ number_format($total_ph_emp, 2) }}</th>
                            <th class="text-right">{{ number_format($total_deductions, 2) }}</th>
                            <th class="text-right">{{ number_format($total_actual_comp, 2) }}</th>

                        </tfoot>
                   </thead>
                   <tbody id="list_body" name="list">
                    @if(count($_1601c) > 0 )
                        @foreach($_1601c as $row)
                            <tr>
                                <td>{{ $row->TIN_Empl }}</td>
                                <td>{{ $row->Name_Empl }}</td>
                                <td>{{ $row->BirthDate_Empl }}</td>
                                <td>{{ $row->civilStatus }}</td>
                                <td>{{ $row->Position_Empl }}</td>
                                <td>{{ $row->Department_Empl }}</td>

                                <td class="text-right">{{ number_format($row->DailyRate, 2) }}</td>
                                <td class="text-right">{{ number_format($row->MonthlyRate, 2) }}</td>

                                <td class="text-right">{{ number_format($row->{'13thMonthPay'}, 2) }}</td>

                                <td class="text-right">{{ number_format($row->OvertimePay, 2) }}</td>
                                <td class="text-right">{{ number_format($row->MealAllowances, 2) }}</td>

                                <td class="text-right">{{ number_format($row->ServiceCharge, 2) }}</td>
                                <td class="text-right">{{ number_format($row->WTAX, 2) }}</td>
                                <td>{{ $row->HDMF_NO }}</td>
                                <td class="text-right">{{ number_format($row->EmployerShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->EmployeeShare, 2) }}</td>

                                <td>{{ $row->SSS_Empl }}</td>
                                <td class="text-right">{{ number_format($row->SSS_EmployerShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->SSS_EmployeeShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->wisp_EmployerShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->wisp_EmployeeShare, 2) }}</td>

                                <td>{{ $row->philhealth_no }}</td>
                                <td class="text-right">{{ number_format($row->PH_EmployerShare, 2) }}</td>
                                <td class="text-right">{{ number_format($row->PH_EmployeeShare, 2) }}</td>

                                <td class="text-right">{{ number_format($row->OtherDeductions, 2) }}</td>
                                <td class="text-right">{{ number_format($row->ActualCompensation, 2) }}</td>
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
        $('#sidebarToggle').click();
   });
    
    $('#1601c_contri_table').DataTable({
        dom: 'Bfrtip',
        // buttons: [
        //     'csv', 'excel', 'pdf',
        //     // 'copy', 'csv', 'excel', 'pdf', 'print',
        // ],
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