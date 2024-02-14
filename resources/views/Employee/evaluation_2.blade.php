@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')

@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Evaluation 2')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @include('Templates.alert_message')

    <form action="{{ url('/save_print_evaluation') }}" method="POST">
        <div class="card">
            <div class="card-body table-responsive">
                
                @csrf
                <input type="hidden" name="emp_id" value="{{ $employee->SysPK_Empl }}" />
                <table class="table table-sm table-bordered">
                    <tr>
                        <th colspan="12" class="text-center"><img src="{{ asset('public/img/abaca_logo.png') }}" alt="" style="width: 15%"></th>
                    </tr>
                    <tr>
                        <td colspan="1"><b>Employee</b></td>
                        <td colspan="2">{{ $employee->Name_Empl }}</td>
                        <td colspan="3">Job Title/Type</td>
                        <td colspan="2">{{ $employee->Position_Empl }}</td>
                        <td colspan="2">Date Hired</td>
                        <td colspan="2">{{ date('M d, Y', strtotime($employee->DateHired_Empl)) }}</td>
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
                        <td colspan="12" class="text-center"><b>Rating Interpretation</b></td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom:none; border-top:none">
                            <div class="row">
                                <div class="col-3" title="Total Scores {{ $part_1_total_scores }}"><b>Part I: Supervisor's Evaluation 20%</b></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-2"></div>
                                <div class="col-3">
                                    <input type="number" id="part_1_score_percentage" name="part_1_score_percentage" value="{{ $part_1_score_percentage }}" class="float-right text-right" style="width: 5em" readonly/>
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
                                    <input type="number" id="part_2_score_percentage" name="part_2_score_percentage" value="{{ $part_2_score_percentage }}" class="float-right text-right" style="width: 5em" readonly/>
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
                                        <input type="radio" class="form-check-input p3_no_1_score" value="10" name="p3_no_1_score" style="transform: scale(1.5);"><small>[10] with suspension/s or major compliance violation</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_1_score" value="5" name="p3_no_1_score" style="transform: scale(1.5);"><small>[5] with warnings and/ or 3+ minor violations</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_1_score" value="0" name="p3_no_1_score" style="transform: scale(1.5);" checked><small>[0] no cited violations</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <input type="number" id="p3_deduct_1" name="p3_deduct_1" value="0" class="float-right text-right" style="width: 5em" readonly/>
                                </div>
                                
                                <div class="col-3 mt-3">B. Interdepartment Relationships <br /> <small class="ml-4">Deductible from weighted score</small></div>
                                <div class="col-2 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_2_score" value="10" name="p3_no_2_score" style="transform: scale(1.5);"><small>[10] 75% achievement</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_2_score" value="5" name="p3_no_2_score" style="transform: scale(1.5);"><small> [5] 75-85% achievement</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input p3_no_2_score" value="0" name="p3_no_2_score" style="transform: scale(1.5);" checked><small> [0] 86-100% achievement</small>
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
                                    <input type="number" id="net_performance_rating" name="net_performance_rating" class="float-right text-right" style="width: 5em" readonly/>
                                </div>
                            </div>
                        </td>
                    </tr>
                        <td colspan="12">
                            <div class="row">
                                <div class="col-6">
                                    <textarea name="hr_remarks" id="" cols="62" rows="10"></textarea>
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
                                                <td class="text-right">{{ number_format($employee->BasicSalary_Empls, 2) }}</td>
                                                    <input type="hidden" id="basic_salary" name="basic_salary" value="{{ $employee->BasicSalary_Empls }}" />
                                                <td class="text-right">{{ number_format($total_allowance, 2) }}</td>
                                                <td class="text-center"><small>97-100</small></td>
                                                <td class="text-center"><small>15%</small></td>
                                            </tr>
                                            <tr>
                                                <td><small>CAI</small></td>
                                                <td class="text-right"><small><span id="percentage_score"></span> %</small></td>
                                                <input type="hidden" id="percentage_score_val" name="percentage_score" />
                                                <td class="text-right" id="salary_increased"></td>
                                                <td></td>
                                                <td class="text-center"><small>95-96</small></td>
                                                <td class="text-center"><small>14%</small></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><small>Compensation Adjustment</small></td>
                                                <td class="text-right" id="compensation_adjustments"></td>
                                                <input type="hidden" name="compensation_adjustments" id="compensation_adjustments_val" />
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
                            <textarea name="recommendation" rows="2" style="width:100%"></textarea>
                            <br />
                            <br />
                            __________________________
                            <br />
                            <span class="ml-4">Department Head</span>
                        </td>
                        <td colspan="6" class="p-3">
                            <span>Remakrs/Approval:</span>
                            <br />
                            <textarea name="remarks_approval" rows="2" style="width:100%"></textarea>
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
        <button type="button" class="btn btn-sm btn-info float-left mt-2 mr-2" onclick="window.history.back()"><< Previous</button>
        <button type="submit" class="btn btn-sm btn-info float-right mt-2 ml-2" onclick="return confirm('Save & Print?')">Save & Print</button>
    </form>

    <hr>

</div>

<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/employees_evaluation.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}