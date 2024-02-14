@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>

.bg-light-gray {
    background-color: #f7f7f7;
}
.table-bordered thead td, .table-bordered thead th {
    border-bottom-width: 2px;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
}


.bg-sky.box-shadow {
    box-shadow: 0px 5px 0px 0px #00a2a7
}

.bg-orange.box-shadow {
    box-shadow: 0px 5px 0px 0px #af4305
}

.bg-green.box-shadow {
    box-shadow: 0px 5px 0px 0px #4ca520
}

.bg-yellow.box-shadow {
    box-shadow: 0px 5px 0px 0px #dcbf02
}

.bg-pink.box-shadow {
    box-shadow: 0px 5px 0px 0px #e82d8b
}

.bg-purple.box-shadow {
    box-shadow: 0px 5px 0px 0px #8343e8
}

.bg-lightred.box-shadow {
    box-shadow: 0px 5px 0px 0px #d84213
}


.bg-sky {
    background-color: #02c2c7
}

.bg-orange {
    background-color: #e95601
}

.bg-green {
    background-color: #5bbd2a
}

.bg-yellow {
    background-color: #f0d001
}

.bg-pink {
    background-color: #ff48a4
}

.bg-purple {
    background-color: #9d60ff
}

.bg-lightred {
    background-color: #ff5722
}

.padding-15px-lr {
    padding-left: 15px;
    padding-right: 15px;
}
.padding-5px-tb {
    padding-top: 5px;
    padding-bottom: 5px;
}
.margin-10px-bottom {
    margin-bottom: 10px;
}
.border-radius-5 {
    border-radius: 5px;
}

.margin-10px-top {
    margin-top: 10px;
}

.font-size20 {
    font-size: 20px;
}

.font-size18 {
    font-size: 18px;
}

.font-size16 {
    font-size: 16px;
}

.font-size14 {
    font-size: 14px;
}

.text-light-gray {
    color: #a7a7a7;
}
.font-size13 {
    font-size: 13px;
}

