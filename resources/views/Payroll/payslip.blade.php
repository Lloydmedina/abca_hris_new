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

                        <div class="col-lg-6 col-sm-12" hidden>
                            <div class="form-group">
                                <label class="control-label">Department <i class="text-small text-danger">*</i></label>
                                <select id="department" name="department" class="form-control custom-select">
                                    <option value="0">All</option>
                                    @foreach ($department as $row)
                                        <option value="{{ $row->SysPK_Dept }}" <?php echo $row->SysPK_Dept == @$_GET['department'] ? 'selected' : ''; ?>>{{ $row->Name_Dept }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Payroll Date <i class="text-small text-danger">*</i></label>
                                <select id="payroll" name="payroll" class="form-control custom-select">
                                    <option value="0" selected>Select All</option>
                                    @foreach ($payrollDate as $row)
                                        <option value="{{ $row->payroll_id }}" <?php echo $row->payroll_id == @$_GET['payroll'] ? 'selected' : ''; ?>>{{ $row->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-12">
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
                    <div class="col-lg-4 col-sm-12">
                        <h4 class="card-title">Payslip</h4>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()"
                            placeholder="Search for names..">
                    </div>
                    <div class="col-lg-4 col-sm-12 text-right">
                        {{-- <a href="{{ route('shift_entry') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus-circle"></i> Add Shift
                    </a> --}}
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                {{-- <th class="">Payroll#</th> --}}
                                <th class="" style="min-width: 240px;">Pay Period</th>
                                <th class="">Department</th>
                                <th class="">Employee Name</th>
                                <th class="">Designation</th>
                                <th class="text-right">Basic Pay</th>
                                <th class="text-right">Net Pay</th>
                                <th class="text-center" style="min-width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTbody">
                            @if (count($payslip))
                                @php
                                    $index = 0;
                                @endphp
                                @foreach ($payslip as $row)
                                    <tr id="{{ $row->payroll_detail_id . md5($row->payroll_detail_id) }}">
                                        {{-- <td>{{ $row->payroll_id }}</td> --}}
                                        <td>{{ date('M d, Y', strtotime($row->date_from)) }} -
                                            {{ date('M d, Y', strtotime($row->date_to)) }}</td>
                                        <td>{{ $row->Name_Dept }}</td>
                                        <td>{{ $row->FirstName_Empl }} {{ $row->LastName_Empl }}</a></td>
                                        <td>{{ $row->Position_Empl }}</td>
                                        <td class="text-right">{{ number_format($row->basic_pay, 2) }}</td>
                                        <td class="text-right">{{ number_format($row->net_pay_atm, 2) }}</td>
                                        <td class="text-center">

                                            <button onclick="PrintElem('employee_payslip_modal_div')"
                                                class="btn btn-sm btn-dark" title="Download"><i
                                                    class="fa-solid fa-download"></i></button> -
                                            <button class="btn btn-sm btn-info" title="View" data-toggle="modal"
                                                data-target="#employee_payslip_modal"
                                                onClick="displayPayslip('{{ $row->company }}',
                                                            '{{ $row->emp_name }}',
                                                            '{{ date('M d, Y', strtotime($row->date_from)) }} - {{ date('M d, Y', strtotime($row->date_to)) }}',
                                                            '{{ number_format($row->basic_pay, 2) }}',
                                                            '{{ number_format($row->net_pay_atm, 2) }}',
                                                            '{{ $row->SSS_Empl ? $row->SSS_Empl : 'N/A' }}',
                                                            '{{ $row->TIN_Empl ? $row->TIN_Empl : 'N/A' }}',
                                                            '{{ $row->philhealth_no ? $row->philhealth_no : 'N/A' }}',
                                                            '{{ $row->pagibig_no ? $row->pagibig_no : 'N/A' }}',
                                                            '{{ $row->department ? $row->department : 'N/A' }}',
                                                            '{{ number_format($row->service_charge, 2) }}',
                                                            '{{ number_format($row->rice_allowance, 2) }}',
                                                            '{{ number_format($row->ot_pay, 2) }}',
                                                            '{{ number_format($row->night_premium_pay, 2) }}',
                                                            '{{ number_format($row->basic_pay + $row->service_charge + $row->ot_pay + $row->night_premium_pay + $row->LegalHolidayOTPay + $row->SpecialHolidayPay + $row->SpecialHolidayOTPay + $row->BirthdayLeavePay, 2) }}',
                                                            '{{ number_format($row->sss, 2) }}',
                                                            '{{ number_format($row->ssswisp, 2) }}',
                                                            '{{ number_format($row->philhealth, 2) }}',
                                                            '{{ number_format($row->pag_ibig, 2) }}',
                                                            '{{ number_format($row->wtax, 2) }}',
                                                            '{{ number_format($row->holiday_pay, 2) }}',
                                                            '{{ number_format($row->SLPay, 2) }}',
                                                            '{{ number_format($row->VLPay, 2) }}',
                                                            '{{ number_format($row->RDPay, 2) }}',
                                                            '{{ number_format($row->LegalHolidayOTPay, 2) }}',
                                                            '{{ number_format($row->SpecialHolidayPay, 2) }}',
                                                            '{{ number_format($row->BirthdayLeavePay, 2) }}',
                                                            '{{ number_format($row->SpecialHolidayOTPay, 2) }}',
                                                            '{{ number_format($row->ReimbursementFee, 2) }}',
                                                            '{{ number_format($row->TransportationAllowance, 2) }}',
                                                            '{{ number_format($row->HousingAllowance, 2) }}',
                                                            '{{ number_format($row->Allowance, 2) }}',
                                                            '{{ number_format($row->OtherIncome, 2) }}',
                                                            '{{ number_format($row->rice_allowance + $row->ReimbursementFee + $row->TransportationAllowance + $row->HousingAllowance + $row->Allowance + $row->OtherIncome, 2) }}',
                                                            '{{ number_format($row->late_deduction, 2) }}',
                                                            '{{ number_format($row->ATD, 2) }}',
                                                            '{{ number_format($row->cash_advance, 2) }}',
                                                            '{{ number_format($row->AbsentDeduction + $row->UTDeduction + $row->late_deduction + $row->sss + $row->ssswisp + $row->philhealth + $row->pag_ibig + $row->wtax + $row->cash_advance + $row->ATD, 2) }}',
                                                            '{{ number_format($row->AbsentDeduction, 2) }}',
                                                            '{{ number_format($row->UTDeduction, 2) }}',
                                                            '{{ number_format($row->sss_loan, 2) }}',
                                                            '{{ number_format($row->pagibig_loan, 2) }}',
                                                            '{{ number_format($row->MOORGATE_Loan, 2) }}',
                                                            '{{ number_format($row->sss_loan + $row->pagibig_loan + $row->MOORGATE_Loan, 2) }}')">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

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
                    <div class="float-left"> {{ $paginationLinks }} </div>
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
                    <h4 class="modal-title">My Employee Payslip</h4>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <form id="update_employee_form" action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-body" id="employee_payslip_modal_div">
                            {{-- <div style="border-style: dashed;"> --}}

                            <div class="row p-t-20" style="margin: 1%;">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h1 id="companyName">TAVOLTA INC.</h1>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-right" id="payPeriod"> </label>
                                        </div>
                                    </div>

                                    <div style="border: 2px solid; border-radius: 5px;">
                                        <div class="row p-t-20" style="padding: 1%;">
                                            <div class="col-md-6">
                                                <p><b>Employee Name</b></p>
                                                <p id="empName"></p>
                                            </div>
                                            <div class="col-md-2">
                                                <p><b>TIN</b></p>
                                                <p id="tin">123456789</p>
                                            </div>
                                            <div class="col-md-2">
                                                <p><b>PH No</b></p>
                                                <p id="phNo">123456789</p>
                                            </div>
                                            <div class="col-md-2">
                                                <p><b>Basic Pay</b></p>
                                                <p id="basicPay">123456789</p>
                                            </div>
                                        </div>
                                        <div class="row p-t-20" style="padding: 1%;">
                                            <div class="col-md-6">
                                                <p><b>Department / Section</b></p>
                                                <p id="deptName"></p>
                                            </div>
                                            <div class="col-md-2">
                                                <p><b>SSS No</b></p>
                                                <p id="sssNo">123456789</p>
                                            </div>
                                            <div class="col-md-2">
                                                <p><b>HDMF No</b></p>
                                                <p id="pagibigNo">123456789</p>
                                            </div>
                                            <div class="col-md-2">
                                                <p><b>Net Pay</b></p>
                                                <p id="netPay">123456789</p>
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
                                                                <td>Basic Pay</td>
                                                                <td class="text-right align-middle" id="basicPay2"></td>
                                                            </tr>
                                                            <tr id="tr_ot">
                                                                <td>OT Pay</td>
                                                                <td class="text-right align-middle" id="ot"></td>
                                                            </tr>
                                                            <tr id="tr_np">
                                                                <td>NP Pay</td>
                                                                <td class="text-right align-middle" id="np"></td>
                                                            </tr>
                                                            <tr id="tr_VLPay">
                                                                <td>VL Pay</td>
                                                                <td class="text-right align-middle" id="VLPay"></td>
                                                            </tr>
                                                            <tr id="tr_SLPay">
                                                                <td>SL Pay</td>
                                                                <td class="text-right align-middle" id="SLPay"></td>
                                                            </tr>
                                                            <tr id="tr_holiday_pay">
                                                                <td>Legal<br>Holiday<br>Pay</td>
                                                                <td class="text-right align-middle" id="holiday_pay"></td>
                                                            </tr>
                                                            <tr id="tr_LegalHolidayOTPay">
                                                                <td>Legal<br>Holiday<br>OT<br>Pay</td>
                                                                <td class="text-right align-middle"
                                                                    id="LegalHolidayOTPay">
                                                                </td>
                                                            </tr>
                                                            <tr id="tr_SpecialHolidayPay">
                                                                <td>Special<br>Holiday<br>Pay</td>
                                                                <td class="text-right align-middle"
                                                                    id="SpecialHolidayPay">
                                                                </td>
                                                            </tr>
                                                            <tr id="tr_BirthdayLeavePay">
                                                                <td>Birthday Work<br>Pay</td>
                                                                <td class="text-right align-middle"
                                                                    id="BirthdayLeavePay">
                                                                </td>
                                                            </tr>
                                                            <tr id="tr_SpecialHolidayOTPay">
                                                                <td>Special<br>Holiday<br>OT<br>Pay</td>
                                                                <td class="text-right align-middle"
                                                                    id="SpecialHolidayOTPay"></td>
                                                            </tr>
                                                            <tr id="tr_serviceCharge">
                                                                <td>Service<br>Charge</td>
                                                                <td class="text-right align-middle" id="serviceCharge">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Total<br>Taxable<br>Earnings<b></td>
                                                                <td class="text-right align-middle" id="totalTaxable">
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
                                                            <tr id="tr_mealAllowance">
                                                                <td>Meal<br>Allowance</td>
                                                                <td class="text-right align-middle" id="mealAllowance">
                                                                </td>
                                                            </tr>
                                                            <tr id="tr_TransportationAllowance">
                                                                <td>Transportation<br>Allowance</td>
                                                                <td class="text-right align-middle"
                                                                    id="TransportationAllowance"></td>
                                                            </tr>
                                                            <tr id="tr_HousingAllowance">
                                                                <td>Housing<br>Allowance</td>
                                                                <td class="text-right align-middle" id="HousingAllowance">
                                                                </td>
                                                            </tr>
                                                            <tr id="tr_Allowance">
                                                                <td>Allowance</td>
                                                                <td class="text-right align-middle" id="Allowance"></td>
                                                            </tr>
                                                            <tr id="tr_OtherIncome">
                                                                <td>Other Income</td>
                                                                <td class="text-right align-middle" id="OtherIncome"></td>
                                                            </tr>
                                                            <tr id="tr_ReimbursementFee">
                                                                <td>Reimbursement<br>Fee</td>
                                                                <td class="text-right align-middle" id="ReimbursementFee">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Total<br>Non-Taxable<br>Earnings<b></td>
                                                                <td class="text-right align-middle" id="totalNonTaxable">
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
                                                            <tr>
                                                                <td>Late</td>
                                                                <td class="text-right align-middle" id="late_deduction">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Absent<b></td>
                                                                <td class="text-right align-middle" id="absent"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>UT<b></td>
                                                                <td class="text-right align-middle" id="ut"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SSS</b></td>
                                                                <td class="text-right align-middle" id="sss"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SSS WISP<b></td>
                                                                <td class="text-right align-middle" id="wisp"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PhilHealth<b></td>
                                                                <td class="text-right align-middle" id="philhealth"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HDMF<b></td>
                                                                <td class="text-right align-middle" id="pagibig"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>W/Tax<b></td>
                                                                <td class="text-right align-middle" id="wtax"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>ATD<b></td>
                                                                <td class="text-right align-middle" id="atd"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Cash Advance<b></td>
                                                                <td class="text-right align-middle" id="ca"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Total Deductions<b></td>
                                                                <td class="text-right align-middle" id="totalDeduction">
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
                                                            <tr>
                                                                <td><b>SSS Loan<b></td>
                                                                <td class="text-right align-middle"
                                                                    id="sss_loan"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PAGIBIG Loan<b></td>
                                                                <td class="text-right align-middle"
                                                                    id="pagibig_loan"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>MOORGATE Loan<b></td>
                                                                <td class="text-right align-middle"
                                                                    id="MOORGATE_Loan"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Total Loan<b></td>
                                                                <td class="text-right align-middle"
                                                                    id="totalLoan"></td>
                                                            </tr>
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
                                                                <td><b>Amount<b></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>SSS</td>
                                                                <td class="text-right align-middle" id="sssYTD"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>SSS WISP<b></td>
                                                                <td class="text-right align-middle" id="wispYTD"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>PhilHealth<b></td>
                                                                <td class="text-right align-middle" id="philhealthYTD">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>HDMF<b></td>
                                                                <td class="text-right align-middle" id="pagibigYTD"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>W/Tax<b></td>
                                                                <td class="text-right align-middle" id="pagibigYTD"></td>
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

                            {{-- </div> --}}
                        </div>
                    </div>
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

            // Trigger click submit search button
            $('#btn-search-shift-monitoring').click(function() {
                $('#btn-search-button').click();
            });
        });

        function displayPayslip(
            company,
            emp_name,
            payPeriod,
            basic,
            net,
            sssNo,
            tinNo,
            philhealtNo,
            pagibigNo,
            department,
            service_charge,
            meal_allowance,
            ot,
            np,
            total_taxable,
            sss,
            wisp,
            philhealth,
            pagibig,
            wtax,
            holiday_pay,
            SLPay,
            VLPay,
            RDPay,
            LegalHolidayOTPay,
            SpecialHolidayPay,
            BirthdayLeavePay,
            SpecialHolidayOTPay,
            ReimbursementFee,
            TransportationAllowance,
            HousingAllowance,
            Allowance,
            OtherIncome,
            totalNonTaxable,
            late_deduction,
            atd,
            ca,
            totalDeduction,
            AbsentDeduction,
            UTDeduction,
            sss_loan,
            pagibig_loan,
            MOORGATE_Loan,
            totalLoan) {
            document.getElementById("companyName").innerHTML = company;
            document.getElementById("empName").innerHTML = emp_name;
            document.getElementById("payPeriod").innerHTML = "Pay Period: " + payPeriod;
            document.getElementById("basicPay").innerHTML = basic;
            document.getElementById("basicPay2").innerHTML = basic;
            document.getElementById("netPay").innerHTML = net;
            document.getElementById("sssNo").innerHTML = sssNo;
            document.getElementById("tin").innerHTML = tinNo;
            document.getElementById("phNo").innerHTML = philhealtNo;
            document.getElementById("pagibigNo").innerHTML = pagibigNo;
            document.getElementById("deptName").innerHTML = department;

            //TAXABLE
            if (service_charge === "0.00") {
                $("#tr_serviceCharge").hide();
            } else {
                $("#tr_serviceCharge").show();
                document.getElementById("serviceCharge").innerHTML = service_charge;
            }
            if (ot === "0.00") {
                $("#tr_ot").hide();
            } else {
                $("#tr_ot").show();
                document.getElementById("ot").innerHTML = ot;
            }
            if (np === "0.00") {
                $("#tr_np").hide();
            } else {
                $("#tr_np").show();
                document.getElementById("np").innerHTML = np;
            }
            if (VLPay === "0.00") {
                $("#tr_VLPay").hide();
            } else {
                $("#tr_VLPay").show();
                document.getElementById("VLPay").innerHTML = VLPay;
            }
            if (VLPay === "0.00") {
                $("#tr_SLPay").hide();
            } else {
                $("#tr_SLPay").show();
                document.getElementById("SLPay").innerHTML = SLPay;
            }
            if (holiday_pay === "0.00") {
                $("#tr_holiday_pay").hide();
            } else {
                $("#tr_holiday_pay").show();
                document.getElementById("holiday_pay").innerHTML = holiday_pay;
            }
            if (LegalHolidayOTPay === "0.00") {
                $("#tr_LegalHolidayOTPay").hide();
            } else {
                $("#tr_LegalHolidayOTPay").show();
                document.getElementById("LegalHolidayOTPay").innerHTML = LegalHolidayOTPay;
            }
            if (SpecialHolidayPay === "0.00") {
                $("#tr_SpecialHolidayPay").hide();
            } else {
                $("#tr_SpecialHolidayPay").show();
                document.getElementById("SpecialHolidayPay").innerHTML = SpecialHolidayPay;
            }
            if (BirthdayLeavePay === "0.00") {
                $("#tr_BirthdayLeavePay").hide();
            } else {
                $("#tr_BirthdayLeavePay").show();
                document.getElementById("BirthdayLeavePay").innerHTML = BirthdayLeavePay;
            }
            if (SpecialHolidayOTPay === "0.00") {
                $("#tr_SpecialHolidayOTPay").hide();
            } else {
                $("#tr_SpecialHolidayOTPay").show();
                document.getElementById("SpecialHolidayOTPay").innerHTML = SpecialHolidayOTPay;
            }
            document.getElementById("totalTaxable").innerHTML = total_taxable;
            //NON TAXABLE
            if (mealAllowance === "0.00") {
                $("#tr_mealAllowance").hide();
            } else {
                $("#tr_mealAllowance").show();
                document.getElementById("mealAllowance").innerHTML = meal_allowance;
            }
            if (TransportationAllowance === "0.00") {
                $("#tr_TransportationAllowance").hide();
            } else {
                $("#tr_TransportationAllowance").show();
                document.getElementById("TransportationAllowance").innerHTML = TransportationAllowance;
            }
            if (HousingAllowance === "0.00") {
                $("#tr_HousingAllowance").hide();
            } else {
                $("#tr_HousingAllowance").show();
                document.getElementById("HousingAllowance").innerHTML = HousingAllowance;
            }
            if (Allowance === "0.00") {
                $("#tr_Allowance").hide();
            } else {
                $("#tr_Allowance").show();
                document.getElementById("Allowance").innerHTML = Allowance;
            }

            if (OtherIncome === "0.00") {
                $("#tr_OtherIncome").hide();
            } else {
                $("#tr_OtherIncome").show();
                document.getElementById("OtherIncome").innerHTML = OtherIncome;
            }

            if (ReimbursementFee === "0.00") {
                $("#tr_ReimbursementFee").hide();
            } else {
                $("#tr_ReimbursementFee").show();
                document.getElementById("ReimbursementFee").innerHTML = ReimbursementFee;
            }
            document.getElementById("totalNonTaxable").innerHTML = totalNonTaxable;

            document.getElementById("sss").innerHTML = sss;
            document.getElementById("wisp").innerHTML = wisp;
            document.getElementById("philhealth").innerHTML = philhealth;
            document.getElementById("pagibig").innerHTML = pagibig;
            document.getElementById("wtax").innerHTML = wtax;
            document.getElementById("late_deduction").innerHTML = late_deduction;
            document.getElementById("absent").innerHTML = AbsentDeduction;
            document.getElementById("ut").innerHTML = UTDeduction;
            document.getElementById("atd").innerHTML = atd;
            document.getElementById("ca").innerHTML = ca;
            document.getElementById("totalDeduction").innerHTML = totalDeduction;

            document.getElementById("sss_loan").innerHTML = sss_loan;
            document.getElementById("pagibig_loan").innerHTML = pagibig_loan;
            document.getElementById("MOORGATE_Loan").innerHTML = MOORGATE_Loan;
            document.getElementById("totalLoan").innerHTML = totalLoan;
        }

        // Filter names
        function searchNames() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInputSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTbody");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
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

        // Print div
        function PrintElem(elem) {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
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
