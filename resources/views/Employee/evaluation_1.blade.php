@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Evaluation')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @include('Templates.alert_message')

    <div class="row">
        <form action="{{ route('evaluation_2') }}" method="POST">
            @csrf
            <div class="col-3">
                <div class="form-group">
                    <select id="employee_id" name="employee_id" class="border form-control custom-select selectpicker" data-live-search="true" required>
                            <option value="" selected disabled>Select Employee</option>
                        @foreach($employess as $row)
                            <option value="{{ $row->SysPK_Empl.md5($row->SysPK_Empl) }}" @if (old('employee_id') == $row->SysPK_Empl.md5($row->SysPK_Empl)) {{ 'selected' }} @endif>{{ $row->Name_Empl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-bordered">
                        <tr>
                            <th colspan="12" class="text-center"><img src="{{ asset('public/img/abaca_logo.png') }}" alt="" style="width: 15%"></th>
                        </tr>
                        <tr>
                            <th colspan="12" class="text-center">Performance Appraisal Report</th>
                        </tr>
                        <tr>
                            <td colspan="1"><b>Employee</b></td>
                            <td colspan="2"><span id="employee_name"></span></td>
                            <td colspan="3">Job Title/Type</td>
                            <td colspan="2"><span id="employee_position"></span></td>
                            <td colspan="2">Date Hired</td>
                            <td colspan="2"><span id="employee_date_hired"></span></td>
                        </tr>
                        <tr>
                            <td colspan="1"><b>Supervisor</b></td>
                            <td colspan="2">{{ ucwords(strtolower(session('user')->first_name.' '.session('user')->last_name)) }}</td>
                            <td colspan="3">Section/Unit</td>
                            <td colspan="2">{{ session('employee')->emp_position }}</td>
                            <td colspan="2">Department</td>
                            <td colspan="2">{{ session('employee')->Department_Empl }}</td>
                        </tr>
                        <tr>
                            <td colspan="12"><b>Part I - Supervisor's Evaluation</b></td>
                        </tr>

                        @if(count($part_1_eval) > 0)
                            <?php $ctr = 1; ?>
                            @foreach($part_1_eval as $row)
                            <tr>
                                <td colspan="12">
                                    <div class="row">
                                        <div class="col-3"><b>{{ $ctr }}. {{ $row->title }}</b></div>
                                        <input type="hidden" name="part_1_eval_id[]" value="{{ $row->id }}" />
                                        <input type="hidden" name="part_1_eval_title[]" value="{{ $row->title }}" />
                                        <div class="col-3">
                                        <div class="form-check" title="{{ $row->score1 }}">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="{{ $row->score1 }}" name="p1_no_{{ $ctr }}_score" style="transform: scale(1.5);" checked/>Sometimes Complied
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-3">
                                        <div class="form-check" title="{{ $row->score2 }}">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="{{ $row->score2 }}" name="p1_no_{{ $ctr }}_score" style="transform: scale(1.5);"/>Meet Expectations
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-3">
                                        <div class="form-check" title="{{ $row->score3 }}">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="{{ $row->score3 }}" name="p1_no_{{ $ctr }}_score" style="transform: scale(1.5);"/>Exceeds Expectations
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-12">
                                            {{ $row->description }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $ctr++; ?>
                            @endforeach
                            {{-- - 1 kay 10 nya nag increment pa --}}
                            <input type="hidden" value="{{ $ctr - 1 }}" name="part_1_array_score" />
                        @endif
                        
                        <tr style="border-top:solid">
                            <td colspan="9"><b>Part II 80%</b></td>
                            <td colspan="1" class="text-center">Sometimes Complied</td>
                            <td colspan="1" class="text-center">Most Often Complied</td>
                            <td colspan="1" class="text-center">Always Complied</td>
                        </tr>

                        @if(count($part_2_eval) > 0)
                            <?php $ctr = 1; ?>
                            @foreach($part_2_eval as $row)
                                <tr>
                                    <td colspan="9">{{ $ctr }}. <b>{{ $row->title }}:</b> <br /> {{ $row->description }}</td>
                                    <input type="hidden" name="part_2_eval_id[]" value="{{ $row->id }}" />
                                    <input type="hidden" name="part_2_eval_title[]" value="{{ $row->title }}" />
                                    <td colspan="1" class="text-center">
                                        <div class="form-check" title="{{ $row->score1 }}">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="{{ $row->score1 }}" name="p2_no_{{ $ctr }}_score" style="transform: scale(1.5);" checked/>
                                            </label>
                                        </div>
                                    </td>
                                    <td colspan="1" class="text-center">
                                        <div class="form-check" title="{{ $row->score2 }}">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="{{ $row->score2 }}" name="p2_no_{{ $ctr }}_score" style="transform: scale(1.5);"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td colspan="1" class="text-center">
                                        <div class="form-check" title="{{ $row->score3 }}">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="{{ $row->score3 }}" name="p2_no_{{ $ctr }}_score" style="transform: scale(1.5);"/>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <?php $ctr++ ?>
                            @endforeach
                            {{-- - 1 kay 10 nya nag increment pa --}}
                            <input type="hidden" value="{{ $ctr - 1 }}" name="part_2_array_score" />
                        @endif

                        <tr>
                            <td colspan="12" class="p-3">
                                <span>Immediate Superior's assessment (Discuss with employee)</span>
                                <br />
                                <textarea name="superior_assessment" rows="3" style="width:100%"></textarea>
                                _________________________
                                <br />
                                <span class="ml-3">Supervisor's Signature</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="p-3">
                                <span>Rated employee's comments:</span>
                                <br />
                                <textarea name="rated_emp_comments" rows="3" style="width:100%"></textarea>
                                _________________________
                                <br />
                                <span class="ml-4">Ratee's Signature</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" class="text-center"><small><b>Forward completed Performance Appraisal Report to HRD</b></small></td>
                        </tr>
                        </table>
                    </div>
                </div>
                <button type="submit" id="btn_submit" class="btn btn-sm btn-info float-right mt-2 ml-2 d-none">Next</button>
                <button type="button" id="btn_next" class="btn btn-sm btn-info float-right mt-2 ml-2">Next Page >></button>
            </div>
            
        </form>
            
    </div>

    <hr>
</div>
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/employees_evaluation.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}