.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
}
.table td, .table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Shift Schedule')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 550px">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('my_shift') }}" method="get">
                <div class="row">

                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Month</label>
                            <select id="month" name="month" class="form-control custom-select">
                                @php
                                    $monthSelected = $_GET['month'] ?? date('n');
                                @endphp
                                    @foreach($months as $i => $month)
                                    <option value="{{ $i }}" <?php echo ($i == $monthSelected) ? 'selected':'' ?>>{{ $month }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Year</label>
                            <select id="year" name="year" class="form-control custom-select">
                                @php
                                    $yearSelected = $_GET['year'] ?? date('Y');
                                @endphp
                                    @foreach($years as $i => $year)
                                        <option value="{{ $i }}" <?php echo ($i == $yearSelected) ? 'selected':'' ?>>{{ $year }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label style="visibility: hidden">Search Button</label>
                            @include('button_component.search_button', ['margin_top' => "8.5"])
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>
    
    <div class="card">
        <div class="card-body">
            <div class="timetable-img">
                <h2 class="title"><span class="text-left">{{ date('F', strtotime($yearSelected.'-'.$monthSelected)) }}</span> <span class="float-right">{{ $yearSelected }}</span></h2>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-light-gray">
                            <th class="text-uppercase">Sunday</th>
                            <th class="text-uppercase">Monday</th>
                            <th class="text-uppercase">Tuesday</th>
                            <th class="text-uppercase">Wednesday</th>
                            <th class="text-uppercase">Thursday</th>
                            <th class="text-uppercase">Friday</th>
                            <th class="text-uppercase">Saturday</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $tr_indicator_1 = 0;
                            $tr_indicator_2 = 0;
                        @endphp
                        
                        @foreach($shift_of_the_months as $row)
                            @if($tr_indicator_1 == 0)
                                @php
                                    $tr_indicator_1++;
                                @endphp
                                <tr>
                            @endif
                            
                            @php
                                $style = " ";
                                $class_td = " ";
                                $status = 'Pending';
                                $text_shift_code_color = "text-white";
                            @endphp
                            @if ($row['shift'] && $row['shift']->status == 1)
                                @php
                                    $style = "border: 2px solid; background-color:#222222; ";
                                    $status = 'Approved';
                                @endphp
                            @elseif($row['date'])
                                @php
                                    // $style = "border: 2px solid; background-color:#f5f6fa; ";
                                    
                                    $style = "border: 2px solid; background-color:#222222; ";
                                @endphp
                            @endif

                            @if ($row['shift'])
                                @php
                                    if(strtoupper($row['shift']->shift_code) == 'RD'){
                                        // $text_shift_code_color = "text-muted";
                                        // $style = "border: 2px solid; background-color:#ffffff; color:#808080; ";

                                        $style = "border: 2px solid; background-color:#222222; ";
                                    }
                                    $style = $style."cursor: pointer; ";
                                    $class_td = "shift_view_modal ";
                                    // dd($row['shift']->shift_date);
                                @endphp
                            @endif
                            @php
                                $requested_sched_shift_code = '';
                                $requested_sched_shift_date = '';
                                $requested_sched_shift_break = '';
                                $requested_sched_status = '';
                                $requested_sched_status_remarks = '';
                                $requested_sched_remarks = '';
                                $isPastDate = 0;
                            @endphp
                            @if($row['date'])
                                @php
                                    $theDate = new DateTime(date('Y-m-d H:i:s', strtotime($row['date'])));
                                    $now = new DateTime();
                                    
                                    if($theDate < $now) $isPastDate = 1;
                                @endphp
                                @if($row['shift'])
                                    @if(isset($row['shift']->requested_sched))
                                        @php
                                            $requested_sched_shift_code = $row['shift']->requested_sched->shift_code . ' - '. $row['shift']->requested_sched->Description;
                                            $requested_sched_shift_date = $row['shift']->requested_sched->ps_date_to;
                                            $requested_sched_shift_break = $row['shift']->requested_sched->default_break_hrs;
                                            $requested_sched_status = $row['shift']->requested_sched->ps_status;
                                            $requested_sched_status_remarks = $row['shift']->requested_sched->status_remarks;
                                            $requested_sched_remarks = $row['shift']->requested_sched->Remarks;
                                        @endphp
                                    @endif
                                @endif
                            @endif
                            
                            <td class="{{ $class_td }}" style="{{ $style }} width: 14.28571428571429%; height:20%;" title="{{ ($row['date']) ? date('l F d, Y', strtotime($row['date'])) : '' }}"
                                data-shift_code="{{ ($row['shift']) ? $row['shift']->shift_code.' - '.$row['shift']->Description : '' }}"
                                data-shift_date="{{ ($row['date']) ? date('m/d/Y', strtotime($row['date'])) : '' }}"
                                data-shift_brk_hrs="{{ ($row['shift']) ? $row['shift']->no_hr_break : 0 }}"
                                data-shift_status="{{ $status }}"
                                data-shift_id="{{ ($row['shift']) ? $row['shift']->id : '' }}"
                                data-date_from="{{ ($row['shift']) ? $row['shift']->shift_date : '' }}"
                                data-shift_code_id="{{ ($row['shift']) ? $row['shift']->shift_code_id : '' }}"
                                data-requested_sched_shift_code="{{ $requested_sched_shift_code }}"
                                data-requested_sched_shift_date="{{ date('m/d/Y', strtotime($requested_sched_shift_date)) }}"
                                data-requested_sched_shift_break="{{ $requested_sched_shift_break }}"
                                data-requested_sched_status="{{ $requested_sched_status }}"
                                data-requested_sched_status_remarks="{{ $requested_sched_status_remarks }}"
                                data-requested_sched_remarks="{{ $requested_sched_remarks }}"
                                data-is_past_date="{{ $isPastDate }}"
                                >
                                @if($row['date'])

                                    @if($row['shift'])
                                        @if(isset($row['shift']->requested_sched))
                                            @php
                                                $ps_status = '';
                                                $ps_text = '';
                                                if($row['shift']->requested_sched->ps_status == 0) {
                                                    $ps_status = "Pending..."; 
                                                    $ps_text = 'text-muted';
                                                }
                                                elseif($row['shift']->requested_sched->ps_status == 1){
                                                    $ps_status = "Approved";
                                                    $ps_text = 'text-success';
                                                } 
                                                elseif($row['shift']->requested_sched->ps_status == 2){
                                                    $ps_status = "Rejected";
                                                    $ps_text = 'text-danger';
                                                }
                                                elseif($row['shift']->requested_sched->ps_status == 3){
                                                    $ps_status = "Partially Approved";
                                                    $ps_text = 'text-primary';
                                                }
                                            @endphp
                                            <i class="fa-solid fa-arrow-right-arrow-left fa-sm float-left {{ $ps_text }}" aria-hidden="true" title="Change Schedule Request {{ $ps_status }}"></i>
                                    
                                        @else
                                            <i class="fa fa-check float-left" aria-hidden="true" style="visibility: hidden"></i>
                                        @endif
                                    @endif
                                    <span style="background-color: {{ ( date('ymd', strtotime($row['date']))) == date('ymd', strtotime(date('Y-m-d')) ) ? '#ffffff' : '' }}" class="bg-sky_ padding-5px-tb padding-15px-lr border-radius-5 margin-10px-bottom font-size20 xs-font-size13"><b>{{ date('d', strtotime($row['date'])) }}</b></span>
                                    @if($row['shift'])
                                        
                                        @if($row['shift']->is_active === 0)
                                            <i class="fa-solid fa-xmark fa-sm float-right text-danger" aria-hidden="true" title="This shift code has been deleted. Please report to your admin."></i>
                                        @elseif($row['shift']->status == 1)
                                            <i class="fa-solid fa-square-check fa-sm float-right {{ $text_shift_code_color }}" aria-hidden="true" title="Approved"></i>
                                        @else
                                            <i class="fa-solid fa-ban fa-sm float-right {{ $text_shift_code_color }}" aria-hidden="true"></i>
                                        @endif
                                        
                                        @if($row['shift']->is_active === 1)
                                            <div class="margin-10px-top font-size18 {{ $text_shift_code_color }}">
                                                <small>{{ $row['shift']->shift_code}}</small>
                                            </div>

                                            <div class="font-size16 {{ $text_shift_code_color }}">
                                                <small>{{ $row['shift']->Description }}</small>
                                            </div>
                                        @else
                                            <div class="margin-10px-top font-size18 {{ $text_shift_code_color }}">
                                                <small>This shift code ({{ $row['shift']->shift_code}}) has been deleted.</small>
                                            </div>

                                            <div class="font-size16 {{ $text_shift_code_color }}">
                                                <small>Please report to your admin.</small>
                                            </div>
                                        @endif
                                    
                                    @else

                                        {{-- <i class="fa-solid fa-ellipsis fa-sm float-right" aria-hidden="true" title="..."></i>
                                        <i class="fa-solid fa-ellipsis fa-sm float-left" aria-hidden="true" title="..."></i> --}}
                                        
                                        <div class="margin-10px-top font-size18">
                                            <small>N/A</small>
                                        </div>
                                        <div class="font-size16 text-light-gray">
                                            <small>No shift scheduled</small>
                                        </div>
                                    @endif
                                    
                                @else
                                    <span style="visibility: hidden" class="bg-sky padding-5px-tb padding-15px-lr border-radius-5 margin-10px-bottom text-white font-size20 xs-font-size13">Lorem</span>
                                    <div style="visibility: hidden"  class="margin-10px-top font-size14">Lorem</div>
                                    <div style="visibility: hidden"  class="font-size13 text-light-gray">Lorem</div>
                                @endif
                            </td>

                            @if($tr_indicator_2 == 7)
                                </tr>
                            @endif

                            @php
                                $tr_indicator_2++;
                            @endphp

                            @if($tr_indicator_2 == 7)
                                @php
                                $tr_indicator_1 = 0;
                                $tr_indicator_2 = 0;
                                @endphp
                            @endif

                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

</div>

{{-- SHIFT VIEW MODAL --}}
<div class="modal fade" id="shift_view_modal" tabindex="-1" aria-labelledby="shift_view_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shift_view_modalLabel">Shift</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
            </div>
            <form id="file_change_schedule_request" action="{{ route('file_change_schedule_request') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <center>
                        <h5 class="modal-title mb-2" id="shift_date_modal"></h5>
                        <a class="btn btn-link text-info" id="change_sched_id">Change Schedule Request</a> <i class="fa fa-times text-danger d-none hide_request_element cancel_req" aria-hidden="true" style="cursor: pointer" title="Cancel"></i>
                        <input type="date" class="form-control d-none hide_request_element" name="date_to" id="change_date_to" value="" required/>
                        {{-- <label class="control-label mt-1 d-none hide_request_element">Shift Code</label> --}}
                        <select id="shift_code" name="shift_code" class="form-control custom-select mt-3 d-none hide_request_element">
                            {{-- <option value="" id="def_shift_code"></option> --}}
                            @foreach($shift_codes as $i => $sc)
                                <option value="{{ $sc->id }}">{{ $sc->shift_code }} - {{ $sc->Description }}</option>
                            @endforeach
                        </select>
                        <label class="control-label mt-2 d-none hide_request_element">Reason</label>
                        <textarea id="remarks_reason_text" class="form-control d-none hide_request_element" name="remarks" placeholder="Type in your message" rows="5" maxlength="100" required></textarea>
                        <h6 class="pull-right mt-1 d-none hide_request_element" id="count_message"></h6>
                        <input type="hidden" name="shift_id" id="shift_id" />
                        <input type="hidden" name="date_from" id="date_from_id" />
                    </center>
                    <br>
                    <table id="" class="display nowrap table table-sm table-hover table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="">Shift Code</th>
                                <th class="text-center">No. Hrs Break</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="shift_code_modal"></td>
                                <td id="shift_brk_hrs_modal" class="text-center"></td>
                                <td id="shift_status_modal" class="text-center"></td>
                            </tr>
                        </tbody>
                        <tr class="requested_sched_modal">
                            <th class="text-center" colspan="3">Requested Schedule <br><span id="requested_sched_shift_date"></span></th>
                        </tr>
                        <tr class="requested_sched_modal">
                            <th class="">Shift Code</th>
                            <th class="text-center">No. Hrs Break</th>
                            <th class="text-center">Status</th>
                        </tr>
                        <tr class="requested_sched_modal">
                            <td id="requested_sched_shift_code"></td>
                            <td id="requested_sched_shift_break" class="text-center"></td>
                            <td id="requested_sched_status" class="text-center"></td>
                        </tr>
                        <tr class="requested_sched_remarks">
                            <td colspan="3" id="requested_sched_remarks"></td>
                        </tr>
                        <tr class="requested_sched_status_remarks">
                            <td colspan="3" id="requested_sched_status_remarks"></td>
                        </tr>
                        </table>
                
                </div>
                <div class="modal-footer">
                    {{-- <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button> --}}
                    <button class="btn btn-dark d-none hide_request_element" type="submit">Change Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- END SHIFT VIEW MODAL --}}

    <!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')

