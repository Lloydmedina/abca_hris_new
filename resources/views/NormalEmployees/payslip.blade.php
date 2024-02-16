@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS --}}
@section('page_level_css')
    <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('uidesign/css/custom/payslip_modal.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title', 'Payslip')
{{-- BEGIN CONTENT --}}
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid" style="min-height: 550px">

        @include('Templates.alert_message')

        <div class="card">
            <div class="card-body">
                <form action="{{ route('payslip') }}" method="get">
                    {{-- @csrf --}}
                    <div class="row">


                        <div class="col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Payroll Date <i class="text-small text-danger">*</i></label>
                                <select id="payroll" name="payroll" class="form-control custom-select" required>
                                    <option value="" selected disabled>Select Payroll</option>
                                    @foreach ($payrollDate as $row)
                                        <option value="{{ $row->payroll_id }}" <?php echo $row->payroll_id == @$_GET['payroll'] ? 'selected' : ''; ?>>{{ $row->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-lg-3 col-sm-12">
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
                         </div> --}}

                         <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label class="hide" style="visibility: hidden">Search Button</label>
                                @include('button_component.search_button', ['margin_top' => '8.5'])
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
                    <div class="col-4">
                        <h4 class="card-title">Payslip <small>({{ count($payslip) }})</small></h4>
                    </div>
                    <div class="col-4">
                        {{-- <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names.."> --}}
                    </div>
                    <div class="col-4 text-right">
                        {{-- <a href="{{ route('shift_entry') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus-circle"></i> Add Shift
                    </a> --}}
                    </div>
                </div>
                <div class="table-responsive m-t-40">
                    <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="">Payroll #</th>
                                <th class="">Pay Period</th>
                                <th class="text-right">Basic Pay</th>
                                <th class="text-right">Net Pay</th>
                                <th colspan="3" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTbody">
                            @if (count($payslip))
                                @php
                                    $index = 0;
                                @endphp
                                @foreach ($payslip as $row)
                                    <tr id="{{ $row->payroll_detail_id . md5($row->payroll_detail_id) }}">
                                        <td>{{ $row->payroll_id }}</td>
                                        <td>{{ date('M d, Y', strtotime($row->date_from)) }} -
                                            {{ date('M d, Y', strtotime($row->date_to)) }}</td>
                                        <td class="text-right">{{ number_format($row->basic_pay, 2) }}</td>
                                        <td class="text-right">{{ number_format($row->net_pay_atm, 2) }}</td>

                                        <td class="text-center">

                                            <a href="javascript:(0)" 
                                                onclick="PrintElem('employee_payslip_modal_div')"
                                                class="text-dark" title="Download">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{-- <button onclick="PrintElem('employee_payslip_modal_div')"
                                                class="btn btn-sm btn-dark" title="Download"><i
                                                    class="fa-solid fa-download"></i></button> - --}}

                                            <a href="javascript:(0)" 
                                                class="text-info" title="View" data-toggle="modal"
                                                data-target="#employee_payslip_modal">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            {{-- <button class="btn btn-sm btn-info" title="View" data-toggle="modal"
                                                data-target="#employee_payslip_modal"><i
                                                    class="fa-solid fa-eye"></i></button> --}}

                                        </td>
                                    </tr>
                                    <?php ++$index; ?>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="12">No record found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr>

    </div>

    {{-- PAYSLIP MODAL --}}
    <div id="employee_payslip_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd"
        style=" padding-right: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">Employee Payslip</h4>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <form id="update_employee_form" action="" method="post">
                    @csrf
                    @if (count($payslip))
                        @foreach ($payslip as $row)
                            <div class="modal-body">
                                <div class="form-body" id="employee_payslip_modal_div">
                                    <div class="row p-t-20" style="margin: 1%;">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h1 id="companyName">{{ $row->company }}</h1>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-right" id="payPeriod">
                                                        {{ date('M d, Y', strtotime($row->date_from)) }} -
                                                        {{ date('M d, Y', strtotime($row->date_to)) }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div style="border: 2px solid; border-radius: 5px;">
                                                <div class="row p-t-20" style="padding: 1%;">
                                                    <div class="col-md-6">
                                                        <p><b>Employee Name</b></p>
                                                        <p id="empName">{{ $row->emp_name }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p><b>TIN</b></p>
                                                        <p id="tin">{{ $row->TIN_Empl }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p><b>PH No</b></p>
                                                        <p id="phNo">{{ $row->philhealth_no }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p><b>Basic Pay</b></p>
                                                        <p id="basicPay">{{ number_format($row->basic_pay, 2) }}</p>
                                                    </div>
                                                </div>
                                                <div class="row p-t-20" style="padding: 1%;">
                                                    <div class="col-md-6">
                                                        <p><b>Department / Section</b></p>
                                                        <p id="deptName">{{ $row->department }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p><b>SSS No</b></p>
                                                        <p id="sssNo">{{ $row->SSS_Empl }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p><b>HDMF No</b></p>
                                                        <p id="pagibigNo">{{ $row->pagibig_no }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p><b>Net Pay</b></p>
                                                        <p id="netPay">{{ number_format($row->net_pay_atm, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>

                                    <div style="border: 2px solid; border-radius: 5px; margin: 2.5%;">
                                        <div class="row p-t-20" style="padding: 1%;">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="table-responsive m-t-40">
                                                            <table class="display nowrap table table-sm table-hover"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td><b>Taxable Earnings<b></td>
                                                                        <td><b>Amount<b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Basic Pay
                                                                          {{ $row->rate_type == "Monthly" ? "" : "(".number_format($row->total_hour, 2).")" }}
                                                                        </td>
                                                                        <td class="text-right align-middle"
                                                                            id="basicPay2">
                                                                            {{ number_format($row->basic_pay, 2) }}</td>
                                                                    </tr>
                                                                    @if ($row->ot_pay != 0)
                                                                        <tr id="tr_ot">
                                                                            <td>OT Pay:
                                                                                ({{ number_format($row->reg_ot_hours, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="ot">
                                                                                {{ number_format($row->ot_pay, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->night_premium_pay != 0)
                                                                        <tr id="tr_np">
                                                                            <td>NightDiff:
                                                                                ({{ number_format($row->night_premium_hour, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="np">
                                                                                {{ number_format($row->night_premium_pay, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->VLPay != 0)
                                                                        <tr id="tr_VLPay">
                                                                            <td>VL Pay:
                                                                                ({{ number_format($row->VacationLeaveWithPay, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="VLPay">
                                                                                {{ number_format($row->VLPay, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->SLPay != 0)
                                                                        <tr id="tr_SLPay">
                                                                            <td>SL Pay:
                                                                                ({{ number_format($row->SickLeaveWithPay, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="SLPay">
                                                                                {{ number_format($row->SLPay, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->BirthdayLeavePay != 0)
                                                                        <tr id="tr_BLPay">
                                                                            <td>BL Pay:
                                                                                ({{ number_format($row->BirthDayLeave, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="BLPay">
                                                                                {{ number_format($row->BirthdayLeavePay, 2) }}</td>
                                                                        </tr>
                                                                    @endif


                                                                    @if ($row->holiday_pay != 0)
                                                                        <tr id="tr_holiday_pay">
                                                                            <td>Legal<br>Holiday<br>Pay:
                                                                                ({{ number_format($row->LegalHolidy, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="holiday_pay">
                                                                                {{ number_format($row->holiday_pay, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->LegalHolidayOTPay != 0)
                                                                        <tr id="tr_LegalHolidayOTPay">
                                                                            <td>Legal<br>Holiday<br>OT<br>Pay:
                                                                                ({{ number_format($row->LegalHolidayOT, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="LegalHolidayOTPay">
                                                                                {{ number_format($row->LegalHolidayOTPay, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->SpecialHolidayPay != 0)
                                                                        <tr id="tr_SpecialHolidayPay">
                                                                            <td>Special<br>Holiday<br>Pay:
                                                                                ({{ number_format($row->SpecialHoliday, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="SpecialHolidayPay">
                                                                                {{ number_format($row->SpecialHolidayPay, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->SpecialHolidayOTPay != 0)
                                                                        <tr id="tr_SpecialHolidayOTPay">
                                                                            <td>Special<br>Holiday<br>OT<br>Pay:
                                                                                ({{ number_format($row->SpecialHolidayOT, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="SpecialHolidayOTPay">
                                                                                {{ number_format($row->SpecialHolidayOTPay, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->service_charge != 0)
                                                                        <tr id="tr_serviceCharge">
                                                                            <td>Service<br>Charge</td>
                                                                            <td class="text-right align-middle"
                                                                                id="serviceCharge">
                                                                                {{ number_format($row->service_charge, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($row->RDPay != 0)
                                                                        <tr id="tr_serviceCharge">
                                                                            <td>RD Pay:
                                                                                ({{ number_format($row->RestDay, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="serviceCharge">
                                                                                {{ number_format($row->RDPay, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    <tr>
                                                                        <td><b>Total<br>Taxable<br>Earnings<b></td>
                                                                        <td class="text-right align-middle"
                                                                            id="totalTaxable">
                                                                            {{ number_format($row->basic_pay + $row->service_charge + $row->ot_pay + $row->night_premium_pay + $row->LegalHolidayOTPay + $row->SpecialHolidayPay + $row->SpecialHolidayOTPay + $row->RDPay, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <br>

                                                        <div class="table-responsive m-t-40">
                                                            <table class="display nowrap table table-sm table-hover"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td><b>Non-Taxable Earnings<b></td>
                                                                        <td><b>Amount<b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if ($row->adjustments != 0)
                                                                        <tr id="tr_adjustments">
                                                                            <td>Salary<br>Adjustment</td>
                                                                            <td class="text-right align-middle"
                                                                                id="mealAllowance">
                                                                                {{ number_format($row->adjustments, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->rice_allowance != 0)
                                                                        <tr id="tr_mealAllowance">
                                                                            <td>Meal<br>Allowance</td>
                                                                            <td class="text-right align-middle"
                                                                                id="mealAllowance">
                                                                                {{ number_format($row->rice_allowance, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->TransportationAllowance != 0)
                                                                        <tr id="tr_TransportationAllowance">
                                                                            <td>Transportation<br>Allowance</td>
                                                                            <td class="text-right align-middle"
                                                                                id="TransportationAllowance">
                                                                                {{ number_format($row->TransportationAllowance, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->HousingAllowance != 0)
                                                                        <tr id="tr_HousingAllowance">
                                                                            <td>Housing<br>Allowance</td>
                                                                            <td class="text-right align-middle"
                                                                                id="HousingAllowance">
                                                                                {{ number_format($row->HousingAllowance, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->Allowance != 0)
                                                                        <tr id="tr_Allowance">
                                                                            <td>Allowance</td>
                                                                            <td class="text-right align-middle"
                                                                                id="Allowance">
                                                                                {{ number_format($row->Allowance, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->ReimbursementFee != 0)
                                                                        <tr id="tr_ReimbursementFee">
                                                                            <td>Government<br>Adjustment</td>
                                                                            <td class="text-right align-middle"
                                                                                id="ReimbursementFee">
                                                                                {{ number_format($row->ReimbursementFee, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <td><b>Total<br>Non-Taxable<br>Earnings<b></td>
                                                                        <td class="text-right align-middle"
                                                                            id="totalNonTaxable">
                                                                            {{ number_format($row->adjustments + $row->HousingAllowance + $row->Allowance + $row->ReimbursementFee + $row->TransportationAllowance + $row->rice_allowance, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <div class="table-responsive m-t-40">
                                                            <table class="display nowrap table table-sm table-hover"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td><b>Deductions<b></td>
                                                                        <td><b>Amount<b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if ($row->late_deduction != 0)
                                                                        <tr>
                                                                            <td>Late
                                                                                ({{ number_format($row->late_min, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="late_deduction">
                                                                                {{ number_format($row->late_deduction, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->AbsentDeduction != 0)
                                                                        <tr>
                                                                            <td>Absent
                                                                                ({{ number_format($row->absent_hour, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="absent">
                                                                                {{ number_format($row->AbsentDeduction, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->VLWithOutPayDeduction != 0)
                                                                        <tr>
                                                                            <td>VL w/o Pay({{ number_format($row->VacationLeaveWithoutPay, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="ut">
                                                                                {{ number_format($row->VLWithOutPayDeduction, 2) }}
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->SLWithOutPayDeduction != 0)
                                                                        <tr>
                                                                            <td>SL w/o Pay({{ number_format($row->SickLeaveWithoutPay, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="ut">
                                                                                {{ number_format($row->SLWithOutPayDeduction, 2) }}
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->UTDeduction != 0)
                                                                        <tr>
                                                                            <td>UT({{ number_format($row->ut, 2) }}hrs.)
                                                                            </td>
                                                                            <td class="text-right align-middle"
                                                                                id="ut">
                                                                                {{ number_format($row->UTDeduction, 2) }}
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->sss != 0)
                                                                        <tr>
                                                                            <td><b>SSS</b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="sss">
                                                                                {{ number_format($row->sss, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->ssswisp != 0)
                                                                        <tr>
                                                                            <td><b>SSS WISP<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="wisp">
                                                                                {{ number_format($row->ssswisp, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->philhealth != 0)
                                                                        <tr>
                                                                            <td><b>PhilHealth<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="philhealth">
                                                                                {{ number_format($row->philhealth, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->pag_ibig != 0)
                                                                        <tr>
                                                                            <td><b>HDMF<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="pagibig">
                                                                                {{ number_format($row->pag_ibig, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->wtax != 0)
                                                                        <tr>
                                                                            <td><b>W/Tax<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="wtax">
                                                                                {{ number_format($row->wtax, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->ATD != 0)
                                                                        <tr>
                                                                            <td><b>ATD<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="atd">
                                                                                {{ number_format($row->ATD, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->cash_advance != 0)
                                                                        <tr>
                                                                            <td><b>Cash Advance<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="ca">
                                                                                {{ number_format($row->cash_advance, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->sss_loan != 0)
                                                                        <tr>
                                                                            <td><b>SSS Loan<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="ca">
                                                                                {{ number_format($row->sss_loan, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->pagibig_loan != 0)
                                                                        <tr>
                                                                            <td><b>PAGIBIG Loan<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="ca">
                                                                                {{ number_format($row->pagibig_loan, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->MOORGATE_Loan != 0)
                                                                        <tr>
                                                                            <td><b>MOORGATE Loan<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="ca">
                                                                                {{ number_format($row->MOORGATE_Loan, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($row->other_deductions != 0)
                                                                        <tr>
                                                                            <td><b>Other Deduction<b></td>
                                                                            <td class="text-right align-middle"
                                                                                id="ca">
                                                                                {{ number_format($row->other_deductions, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <td><b>Total Deductions<b></td>
                                                                        <td class="text-right align-middle"
                                                                            id="totalDeduction">
                                                                            {{ number_format($row->UTDeduction + $row->VLWithOutPayDeduction + $row->SLWithOutPayDeduction + $row->MOORGATE_Loan + $row->other_deductions + $row->pagibig_loan + $row->sss_loan + $row->late_deduction + $row->sss + $row->ssswisp + $row->philhealth + $row->pag_ibig + $row->wtax + $row->cash_advance + $row->ATD, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <br>

                                                        <div class="table-responsive m-t-40">
                                                            <table class="display nowrap table table-sm table-hover"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td><b>Loan Balance<b></td>
                                                                        <td><b>Amount<b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">

                                                        <div class="table-responsive m-t-40">
                                                            <table class="display nowrap table table-sm table-hover"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td><b>Y-T-D Info<b></td>
                                                                        <td class="text-right align-middle"><b>Amount<b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><b>SSS</b></td>
                                                                        <td class="text-right align-middle" id="sssYTD">
                                                                            @if(count($SSS_emp_contrib))
                                                                                {{ number_format($SSS_emp_contrib[0]->SSS_EmployeeShare,2) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>SSS WISP<b></td>
                                                                        <td class="text-right align-middle" id="wispYTD">
                                                                            @if(count($WISP_emp_contrib))
                                                                                {{ number_format($WISP_emp_contrib[0]->WISP_EmployeeShare,2) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>PhilHealth<b></td>
                                                                        <td class="text-right align-middle" id="philhealthYTD">
                                                                            @if(count($PHILHEALTH_emp_contrib))
                                                                                {{ number_format($PHILHEALTH_emp_contrib[0]->PH_EmployeeShare,2) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>HDMF<b></td>
                                                                        <td class="text-right align-middle" id="pagibigYTD">
                                                                            @if(count($PAGIBIG_emp_contrib))
                                                                                {{ number_format($PAGIBIG_emp_contrib[0]->EmployeeShare,2) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>W/Tax<b></td>
                                                                        <td class="text-right align-middle" id="wTaxYTD">
                                                                            @if(count($WTAX_emp_contrib))
                                                                                {{ number_format($WTAX_emp_contrib[0]->WTAX,2) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="table-responsive m-t-40">
                                                            <table class="display nowrap table table-sm table-hover"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td><b>Leaves<b></td>
                                                                        <td><b>Entitlement This Year<b></td>
                                                                        <td><b>Earned to Date<b></td>
                                                                        <td><b>Leave Credit<b></td>
                                                                        <td><b>Used Balance This Year<b></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
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
                        @endforeach
                    @endif
                    <div class="modal-footer">
                        <div class="form-actions m-auto">
                            {{-- <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button> --}}
                            <button onclick="PrintElem('employee_payslip_modal_div')" type="button"
                                class="btn btn-sm btn-dark ml-2"><i class="fa fa-download" aria-hidden="true"></i>
                                Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- END PAYSLIP MODAL --}}


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
        $(document).ready(function() {


        });

        // Print div
        function PrintElem(elem) {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            // mywindow.document.write('<link href="{{ asset('uidesign/css/custom/payslip_modal.css') }}" rel="stylesheet">');
            mywindow.document.write(
                '<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet">'
            );
            mywindow.document.write('</head><body >');
            // mywindow.document.write('<h1>' + document.title  + '</h1>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            mywindow.document.write('</body></html>');


            setTimeout(function() {
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/
                mywindow.print();
                mywindow.close();
            }, 1000);


            return true;
        }
    </script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}
