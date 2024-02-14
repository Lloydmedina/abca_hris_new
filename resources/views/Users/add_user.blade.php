@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Add User')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <div class="card">
      <div class="card-body">
         <form action="{{ route('add_user') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-12">
                  <div class="alert d-none"></div>
                  <div id='img_contain' class="text-center mb-3">
                     <img class="rounded card card-body m-auto" id="blah" align='middle' src="{{ url('public/img/upload_def.png') }}" alt="upload image" style="width:200px; cursor:pointer" />
                     <h6 class="text-uppercase">Profile Photo</h6>
                  </div>
                  <div class="input-group d-none">
                     <div class="custom-file">
                        <input name="photo_path" type="file" id="inputGroupFile01" class="imgInp custom-file-input" aria-describedby="inputGroupFileAddon01" accept="image/*">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                     </div>
                  </div>
               </div>
               <div class="col-12 text-uppercase">
                  <div class="form-group">
                     <label>First Name <span class="text-danger">*</span>
                     </label>
                     <input name="first_name" type="text" class="form-control text-uppercase" value="{{ old('first_name') }}" placeholder="First Name" required>
                  </div>
               </div>
               <div class="col-12 text-uppercase">
                  <div class="form-group">
                     <label>Last Name <span class="text-danger">*</span>
                     </label>
                     <input name="last_name" type="text" class="form-control text-uppercase" value="{{ old('last_name') }}" placeholder="Last Name" required>
                  </div>
               </div>
               <div class="col-12">
                  <div class="form-group">
                     <label>Email Address <span class="text-danger">*</span>
                     </label>
                     <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Email Address" required>
                  </div>
               </div>
               <div class="col-12 text-uppercase">
                  <div class="form-group">
                     <label>
                     Employee Type <span class="text-danger">*</span>
                     <span class="help"></span>
                     </label>
                     <select name="employee_type_id" class="form-control" required>
                        @foreach($emp_type as $row)
                        @if($row->id == 2 || strtoupper($row->employee_type) == 'SUB ADMIN')
                        <option value="{{ $row->id }}">{{ $row->employee_type }}</option>
                        @endif
                        @endforeach
                     </select>
                  </div>
               </div>
            </div>
            <div class="form-actions text-center">
               <a href="" class="btn btn-sm btn-danger mr-2" onclick="return confirm('Are you sure you want to reset?')"><i class="fas fa-undo"></i> Reset</a>
               <button type="submit" class="btn btn-sm btn-info ml-2"> <i class="fas fa-user-plus"></i> Add</button>
            </div>
         </form>
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
<script src="{{ asset('uidesign/js/custom/add_user.js') }}"></script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}