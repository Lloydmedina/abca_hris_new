@extends('Templates.main_layout')

{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
{{-- END PAGE LEVEL CSS --}}

@section('title','User Access Setup')
	
	{{-- BEGIN CONTENT --}}
	@section('content')
		<!-- Begin Page Content -->
		<div class="container-fluid">


		@if(session('info'))
		    <div class="alert alert-success alert-rounded"> 
		        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
		         User Config Added!
		    </div>
		@endif
		<div class="row">
		    <div class="col-md-12">
		        <div class="card">
		            <div class="card-header bg-info">
		                <h4 class="m-b-0 text-white">User Access Setup</h4>
		            </div>
		            <div class="card-body">
		                <div class="row">
		                    <div class="col-md-12">
		                        <h4 class="card-title float-left">Company List</h4>
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
		                                    <th class="text-center">Company</th>
		                                    <th class="text-center">Remarks</th>
		                                    <th class="text-center">Action</th>
		                                </tr>
		                            </thead>
		                            <tbody id="list_body" name="list">
										
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
		                <h4 class="modal-title">User Access Setup</h4>
		                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
		            </div>
		            <form class="form-material" action="{{ url('/add_company') }}" method="post">
		                <input type="hidden" name="_token" value="{{ csrf_token() }}">
		                <div class="modal-body">
	                		<div class="form-body">
	                            <div class="row p-t-20">
	                                <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">User Management</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">User List</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Add User</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                                
                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Overtime</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Add OT</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Approve OT</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->

                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Cash Advance/Co. Loans</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Add CA/Loans</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Edit</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                            </div>

                                <div class="row p-t-20">
	                                <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Emplooyee</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Employee List</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Add Employee</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Update Employee</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                                
                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Holiday</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Add Holiday</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Update Holiday</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->

                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Payroll</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Entry</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">List</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Payslip</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Deduction Entry</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                            </div>

                                <div class="row p-t-20">
	                                <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Discipline Management</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Code of <br> Conduct</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Incident Report</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Disciplinary Action <br> Report</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                                
                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Personnel Itinerary</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Add Itinerary</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Edit Itinerary</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->

                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Tax Due List</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">View/Print</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                            </div>

                                <div class="row p-t-20">
	                                <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Applicants</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Add</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Approved/Edit</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                                
                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Shift Monitoring</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">View</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Entry</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->

                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Loan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">SSS</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">PAGIBIG</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                            </div>

                                <div class="row p-t-20">
	                                <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">DTR</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Entry</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">List</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Late List</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Incomplete DTR</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Summary</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Import</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                                
                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Leaves</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Monitoring</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Add Leave</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Leave List/Approval</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Leave Type Setup</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->

                                    <div class="col-md-4">
	                                    <div class="table-responsive">
                                            <table  class="table table-bordered" width="100%" cellspacing="0">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center" colspan="2">Company Setup</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-info">
                                                    <tr>
                                                        <td class="text-left">Company</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Outlet</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Cost Center</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Department</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Position</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Employee Level</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" id="remarks" name="remarks" >Allow
                                                        </td>
                                                    </tr>
                                                </tbody>  
                                            </table>
                                        </div>
	                                </div>
	                                <!--/span-->
	                            </div>

		                <div class="modal-footer">
		                    <div class="form-actions m-auto">
		                        <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close"><i class="fa fa-undo"></i> Close</button>
		                        <button type="submit" class="btn btn-sm btn-info ml-2"> <i class="fa fa-plus-circle"></i> Update</button>
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