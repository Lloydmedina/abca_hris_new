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
   <table id="example23" class="display nowrap table table-sm table-hover table-bordered" cellspacing="0" width="100%" style="font-size: 8px;font-weight: bold;padding-left: 20px">
      <thead style="background-color:  rgb(135,206,250);">

         <tr>
                                       <th class="text-center align-middle">No</th>
                                       <th class="text-center align-middle">Account No</th>
                                       <th class="text-center align-middle">Employee Name</th>
                                       <th class="text-center align-middle">Dept.</th>
                                       <th class="text-center align-middle">Daily<br>Rate</th>
                                       <th class="text-center align-middle">Monthly<br>Rate</th>
                                       <th class="text-center align-middle">Semi Monthly<br>Rate</th>
                                       <th class="text-center align-middle">Hourly<br>Rate</th>
                                       <th class="text-center align-middle">Minute<br>Rate</th>
                                       <th class="text-center align-middle">OT<br>Rate</th>
                                       <th class="text-center align-middle">Holiday<br>Rate</th>
                                       <th class="text-center align-middle">Special/Sunday<br>Rate</th>
                                       <th class="text-center align-middle">Night Premium<br>Rate</th>
                                       <th class="text-center align-middle">Standard<br>Hour</th>
                                       <th class="text-center align-middle">Absent<br>Hour</th>
                                       <th class="text-center align-middle">Under Time<br>Hour</th>
                                       <th class="text-center align-middle">Leave Hour</th>
                                       <th class="text-center align-middle">Actual<br>Hour</th>
                                       <th class="text-center align-middle">Late<br>Mins.</th>
                                       <th class="text-center align-middle">OT<br>Hours</th>
                                       <th class="text-center align-middle">Sun/Sp Hol.<br>Hours</th>
                                       <th class="text-center align-middle">Sun/Sp Hol. OT<br>Hours</th>
                                       <th class="text-center align-middle">Holiday<br>Hours</th>
                                       <th class="text-center align-middle">Hol. OT<br>Hours</th>
                                       <th class="text-center align-middle">NP<br>Hours</th>
                                       <th class="text-center align-middle">NP Hol.<br>Hours</th>
                                       <th class="text-center align-middle">NP Sun/Sp Hol.<br>Hours</th>
                                       <th class="text-center align-middle">Paternity Leave<br>Hours</th>
                                       <th class="text-center align-middle">Service Incentive Leave(SIL)<br>Hour</th>
                                       <th class="text-center align-middle">Total Manhour</th>
                                       <th class="text-center align-middle">Rice<br>Allowance</th>
                                       <th class="text-center align-middle">Clothing<br>Allowance</th>
                                       <th class="text-center align-middle">Monitized VL Credits</th>
                                       <th class="text-center align-middle">Medical Cash<br>Allowance<br>Dependent</th>
                                       <th class="text-center align-middle">Med. Health Care Needs</th>
                                       <th class="text-center align-middle">Laundry<br>Allowance</th>
                                       <th class="text-center align-middle">Deminis/Benefits<br>Allowances</th>
                                       <th class="text-center align-middle">COLA</th>
                                       <th class="text-center align-middle">Communication<br>Allowance</th>
                                       <th class="text-center align-middle">Basic Pay</th>
                                       <th class="text-center align-middle">OT<br>Pay</th>
                                       <th class="text-center align-middle">Sun/Sp Hol. <br>Pay</th>
                                       <th class="text-center align-middle">Sun/Sp Hol. <br>OT Pay</th>
                                       <th class="text-center align-middle">Holiday<br>Pay</th>
                                       <th class="text-center align-middle">Holiday<br>OT Pay</th>
                                       <th class="text-center align-middle">NP Pay</th>
                                       <th class="text-center align-middle">Holiday Np<br>Pay</th>
                                       <th class="text-center align-middle">Sun/Spcl. Np<br>Pay</th>
                                       <th class="text-center align-middle">Paternity<br>Leave<br>Pay</th>
                                       <th class="text-center align-middle">Service<br>Incentive<br>Leave (SIL)<br>Pay</th>
                                       <th class="text-center align-middle">Adjustment (+)</th>
                                       <th class="text-center align-middle">Less Late</th>
                                       <th class="text-center align-middle">Gross Pay</th>
                                       <th class="text-center align-middle">Taxable<br>Compensation</th>
                                       <th class="text-center align-middle">SSS</th>
                                       <th class="text-center align-middle">PhilHealth</th>
                                       <th class="text-center align-middle">PAG-IBIG</th>
                                       <th class="text-center align-middle">w/tax</th>
                                       <th class="text-center align-middle">Under witheld</th>
                                       <th class="text-center align-middle">Other Deductions</th>
                                       <th class="text-center align-middle">Total Tax</th>
                                       <th class="text-center align-middle">SSS<br>Loan</th>
                                       <th class="text-center align-middle">PAG-IBIG<br>Loan</th>
                                       <th class="text-center align-middle">Adjustment (-)</th>
                                       <th class="text-center align-middle">Cash<br>Advance</th>
                                       <th class="text-center align-middle">Total<br>Deduction</th>
                                       <th class="text-center align-middle">Net Pay</th>
                                       <th class="text-center align-middle">SSS<br>ER</th>
                                       <th class="text-center align-middle">PhilHealth<br>ER</th>
                                       <th class="text-center align-middle">PAG-IBIG<br>ER</th>
                                       <th class="text-center align-middle">W/Tax<br>ER</th>
                                       <th class="text-center align-middle">Total ER</th>
                                    </tr>
      </thead>
      <tbody id="list_body" name="list" >
         <?php 
            $row_cnt=0;
            $total_Wage=0;
            $total_Overtime=0;
            $total_holiday=0;
            $total_special=0;
            $total_leave=0;
            $total_late=0;
            $total_undertime= 0 ;
            $total_gross=0;
            $total_cola=0;
            $total_sss_loan=0;
            $total_pagibig_loan=0;
            $total_cash_advance=0;
            $total_sss=0;
            $total_philhealth=0;
            $total_pagibig=0;
            $total_wtax=0;
            $total_net=0;
         ?>
         @if(session('payroll_list_print'))
            @foreach(session('payroll_list_print') as $data)
            <?php 
               $row_cnt =$row_cnt+1;
                                 // $total_special =$total_special + (double)str_replace(",","",$list->special_holiday);
                                 // $total_leave =$total_leave + (double)str_replace(",","",$list->LeavePay);
                                 // $total_late =$total_late + (double)str_replace(",","",$list->LateDeduction);
                                 // $total_undertime = $total_undertime +  (double)str_replace(",","",$list->UTDeduction);
                                 // $total_gross =$total_gross + (double)str_replace(",","",$list->Gross );
                                 // $total_cola =$total_cola + (double)str_replace(",","",$list->COLA);
                                 // $total_sss_loan =$total_sss_loan + (double)str_replace(",","",$list->SSS_LOAN);
                                 // $total_pagibig_loan =$total_pagibig_loan + (double)str_replace(",","",$list->PAGIBIG_LOAN);
                                 // $total_cash_advance =$total_cash_advance + (double)str_replace(",","",$list->CA );
                                 // $total_sss =$total_sss + (double)str_replace(",","",$list->SSS);
                                 // $total_philhealth =$total_philhealth + (double)ROUND($list->PHILHEALTH,2);
                                 // $total_pagibig =$total_pagibig + (double)str_replace(",","",$list->PAGIBIG);
                                 // $total_wtax =$total_wtax + (double) str_replace(",","",$list->WTax);
                                 // $total_net =$total_net + (double)str_replace(",","",$list->Net);
            ?>
               <tr>
                  <td class="text-center align-middle ">{{ $row_cnt }}</td>
                  <td class="text-center align-middle">{{$data->AccountNo}}</td>
                  <td class="text-left align-middle">{{$data->EmployeeName}}</td>
                  <td class="text-left align-middle">{{$data->Dept}}</td>
                  <td class="text-right align-middle">{{number_format($data->DailyRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->MonthlyRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SemiMonthlyRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HourlyRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->MinRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->RegOTRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SunOtRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->NPRate,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->StandardHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->AbsentHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->UTHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SL_VL,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->ActualHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->LateMin,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->RegOTHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SpclHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SpclOTHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayOTHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->RegularNP,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayNP,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SunNP,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->PaternityLeave,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->ServiceIncentiveLeave,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->TotalManHour,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->RiceAllowance,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->ClothingAllowance,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->MonetizedUnusedLeave,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->MedicalCashAllowanceDependent,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->MedicalAssistance,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->LaundryAllowance,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->Deminimis,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->COLA,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->CommunicationAllowance,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->BasicPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->RegOTPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SunPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SunOTPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayOTPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->NPPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->HolidayNPPay,2)}}</td>
                  <th class="text-right align-middle">{{number_format($data->SunNPPay,2)}}</th>
                  <td class="text-right align-middle">{{number_format($data->PaternityLeavePay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SILPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->AdditionalAdjustment,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->LateDeduction,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->GrossPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->TaxableCompensation,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SSS,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->PhilHealth,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->PAGIBIG,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->WTax,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->UnderWithheld,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->OtherDeductions,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->TotalTax,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SSSLoan,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->PAGIBIGLoan,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->DeductionAdjustment,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->CA,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->TotalDeductions,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->NetPay,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->SSSER,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->PhilHealthER,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->PAGIBIGER,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->WtaxER,2)}}</td>
                  <td class="text-right align-middle">{{number_format($data->TotalER,2)}}</td>
               </tr>
            @endforeach
               
               @if($row_cnt < 10 )
                  @for($i=$row_cnt; $i <= 10; $i++)
                  <tr>
                        <td class="text-center" colspan="71"></td>   
                  </tr>
                  @endfor
               @endif
               
               <tr>
                  <td  colspan="13" style="border-style: none">
                     <p style="padding-left: 20px">Prepared By:</p>
                     <br>
                     <br>
                  </td>
                  <td colspan="13">
                  <p style="padding-left: 20px"> Approved By:</p>
                  <br>
                  <br>
                  </td>
                  <td colspan="45">
                  <p style="padding-left: 20px"> Remarks:</p>
                  <br>
                  <br>
                  </td>
               </tr>
            @else
               <tr>
                  <td class="text-center" colspan="71">
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
      var css = '@page { size: landscape; } @page : left{ margin-left: 0cm; } @page : right{ margin-right: 0cm;}',
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