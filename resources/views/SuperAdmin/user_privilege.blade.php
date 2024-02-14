@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')

@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','User Privilege')
	
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @include('Templates.alert_message')
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                
                <form action="" method="GET">
                    <select class="custom-select" name="id" id="select_employee_type">
                        <option selected disabled>Select Employee Type</option>
                            @foreach ($employee_types as $employee_type)
                                <option {{ ($employee_type->id == app('request')->input('id')) ? 'selected' : '' }} value="{{ $employee_type->id }}">{{ $employee_type->employee_type }}</option>
                            @endforeach
                    </select>
                    <button type="submit" id="btn_search_emp_type" hidden>Search</button>
                </form>
                

                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ($employee_types as $employee_type)
                            @php
                                $emp_type = ucwords(strtolower($employee_type->employee_type));
                            @endphp
                            @if($employee_type->id == app('request')->input('id'))
                                <li class="breadcrumb-item active" aria-current="page">{{ $emp_type}}</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ url('/user_privilege?id='.$employee_type->id) }}">{{ $emp_type}}</a></li>
                            @endif
                        @endforeach
                    </ol>
                </nav> --}}
                </div>
                {{-- <div class="col-4">
                
                </div> --}}
                <div class="col-6 text-right">
                @if(app('request')->input('id'))
                    <button class="btn btn-md btn-primary" id="submit_up">
                        <i class="fa fa-file"></i> Save
                    </button>
                @endif
                </div>
            </div>
            <hr>
            <div class="table-responsive m-t-40">
            <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="">Page</th>
                        <th class="">Route</th>
                        @if(app('request')->input('id'))
                        <th class="text-center">Allow</th>
                        @endif
                    </tr>
                    
                </thead>
                <tbody>
                    <form action="{{ route('user_privilege_update') }}" method="POST">
                    @csrf
                    <input type="text" name="employee_type_id" value="{{app('request')->input('id')}}" hidden>
                        @php
                            $header_stats = '';
                        @endphp
                        @foreach ($user_privilege_items as $user_privilege_item)
                        @php
                            $employee_type_ids = explode(",",$user_privilege_item->employee_type_ids);
                            $key = array_search(app('request')->input('id'), $employee_type_ids);
                            $checkbox_stats = ($key !== false) ? "checked" : "";
                        @endphp

                        @if ($user_privilege_item->header)

                            @if($user_privilege_item->header != $header_stats)
                                <tr>
                                    <th>{{ strtoupper($user_privilege_item->header) }}</th>
                                    @if(app('request')->input('id'))
                                        <td></td>
                                    @endif
                                    <td class="text-center">
                                        {{-- <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" name="" value="" name="" type="checkbox" id="header_allow_mod_{{ $user_privilege_item->header }}">
                                                
                                            </div>
                                        </div> --}}
                                    </td>
                                </tr>
                            @endif

                                <tr>
                                    <td> <i class="fa fa-link ml-3" aria-hidden="true"></i> {{ $user_privilege_item->item_name }}</td>
                                    <td><a href="{{ url("$user_privilege_item->route") }}" target="_blank">View page</a></td>
                                    @if(app('request')->input('id'))
                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="form-check">
                                                <input {{ $checkbox_stats }} class="form-check-input" name="checked[]" value="{{ $user_privilege_item->id }}" name="" type="checkbox" id="allow_mod_{{ $user_privilege_item->id }}">
                                                
                                                </div>
                                            </div>
                                        </td>
                                    @endif

                                </tr>

                            <?php $header_stats = $user_privilege_item->header; ?>
                        @else

                            <tr>
                                <td> <i class="fa fa-link" aria-hidden="true"></i> {{ $user_privilege_item->item_name }}</td>
                                <td><a href="{{ url("$user_privilege_item->route") }}" target="_blank">View page</a></td>
                                @if(app('request')->input('id'))
                                    <td class="text-center">
                                        <div class="form-group">
                                            <div class="form-check">
                                            <input {{ $checkbox_stats }} class="form-check-input" name="checked[]" value="{{ $user_privilege_item->id }}" name="" type="checkbox" id="allow_mod_{{ $user_privilege_item->id }}">
                                            
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                        @endif
                            
                        @endforeach
                        <button class="btn btn-primary d-none" type="submit" id="btn_submit_up">Submit</button>
                    </form>
                
                </tbody>
            </table>
            </div>

        </div>
    </div>

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
<script>
	$( document ).ready(function() {

        $(document).on('click', '#submit_up', function(e) {
            $('#btn_submit_up').click();
        });

        $(document).on('change', '#select_employee_type', function(){
            $('#btn_search_emp_type').click();
        });
	});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}