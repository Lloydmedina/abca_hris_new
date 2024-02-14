@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','Employee Level Setup')
	
	{{-- BEGIN CONTENT --}}
	@section('content')
		<!-- Begin Page Content -->
		<div class="container-fluid">


		@if(session('info'))
		    <div class="alert alert-success alert-rounded"> 
		        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
		         Employee Level Added!
		    </div>
		@endif
		<div class="row">
		    <div class="col-md-12">
		        <div class="card">
		            <div class="card-header bg-info">
		                <h4 class="m-b-0 text-white">Employee Level Setup</h4>
		            </div>
		            <div class="card-body">
		                <div class="row">
		                    <div class="col-md-12">
		                        <h4 class="card-title float-left">Employee Level List</h4>
		                        <button type="button" class="btn btn-sm btn-info m-l-15 float-right" data-toggle="modal" data-target="#entry_modal">
		                            <i class="fa fa-plus-circle"></i>
		                            Add New
		                        </button>
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-md-12">
		                        <hr>
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="table-responsive">
		                        <table id="list" name="list" class="table table-bordered" width="100%" cellspacing="0">
		                            <thead>
		                                <tr>
		                                    <th class="text-center">Employee Level</th>
		                                    <th class="text-center">Transportation Allowance</th>
		                                    <th class="text-center">Mobile Allowance</th>
		                                    <th class="text-center">Out of Station Allowance</th>
		                                    <th class="text-center">Meal Allowance</th>
		                                    <th class="text-center">Action</th>
		                                </tr>
		                            </thead>
		                            <tbody id="list_body" name="list">
										@if($list)
											@foreach($list as $data)
												<tr>
													<td>{{$data->emp_lvl}}</td>
													<td>{{number_format($data->transpo_allowance,2)}}</td>
													<td>{{number_format($data->mobile_allowance,2)}}</td>
													<td>{{number_format($data->out_station_allowance,2)}}</td>
													<td>{{number_format($data->meal_allowance,2)}}</td>
												<td>Edit</td>
												</tr>
											@endforeach
										@endif
		                            </tbody>  
		                        </table>
		                    </div>
		                </div>               
		            </div>
		        </div>
		    </div>
		</div>

		<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style=" padding-right: 17px;">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <div class="modal-header bg-info text-white">
		                <h4 class="modal-title">Employee Level Entry</h4>
		                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
		            </div>
		            <form class="form-material" action="{{ url('/add_employee_level') }}" method="post">
		                <input type="hidden" name="_token" value="{{ csrf_token() }}">
		                <div class="modal-body">
	                		<div class="form-body">
	                            <div class="row p-t-20">
	                                <div class="col-md-12">
	                                    <div class="form-group">
	                                        <label class="control-label">Employee Level</label>
	                                        <input type="text" id="emp_lvl" name="emp_lvl" class="form-control">
	                                    </div>
	                                </div>
	                                <!--/span-->
	                                
	                            </div>

	                            <div class="row">
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">Transporation Allowance</label>
	                                        <input type="number" id="transpo_allowance" name="transpo_allowance" class="form-control">
	                                   	</div>
	                                </div>
	                                <!--/span-->
	                                <div class="col-md-6">
	                                    <div class="form-group has-danger">
	                                       	<label class="control-label">Mobile Allowance</label>
	                                        <input type="number" id="mobile_allowance" name="mobile_allowance" class="form-control">
	                                	</div>
	                               	</div>
	                                <!--/span-->
	                            </div>

	                            <div class="row">
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">Out of Station Allowance</label>
	                                        <input type="number" id="out_station_allowance" name="out_station_allowance" class="form-control">
	                                   	</div>
	                                </div>
	                                <!--/span-->
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">Meal Allowance</label>
	                                        <input type="number" id="meal_allowance" name="meal_allowance" class="form-control">
	                                   	</div>
	                                </div>
	                                <!--/span-->
	                            </div>

		                <div class="modal-footer">
		                    <div class="form-actions m-auto">
		                        <button type="reset" class="btn btn-sm btn-danger mr-2" onclick="return confirm('Are you sure you want to reset?')"><i class="fa fa-undo"></i> Reset</button>
		                        <button type="submit" class="btn btn-sm btn-info ml-2"> <i class="fa fa-plus-circle"></i> Add</button>
		                    </div>
		                </div>
		            </form>
		        </div>
		    </div>
		</div>
	</div>
		<!-- /.container-fluid -->
	@endsection
	{{-- END CONTENT --}}

{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
	<script src="{{ asset('uidesign/vendor/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}

{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
	<script>
	$( document ).ready(function() {
	    $('#list').DataTable();
	});
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}