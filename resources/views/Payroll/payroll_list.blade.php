@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS --}}
@section('page_level_css')
    <link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">

@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title', 'Payroll List')
{{-- BEGIN CONTENT --}}
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid" style="min-height: 700px">

        @include('Templates.alert_message')

        <form class="form-material" action="{{ url('/payroll_list') }}" method="get">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-sm-12">
                            <label class="form-control-label">Year</label>
                            <input type="number" min="1" max="9999" maxlength="4" class="form-control"
                                name="yr" value="{{ $yr }}">
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Month</label>
                                <select id="payroll_list" name="payroll_list" class="form-control custom-select">
                                    <option value=1>January</option>
                                    <option value=2>Febuary</option>
                                    <option value=3>March</option>
                                    <option value=4>April</option>
                                    <option value=5>May</option>
                                    <option value=6>June</option>
                                    <option value=7>July</option>
                                    <option value=8>August</option>
                                    <option value=9>September</option>
                                    <option value=10>October</option>
                                    <option value=11>November</option>
                                    <option value=12>December</option>
                                    {{-- @foreach ($payroll_month as $month)
                                    <option value={{ $month->value}}>{{ $month->month }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Payroll List</label>
                                <select id="payroll_list" name="payroll_list" class="form-control custom-select">
                                    @foreach ($payroll_master as $list)
                                        <option value={{ $list->payroll_id }}>{{ $list->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12 text-lg-right">

                            <div class="form-group">
                                <label class="hide" style="visibility: hidden">Search button</label>
                                @include('button_component.search_button', ['margin_top' => '9.5'])
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </form>

        <hr>

        <form class="form-material" action="{{ url('/save_payroll') }}" method="post">
            @csrf
            <div class="card">
                <!-- Tab Items -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <!-- Employee Payroll -->
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#EmployeePayroll" role="tab"
                            aria-selected="true">
                            Employee Payroll
                        </a>
                    </li>
                    <!-- Government Mandated -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#GovernmentMandated" role="tab"
                            aria-selected="false">
                            Government Mandated
                        </a>
                    </li>
                    <!-- Bank Deposits -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#BankDeposits" role="tab" aria-selected="false">
                            Bank Deposits
                        </a>
                    </li>
                    <!-- Payroll Summary -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#PayrollSummary" role="tab" aria-selected="false">
                            Payroll Summary
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Employee Payroll -->
                    <div class="tab-pane active show" id="EmployeePayroll" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive m-t-40">
                                    <table id="emp_payroll_table"
                                        class="display nowrap table table-sm table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle"></th>
                                                <th class="text-center align-middle" hidden>pk</th>
                                                <th class="text-center align-middle">No</th>
                                                <th class="text-center align-middle">Account No</th>
                                                <th class="text-center align-middle">Employee No</th>
                                                <th class="text-center align-middle">Employee Name</th>
                                                <th class="text-center align-middle">Dept.</th>
                                                <th class="text-center align-middle">Daily<br>Rate</th>
                                                <th class="text-center align-middle">Monthly<br>Rate</th>
                                                <th class="text-center align-middle">Semi Monthly<br>Rate</th>
                                                <th class="text-center align-middle">Hourly<br>Rate</th>
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
                                                <th class="text-center align-middle">Rest Day </th>
                                                <th class="text-center align-middle">Rest Day OT </th>
                                                <th class="text-center align-middle">Legal Holidy </th>
                                                <th class="text-center align-middle">Legal Holiday OT </th>
                                                <th class="text-center align-middle">Legal Holiday RD </th>
                                                <th class="text-center align-middle">Legal Holiday RDOT </th>
                                                <th class="text-center align-middle">Special Holiday </th>
                                                <th class="text-center align-middle">Special Holiday OT </th>
                                                <th class="text-center align-middle">Special Holiday RD </th>
                                                <th class="text-center align-middle">Special Holiday RDOT </th>
                                                <th class="text-center align-middle">Double Special Holiday </th>
                                                <th class="text-center align-middle">Double Special Holiday OT </th>
                                                <th class="text-center align-middle">Double Special Holiday RD </th>
                                                <th class="text-center align-middle">Double Special Holiday RDOT </th>
                                                <th class="text-center align-middle">Double Legal Holiday </th>
                                                <th class="text-center align-middle">Double Legal Holiday OT </th>
                                                <th class="text-center align-middle">DoubleLegal Holiday RD </th>
                                                <th class="text-center align-middle">DoubleLegal Holiday RDOT </th>
                                                <th class="text-center align-middle">Legal Special Holiday </th>
                                                <th class="text-center align-middle">Legal Special HolidayOT </th>
                                                <th class="text-center align-middle">Legal Special HolidayRD </th>
                                                <th class="text-center align-middle">Legal Special HolidayRDOT </th>
                                                <th class="text-center align-middle">Vacation Leave WithPay </th>
                                                <th class="text-center align-middle">Sick Leave WithPay </th>
                                                <th class="text-center align-middle">Vacation Leave WithoutPay </th>
                                                <th class="text-center align-middle">Sick Leave WithoutPay </th>
                                                <th class="text-center align-middle">spl </th>
                                                <th class="text-center align-middle">BirthDay Leave </th>
                                                <th class="text-center align-middle">Maternity Leave </th>
                                                <th class="text-center align-middle">Paternity Leave </th>
                                                <th class="text-center align-middle">Total Manhour</th>
                                                <th class="text-center align-middle">Rice<br>Allowance</th>
                                                <th class="text-center align-middle">Clothing<br>Allowance</th>
                                                <th class="text-center align-middle">Monitized VL Credits</th>
                                                <th class="text-center align-middle">Medical Cash<br>Allowance<br>Dependent
                                                </th>
                                                <th class="text-center align-middle">Med. Health Care Needs</th>
                                                <th class="text-center align-middle">Laundry<br>Allowance</th>
                                                <th class="text-center align-middle">Transportation<br>Allowance</th>
                                                <th class="text-center align-middle">Housing<br>Allowance</th>
                                                <th class="text-center align-middle">Allowance</th>
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
                                                <th class="text-center align-middle">PaternityLeavePay</th>
                                                <th class="text-center align-middle">Maternity Leave Pay</th>
                                                <th class="text-center align-middle">Birthday Leave Pay</th>
                                                <th class="text-center align-middle">SLPay</th>
                                                <th class="text-center align-middle">VLPay</th>
                                                <th class="text-center align-middle">RDPay</th>
                                                <th class="text-center align-middle">RDOTPay</th>
                                                <th class="text-center align-middle">Legal Holiday OTPay</th>
                                                <th class="text-center align-middle">Legal Holiday RDPay</th>
                                                <th class="text-center align-middle">Legal Holiday RDOTPay</th>
                                                <th class="text-center align-middle">Special Holiday Pay</th>
                                                <th class="text-center align-middle">Special Holiday OTPay</th>
                                                <th class="text-center align-middle">Special Holiday RDPay</th>
                                                <th class="text-center align-middle">Special Holiday RDOTPay</th>
                                                <th class="text-center align-middle">Double Special Holiday Pay </th>
                                                <th class="text-center align-middle">Double Special Holiday OT Pay </th>
                                                <th class="text-center align-middle">Double Special Holiday RD Pay </th>
                                                <th class="text-center align-middle">Double Special Holiday RDOT Pay</th>
                                                <th class="text-center align-middle">Double LegalHoliday Pay</th>
                                                <th class="text-center align-middle">Double Legal Holiday OT Pay</th>
                                                <th class="text-center align-middle">Double Legal Holiday RD Pay</th>
                                                <th class="text-center align-middle">Double Legal Holiday RDOT Pay</th>
                                                <th class="text-center align-middle">VLWithOutPay Deduction</th>
                                                <th class="text-center align-middle">SLWithOutPay Deduction</th>
                                                <th class="text-center align-middle">Service Charge</th>
                                                <th class="text-center align-middle">Adjustment (+)</th>
                                                <th class="text-center align-middle">Other Earnings</th>
                                                <th class="text-center align-middle">Less Late</th>
                                                <th class="text-center align-middle">Less UT</th>
                                                <th class="text-center align-middle">Less Absent</th>
                                                <th class="text-center align-middle">Gross Pay</th>
                                                <th class="text-center align-middle">Taxable<br>Compensation</th>
                                                <th class="text-center align-middle">SSS</th>
                                                <th class="text-center align-middle">SSS WISP</th>
                                                <th class="text-center align-middle">PhilHealth</th>
                                                <th class="text-center align-middle">PAG-IBIG</th>
                                                <th class="text-center align-middle">w/tax</th>
                                                <th class="text-center align-middle">Under witheld</th>
                                                <th class="text-center align-middle">Other Deductions</th>
                                                <th class="text-center align-middle">Total Tax</th>
                                                <th class="text-center align-middle">SSS<br>Loan</th>
                                                <th class="text-center align-middle">PAG-IBIG<br>Loan</th>
                                                <th class="text-center align-middle">MOORGATE<br>Loan</th>
                                                <th class="text-center align-middle">Adjustment (-)</th>
                                                <th class="text-center align-middle">Cash<br>Advance</th>
                                                <th class="text-center align-middle">ATD</th>
                                                <th class="text-center align-middle">Total<br>Deduction</th>
                                                <th class="text-center align-middle">Net Pay</th>
                                                <th class="text-center align-middle">SSS<br>ER</th>
                                                <th class="text-center align-middle">SSS WISP<br>ER</th>
                                                <th class="text-center align-middle">PhilHealth<br>ER</th>
                                                <th class="text-center align-middle">PAG-IBIG<br>ER</th>
                                                <th class="text-center align-middle">W/Tax<br>ER</th>
                                                <th class="text-center align-middle">Total ER</th>
                                            </tr>
                                        </thead>
                                        <tbody id="payroll_list">
                                            @if ($payroll_list)
                                                <?php
                                                $no = 1;
                                                $ctr = 0;
                                                ?>
                                                @foreach ($payroll_list as $data)
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <button type="button" class="btn btn-link">
                                                                <i class="fas fa-fw fa-keyboard"
                                                                    onclick="ShowModal({{ $ctr }} )"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center align-middle" hidden>
                                                            {{ $data->payroll_detail_id }}</td>
                                                        <td class="text-center align-middle">{{ $no }}</td>
                                                        <td class="text-center align-middle">{{ $data->bank_account }}
                                                        </td>
                                                        <td class="text-left align-middle">{{ $data->UserID_Empl }}</td>
                                                        <td class="text-left align-middle">{{ $data->emp_name }}</td>
                                                        <td class="text-left align-middle">{{ $data->department }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->daily_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->monthly_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->semi_monthly_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->hourly_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->reg_ot_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->holiday_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sunday_special_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_rate, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->standart_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->absent_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->ut, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->leave, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->actual_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->late_min, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->reg_ot_hours, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sunday_special_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sunday_special_ot_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->holiday_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->holiday_ot_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_holiday_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_sunday_special_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->RestDay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->RestDayOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidy, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidayOT, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidayRD, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidayRDOT, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHoliday, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayOT, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayRD, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayRDOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHoliday, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayRD, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayRDOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHoliday, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayRD, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayRDOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalSpecialHoliday, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalSpecialHolidayOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalSpecialHolidayRD, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalSpecialHolidayRDOT, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->VacationLeaveWithPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SickLeaveWithPay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->VacationLeaveWithoutPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SickLeaveWithoutPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->spl, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->BirthDayLeave, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->MaternityLeave, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->PaternityLeave, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->total_hour, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->rice_allowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->clothing_allowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->monetized_unused_leave_credit, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->medical_allowance_dependents, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->medical_allowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->laundry_allowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->TransportationAllowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->HousingAllowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->Allowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->deminis_benefits, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->cola, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->communication_allowance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->basic_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->ot_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sunday_special_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sunday_special_ot_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->holiday_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->holiday_ot_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_holiday_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->night_premium_sunday_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->paternity_leave_pay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->MaternityLeavePay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->BirthdayLeavePay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SLPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->VLPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->RDPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->RDOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidayOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidayRDPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->LegalHolidayRDOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayRDPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SpecialHolidayRDOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayRDPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleSpecialHolidayRDOTPay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayRDPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->DoubleLegalHolidayRDOTPay, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->VLWithOutPayDeduction, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SLWithOutPayDeduction, 2) }}</td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->service_charge, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->adjustments, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->OtherIncome, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->late_deduction, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->UTDeduction, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->AbsentDeduction, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->gross_pay, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->taxable_compensation, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sss, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->ssswisp, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->philhealth, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->pag_ibig, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->wtax, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->under_witheld, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->other_deductions, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->total_tax, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sss_loan, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->pagibig_loan, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->MOORGATE_Loan, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->loan_adjustment, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->cash_advance, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->ATD, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->total_deduction, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->net_pay_atm, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->sss_er, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->SSSWISPER, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->philhealth_er, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->pagibig_er, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->wtax_er, 2) }}
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            {{ number_format($data->total_er, 2) }}
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $no = $no + 1;
                                                    $ctr = $ctr + 1;
                                                    ?>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Government Mandated -->
                    <div class="tab-pane" id="GovernmentMandated" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive m-t-40">
                                    <table id="gov_table"
                                        class="display nowrap table table-sm table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle" rowspan="2">No</th>
                                                <th class="text-center align-middle" rowspan="2">Employee Name</th>
                                                <th class="text-center align-middle" rowspan="2">Withholding<br>Tax
                                                </th>
                                                <th class="text-center align-middle" colspan="3">Employee Contribution
                                                </th>
                                                <th class="text-center align-middle" colspan="3">Employer Contribution
                                                </th>
                                                <th class="text-center align-middle" colspan="2">Emp. Loan</th>
                                                <th class="text-center align-middle" colspan="3">Total Contribution
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center align-middle">SSS</th>
                                                <th class="text-center align-middle">P.HEALTH</th>
                                                <th class="text-center align-middle">PAG-IBIG</th>
                                                <th class="text-center align-middle">SSS</th>
                                                <th class="text-center align-middle">P.HEALTH</th>
                                                <th class="text-center align-middle">PAG-IBIG</th>
                                                <th class="text-center align-middle">SSS</th>
                                                <th class="text-center align-middle">PAG-IBIG</th>
                                                <th class="text-center align-middle">SSS</th>
                                                <th class="text-center align-middle">P.HEALTH</th>
                                                <th class="text-center align-middle">PAG-IBIG</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gov_list">
                                            <?php
                                            $no_gov = 1;
                                            $gt_wtax = 0;
                                            $gt_sss = 0;
                                            $gt_phealth = 0;
                                            $gt_pagibig = 0;
                                            $gt_sss_er = 0;
                                            $gt_phealth_er = 0;
                                            $gt_pagibig_er = 0;
                                            $gt_sss_loan = 0;
                                            $gt_pagibig_loan = 0;
                                            $gt_sss_c = 0;
                                            $gt_phealth_c = 0;
                                            $gt_pagibig_c = 0;
                                            ?>
                                            @if ($payroll_list)

                                                @foreach ($payroll_list as $data)
                                                    <?php
                                                    $t_sss = $data->sss + $data->sss_er;
                                                    $t_philhealth = $data->philhealth + $data->philhealth_er;
                                                    $t_pag_ibig = $data->pag_ibig + $data->pagibig_er;
                                                    
                                                    $gt_wtax = $gt_wtax + $data->wtax;
                                                    $gt_sss = $gt_sss + $data->sss;
                                                    $gt_phealth = $gt_phealth + $data->philhealth;
                                                    $gt_pagibig = $gt_phealth + $data->philhealth;
                                                    $gt_sss_er = $gt_sss_er + $data->sss_er;
                                                    $gt_phealth_er = $gt_phealth_er + $data->philhealth_er;
                                                    $gt_pagibig_er = $gt_pagibig_er + $data->pagibig_er;
                                                    $gt_sss_loan = $gt_sss_loan + $data->sss_loan;
                                                    $gt_pagibig_loan = $gt_pagibig_loan + $data->pagibig_loan;
                                                    $gt_sss_c = $gt_sss_c + $t_sss;
                                                    $gt_phealth_c = $gt_phealth_c + $t_philhealth;
                                                    $gt_pagibig_c = $gt_pagibig_c + $t_pag_ibig;
                                                    
                                                    ?>
                                                    <tr>
                                                        <th class="text-center align-middle">{{ $no_gov }}</th>
                                                        <th class="text-left align-middle">{{ $data->emp_name }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->wtax, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->sss, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->philhealth, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->pag_ibig, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->sss_er, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->philhealth_er, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->pagibig_er, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->sss_loan, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($data->pagibig_loan, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($t_sss, 2) }}
                                                        </th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($t_philhealth, 2) }}</th>
                                                        <th class="text-right align-middle">
                                                            {{ number_format($t_pag_ibig, 2) }}</th>
                                                    </tr>
                                                    <?php $no_gov = $no_gov + 1; ?>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center align-middle"></th>
                                                <th class="text-left align-middle">TOTAL PER PAYROLL</th>
                                                <th class="text-right align-middle">{{ number_format($gt_wtax, 2) }}</th>
                                                <th class="text-right align-middle">{{ number_format($gt_sss, 2) }}</th>
                                                <th class="text-right align-middle">{{ number_format($gt_phealth, 2) }}
                                                </th>
                                                <th class="text-right align-middle">{{ number_format($gt_pagibig, 2) }}
                                                </th>
                                                <th class="text-right align-middle">{{ number_format($gt_sss_er, 2) }}
                                                </th>
                                                <th class="text-right align-middle">
                                                    {{ number_format($gt_phealth_er, 2) }}
                                                </th>
                                                <th class="text-right align-middle">
                                                    {{ number_format($gt_pagibig_er, 2) }}
                                                </th>
                                                <th class="text-right align-middle">{{ number_format($gt_sss_loan, 2) }}
                                                </th>
                                                <th class="text-right align-middle">
                                                    {{ number_format($gt_pagibig_loan, 2) }}</th>
                                                <th class="text-right align-middle">{{ number_format($gt_sss_c, 2) }}
                                                </th>
                                                <th class="text-right align-middle">{{ number_format($gt_phealth_c, 2) }}
                                                </th>
                                                <th class="text-right align-middle">{{ number_format($gt_pagibig_c, 2) }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Deposits -->
                    <div class="tab-pane" id="BankDeposits" role="tabpanel">
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="bd_table"
                                    class="display nowrap table table-sm table-hover table-striped table-bordered"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" style="width: 150px">Account Number</th>
                                            <th class="text-center align-middle">Name</th>
                                            <th class="text-center align-middle">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gov_list">
                                        <?php
                                        $total_bd = 0;
                                        $cash_payment = 0;
                                        $online_payment = 0;
                                        ?>
                                        @if ($payroll_list)

                                            @foreach ($payroll_list as $data)
                                                <?php
                                                if ($data->bank_account == '') {
                                                    $cash_payment = $cash_payment + $data->net_pay_atm;
                                                } else {
                                                    $online_payment = $online_payment + $data->net_pay_atm;
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-center align-middle">{{ $data->bank_account }}</td>
                                                    <td class="text-left align-middle">{{ $data->emp_name }}</td>
                                                    <td class="text-right align-middle">
                                                        {{ number_format($data->net_pay_atm, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <?php
                                        $total_bd = $cash_payment + $online_payment;
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td class="text-right align-middle">Cash Payment:</td>
                                            <td class="text-right align-middle">{{ number_format($cash_payment, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="text-right align-middle">Online Payment:</td>
                                            <td class="text-right align-middle">{{ number_format($online_payment, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="text-right align-middle">Overall Total:</td>
                                            <td class="text-right align-middle">{{ number_format($total_bd, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payroll Summary -->
                    <div class="tab-pane active show" id="PayrollSummary" role="tabpanel">
                        <div class="card-body">

                        </div>
                    </div>

                </div>
            </div>
        </form>

        <hr>

    </div>

    <!-- /.container-fluid -->
    {{-- UPDATE MODAL --}}
    <div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd"
        style=" padding-right: 17px;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">UPDATE Payroll</h4>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true"
                        title="Close">×</button>
                </div>
                <form class="form-material" action="{{ url('/update_payroll') }}" method="post">
                    @csrf
                    <input type="text" id="payroll_id" name="payroll_id" hidden>
                    <div class="modal-body">
                        <div class="form-body">
                            {{-- EMP INFO --}}
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">ID</label>
                                        <input class="form-control" type="text" name="UserID_Empl" id="UserID_Empl"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input class="form-control" type="text" name="emp_name" id="emp_name"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- TOTALS --}}
                            <div class="row p-t-20">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Basic Pay</label>
                                        <input class="form-control" type="number" name="basic_pay" id="basic_pay"
                                            min="-9999999" step='any' onchange="ComputeTotal()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Gross Pay</label>
                                        <input class="form-control" type="number" name="gross_pay" id="gross_pay"
                                            min="-9999999" step='any' onchange="ComputeTotal()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Total Deduction</label>
                                        <input class="form-control" type="number" name="total_deduction"
                                            id="total_deduction" min="-9999999" step='any'
                                            onchange="ComputeTotal()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Net Pay</label>
                                        <input class="form-control" type="number" name="net_pay" id="net_pay"
                                            min="-9999999" step='any' onchange="ComputeTotal()">
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <!-- Rates -->
                                    <li class="nav-item">
                                        <a class="nav-link active show" data-toggle="tab" href="#Rates" role="tab"
                                            aria-selected="true">
                                            Rates
                                        </a>
                                    </li>
                                    <!-- Working Hours -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#WorkingHours" role="tab"
                                            aria-selected="false">
                                            Working Hours
                                        </a>
                                    </li>
                                    <!-- Holidays -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#Holidays" role="tab"
                                            aria-selected="false">
                                            Holidays
                                        </a>
                                    </li>
                                    <!-- Leaves -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#Leaves" role="tab"
                                            aria-selected="false">
                                            Leaves
                                        </a>
                                    </li>
                                    <!-- Allowance -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#AllowanceTab" role="tab"
                                            aria-selected="false">
                                            Allowance
                                        </a>
                                    </li>
                                    <!-- Deductions -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#Deduction" role="tab"
                                            aria-selected="false">
                                            Deductions
                                        </a>
                                    </li>
                                    <!-- ER -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#ER" role="tab"
                                            aria-selected="false">
                                            ER
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Tab Items -->
                            <div class="tab-content">
                                <!-- Rates -->
                                <div class="tab-pane active show" id="Rates" role="tabpanel">
                                    <div class="card-body">
                                        {{-- EMP RATE --}}
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Monthly Pay</label>
                                                    <input class="form-control" type="number" name="monthly_rate"
                                                        id="monthly_rate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Semi-Monthly Pay</label>
                                                    <input class="form-control" type="number" name="SemiMonthlyRate"
                                                        id="SemiMonthlyRate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Hourly Rate</label>
                                                    <input class="form-control" type="number" name="hourly_rate"
                                                        id="hourly_rate" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- EMP RATE 2 --}}
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">OT Rate</label>
                                                    <input class="form-control" type="number" name="reg_ot_rate"
                                                        id="reg_ot_rate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH Rate</label>
                                                    <input class="form-control" type="number" name="holiday_rate"
                                                        id="holiday_rate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SH Rate</label>
                                                    <input class="form-control" type="number" name="sunday_special_rate"
                                                        id="sunday_special_rate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">NP Rate</label>
                                                    <input class="form-control" type="number" name="night_premium_rate"
                                                        id="night_premium_rate" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                {{-- Hours --}}
                                <div class="tab-pane" id="WorkingHours" role="tabpanel">
                                    <div class="card-body">
                                        <h3>Hours</h3>
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Total Hour</label>
                                                    <input class="form-control" type="number" name="actual_hour"
                                                        id="actual_hour" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Absent</label>
                                                    <input class="form-control" type="number" name="absent_hour"
                                                        id="absent_hour" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Late</label>
                                                    <input class="form-control" type="number" name="late_min"
                                                        id="late_min" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Undertime</label>
                                                    <input class="form-control" type="number" name="ut"
                                                        id="ut" min="-9999999" step='any'
                                                        onchange="ComputeTotal()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">OT</label>
                                                    <input class="form-control" type="number" name="reg_ot_hours"
                                                        id="reg_ot_hours" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">RD</label>
                                                    <input class="form-control" type="number" name="RestDay"
                                                        id="RestDay" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">RD OT</label>
                                                    <input class="form-control" type="number" name="RestDayOT"
                                                        id="RestDayOT" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-20">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">NP</label>
                                                    <input class="form-control" type="number" name="night_premium_hour"
                                                        id="night_premium_hour" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">LH NP</label>
                                                    <input class="form-control" type="number"
                                                        name="night_premium_holiday_hour" id="night_premium_holiday_hour"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">SH NP</label>
                                                    <input class="form-control" type="number"
                                                        name="night_premium_sunday_special_hour"
                                                        id="night_premium_sunday_special_hour" min="-9999999"
                                                        step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h3>Payment</h3>
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">OT Pay</label>
                                                    <input class="form-control" type="number" name="ot_pay"
                                                        id="ot_pay" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">RD Pay</label>
                                                    <input class="form-control" type="number" name="RDPay"
                                                        id="RDPay" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">RD OT Pay</label>
                                                    <input class="form-control" type="number" name="RDOTPay"
                                                        id="RDOTPay" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-20">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">NP Pay</label>
                                                    <input class="form-control" type="number" name="night_premium_pay"
                                                        id="night_premium_pay" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">LH NP Pay</label>
                                                    <input class="form-control" type="number"
                                                        name="night_premium_holiday_pay" id="night_premium_holiday_pay"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">SH NP Pay</label>
                                                    <input class="form-control" type="number"
                                                        name="night_premium_sunday_pay" id="night_premium_sunday_pay"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h3>Deduction</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="row p-t-20">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Total Hour</label>
                                                        <input class="form-control" type="number" name="actual_hour"
                                                            id="actual_hour" min="-9999999" step='any'
                                                            onchange="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Absent</label>
                                                        <input class="form-control" type="number" name="absent_hour"
                                                            id="absent_hour" min="-9999999" step='any'
                                                            onchange="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Late</label>
                                                        <input class="form-control" type="number" name="late_min"
                                                            id="late_min" min="-9999999" step='any' onchange="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Undertime</label>
                                                        <input class="form-control" type="number" name="ut"
                                                            id="ut" min="-9999999" step='any'
                                                            onchange="ComputeTotal()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Holidays -->
                                <div class="tab-pane" id="Holidays" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH</label>
                                                    <input class="form-control" type="number" name="LegalHolidy"
                                                        id="LegalHolidy" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH OT</label>
                                                    <input class="form-control" type="number" name="LegalHolidayOT"
                                                        id="LegalHolidayOT" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH RD</label>
                                                    <input class="form-control" type="number" name="LegalHolidayRD"
                                                        id="LegalHolidayRD" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH RD OT</label>
                                                    <input class="form-control" type="number" name="LegalHolidayRDOT"
                                                        id="LegalHolidayRDOT" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SH</label>
                                                    <input class="form-control" type="number" name="SpecialHoliday"
                                                        id="SpecialHoliday" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SH OT</label>
                                                    <input class="form-control" type="number" name="SpecialHolidayOT"
                                                        id="LegalHolidayOT" min="-SpecialHolidayOT" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SH RD</label>
                                                    <input class="form-control" type="number" name="SpecialHolidayRD"
                                                        id="SpecialHolidayRD" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SH RD OT</label>
                                                    <input class="form-control" type="number" name="SpecialHolidayRDOT"
                                                        id="SpecialHolidayRDOT" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double SH</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleSpecialHoliday" id="DoubleSpecialHoliday"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double SH OT</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleSpecialHolidayOT" id="LegalHolidayOT"
                                                        min="-DoubleSpecialHolidayOT" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double SH RD</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleSpecialHolidayRD" id="DoubleSpecialHolidayRD"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double SH RD OT</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleSpecialHolidayRDOT" id="DoubleSpecialHolidayRDOT"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double LH</label>
                                                    <input class="form-control" type="number" name="DoubleLegalHoliday"
                                                        id="DoubleLegalHoliday" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double LH OT</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleLegalHolidayOT" id="DoubleLegalHolidayOT"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double LH RD</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleLegalHolidayRD" id="DoubleLegalHolidayRD"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Double LH RD OT</label>
                                                    <input class="form-control" type="number"
                                                        name="DoubleLegalHolidayRDOT" id="DoubleLegalHolidayRDOT"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH SH</label>
                                                    <input class="form-control" type="number"
                                                        name="LegalSpecialHoliday" id="LegalSpecialHoliday"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH SH OT</label>
                                                    <input class="form-control" type="number"
                                                        name="LegalSpecialHolidayOT" id="LegalSpecialHolidayOT"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH SH RD</label>
                                                    <input class="form-control" type="number"
                                                        name="LegalSpecialHolidayRD" id="LegalSpecialHolidayRD"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">LH SH RD OT</label>
                                                    <input class="form-control" type="number"
                                                        name="LegalSpecialHolidayRDOT" id="LegalSpecialHolidayRDOT"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Leaves -->
                                <div class="tab-pane" id="Leaves" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">VL w/ Pay</label>
                                                    <input class="form-control" type="number"
                                                        name="VacationLeaveWithPay" id="VacationLeaveWithPay"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SL w/ Pay</label>
                                                    <input class="form-control" type="number" name="SickLeaveWithPay"
                                                        id="SickLeaveWithPay" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">VL w/o Pay</label>
                                                    <input class="form-control" type="number"
                                                        name="VacationLeaveWithoutPay" id="VacationLeaveWithoutPay"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SL w/o Pay</label>
                                                    <input class="form-control" type="number"
                                                        name="SickLeaveWithoutPay" id="SickLeaveWithoutPay"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Special Leave</label>
                                                    <input class="form-control" type="number" name="spl"
                                                        id="spl" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Birth Day Leave</label>
                                                    <input class="form-control" type="number" name="BirthDayLeave"
                                                        id="BirthDayLeave" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Maternity Leave</label>
                                                    <input class="form-control" type="number" name="MaternityLeave"
                                                        id="MaternityLeave" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Paternity Leave</label>
                                                    <input class="form-control" type="number" name="PaternityLeave"
                                                        id="PaternityLeave" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Allowance -->
                                <div class="tab-pane" id="AllowanceTab" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Meal Allowance</label>
                                                    <input class="form-control" type="number" name="rice_allowance"
                                                        id="rice_allowance" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Transportation Allowance</label>
                                                    <input class="form-control" type="number"
                                                        name="TransportationAllowance" id="TransportationAllowance"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Housing Allowance</label>
                                                    <input class="form-control" type="number" name="HousingAllowance"
                                                        id="HousingAllowance" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Allowance</label>
                                                    <input class="form-control" type="number" name="Allowance"
                                                        id="Allowance" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Deminimis Benefit</label>
                                                    <input class="form-control" type="number" name="deminis_benefits"
                                                        id="deminis_benefits" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Deductions -->
                                <div class="tab-pane" id="Deduction" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SSS</label>
                                                    <input class="form-control" type="number" name="sss"
                                                        id="sss" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">WISP</label>
                                                    <input class="form-control" type="number" name="sss"
                                                        id="sss" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">PhilHealth</label>
                                                    <input class="form-control" type="number" name="philhealth"
                                                        id="philhealth" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">PAG-IBIG</label>
                                                    <input class="form-control" type="number" name="pagibig"
                                                        id="pagibig" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">SSS Loan</label>
                                                    <input class="form-control" type="number" name="sss_loan"
                                                        id="sss_loan" min="-9999999" step='any'
                                                        onchange="ComputeTotal()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">PAG-IBIG Loan</label>
                                                    <input class="form-control" type="number" name="pagibig_loan"
                                                        id="pagibig_loan" min="-9999999" step='any'
                                                        onchange="ComputeTotal()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">MOORGATE Loan</label>
                                                    <input class="form-control" type="number" name="MOORGATE_Loan"
                                                        id="MOORGATE_Loan" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">ATD</label>
                                                    <input class="form-control" type="number" name="ATD"
                                                        id="ATD" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-20">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Taxable Compensation</label>
                                                    <input class="form-control" type="number"
                                                        name="taxable_compensation" id="taxable_compensation"
                                                        min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Witholding Tax</label>
                                                    <input class="form-control" type="number" name="wtax"
                                                        id="wtax" min="-9999999" step='any' onchange="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Cash Advance</label>
                                                    <input class="form-control" type="number" name="cash_advance"
                                                        id="cash_advance" min="-9999999" step='any'
                                                        onchange="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- ER -->
                                <div class="tab-pane" id="ER" role="tabpanel">
                                    <div class="card-body">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-actions m-auto">
                                <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary ml-2"> <i
                                        class="fa fa-plus-circle"></i> UPDATE</button>
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
        $(document).ready(function() {
            $('#emp_payroll_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
                'btn btn-sm btn-primary mr-1');
            $('#gov_table').DataTable();
            $('#bd_table').DataTable({
                "order": [
                    [1, "asc"]
                ]
            });
        });

        // $('#example23').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print',
        //         {
        //             extend: "print",
        //             customize: function(win)
        //             {

        //                 var last = null;
        //                 var current = null;
        //                 var bod = [];

        //                 var css = '@page { size: landscape; }',
        //                     head = win.document.head || win.document.getElementsByTagName('head')[0],
        //                     style = win.document.createElement('style');

        //                 style.type = 'text/css';
        //                 style.media = 'print';

        //                 if (style.styleSheet)
        //                 {
        //                   style.styleSheet.cssText = css;
        //                 }
        //                 else
        //                 {
        //                   style.appendChild(win.document.createTextNode(css));
        //                 }

        //                 head.appendChild(style);
        //             }
        //          },
        //     ]
        // });



        function print_payroll() {
            var myWindow = window.open("{{ url('/print_payroll') }}", "myWindow", 'width=1500,height=800');
        }

        function ComputeTotal() {
            // var basic_pay = document.getElementById("basic_pay").value;
            // var honorarium = document.getElementById("honorarium").value;
            // var pagibig = document.getElementById("pagibig").value;
            // var sss = document.getElementById("sss").value;
            // var philhealth = document.getElementById("philhealth").value;
            // var wtax = document.getElementById("wtax").value;
            // var coop = document.getElementById("coop").value;
            // var pagibig_loan = document.getElementById("pagibig_loan").value;
            // var sss_loan = document.getElementById("sss_loan").value;
            // var other_loan = document.getElementById("other_loan").value;
            // var absent = document.getElementById("absent").value;
            // var late = document.getElementById("late").value;
            // document.getElementById("total_deduction").value = parseFloat(pagibig) + parseFloat(sss) + parseFloat(
            //         philhealth) + parseFloat(wtax) + parseFloat(coop) + parseFloat(pagibig_loan) + parseFloat(sss_loan) +
            //     parseFloat(other_loan) + parseFloat(absent) + parseFloat(late);
            // document.getElementById("net_pay").value = (parseFloat(basic_pay) + parseFloat(honorarium)) - parseFloat(
            //     document.getElementById("total_deduction").value);
        }

        function ShowModal(index) {
            clearFields();
            var row_val = $('#list_body').find('tr');
            document.getElementById("payroll_id").value = $('#emp_payroll_table').DataTable().cell(index, 1).data();
            document.getElementById("UserID_Empl").value = $('#emp_payroll_table').DataTable().cell(index, 4).data();
            document.getElementById("emp_name").value = $('#emp_payroll_table').DataTable().cell(index, 5).data();
            // document.getElementById("monthly_rate").value = $('#emp_payroll_table').DataTable().cell(index, 8).data()
            //     .replace(",", "");
            // document.getElementById("basic_pay").value = $('#emp_payroll_table').DataTable().cell(index, 72).data().replace(
            //     ",", "");
            // document.getElementById("pagibig").value = $('#emp_payroll_table').DataTable().cell(index, 7).data().replace(
            //     ",", "");
            // document.getElementById("sss").value = $('#emp_payroll_table').DataTable().cell(index, 8).data().replace(",",
            //     "");
            // document.getElementById("philhealth").value = $('#emp_payroll_table').DataTable().cell(index, 9).data().replace(
            //     ",", "");
            // document.getElementById("wtax").value = $('#emp_payroll_table').DataTable().cell(index, 10).data().replace(",",
            //     "");
            // document.getElementById("pagibig_loan").value = $('#emp_payroll_table').DataTable().cell(index, 13).data()
            //     .replace(",", "");
            // document.getElementById("sss_loan").value = $('#emp_payroll_table').DataTable().cell(index, 14).data().replace(
            //     ",", "");
            // document.getElementById("ut").value = $('#emp_payroll_table').DataTable().cell(index, 18).data().replace(",",
            //     "");
            // document.getElementById("total_deduction").value = $('#emp_payroll_table').DataTable().cell(index, 127).data()
            //     .replace(",", "");
            // document.getElementById("net_pay").value = $('#emp_payroll_table').DataTable().cell(index, 128).data().replace(
            //     ",", "");
            $('#entry_modal').modal('show');

        }

        function clearFields() {
            // document.getElementById("payroll_id").value = 0;
            // document.getElementById("emp_name").value = "";
            // document.getElementById("monthly_rate").value = 0;
            // document.getElementById("basic_pay").value = 0;
            // document.getElementById("pagibig").value = 0;
            // document.getElementById("sss").value = 0;
            // document.getElementById("philhealth").value = 0;
            // document.getElementById("wtax").value = 0;
            // document.getElementById("pagibig_loan").value = 0;
            // document.getElementById("sss_loan").value = 0;
            // document.getElementById("ut").value = 0;
            // document.getElementById("total_deduction").value = 0;
            // document.getElementById("net_pay").value = 0;
        }
    </script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}
