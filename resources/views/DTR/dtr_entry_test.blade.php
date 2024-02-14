@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
	<!-- Date Time Picker -->

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

	<!-- Date Time Picker -->

	<!-- Java Script  -->
	<script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.flash.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.html5.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script>
	<!-- Java Script  -->

@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','DTR Entry')
	
	{{-- BEGIN CONTENT --}}
	@section('content')
		<!-- Begin Page Content -->
		<div class="container-fluid">


		@if(session('info'))
		    <div class="alert alert-success alert-rounded"> 
		        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
		         Position Added!
		    </div>
		@endif

		<section id="">
			<div class="row">
				<div class="col-md-12">
					<div class="card border">
						<div class="card-header bg-info">
							<h3 class="m-b-0 text-white">Pass Slip Entry</h3>
						</div>
				<div class="row">
				<div class="col-md-12">
				<div class="card border border-1">
				<div class="card-body">
				<h4 class="card-title"></h4>
				<div class="row">
				<div class="col-md-5">Payroll From <input id="start" data-date-format="mm-dd-yyyy" class="datepicker form-control" readonly >  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
				<div class="col-md-5">Payroll To <input id="end" data-date-format="mm-dd-yyyy"  class=" datepicker form-control"readonly> </div>
				</div>
				<br>
				<div>
				<table  id ="dtBasicExample" class=" nowrap table table-sm table-hover table-striped table-bordered " cellspacing="0" width="100%">
				<thead>
					<tr>
						<th class="text-center">DAY</th>
						<th class="text-center">NAME OF DAY</th>
						<th class="text-center">AM IN</th>
						<th class="text-center">AM OUT</th>
						<th class="text-center">PM IN</th>
						<th class="text-center">PM OUT</th>
						<th class="text-center">HRS LATE</th>
						<th class="text-center">UNDERTIME</th>
						<th class="text-center">OVERTIME</th>
						<th class="text-center">REMARKS</th>
					</tr>
					</thead>
					<tbody id="">
					<tr>
					<td class="pt-3-half" contenteditable="true">1</td>
					<td class="pt-3-half" contenteditable="true">Friday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">2</td>
					<td class="pt-3-half" contenteditable="true">Saturday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">3</td>
					<td class="pt-3-half" contenteditable="true">Sunday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">4</td>
					<td class="pt-3-half" contenteditable="true">Monday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr> 

					<tr> 
					<td class="pt-3-half" contenteditable="true">5</td>
					<td class="pt-3-half" contenteditable="true">Tuesday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr> 

					<tr> 
					<td class="pt-3-half" contenteditable="true">6</td>
					<td class="pt-3-half" contenteditable="true">Wednesday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">7</td>
					<td class="pt-3-half" contenteditable="true">Thursday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">8</td>
					<td class="pt-3-half" contenteditable="true">Friday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">9</td>
					<td class="pt-3-half" contenteditable="true">Saturday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">10</td>
					<td class="pt-3-half" contenteditable="true">Sunday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">11</td>
					<td class="pt-3-half" contenteditable="true">Monday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">12</td>
					<td class="pt-3-half" contenteditable="true">Tuesday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">13</td>
					<td class="pt-3-half" contenteditable="true">Wednesday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">14</td>
					<td class="pt-3-half" contenteditable="true">Thursday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>

					<tr> 
					<td class="pt-3-half" contenteditable="true">15</td>
					<td class="pt-3-half" contenteditable="true">Friday</td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true"></td>
					<td class="pt-3-half" contenteditable="true">N/A</td>
					</tr>                                                                             </tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
					</div>
				</div>
			</div>
		</section>
</div>


		<div id="entry_modal" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style=" padding-right: 17px;">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <div class="modal-header bg-info text-white">
		                <h4 class="modal-title">DTR Entry Entry</h4>
		                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
		            </div>
		            <form class="form-material" action="{{ url('/add_item_setup') }}" method="post">
		                <input type="hidden" name="_token" value="{{ csrf_token() }}">
		                <div class="modal-body">
	                		<div class="form-body">
	                            <div class="row p-t-20">
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">Position Code</label>
	                                        <input type="text" id="DepartmentCode" name="DepartmentCode" class="form-control">
	                                    </div>
	                                </div>
	                                <!--/span-->
	                                <div class="col-md-6">
	                                    <div class="form-group has-danger">
	                                        <label class="control-label">Position</label>
	                                        <input type="text" id="Department" name="Department" class="form-control">
	                                    </div>
	                                </div>
	                                <!--/span-->
	                            </div>

	                            <div class="row">
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">Daily Rate</label>
	                                        <input type="number" id="Department" name="Department" class="form-control">
	                                   	</div>
	                                </div>
	                                <!--/span-->
	                                <div class="col-md-6">
	                                    <div class="form-group has-danger">
	                                       	<label class="control-label">Monthly Rate</label>
	                                        <input type="number" id="Department" name="Department" class="form-control">
	                                	</div>
	                               	</div>
	                                <!--/span-->
	                            </div>

	                            <div class="row">
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">COLA</label>
	                                        <input type="number" id="Department" name="Department" class="form-control">
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
	$(document).ready(function () {
	$('#dtBasicExample').DataTable();
	$('.dataTables_length').addClass('bs-select');
	});
	</script>



<!-- Date picker code start -->

  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $('.datepicker').datepicker({
      format: 'yyyy/mm/dd',    // Or whatever format you want.
      startDate: '2015/01/01'  // Or whatever start date you want.
    });
  });
</script>

<!-- Date picker code end -->

@endsection
{{-- END PAGE LEVEL SCRIPT --}}

