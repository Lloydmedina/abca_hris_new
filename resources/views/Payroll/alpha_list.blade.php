@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">

@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Alphalist')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 700px">

   @include('Templates.alert_message')

   <form class="form-material" action="{{ url('/payroll_list') }}" method="get">
      <div class="row">
         <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">BIR Alphalist</div>
                        <hr>
                     </div>
                  </div>
                  <div class="row no-gutters align-items-center">

                     <div class="row">
                        
                        <div class="col-3">
                           <label class="form-control-label">Year</label>
                           <input type="number" min="1" max="9999" maxlength="4" class="form-control" name="yr" value="{{ $yr }}" >
                        </div>
                        <div class="col-2 text-right">
                           <button class="btn btn-sm btn-info ml-5" type="submit">
                              <i class="fa fa-search">&nbsp;Search</i>
                           </button>
                        </div>
                     </div>
                  </div>
                  
               </div>
            </div>
         </div>
      </div>
   </form>

   <hr>

   <form class="form-material" action="{{url('/save_payroll')}}" method="post">
      @csrf
      <div class="row pt-3">
         <div class="col-12">
            <div class="card border-left-success shadow h-100 py-2">
               <!-- Tab Items -->
               <ul class="nav nav-tabs profile-tab" role="tablist">
                  <!-- 1604CF Schedule 5 -->
                  <li class="nav-item">
                     <a class="nav-link active show" data-toggle="tab" href="#sched_5" role="tab" aria-selected="true">
                        1604CF Schedule 5
                     </a>
                  </li>
                  <!-- 1604CF Schedule 6 -->
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#sched_6" role="tab" aria-selected="false">
                        1604CF Schedule 6
                     </a>
                  </li>
                  <!-- 1604CF Schedule 7.1 -->
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#sched_7_1" role="tab" aria-selected="false">
                        1604CF Schedule 7.1
                     </a>
                  </li>
                  <!-- 1604CF Schedule 7.2 -->
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#sched_7_2" role="tab" aria-selected="false">
                        1604CF Schedule 7.2
                     </a>
                  </li>
                  <!-- 1604CF Schedule 7.3 -->
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#sched_7_3" role="tab" aria-selected="false">
                        1604CF Schedule 7.3
                     </a>
                  </li>
                  <!-- 1604CF Schedule 7.4 -->
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#sched_7_4" role="tab" aria-selected="false">
                        1604CF Schedule 7.4
                     </a>
                  </li>
                  <!-- 1604CF Schedule 7.5 -->
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#sched_7_5" role="tab" aria-selected="false">
                        1604CF Schedule 7.5
                     </a>
                  </li>
               </ul>
               <!-- Tab panes -->
               <div class="tab-content">
                  <!-- 1604CF Schedule 5 -->
                  <div class="tab-pane active show" id="sched_5" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_5_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">payeeTIN</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">companyName</th>
                                       <th class="text-center align-middle">surName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">statusCode</th>
                                       <th class="text-center align-middle">ATC</th>
                                       <th class="text-center align-middle">income_payment</th>
                                       <th class="text-center align-middle">ewt_rate</th>
                                       <th class="text-center align-middle">tax_amount</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- 1604CF Schedule 6 -->
                  <div class="tab-pane" id="sched_6" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_6_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">employeeTin</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">surName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">ATC</th>
                                       <th class="text-center align-middle">fringeAmount</th>
                                       <th class="text-center align-middle">grossUp</th>
                                       <th class="text-center align-middle">amountWithheld</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- 1604CF Schedule 7.1 -->
                  <div class="tab-pane" id="sched_7_1" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_7_1_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">tinNumber</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">lastName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">address</th>
                                       <th class="text-center align-middle">zipcode</th>
                                       <th class="text-center align-middle">birthday</th>
                                       <th class="text-center align-middle">telNumber</th>
                                       <th class="text-center align-middle">startDate</th>
                                       <th class="text-center align-middle">endDate</th>
                                       <th class="text-center align-middle">grossCompensation</th>
                                       <th class="text-center align-middle">nonTaxable13th_others</th>
                                       <th class="text-center align-middle">nonTaxableDeminimis</th>
                                       <th class="text-center align-middle">SSS_HDMF_Philhealth</th>
                                       <th class="text-center align-middle">nonTaxableSaleries_others</th>
                                       <th class="text-center align-middle">totalNontaxable</th>
                                       <th class="text-center align-middle">taxableBasic</th>
                                       <th class="text-center align-middle">taxable13th_others</th>
                                       <th class="text-center align-middle">taxableSalaries_others</th>
                                       <th class="text-center align-middle">totalTaxableIncome</th>
                                       <th class="text-center align-middle">premiumPaidonHealth</th>
                                       <th class="text-center align-middle">netTaxable</th>
                                       <th class="text-center align-middle">totalTaxdue</th>
                                       <th class="text-center align-middle">amountWithheld</th>
                                       <th class="text-center align-middle">decemberTax</th>
                                       <th class="text-center align-middle">taxRefund</th>
                                       <th class="text-center align-middle">taxAdjusted</th>
                                       <th class="text-center align-middle">substituted</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        
                     </div>
                  </div>

                  <!-- 1604CF Schedule 7.2 -->
                  <div class="tab-pane" id="sched_7_2" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_7_2_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">tinNumber</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">lastName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">nonTaxable13th_others</th>
                                       <th class="text-center align-middle">nonTaxableDeminimis</th>
                                       <th class="text-center align-middle">SSS_HDMF_Philhealth</th>
                                       <th class="text-center align-middle">nonTaxableSaleries_others</th>
                                       <th class="text-center align-middle">totalNontaxable</th>
                                       <th class="text-center align-middle">taxableBasic</th>
                                       <th class="text-center align-middle">taxableSalaries_others</th>
                                       <th class="text-center align-middle">taxableCompensation</th>
                                       <th class="text-center align-middle">grossCompensation</th>
                                       <th class="text-center align-middle">premiumPaidonHealth</th>
                                       <th class="text-center align-middle">netTaxable</th>
                                       <th class="text-center align-middle">totalTaxdue</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- 1604CF Schedule 7.3 -->
                  <div class="tab-pane" id="sched_7_3" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_7_3_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">tinNumber</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">lastName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">address</th>
                                       <th class="text-center align-middle">zipcode</th>
                                       <th class="text-center align-middle">birthday</th>
                                       <th class="text-center align-middle">telNumber</th>
                                       <th class="text-center align-middle">grossCompensation</th>
                                       <th class="text-center align-middle">nonTaxable13th_others</th>
                                       <th class="text-center align-middle">nonTaxableDeminimis</th>
                                       <th class="text-center align-middle">SSS_HDMF_Philhealth</th>
                                       <th class="text-center align-middle">nonTaxableSaleries_others</th>
                                       <th class="text-center align-middle">totalNontaxable</th>
                                       <th class="text-center align-middle">taxableBasic</th>
                                       <th class="text-center align-middle">taxable13th_others</th>
                                       <th class="text-center align-middle">taxableSalaries_others</th>
                                       <th class="text-center align-middle">totalTaxableIncome</th>
                                       <th class="text-center align-middle">premiumPaidonHealth</th>
                                       <th class="text-center align-middle">netTaxable</th>
                                       <th class="text-center align-middle">totalTaxdue</th>
                                       <th class="text-center align-middle">amountWithheld</th>
                                       <th class="text-center align-middle">decemberTax</th>
                                       <th class="text-center align-middle">taxRefund</th>
                                       <th class="text-center align-middle">taxAdjusted</th>
                                       <th class="text-center align-middle">substituted</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- 1604CF Schedule 7.4 -->
                  <div class="tab-pane" id="sched_7_4" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_7_4_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">tinNumber</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">lastName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">address</th>
                                       <th class="text-center align-middle">zipcode</th>
                                       <th class="text-center align-middle">birthday</th>
                                       <th class="text-center align-middle">telNumber</th>
                                       <th class="text-center align-middle">prev_13th_others</th>
                                       <th class="text-center align-middle">prev_deMinimis</th>
                                       <th class="text-center align-middle">prev_SSS_HDMF_Philhealth_Dues</th>
                                       <th class="text-center align-middle">prev_salariesOthers</th>
                                       <th class="text-center align-middle">prev_totalNonTaxable</th>
                                       <th class="text-center align-middle">prev_taxableBasicWage</th>
                                       <th class="text-center align-middle">prev_taxable13th_others</th>
                                       <th class="text-center align-middle">prev_taxableSalariesOthers</th>
                                       <th class="text-center align-middle">prev_totalTaxable</th>
                                       <th class="text-center align-middle">pres_13th_others</th>
                                       <th class="text-center align-middle">pres_deMinimis</th>
                                       <th class="text-center align-middle">pres_SSS_HDMF_Philhealth_Dues</th>
                                       <th class="text-center align-middle">pres_salariesOthers</th>
                                       <th class="text-center align-middle">pres_totalNonTaxable</th>
                                       <th class="text-center align-middle">pres_taxableBasicWage</th>
                                       <th class="text-center align-middle">pres_taxable13th_others</th>
                                       <th class="text-center align-middle">pres_taxableSalariesOthers</th>
                                       <th class="text-center align-middle">pres_totalTaxable</th>
                                       <th class="text-center align-middle">totalGrossCompensationIncome</th>
                                       <th class="text-center align-middle">totalTaxable</th>
                                       <th class="text-center align-middle">premiumPaidonHealth</th>
                                       <th class="text-center align-middle">netTaxableIncome</th>
                                       <th class="text-center align-middle">taxDue</th>
                                       <th class="text-center align-middle">prev_taxPaid</th>
                                       <th class="text-center align-middle">taxPaidDecember</th>
                                       <th class="text-center align-middle">taxRefunded</th>
                                       <th class="text-center align-middle">taxWithheldAjusted</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- 1604CF Schedule 7.5 -->
                  <div class="tab-pane" id="sched_7_5" role="tabpanel">
                     <div class="card-body">
                        <div class="row">
                           <div class="table-responsive m-t-40">
                              <table id="sched_7_5_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th class="text-center align-middle">tinNumber</th>
                                       <th class="text-center align-middle">branchCode</th>
                                       <th class="text-center align-middle">lastName</th>
                                       <th class="text-center align-middle">firstName</th>
                                       <th class="text-center align-middle">middleName</th>
                                       <th class="text-center align-middle">address</th>
                                       <th class="text-center align-middle">zipcode</th>
                                       <th class="text-center align-middle">birthday</th>
                                       <th class="text-center align-middle">telNumber</th>
                                       <th class="text-center align-middle">region</th>
                                       <th class="text-center align-middle">noOfDays</th>
                                       <th class="text-center align-middle">minimumDaily</th>
                                       <th class="text-center align-middle">empFrom</th>
                                       <th class="text-center align-middle">empTo</th>
                                       <th class="text-center align-middle">prev_basicWage</th>
                                       <th class="text-center align-middle">prev_holidayPay</th>
                                       <th class="text-center align-middle">prev_overtimePay</th>
                                       <th class="text-center align-middle">prev_nightDiff</th>
                                       <th class="text-center align-middle">prev_hazardPay</th>
                                       <th class="text-center align-middle">prev_13th_others</th>
                                       <th class="text-center align-middle">prev_deMinimis</th>
                                       <th class="text-center align-middle">prev_SSS_HDMF_Philhealth_Dues</th>
                                       <th class="text-center align-middle">prev_salariesOthers</th>
                                       <th class="text-center align-middle">prev_totalNonTaxable</th>
                                       <th class="text-center align-middle">prev_taxableBasicWage</th>
                                       <th class="text-center align-middle">prev_taxable13th_others</th>
                                       <th class="text-center align-middle">prev_taxableSalariesOthers</th>
                                       <th class="text-center align-middle">prev_totalTaxable</th>
                                       <th class="text-center align-middle">prev_grossIncome</th>
                                       <th class="text-center align-middle">pres_basicWage</th>
                                       <th class="text-center align-middle">pres_holidayPay</th>
                                       <th class="text-center align-middle">pres_overtimePay</th>
                                       <th class="text-center align-middle">pres_nightDiff</th>
                                       <th class="text-center align-middle">pres_hazardPay</th>
                                       <th class="text-center align-middle">pres_13th_others</th>
                                       <th class="text-center align-middle">pres_deMinimis</th>
                                       <th class="text-center align-middle">pres_SSS_HDMF_Philhealth_Dues</th>
                                       <th class="text-center align-middle">pres_salariesOthers</th>
                                       <th class="text-center align-middle">pres_totalNonTaxable</th>
                                       <th class="text-center align-middle">pres_taxableBasicWage</th>
                                       <th class="text-center align-middle">pres_taxable13th_others</th>
                                       <th class="text-center align-middle">pres_taxableSalariesOthers</th>
                                       <th class="text-center align-middle">pres_totalTaxable</th>
                                       <th class="text-center align-middle">pres_grossIncome</th>
                                       <th class="text-center align-middle">totalTaxable</th>
                                       <th class="text-center align-middle">premiumPaidonHealth</th>
                                       <th class="text-center align-middle">netTaxableIncome</th>
                                       <th class="text-center align-middle">taxDue</th>
                                       <th class="text-center align-middle">prev_taxPaid</th>
                                       <th class="text-center align-middle">taxPaidDecember</th>
                                       <th class="text-center align-middle">taxRefunded</th>
                                       <th class="text-center align-middle">taxWithheldAjusted</th>
                                    </tr>
                                 </thead>
                                 <tbody id="">
                                    @if($list_7_5)
                                       @foreach($list_7_5 as $list)
                                          <tr>
                                             <td class="text-left align-middle">{{$list->tinNumber}}</td>
                                             <td class="text-left align-middle">{{$list->branchCode}}</td>
                                             <td class="text-left align-middle">{{$list->lastName}}</td>
                                             <td class="text-left align-middle">{{$list->firstName}}</td>
                                             <td class="text-left align-middle">{{$list->middleName}}</td>
                                             <td class="text-left align-middle">{{$list->address}}</td>
                                             <td class="text-left align-middle">{{$list->zipcode}}</td>
                                             <td class="text-left align-middle">{{$list->birthday}}</td>
                                             <td class="text-left align-middle">{{$list->telNumber}}</td>
                                             <td class="text-left align-middle">{{$list->region}}</td>
                                             <td class="text-center align-middle">{{$list->noOfDays}}</td>
                                             <td class="text-right align-middle">{{$list->minimumDaily}}</td>
                                             <td class="text-left align-middle">{{$list->empFrom}}</td>
                                             <td class="text-left align-middle">{{$list->empTo}}</td>
                                             <td class="text-right align-middle">{{$list->prev_basicWage}}</td>
                                             <td class="text-right align-middle">{{$list->prev_holidayPay}}</td>
                                             <td class="text-right align-middle">{{$list->prev_overtimePay}}</td>
                                             <td class="text-right align-middle">{{$list->prev_nightDiff}}</td>
                                             <td class="text-right align-middle">{{$list->prev_hazardPay}}</td>
                                             <td class="text-right align-middle">{{$list->prev_13th_others}}</td>
                                             <td class="text-right align-middle">{{$list->prev_deMinimis}}</td>
                                             <td class="text-right align-middle">{{$list->prev_SSS_HDMF_Philhealth_Dues}}</td>
                                             <td class="text-right align-middle">{{$list->prev_salariesOthers}}</td>
                                             <td class="text-right align-middle">{{$list->prev_totalNonTaxable}}</td>
                                             <td class="text-right align-middle">{{$list->prev_taxableBasicWage}}</td>
                                             <td class="text-right align-middle">{{$list->prev_taxable13th_others}}</td>
                                             <td class="text-right align-middle">{{$list->prev_taxableSalariesOthers}}</td>
                                             <td class="text-right align-middle">{{$list->prev_totalTaxable}}</td>
                                             <td class="text-right align-middle">{{$list->prev_grossIncome}}</td>
                                             <td class="text-right align-middle">{{$list->pres_basicWage}}</td>
                                             <td class="text-right align-middle">{{$list->pres_holidayPay}}</td>
                                             <td class="text-right align-middle">{{$list->pres_overtimePay}}</td>
                                             <td class="text-right align-middle">{{$list->pres_nightDiff}}</td>
                                             <td class="text-right align-middle">{{$list->pres_hazardPay}}</td>
                                             <td class="text-right align-middle">{{$list->pres_13th_others}}</td>
                                             <td class="text-right align-middle">{{$list->pres_deMinimis}}</td>
                                             <td class="text-right align-middle">{{$list->pres_SSS_HDMF_Philhealth_Dues}}</td>
                                             <td class="text-right align-middle">{{$list->pres_salariesOthers}}</td>
                                             <td class="text-right align-middle">{{$list->pres_totalNonTaxable}}</td>
                                             <td class="text-right align-middle">{{$list->pres_taxableBasicWage}}</td>
                                             <td class="text-right align-middle">{{$list->pres_taxable13th_others}}</td>
                                             <td class="text-right align-middle">{{$list->pres_taxableSalariesOthers}}</td>
                                             <td class="text-right align-middle">{{$list->pres_totalTaxable}}</td>
                                             <td class="text-right align-middle">{{$list->pres_grossIncome}}</td>
                                             <td class="text-right align-middle">{{$list->totalTaxable}}</td>
                                             <td class="text-right align-middle">{{$list->premiumPaidonHealth}}</td>
                                             <td class="text-right align-middle">{{$list->netTaxableIncome}}</td>
                                             <td class="text-right align-middle">{{$list->taxDue}}</td>
                                             <td class="text-right align-middle">{{$list->prev_taxPaid}}</td>
                                             <td class="text-right align-middle">{{$list->taxPaidDecember}}</td>
                                             <td class="text-right align-middle">{{$list->taxRefunded}}</td>
                                             <td class="text-right align-middle">{{$list->taxWithheldAjusted}}</td>
                                          </tr>
                                       @endforeach()
                                    @endif
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

               </div>
            </div>
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
            <h4 class="modal-title">UPDATE Payroll</h4>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
         </div>
         <form class="form-material" action="" method="post">
            @csrf
            <div class="modal-body">
               <div class="form-body">
                  <div class="row p-t-20">
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
         $('#sched_7_5_table').DataTable({
             dom: 'Bfrtip',
             buttons: [
                 'copy', 'csv', 'excel', 'pdf', 'print'
             ]
         });
         $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');
         $('#gov_table').DataTable();
         $('#bd_table').DataTable({
            "order": [[ 1, "asc" ]]
         });
   });


   function print_payroll(){
        var myWindow = window.open("{{ url('/print_payroll') }}", "myWindow", 'width=1500,height=800');
   }

   function ShowModal(index){
      clearFields();
      var row_val = $('#list_body').find('tr');

      $('#entry_modal').modal('show');

   }
   function clearFields(){

   }
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}