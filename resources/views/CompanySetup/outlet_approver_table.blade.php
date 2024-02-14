<table class="table table-sm table-borderless" style="margin-top: -5px">
    <thead class="mb-2" style="position: sticky; inset-block-start: 0; background-color: rgb(61, 128, 242)">
      <tr>
        <th colspan="2" class="text-white">Approver <span class="float-right">(<span class="total_approver">{{ count( $employees) }}</span>)</span></th>
      </tr>
    </thead>
    <tbody>
        @if($employees)
            @foreach ( $employees as $employee )
                <tr>
                    <td><a href=""  data-emp_id={{ $employee->SysPK_Empl }} class="remove_emp">Remove</a></td>
                    <td class="text-left">{{ $employee->Name_Empl }}</td>
                </tr>
            @endforeach
        @else
            <tr><td class="text-center p-4" colspan="2">Select an approver below</td></tr>
        @endif
    </tbody>
  </table>