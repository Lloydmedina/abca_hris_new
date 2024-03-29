@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','Dashboard')
	
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

	@include('Templates.alert_message')
		
	<!-- Page Heading -->
	{{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
		<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
	</div> --}}
	<!-- Content Row -->
	<div class="row">
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Employees</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $default[0]->emp_count }}</div>
					</div>
					<div class="col-auto">
						<img class="img-profile" src="{{ asset('costum_picture/web_pictures/emp_emp.png') }}" style="width: 50px;height: 50px">
					</div>
				</div>
			</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Male Employees</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $default[0]->male }}</div>
					</div>
					<div class="col-auto">
						<img class="img-profile" src="{{ asset('costum_picture/web_pictures/male_emp.png') }}" style="width: 50px;height: 50px">
					</div>
				</div>
			</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Female Employees</div>
						<div class="row no-gutters align-items-center">
						<div class="col-auto">
							<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $default[0]->female }}</div>
						</div>
						
						</div>
					</div>
					<div class="col-auto">
						<img class="img-profile" src="{{ asset('costum_picture/web_pictures/female_emp.png') }}" style="width: 50px;height: 50px">
					</div>
				</div>
			</div>
			</div>
		</div>
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
					</div>
					<div class="col-auto">
						<i class="fas fa-comments fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	<!-- Content Row -->
	<div class="row">
		<div class="col-xl-4 col-lg-4">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Gender Chart</h6>
						<div class="dropdown no-arrow">
							<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
								<div class="dropdown-header">Dropdown Header:</div>
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Something else here</a>
							</div>
						</div>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12">
								
								<div class="chart-pie pt-4">
									<canvas id="gender_chart"></canvas>
								</div>

								<div class="mt-4 text-center small">
									<span class="mr-2">
									<i class="fas fa-circle text-primary"></i> Male
									</span>
									<span class="mr-2">
									<i class="fas fa-circle text-success"></i> Female
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
		<div class="col-xl-4 col-lg-4">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Civil Status</h6>
						<div class="dropdown no-arrow">
							<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
								<div class="dropdown-header">Dropdown Header:</div>
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Something else here</a>
							</div>
						</div>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12">
								
								<div class="chart-pie pt-4">
									<canvas id="cs_chart"></canvas>
								</div>

								<div class="mt-4 text-center small">
									<span class="mr-2">
									<i class="fas fa-circle text-primary"></i> Single
									</span>
									<span class="mr-2">
									<i class="fas fa-circle text-success"></i> Married
									</span>
									<span class="mr-2">
									<i class="fas fa-circle text-info"></i> Widower
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
		<div class="col-xl-4 col-lg-4">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Absent Monitoring</h6>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
							<div class="dropdown-header">Dropdown Header:</div>
							<a class="dropdown-item" href="#">Action</a>
							<a class="dropdown-item" href="#">Another action</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">Something else here</a>
						</div>
					</div>
				</div>
				<!-- Card Body -->
				<div class="card-body">
					<div class="chart-pie pt-4 pb-2">
						<canvas id="myPieChart"></canvas>
					</div>
					<div class="mt-4 text-center small">
						<span class="mr-2">
						<i class="fas fa-circle text-primary"></i> Awol
						</span>
						<span class="mr-2">
						<i class="fas fa-circle text-success"></i> Undertime
						</span>
						<span class="mr-2">
						<i class="fas fa-circle text-info"></i> Sickness Related
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Manpower Dashboard</h6>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
							<div class="dropdown-header">Dropdown Header:</div>
							<a class="dropdown-item" href="#">Action</a>
							<a class="dropdown-item" href="#">Another action</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">Something else here</a>
						</div>
					</div>
				</div>
				<!-- Card Body -->
				<div class="card-body">
					<div class="row">
						<div class="col-xl-12 col-lg-12">
							<div class="table m-t-40">
								<table id="manpower_table" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="text-center align-middle">Department</th>
											<th class="text-center align-middle">Position</th>
											<th class="text-center align-middle">Regular</th>
											<th class="text-center align-middle">Probationary</th>
											<th class="text-center align-middle">Total</th>
										</tr>
									</thead>
									
									<tbody>
										<?php
											$total_regular = 0;
											$total_proby = 0;
											$total_emp = 0;
											$total_position = 0;
										?>
										@if($manpower)
											@foreach($manpower as $row)
											<?php
												$total_regular = $total_regular + $row->Regular;
												$total_proby = $total_proby + $row->Probationary;
												$total_position = $row->Regular + $row->Probationary;
												$total_emp = $total_emp + $total_position;
											?>
												<tr>
													<td class="align-middle">{{ $row->department }}</td>
													<td class="align-middle">{{ $row->position }}</td>
													<td class="text-center align-middle">{{ $row->Regular }}</td>
													<td class="text-center align-middle">{{ $row->Probationary }}</td>
													<td class="text-center align-middle">{{ $total_position }}</td>
												</tr>
											@endforeach
										@endif
									</tbody>
									<tfoot>
										<tr>
											<th colspan="2" class="text-right align-middle">TOTAL EMPLOYEES: </th>
											<th class="text-center align-middle">{{ $total_regular }}</th>
											<th class="text-center align-middle">{{ $total_proby }}</th>
											<th class="text-center align-middle">{{ $total_emp }}</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- <div class="row">
		<!-- Area Chart -->
		<div class="col-xl-8 col-lg-7">
			<div class="card shadow mb-4">
			<!-- Card Header - Dropdown -->
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
				<div class="dropdown no-arrow">
					<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
						<div class="dropdown-header">Dropdown Header:</div>
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">Something else here</a>
					</div>
				</div>
			</div>
			<!-- Card Body -->
			<div class="card-body">
				<div class="chart-area">
					<canvas id="myAreaChart"></canvas>
				</div>
			</div>
			</div>
		</div>
		<!-- Pie Chart -->
		<div class="col-xl-4 col-lg-5">
			<div class="card shadow mb-4">
			<!-- Card Header - Dropdown -->
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Absent Monitoring</h6>
				<div class="dropdown no-arrow">
					<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
						<div class="dropdown-header">Dropdown Header:</div>
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">Something else here</a>
					</div>
				</div>
			</div>
			<!-- Card Body -->
			<div class="card-body">
				<div class="chart-pie pt-4 pb-2">
					<canvas id="myPieChart"></canvas>
				</div>
				<div class="mt-4 text-center small">
					<span class="mr-2">
					<i class="fas fa-circle text-primary"></i> Awol
					</span>
					<span class="mr-2">
					<i class="fas fa-circle text-success"></i> Undertime
					</span>
					<span class="mr-2">
					<i class="fas fa-circle text-info"></i> Sickness Related
					</span>
				</div>
			</div>
			</div>
		</div>
	</div>
	
	<!-- Content Row -->
	<div class="row">
		<!-- Content Column -->
		<div class="col-lg-6 mb-4">
			<!-- Project Card Example -->
			<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">TURN OVER MONITORING</h6>
			</div>
			<div class="card-body">
				<h4 class="small font-weight-bold">Study-Resigned <span class="float-right">20%</span></h4>
				<div class="progress mb-4">
					<div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Family Concerns-Resigned <span class="float-right">40%</span></h4>
				<div class="progress mb-4">
					<div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Quality Issue-Resigned <span class="float-right">60%</span></h4>
				<div class="progress mb-4">
					<div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Greener Pasture-Resigned <span class="float-right">80%</span></h4>
				<div class="progress mb-4">
					<div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">AWOL <span class="float-right">80%</span></h4>
				<div class="progress">
					<div class="progress-bar bg-success" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
			</div>
			<!-- Color System -->
			<div class="row">
			<div class="col-lg-6 mb-4">
				<div class="card bg-primary text-white shadow">
					<div class="card-body">
						Primary
						<div class="text-white-50 small">#090909</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mb-4">
				<div class="card bg-success text-white shadow">
					<div class="card-body">
						Success
						<div class="text-white-50 small">#1cc88a</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mb-4">
				<div class="card bg-info text-white shadow">
					<div class="card-body">
						Info
						<div class="text-white-50 small">#36b9cc</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mb-4">
				<div class="card bg-warning text-white shadow">
					<div class="card-body">
						Warning
						<div class="text-white-50 small">#f6c23e</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mb-4">
				<div class="card bg-danger text-white shadow">
					<div class="card-body">
						Danger
						<div class="text-white-50 small">#e74a3b</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 mb-4">
				<div class="card bg-secondary text-white shadow">
					<div class="card-body">
						Secondary
						<div class="text-white-50 small">#858796</div>
					</div>
				</div>
			</div>
			</div>
		</div>
		<div class="col-lg-6 mb-4">
			<!-- Illustrations -->
			<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
			</div>
			<div class="card-body">
				<div class="text-center">
					<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
				</div>
				<p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
				<a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
			</div>
			</div>
			<!-- Approach -->
			<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
			</div>
			<div class="card-body">
				<p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce CSS bloat and poor page performance. Custom CSS classes are used to create custom components and custom utility classes.</p>
				<p class="mb-0">Before working with this theme, you should become familiar with the Bootstrap framework, especially the utility classes.</p>
			</div>
			</div>
		</div>
	</div> --}}
	
