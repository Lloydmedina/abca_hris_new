@extends('Templates.print_layout')
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

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-sm table-bordered">
            <tr>
                <th colspan="12" class="text-center"><img src="public/img/abaca_logo.png" alt="" style="width: 20%"></th>
            </tr>
            <tr>
                <th colspan="12" class="text-center">Performance Appraisal Report</th>
            </tr>
            <tr>
                <td colspan="1"><b>Employee</b></td>
                <td colspan="2"><span id="employee_name">{{ $employee->Name_Empl }}</span></td>
                <td colspan="3">Job Title/Type</td>
                <td colspan="2"><span id="employee_position">{{ $employee->Position_Empl }}</span></td>
                <td colspan="2">Date Hired</td>
                <td colspan="2"><span id="employee_date_hired">{{ date('M d, Y', strtotime($employee->DateHired_Empl))}}</span></td>
            </tr>
            <tr>
                <td colspan="1"><b>Supervisor</b></td>
                <td colspan="2">{{ $evaluator->Name_Empl }}</td>
                <td colspan="3">Section/Unit</td>
                <td colspan="2">{{ $evaluator->Position_Empl }}</td>
                <td colspan="2">Department</td>
                <td colspan="2">{{ $evaluator->Department_Empl }}</td>
            </tr>
            <tr>
                <td colspan="12"><b>Part I - Supervisor's Evaluation</b></td>
            </tr>

            @if(count($part_1_eval_data) > 0)
                <?php $ctr = 1; ?>
                @foreach($part_1_eval_data as $row)
                <tr>
                    <td colspan="12">
                        <div class="row">
                            <div class="col-3"><b>{{ $ctr }}. {{ $row->title }}</b></div>
                            <div class="col-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="p1_no_{{ $ctr }}_score" style="transform: scale(1.5);" {{ ($part_1_scoring->score1 == $row->score) ? 'checked' : 'disabled' }}/>Sometimes Complied
                                </label>
                            </div>
                            </div>
                            <div class="col-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="p1_no_{{ $ctr }}_score" style="transform: scale(1.5);" {{ ($part_1_scoring->score2 == $row->score) ? 'checked' : 'disabled' }}/>Meet Expectations
                                </label>
                            </div>
                            </div>
                            <div class="col-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"  name="p1_no_{{ $ctr }}_score" style="transform: scale(1.5);" {{ ($part_1_scoring->score3 == $row->score) ? 'checked' : 'disabled' }}/>Exceeds Expectations
                                </label>
                            </div>
                            </div>
                            <div class="col-12">
                                {{ $part_1_evaluations[$ctr-1]->description }}
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

            @if(count($part_2_eval_data))
                <?php $ctr = 1; ?>
                @foreach($part_2_eval_data as $row)
                    <tr>
                        <td colspan="9">{{ $ctr }}. <b>{{ $row->title }}:</b> <br /> {{ $part_2_evaluations[$ctr-1]->description }}</td>
                        <td colspan="1" class="text-center">
                            <div class="form-check" title="{{ '$row->score1' }}">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="{{ '$row->score1' }}" name="p2_no_{{ $ctr }}_score" style="transform: scale(1.5);" {{ ($part_2_scoring->score1 == $row->score) ? 'checked' : 'disabled' }}/>
                                </label>
                            </div>
                        </td>
                        <td colspan="1" class="text-center">
                            <div class="form-check" title="{{ '$row->score2' }}">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="{{ '$row->score2' }}" name="p2_no_{{ $ctr }}_score" style="transform: scale(1.5);" {{ ($part_2_scoring->score2 == $row->score) ? 'checked' : 'disabled' }}/>
                                </label>
                            </div>
                        </td>
                        <td colspan="1" class="text-center">
                            <div class="form-check" title="{{ '$row->score3' }}">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="{{ '$row->score3' }}" name="p2_no_{{ $ctr }}_score" style="transform: scale(1.5);" {{ ($part_2_scoring->score3 == $row->score) ? 'checked' : 'disabled' }}/>
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
                    <textarea name="superior_assessment" rows="3" style="width:100%" readonly>{{$part_1_2_3_eval_data->superior_assessment}}</textarea>
                    _________________________
                    <br />
                    <span class="ml-3">Supervisor's Signature</span>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="p-3">
                    <span>Rated employee's comments:</span>
                    <br />
                    <textarea name="rated_emp_comments" rows="3" style="width:100%"readonly>{{$part_1_2_3_eval_data->rated_emp_comments}}</textarea>
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

    <div class="card">
        <div class="card-body table-responsive">
                <table class="table table-sm table-bordered">
                    <tr>
                        <th colspan="12" class="text-center"><img src="public/img/abaca_logo.png" alt="" style="width: 20%"></th>
                    </tr>
                    <tr>
                        <td colspan="1"><b>Employee</b></td>
                        <td colspan="2"><span id="employee_name">{{ $employee->Name_Empl }}</span></td>
                        <td colspan="3">Job Title/Type</td>
                        <td colspan="2"><span id="employee_position">{{ $employee->Position_Empl }}</span></td>
                        <td colspan="2">Date Hired</td>
                        <td colspan="2"><span id="employee_date_hired">{{ date('M d, Y', strtotime($employee->DateHired_Empl))}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="1"><b>Supervisor</b></td>
                        <td colspan="2">{{ $evaluator->Name_Empl }}</td>
                        <td colspan="3">Section/Unit</td>
                        <td colspan="2">{{ $evaluator->Position_Empl }}</td>
                        <td colspan="2">Department</td>
                        <td colspan="2">{{ $evaluator->Department_Empl }}</td>
                    </tr>
                    <tr>
                        <td colspan="12" class="text-center"><b>Rating Interpretation</b></td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom:none; border-top:none">
                            <div class="row">
                                <div class="col-3" title="Total Scores {{ '$part_1_total_scores' }}"><b>Part I: Supervisor's Evaluation 20%</b></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-3">
                                    <input type="number" id="part_1_score_percentage" name="part_1_score_percentage" value="{{ $part_1_2_3_eval_data->part_1_score_percentage }}" class="float-right text-right" style="width: 5em" readonly/>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom:none; border-top:none">
                            <div class="row">
                                <div class="col-3"><b>Part II: Performance Evaluation 80%</b></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-3">
                                    <input type="number" id="part_2_score_percentage" name="part_2_score_percentage" value="{{ $part_1_2_3_eval_data->part_2_score_percentage }}" class="float-right text-right" style="width: 5em" readonly/>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom:none; border-top:none">
                            <div class="row">
                                <div class="col-12"><b>Part III: Demerit</b></div>

                                <div class="col-3">A. Compliance Report and Violations <br /> <small class="ml-4">Deductible from weighted score</small></div>
                                <div class="col-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_1_score" value="10" name="p3_no_1_score" style="transform: scale(1.5);" {{ ($part_1_2_3_eval_data->p3_no_1_score == 10) ? 'checked' : 'disabled' }}><small>[10] with suspension/s or major compliance violation</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_1_score" value="5" name="p3_no_1_score" style="transform: scale(1.5);" {{ ($part_1_2_3_eval_data->p3_no_1_score == 5) ? 'checked' : 'disabled' }}><small>[5] with warnings and/ or 3+ minor violations</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_1_score" value="0" name="p3_no_1_score" style="transform: scale(1.5);" {{ ($part_1_2_3_eval_data->p3_no_1_score == 0) ? 'checked' : 'disabled' }}><small>[0] no cited violations</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <input type="number" id="p3_deduct_1" name="p3_deduct_1" value="{{ $part_1_2_3_eval_data->p3_deduct_1 }}" class="float-right text-right" style="width: 5em" readonly/>
                                </div>
                                
                                <div class="col-3 mt-3">B. Interdepartment Relationships <br /> <small class="ml-4">Deductible from weighted score</small></div>
                                <div class="col-2 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_2_score" value="10" name="p3_no_2_score" style="transform: scale(1.5);" {{ ($part_1_2_3_eval_data->p3_no_2_score == 10) ? 'checked' : 'disabled' }} /><small>[10] 75% achievement</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_2_score" value="5" name="p3_no_2_score" style="transform: scale(1.5);" {{ ($part_1_2_3_eval_data->p3_no_2_score == 5) ? 'checked' : 'disabled' }} /><small> [5] 75-85% achievement</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_2_score" value="0" name="p3_no_2_score" style="transform: scale(1.5);" {{ ($part_1_2_3_eval_data->p3_no_2_score == 0) ? 'checked' : 'disabled' }}><small> [0] 86-100% achievement</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3 mt-3">
                                    <input type="number" id="p3_deduct_2" name="p3_deduct_2" value="0" class="float-right text-right" style="width: 5em" readonly/>
                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom:none; border-top:none">
                            <div class="row">
                                <div class="col-3"><b>Net Performance Rating</b></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-3">
                                    <input type="number" value="{{ $part_1_2_3_eval_data->net_performance_rating }}" name="net_performance_rating" class="float-right text-right" style="width: 5em" readonly/>
                                </div>
                            </div>
                        </td>
                    </tr>
                        <td colspan="12">
                            <div class="row">
                                <div class="col-6">
                                    <textarea name="hr_remarks" id="" cols="62" rows="10" readonly>{{ $part_1_2_3_eval_data->hr_remarks }}</textarea>
                                    <br />
                                    <br />
                                    _____________________________________
                                    <br />
                                    <span class="ml-3">Human Resources Department</span>
                                </div>
                                <div class="col-6">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center"><small>Compensation</small></th>
                                                <th colspan="2" class="text-center" title="Compensation Adjustment Index "><small>(CAI)</small></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="text-center"><small>Salary</small></td>
                                                <td class="text-center"><small>Allowance</small></td>
                                                <td class="text-center"><small>Score</small></td>
                                                <td class="text-center" title="Percentage"><small>%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><small>Existing compensation</small></td>
                                                <td class="text-right">{{ number_format($part_1_2_3_eval_data->basic_salary, 2) }}</td>
                                                <td class="text-right">{{ number_format($total_allowance, 2) }}</td>
                                                <td class="text-center"><small>97-100</small></td>
                                                <td class="text-center"><small>15%</small></td>
                                            </tr>
                                            <tr>
                                                <td><small>CAI</small></td>
                                                <td class="text-right"><small><span id=""></span> {{ $part_1_2_3_eval_data->percentage_score}}%</small></td>
                                                <td class="text-right" id="">
                                                    <?php
                                                        $salary_increased = ($part_1_2_3_eval_data->basic_salary / 100) * $part_1_2_3_eval_data->percentage_score;
                                                        echo number_format($salary_increased, 2);   
                                                    ?>
                                                </td>
                                                <td></td>
                                                <td class="text-center"><small>95-96</small></td>
                                                <td class="text-center"><small>14%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><small>Compensation Adjustment</small></td>
                                                <td class="text-right" id="">
                                                    <?php
                                                        $salary_increased = ($part_1_2_3_eval_data->basic_salary / 100) * $part_1_2_3_eval_data->percentage_score;
                                                        $compensation_adjustments_val =  $salary_increased + $part_1_2_3_eval_data->basic_salary;
                                                        echo  number_format($compensation_adjustments_val , 2);
                                                    ?>
                                                </td>
                                                <td></td>
                                                <td class="text-center"><small>93-94</small></td>
                                                <td class="text-center"><small>13%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>91-92</small></td>
                                                <td class="text-center"><small>12%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>90-91</small></td>
                                                <td class="text-center"><small>11%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>88-89</small></td>
                                                <td class="text-center"><small>10%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>86-87</small></td>
                                                <td class="text-center"><small>9%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>84-85</small></td>
                                                <td class="text-center"><small>8%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>82-83</small></td>
                                                <td class="text-center"><small>7%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>80-81</small></td>
                                                <td class="text-center"><small>6%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>78-79</small></td>
                                                <td class="text-center"><small>5%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>76-77</small></td>
                                                <td class="text-center"><small>4%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>74-75</small></td>
                                                <td class="text-center"><small>3%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>72-73</small></td>
                                                <td class="text-center"><small>2%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border:none"></td>
                                                <td class="text-center"><small>70-71</small></td>
                                                <td class="text-center"><small>1%</small></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </td>
                    <tr>
                        <td colspan="12" class="text-center">
                            <small><b>Immediate Superior's assessment (Discuss with employee)</b></small>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="p-3">
                            <span>Recommendation:</span>
                            <br />
                            <textarea name="recommendation" rows="2" style="width:100%" readonly>{{ $part_1_2_3_eval_data->recommendation }}</textarea>
                            <br />
                            <br />
                            __________________________
                            <br />
                            <span class="ml-4">Department Head</span>
                        </td>
                        <td colspan="6" class="p-3">
                            <span>Remakrs/Approval:</span>
                            <br />
                            <textarea name="remarks_approval" rows="2" style="width:100%" readonly>{{ $part_1_2_3_eval_data->remarks_approval }}</textarea>
                            <br />
                            <br />
                                <span style="text-decoration: underline">Benedicto L. Aliser</span>
                            <br />
                            <span class="ml-3">Chief Executive</span>
                        </td>
                    </tr>
                </table>
                
        </div>
    </div>
    <button type="submit" id="" class="btn btn-sm btn-info float-right mt-2 ml-2" onclick="return confirm('Print evaluation?')">Print Evaluation</button>

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