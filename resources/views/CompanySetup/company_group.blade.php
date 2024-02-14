@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','Company Group Setup')
	
	{{-- BEGIN CONTENT --}}
	@section('content')
		<!-- Begin Page Content -->
		<div class="container-fluid">


		@if(session('info'))
		    <div class="alert alert-success alert-rounded"> 
		        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
		         Company Group Added!
		    </div>
		@endif
		<div class="row">
		    <div class="col-md-12">
		        <div class="card">
		            <div class="card-header bg-info">
		                <h4 class="m-b-0 text-white">Company Setup</h4>
		            </div>
		            <div class="card-body">
		                <div class="row">
		                    <div class="col-md-12">
		                        <h4 class="card-title float-left">Company Group List</h4>
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
		                                    <th class="text-center">Company Group</th>
		                                    <th class="text-center">Remarks</th>
		                                    <th class="text-center">Action</th>
		                                </tr>
		                            </thead>
		                            <tbody id="list_body" name="list">
										@if($list)
											@foreach($list as $data)
												<tr>
													<td>{{$data->company_group}}</td>
                                                    <td>{{$data->remarks}}</td>
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
		                <h4 class="modal-title">Company Group Entry</h4>
		                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
		            </div>
		            <form class="form-material" action="{{ url('/add_company_group') }}" method="post">
		                <input type="hidden" name="_token" value="{{ csrf_token() }}">
		                <div class="modal-body">
	                		<div class="form-body">
	                            <div class="row p-t-20">
	                                <div class="col-md-12">
	                                    <div class="form-group">
	                                        <label class="control-label">Company Group</label>
	                                        <input type="text" id="company_group" name="company_group" class="form-control">
	                                    </div>
	                                </div>
	                                <!--/span-->
	                                
	                            </div>

                                <div class="row p-t-20">
	                                <div class="col-md-12">
	                                    <div class="form-group">
	                                        <label class="control-label">Remarks</label>
	                                        <input type="text" id="remarks" name="remarks" class="form-control">
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