</div>
<!-- /.container-fluid -->
@endsection
{{-- END CONTENT --}}

{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')
	<script src="{{ asset('uidesign/vendor/chart.js/Chart.min.js') }}"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}

{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
	<script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.flash.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/pdfmake.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/ajax/libs/pdfmake/0.1.32/vfs_fonts.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.html5.min.js') }}"></script>
	<script src="{{ asset('uidesign/vendor/elite/buttons/1.5.1/js/buttons.print.min.js') }}"></script>

	<script src="{{ asset('uidesign/js/demo/chart-area-demo.js') }}"></script>
	<script src="{{ asset('uidesign/js/demo/chart-pie-demo.js') }}"></script>
	<script>
		$('#manpower_table').DataTable();
		// Set new default font family and font color to mimic Bootstrap's default styling
		Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
		Chart.defaults.global.defaultFontColor = '#858796';

		// Pie Chart Example
		var gender_chart = document.getElementById("gender_chart");
		var myPieChart1 = new Chart(gender_chart, {
		  type: 'doughnut',
		  data: {
		    labels: ["Male", "Female"],
		    datasets: [{
		      data: [{{ $male_percentage }}, {{ $female_percentage }}],
		      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
		      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
		      hoverBorderColor: "rgba(234, 236, 244, 1)",
		    }],
		  },
		  options: {
		    maintainAspectRatio: false,
		    tooltips: {
		      backgroundColor: "rgb(255,255,255)",
		      bodyFontColor: "#858796",
		      borderColor: '#dddfeb',
		      borderWidth: 1,
		      xPadding: 15,
		      yPadding: 15,
		      displayColors: false,
		      caretPadding: 10,
		    },
		    legend: {
		      display: false
		    },
		    cutoutPercentage: 10,
		  },
		});

		var cs_chart = document.getElementById("cs_chart");
		var myPieChart2 = new Chart(cs_chart, {
		  type: 'doughnut',
		  data: {
		    labels: ["Single", "Married","Widower"],
		    datasets: [{
		    	@if(!empty($civil_status))
		    		data: 
		    			[
		    				{{ $civil_status[0]->Single }}, 
		    				{{ $civil_status[0]->Married }},
		    				{{ $civil_status[0]->Widower }}
		    			],
		    	@else
		    		data: [0, 0,0],
		    	@endif
		      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
		      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
		      hoverBorderColor: "rgba(234, 236, 244, 1)",
		    }],
		  },
		  options: {
		    maintainAspectRatio: false,
		    tooltips: {
		      backgroundColor: "rgb(255,255,255)",
		      bodyFontColor: "#858796",
		      borderColor: '#dddfeb',
		      borderWidth: 1,
		      xPadding: 15,
		      yPadding: 15,
		      displayColors: false,
		      caretPadding: 10,
		    },
		    legend: {
		      display: false
		    },
		    cutoutPercentage: 10,
		  },
		});
	</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}