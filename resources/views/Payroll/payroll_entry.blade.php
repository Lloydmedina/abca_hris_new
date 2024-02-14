@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS --}}
@section('page_level_css')
    <link href="{{ asset('uidesign/vendor/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link href="{{ asset('uidesign/css/custom/custom_mat.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title', 'Payroll Entry')
{{-- BEGIN CONTENT --}}
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid" style="min-height: 700px">

        @include('Templates.alert_message')

        <form class="form-material" action="{{ url('/payroll_entry') }}" method="get">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Company</label>
                                <select id="Department_Empl" name="Department_Empl" class="form-control custom-select">
                                    @foreach ($department as $row)
                                        @if ($dept_id)
                                            @if ($dept_id == $row->company_id)
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
                                <label class="control-label">Paygroup</label>
                                <select id="paygroup_id" name="paygroup_id" class="form-control custom-select">
                                    @foreach ($paygroup as $row)
                                        @if ($paygroup_id)
                                            @if ($paygroup_id == $row->paygroup_id)
                                                <option value="{{ $row->paygroup_id }}" selected>{{ $row->paygroup }}</option>
                                            @else
                                                <option value="{{ $row->paygroup_id }}">{{ $row->paygroup }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $row->paygroup_id }}">{{ $row->paygroup }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Date From</label>
                                <input type="date" class="form-control" id="date_from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d'); ?>"
                                    name="date_from" required>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Date To</label>
                                <input type="date" class="form-control" id="date_to" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d', strtotime(date('Y-m-d') . ' + 15 days')); ?>"
                                    name="date_to" required>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label class="hide" style="visibility: hidden">Search</label>
                                @include('button_component.search_button', ['margin_top' => "16.5"])
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
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-4">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Employee's Payroll</div>
                            <hr>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center" hidden>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">Company</label>
                                <select id="p_Department_Empl" name="p_Department_Empl" class="form-control custom-select">
                                    <option value="{{ 0 }}" selected>All</option>
                                    @foreach ($department as $row)
                                        @if ($dept_id)
                                            @if ($dept_id == $row->company_id)
                                                <option value="{{ $row->company_id }}" selected>{{ $row->company }}
                                                </option>
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
                    </div>
                    <div class="row mx-1" hidden>
                        <input type="text" id="p_paygroup_id" name="p_paygroup_id" value="<?php echo isset($_GET['paygroup_id']) ? $_GET['paygroup_id'] : 0; ?>"/>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Date From</label>
                                <input type="date" class="form-control" id="p_date_from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d'); ?>"
                                    name="p_date_from" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Date To</label>
                                <input type="date" class="form-control" id="p_date_to" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d', strtotime(date('Y-m-d') . ' + 15 days')); ?>"
                                    name="p_date_to" required>
                            </div>
                        </div>
                    </div>
                

                <div class="table-responsive m-t-40">
                    <table id="emp_payroll_table"
                        class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
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
                                <th class="text-center align-middle">Late<br>Hr.</th>
                                <th class="text-center align-middle">OT<br>Hours</th>
                                <th class="text-center align-middle">NP<br>Hours</th>
                                <th class="text-center align-middle">NP Hol.<br>Hours</th>
                                <th class="text-center align-middle">NP Sun/Sp Hol.<br>Hours</th>
                                <th class="text-center align-middle">Rest Day </th>
                                <th class="text-center align-middle">Rest Day OT </th>
                                <th class="text-center align-middle">Legal Holiday </th>
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
                                <th class="text-center align-middle">Medical Cash<br>Allowance<br>Dependent</th>
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
                                ?>
                                @foreach ($payroll_list as $data)
                                    <tr>
                                        <td class="text-center align-middle">{{ $no }}</td>
                                        <td class="text-center align-middle">{{ $data->AccountNo }}</td>
                                        <td class="text-left align-middle">{{ $data->UserID }}</td>
                                        <td class="text-left align-middle">{{ $data->EmployeeName }}</td>
                                        <td class="text-left align-middle">{{ $data->Dept }}</td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->DailyRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->MonthlyRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SemiMonthlyRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->HourlyRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->RegOTRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->HolidayRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SunOtRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->NPRate, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->StandardHour, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->AbsentHour, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->UTHour, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SL_VL, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->ActualHour, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->LateMin, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->RegOTHour, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->RegularNP, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->HolidayNP, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SunNP, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->RestDay, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->RestDayOT, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->LegalHolidy, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->LegalHolidayOT, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->LegalHolidayRD, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->LegalHolidayRDOT, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->SpecialHoliday, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->SpecialHolidayOT, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->SpecialHolidayRD, 2) }}
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
                                        <td class="text-right align-middle">{{ number_format($data->SickLeaveWithPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->VacationLeaveWithoutPay, 2) }}</td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SickLeaveWithoutPay, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->spl, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->BirthDayLeave, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->MaternityLeave, 2) }}
                                        </td>
                                        <td class="text-right align-middle">{{ number_format($data->PaternityLeave, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->TotalManHour, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->RiceAllowance, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->ClothingAllowance, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->MonetizedUnusedLeave, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->MedicalCashAllowanceDependent, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->MedicalAssistance, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->LaundryAllowance, 2) }}
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
                                            {{ number_format($data->Deminimis, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->COLA, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->CommunicationAllowance, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->BasicPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->RegOTPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SunPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SunOTPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->HolidayPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->HolidayOTPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->NPPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->HolidayNPPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SunNPPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->PaternityLeavePay, 2) }}</td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->MaternityLeavePay, 2) }}</td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->BirthdayLeavePay, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->SLPay, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->VLPay, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->RDPay, 2) }}</td>
                                        <td class="text-right align-middle">{{ number_format($data->RDOTPay, 2) }}</td>
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
                                            {{ number_format($data->DoubleSpecialHolidayRDOTPay, 2) }}</td>
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
                                            {{ number_format($data->ServiceFee, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->AdditionalAdjustment, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->OtherIncome, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->LateDeduction, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->UTDeduction, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->AbsentDeduction, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->GrossPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->TaxableCompensation, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SSS, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SSSWISP, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->PhilHealth, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->PAGIBIG, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->WTax, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->UnderWithheld, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->OtherDeductions, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->TotalTax, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SSSLoan, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->PAGIBIGLoan, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->MOORGATE_Loan, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->DeductionAdjustment, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->CA, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->ATD, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->TotalDeductions, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->NetPay, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SSSER, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->SSSWISPER, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->PhilHealthER, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->PAGIBIGER, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->WtaxER, 2) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            {{ number_format($data->TotalER, 2) }}
                                        </td>
                                    </tr>
                                    <?php $no = $no + 1; ?>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row pt-3">
                    <div class="col-lg-3 col-sm-12">
                        <button class="btn btn-sm btn-success mb-2" type="button" onclick="print_payroll()">
                            <i class="fa-solid fa-print"></i> Print
                        </button>
                    </div>
                    <div class="col-lg-9 col-sm-12 text-lg-right">
                        <button class="btn btn-sm btn-dark mb-2" type="submit">
                            <i class="fa-regular fa-floppy-disk"></i> Save
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </form>
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
        $(document).ready(function() {

        });

        $('#emp_payroll_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
            'btn btn-sm btn-primary mr-1');

        function print_payroll() {
            var myWindow = window.open("{{ url('/print_payroll') }}", "myWindow", 'width=1500,height=800');
        }
    </script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}
