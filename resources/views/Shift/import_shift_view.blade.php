
<input type="date" value="{{ $shiftDates['SUN'] ?? $shiftDates['MON'] ?? $shiftDates['TUE'] ?? $shiftDates['WED'] ?? $shiftDates['THU'] ?? $shiftDates['FRI'] ?? $shiftDates['SAT'] }}" name="date_from" hidden>
<input type="date" value="{{ $shiftDates['SAT'] ?? $shiftDates['FRI'] ?? $shiftDates['THU'] ?? $shiftDates['WED'] ?? $shiftDates['TUE'] ?? $shiftDates['MON'] ?? $shiftDates['SUN'] }}" name="date_to" hidden>

<div class="table-responsive">
    <table class="table table-sm table-bordered text-sm">
        <thead>
            <tr>
                <th scope="col" style="width: 10%"></th>
                <th scope="col" style="width: 20%"></th>
                @foreach ($shiftDates as $i => $val)
                    <th scope="col" class="text-center">{{ date('M d, Y', strtotime($val)) }}</th>
                @endforeach
            </tr>
            <tr>
                <th scope="col">Emp. No.</th>
                <th scope="col">Name</th>
                @foreach ($shiftDates as $i => $val)
                    <th scope="col" class="text-center">{{ $i }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            
            @if($empShiftView)
                @foreach ($empShiftView as $val)
                    <tr>
                        <td scope="row"><small>{{ $val['emp_id_numer'] }}</small></td>
                        <td><small>{{ $val['emp_name'] }}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_1'] }} <br/> {!! $val['emp_shift_code_desc_1'] !!}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_2'] }} <br/> {!! $val['emp_shift_code_desc_2'] !!}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_3'] }} <br/> {!! $val['emp_shift_code_desc_3'] !!}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_4'] }} <br/> {!! $val['emp_shift_code_desc_4'] !!}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_5'] }} <br/> {!! $val['emp_shift_code_desc_5'] !!}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_6'] }} <br/> {!! $val['emp_shift_code_desc_6'] !!}</small></td>
                        <td class="text-center"><small>{{ $val['emp_shift_code_7'] }} <br/> {!! $val['emp_shift_code_desc_7'] !!}</small></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No data found. <br> Please double-check the Excel file format and the employee details if you are the approver of them.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>