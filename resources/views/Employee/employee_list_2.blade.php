@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')

<style>
    /* body{
    margin-top:20px;
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;    
}
.main-body {
    padding: 15px;
} */

.btn_href_view_details:hover {
    background-color: #222222;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}

.bg-white {
    background-color: #fff!important;
}
.btn-light {
    color: #1a202c;
    background-color: #fff;
    border-color: #cbd5e0;
}
.ml-2, .mx-2 {
    margin-left: .5rem!important;
}

.card-footer:last-child {
    border-radius: 0 0 .25rem .25rem;
}
.card-footer, .card-header {
    display: flex;
    align-items: center;
}
.card-footer {
    padding: .5rem 1rem;
    background-color: #fff;
    border-top: 0 solid rgba(0,0,0,.125);
}

</style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Employees')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    @include('Templates.alert_message')
    
    <div class="card">
        <div class="card-body">
            @php
                $arr_emp_status = request()->input('emp_status') ?? [];
                // dd($arr_emp_status);
            @endphp
            <form action="{{ route('employees') }}" method="get">
                {{-- @csrf --}}
                <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="form-check form-check-inline mb-3">
                        <input class="form-check-input emp_status_all" name="emp_status[]" type="checkbox" id="inlineCheckbox_ALL" value="ALL" {{ (in_array("ALL", $arr_emp_status)) ? 'checked' : '' }}>
                        <label class="form-check-label mr-3" for="inlineCheckbox_ALL">ALL</label> |
                    </div>
                    @foreach($empStatus as $row)
                        <div class="form-check form-check-inline mb-3">
                            <input class="form-check-input emp_status" name="emp_status[]" type="checkbox" id="inlineCheckbox_{{ $row->Status_Empl }}" value="{{ $row->Status_Empl }}"  {{ (in_array($row->Status_Empl, $arr_emp_status)) ? 'checked' : '' }}>
                            <label class="form-check-label mr-3" for="inlineCheckbox_"{{ $row->Status_Empl }}>{{ $row->Status_Empl }}</label> |
                        </div>
                    @endforeach
                </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                        <label class="control-label">Department</label>
                            <select id="department" name="department" class="form-control custom-select">
                                    <option value="0">All</option>
                                    @foreach($department as $row)
                                        <option value="{{ $row->SysPK_Dept }}" {{ ($row->SysPK_Dept == request()->get('department')) ? 'selected':'' }}>{{ $row->Name_Dept }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                        <label class="control-label">Outlet</label>
                            <select id="outlet" name="outlet" class="form-control custom-select">
                                    <option value="0">All</option>
                                    @foreach($outlets as $row)
                                    <option value="{{ $row->outlet_id }}" {{ ($row->outlet_id == request()->get('outlet')) ? 'selected':'' }}> {{ $row->outlet }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12"></div>
                    <div class="col-lg-2 col-sm-12">
                        <div class="form-group">
                            {{-- <input style="cursor:pointer" type="submit" class="form-control btn-primary" id="btn-search-button" value="SEARCH" name="btn_search" hidden> --}}
                            <label class="hide" style="visibility: hidden">Search Button</label>
                            @include('button_component.search_button', ['margin_top' => "-1.5"])
                            {{-- <button type="button" class="btn btn-primary mt-auto w-100" id="btn-search-employees"><i class="fa fa-search" aria-hidden="true"></i> Search</button> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
       
    <hr>
            
    <div class="row">
        <div class="col-lg-4 col-sm-12">
            <h4 class="card-title">Employees <small>({{ count($employees) }})</small></h4>
        </div>
        <div class="col-lg-4 col-sm-12">
            <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
        </div>
        <div class="col-lg-4 col-sm-12 text-lg-right">
            <a href="{{ url('/add-employee') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i> Add New</a>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 gutters-sm mt-3" id="myEmpList">
        @foreach($employees as $employee)
            @php
                if (strtolower($employee->gender) == 'male'){
                    $defCover = 'public/default/cover/Male.png';
                    $defProfile = 'public/default/profile/Male.jpg';
                }else {
                    $defCover = 'public/default/cover/Female.png';
                    $defProfile = 'public/default/profile/Female.jpg';
                }
                $profilePic = $employee->picture_path ? $employee->picture_path : $defProfile;
            @endphp

            <div class="col-lg-2 col-md-4 col-sm-3 mb-3 myEmpList">
                <div class="card">
                    <img src="{{ $defCover }}" alt="Cover" class="card-img-top">
                    <div class="card-body text-center">
                        <img src="{{ $profilePic }}" style="width:100px;margin-top:-65px" alt="User" class="img-fluid img-thumbnail rounded-circle border-0 mb-3">
                        <h5 class="card-title text-dark">{{ ucwords(strtolower($employee->Name_Empl)) }}</h5>
                        <p class="text-secondary mb-1"><i class="fa fa-building" title="Outlet" aria-hidden="true"></i> {{ ucwords(strtolower($employee->outlet)) }}</p>
                        <p class="text-secondary mb-1"><i class="fa fa-briefcase" title="Position" aria-hidden="true"></i> {{ ucwords(strtolower($employee->Position_Empl)) }}</p>
                        <p class="text-muted font-size-sm mb-1"><i class="fa fa-map-marker" title="Address" aria-hidden="true"></i> {{ \Illuminate\Support\Str::limit(ucwords(strtolower($employee->Address_Empl)), 50, $end='...') }}</p>
                        
                        @if($employee->contact_no)
                            <p class="text-muted font-size-sm mb-1"><i class="fa fa-phone" title="Mobile" aria-hidden="true"></i> {{ $employee->contact_no }}</p>
                        @endif
                        <strong><p class="text-muted font-size-sm mb-0">{{ $employee->Status_Empl }}</p></strong>
                    </div>
                    <div class="card-footer btn_href_view_details">
                        <a href="{{ url('employee?id='.$employee->SysPK_Empl.md5( $employee->SysPK_Empl) ) }}" class="btn btn-light btn-sm bg-white has-icon btn-block" type="button"><i class="material-icons">View full details</i></a>
                    </div>
                </div>
            </div>

        @endforeach
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
    $(document).ready(function(){

        $(".emp_status_all").click(function(){
            $('.emp_status').not(this).prop('checked', this.checked);
        });

        // $('#btn-search-employees').click(function(){
        //     $('#btn-search-button').click();
        // });
    });

    function searchNames() {
        // Declare variables
        var input, filter, div, h5, name, i, txtValue;
        input = document.getElementById("myInputSearch");
        filter = input.value.toUpperCase();
        div = document.getElementById("myEmpList");
        divC = document.getElementsByClassName("myEmpList");
        h5 = div.getElementsByTagName("h5");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < h5.length; i++) {
            name = h5[i];
            if (name) {
                txtValue = name.textContent || name.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    divC[i].style.display = "";
                } else {
                    divC[i].style.display = "none";
                }
            }
        }
    }
</script>

@endsection
{{-- END PAGE LEVEL SCRIPT --}}