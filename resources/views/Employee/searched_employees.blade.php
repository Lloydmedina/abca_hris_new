@if(count($employees) > 0)
    @foreach($employees as $emp)
    <tr>
        <td>{{ $emp->Name_Empl }}</td>
        <td>{{ $emp->emp_position }}</td>
        <td>{{ $emp->Department_Empl }}</td>
        <td>{{ $emp->Status_Empl }}</td>
        <td class="text-center employee-checkbox">
            <input type="checkbox" class="checked" style="top: .8rem;width: 1rem;height: 1rem;" value="{{ $emp->SysPK_Empl }}" name="checked[]">
        </td>
        <td hidden><input type="text" name="employee_id[]" value="'+result[employee].SysPK_Empl+'"></td>
        <td hidden><input type="text" name="employee_number[]" value="'+result[employee].UserID_Empl+'"></td>
        <td hidden><input type="text" name="emp_name[]" value="'+result[employee].Name_Empl+'"></td>
    </tr>
    @endforeach
@else
0
@endif