<script>

$(document).ready(function(){

    $("#file_change_schedule_request").submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: "Would you like to request a schedule change?",
            // text: "Would you like to request a schedule change?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#222222",
            confirmButtonText: "Yes, continue!",
            cancelButtonText: "No, cancel!",
            cancelButtonColor: "#d9534f",
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                $("#file_change_schedule_request").unbind('submit').submit();
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });

    let text_max = 100;
    $('#count_message').html(text_max + ' remaining');
    $('#remarks_reason_text').keyup(function() {
        var text_length = $('#remarks_reason_text').val().length;
        var text_remaining = text_max - text_length;
        $('#count_message').html(text_remaining + ' remaining');
    }); 

    $(document).on('click', '#change_sched_id', function(){
        $('.hide_request_element').removeClass('d-none');
    });

    $(document).on('click', '.cancel_req', function(){
        $('.hide_request_element').addClass('d-none');
    });

    // View shift modal
    $('.shift_view_modal').click(function(){

        let requested_sched_shift_code = $(this).data('requested_sched_shift_code');
        let requested_sched_shift_date = $(this).data('requested_sched_shift_date');
        let requested_sched_shift_break = $(this).data('requested_sched_shift_break');
        let requested_sched_status = $(this).data('requested_sched_status');
        let requested_sched_status_remarks = $(this).data('requested_sched_status_remarks');
        let requested_sched_remarks = $(this).data('requested_sched_remarks');
        let is_past_date = $(this).data('is_past_date');
        
        if(is_past_date == 1 || is_past_date == '1'){
            $('#change_sched_id').addClass('d-none');
        }
        else{
            $('#change_sched_id').removeClass('d-none');
        }

        let requested_sched_status_text = '';

        $('.requested_sched_modal').addClass('d-none');
        $('.requested_sched_status_remarks').addClass('d-none');
        $('.requested_sched_remarks').addClass('d-none');

        if(requested_sched_shift_code){
            $('#requested_sched_shift_code').text(requested_sched_shift_code);
            $('#requested_sched_shift_date').text(requested_sched_shift_date);
            $('#requested_sched_shift_break').text(requested_sched_shift_break);

            if(requested_sched_status == 0){
                requested_sched_status_text = 'Pending...';
            }
            else if(requested_sched_status == 1){
                requested_sched_status_text = 'Approved';
            }
            else if(requested_sched_status == 2){
                requested_sched_status_text = 'Rejected';
            }
            else if(requested_sched_status == 3){
                requested_sched_status_text = 'Partially Approved';
            }
            $('#requested_sched_status').text(requested_sched_status_text);

            if(requested_sched_remarks){
                $('#requested_sched_remarks').text(requested_sched_remarks);
                $('.requested_sched_remarks').removeClass('d-none');
            }

            if(requested_sched_status_remarks){
                $('#requested_sched_status_remarks').text(requested_sched_status_remarks);
                $('.requested_sched_status_remarks').removeClass('d-none');
            }
            
            $('.requested_sched_modal').removeClass('d-none');
        }


        // $('#shift_date_modal').text($(this).data('shift_date'));
        $('#shift_date_modal').text(formatDateAbaca(new Date($(this).data('shift_date'))));
        $('#shift_code_modal').text($(this).data('shift_code'));
        $('#shift_brk_hrs_modal').text($(this).data('shift_brk_hrs'));
        $('#shift_status_modal').text($(this).data('shift_status'));
        $('#shift_id').val($(this).data('shift_id'));
        $('#date_from_id').val($(this).data('date_from'));
        $("#shift_code").val($(this).data('shift_code_id')).change();
        
        $('#shift_view_modal').modal('show');
    });
});

</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}