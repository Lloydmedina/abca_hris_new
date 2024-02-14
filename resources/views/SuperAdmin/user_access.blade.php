@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')

@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','User Access')
	
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ($employee_types as $employee_type)
                            @php
                                $emp_type = ucwords(strtolower($employee_type->employee_type));
                            @endphp
                            @if($employee_type->id == app('request')->input('id'))
                                <li class="breadcrumb-item active" aria-current="page">{{ $emp_type}}</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ url('/user_access_setup?id='.$employee_type->id) }}">{{ $emp_type}}</a></li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
                </div>
                {{-- <div class="col-4">
                
                </div> --}}
                <div class="col-4 text-right">
                @if(app('request')->input('id'))
                    <a href="{{ route('shift_entry') }}" class="btn btn-md btn-primary">
                        <i class="fa fa-file"></i> Save
                    </a>
                @endif
                </div>
            </div>
            <div class="table-responsive m-t-40">
                <table id="" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="">Module</th>
                        <th class="">Route</th>
                        <th class="text-center">Access</th>
                        {{-- Hide for emp user --}}
                    @if(app('request')->input('id') != 5)
                        <th class="text-center">Add</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                    @endif
                    </tr>
                    
                    </thead>
                    <tbody>
                    <form action="">
                        @foreach ($modules as $module)
                            <tr>
                                <td>{{ $module->module }} @if($module->desc) - <small>({{ $module->desc }})</small> @endif</td>
                                <td><a href="{{ url("$module->module_route") }}" target="_blank">View module</a></td>
                                <td class="text-center">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="access_mod_{{ $module->id }}">
                                            
                                        </div>
                                    </div>
                                </td>
                                @if(app('request')->input('id') != 5)
                                <td class="text-center">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_add_{{ $module->id }}">
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_edit_{{ $module->id }}">
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_delete_{{ $module->id }}">
                                            
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
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
	    
	});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}