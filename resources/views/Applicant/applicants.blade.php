@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<link href="{{ asset('uidesign/vendor/elite/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Applicants')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @include('Templates.alert_message')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <h4 class="card-title">Applicants</h4>
                </div>
            </div>
            <div class="table-responsive m-t-40">
                <table id="myTable" class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Seq</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>File</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th colspan="3" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($applicants) > 0)
                            <?php $count = count($applicants) ?>
                            @foreach ($applicants as $row)

                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ str_limit(ucwords(strtolower($row->first_name) ." ". strtolower($row->middle_name) ." ". strtolower($row->last_name)), 30) }}</td>
                                    <td>{{ $row->position }}</td>
                                    <td>
                                        <a href="{{ asset( $row->full_path) }}" target="_blank" title="View application">
                                            @if($row->file_ex == 'pdf')
                                                <i class="text-dark far fa-file-pdf">.{{ $row->file_ex }}</i>
                                            @else
                                                <i class="text-dark far fa-file-image">.{{ $row->file_ex }}</i>
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ date('M d, Y H:i', strtotime($row->created_at)) }}</td>
                                    <td>
                                        @if($row->status == 0)
                                            For Approval
                                        @elseif($row->status == 1)
                                            Hired
                                        @elseif($row->status == 2)
                                            Rejected
                                        @endif
                                    </td>
                                    @if($row->status == 0)
                                        <td class="text-center">
                                            <div class="w-100">
                                                <a href="{{ url('/applicant-decline?id='.$row->id.md5($row->id) ) }}" title="Decline" class="mr-1" onclick="return confirm('Decline applicant?')">
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100">
                                                <a href="{{ url('/applicant-hire?id='.$row->id.md5($row->id) ) }}" title="Hire" class="ml-1" onclick="return confirm('Hire applicant?')">
                                                    <i class="fa-solid fa-check text-info"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100">
                                                <i class="fa-solid fa-ellipsis" title="No action"></i>
                                            </div>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <div class="w-100">
                                                <i class="fa-solid fa-ellipsis" title="No action"></i>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100">
                                                <i class="fa-solid fa-ellipsis" title="No action"></i>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100">
                                                <a href="{{ url('/applicant-delete?id='.$row->id.md5($row->id) ) }}" title="Delete" onclick="return confirm('Delete applicantion?')">
                                                    <i class="fa-solid fa-trash-can text-danger"></i>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                <?php $count--; ?>
                            @endforeach
                        @endif
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
<script src="{{ asset('uidesign/vendor/elite/datatables/datatables.min.js') }}"></script>
@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script>
    $(function() {
        $('#myTable').DataTable();
    });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}