@extends('Templates.main_layout')
{{-- BEGIN PAGE LEVEL CSS--}}
@section('page_level_css')
<style>
   input.larger {
     width: 15px;
     height: 15px;
   }

   .custom-control-input:checked~.custom-control-label::before {
      color: #fff;
      border-color: #7B1FA2;
   }

   .custom-control-input:checked~.custom-control-label.red_label::before {
      background-color: red;
   }

   .custom-control-input:checked~.custom-control-label.green_label::before {
      background-color: green;
   }
 </style>
@endsection
{{-- END PAGE LEVEL CSS --}}
@section('title','Users')
{{-- BEGIN CONTENT --}}
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

   @include('Templates.alert_message')

   <!-- Start Page Content -->
   <!-- ============================================================== -->

   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-4">
               <h4 class="card-title">List of all users <small>({{ count($users) }})</small></h4>
            </div>
            <div class="col-4">
               <input type="text" class="form-control mb-2" id="myInputSearch" onkeyup="searchNames()" placeholder="Search for names..">
            </div>
            <div class="col-4 text-right">
               {{-- <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus-circle"></i> Add New</button> --}}
            </div>
         </div>
         <div class="table-responsive m-t-40">
            <table id="example23_" class="display nowrap table table-sm table-hover table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     {{-- <th class="text-center">Photo</th> --}}
                     <th>Username</th>
                     <th>Name</th>
                     <th>Email Address</th>
                     <th>User Type</th>
                     <th class="text-center">Status</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody id="myTbody">
                  @foreach($users as $row)

                     @if(session('user')->employee_type_id != 1)
                        @if($row->employee_type_id == 1)
                           <?php continue; ?>
                        @endif
                     @endif
                     <tr>
                        {{-- <td class="text-center">
                           <a href="{{ asset($row->photo) }}" target="_blank">
                              <img class="rounded-circle" style="width: 30px; height: 30px; cursor: pointer;" src="{{ asset($row->photo) }}" alt="Profile photo">
                           </a> --}}
                        <td>{{ $row->username }}</td>
                        <td>
                           {{ strtoupper($row->first_name) .' '. strtoupper($row->last_name) }}
                           {{-- <a href="{{ url('employee?id='.$row->emp_id.md5( $row->emp_id) ) }}" class="text-info" title="View Information">
                              {{ ucwords(strtolower($row->first_name)) .' '. ucwords(strtolower($row->last_name)) }}
                           </a> --}}
                        </td>
                        
                        <td>{{ $row->email }}</td>
                        <td>{{ ucwords(strtolower($row->employee_type)) }} 
                           
                        </td>
                        <td class="text-center">
                           @if(strtoupper($row->status )== 'ACTIVE')
                              <span style="color: green">{{ ucwords(strtolower($row->status)) }}</span>
                           @else
                              <span style="color: red">{{ ucwords(strtolower($row->status)) }}</span>
                           @endif
                        </td>
                        <td class="text-center">
                           {{-- @if(strtoupper($row->status )== 'ACTIVE')
                           <a href="{{ url('deactivate_user/'.$row->id.md5($row->id)) }}" onclick="return confirm('Are you sure you want to deactivate {{ $row->email }}?')">
                           <span class="badge badge-pill badge-danger" title="Deactivate account">Deactivate</span>
                           </a>
                           @else
                           <a href="{{ url('activate_user/'.$row->id.md5($row->id)) }}" onclick="return confirm('Are you sure you want to activate {{ $row->email }}?')">
                           <span class="badge badge-pill badge-primary" title="Activate account">Activate</span>
                           </a>
                           @endif --}}
                           
                           <span class="ml-1 text-primary" title="Update user type" style="cursor: pointer;" data-toggle="modal" data-target=".editModal">
                              <span class="fa-solid fa-user-pen" onclick="get_user_id({{ $row->id }} +'{{ md5($row->id) }}')"></span>
                           </span>
                        </td>
                     </tr>

                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <hr>

</div>
<!-- /.container-fluid -->

{{-- BEGIN MODAL --}}
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="exampleModalLabel">New User</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form action="{{ route('add_user') }}" method="post" enctype="multipart/form-data">
            <div class="modal-body">
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
                  <div class="col-6 text-uppercase">
                     <div class="form-group">
                        <label>First Name <span class="text-danger">*</span>
                        </label>
                        <input name="first_name" type="text" class="form-control text-uppercase" value="{{ old('first_name') }}" placeholder="First Name" required>
                     </div>
                  </div>
                  <div class="col-6 text-uppercase">
                     <div class="form-group">
                        <label>Last Name <span class="text-danger">*</span>
                        </label>
                        <input name="last_name" type="text" class="form-control text-uppercase" value="{{ old('last_name') }}" placeholder="Last Name" required>
                     </div>
                  </div>
                  <div class="col-6">
                     <div class="form-group">
                        <label>Email Address <span class="text-danger">*</span>
                        </label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Email Address" required>
                     </div>
                  </div>
                  <div class="col-6 text-uppercase">
                     <div class="form-group">
                        <label>
                        Employee Type <span class="text-danger">*</span>
                        <span class="help"></span>
                        </label>
                        <select name="employee_type_id" class="form-control" required>
                           @foreach($emp_type as $row)
                              @if($row->id == 2 || strtoupper( $row->employee_type ) != 'ADMIN')
                              <option value="{{ $row->id.md5( $row->id ) }}">{{ $row->employee_type }}</option>
                              @endif
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="form-actions m-auto">
                  <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="submit" class="btn btn-sm btn-primary ml-1"> <i class="fas fa-user-plus"></i> Add</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- END MODAL --}}
<!-- Logout Modal-->
<div class="modal fade editModal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="exampleModalLabel">Update user?</h5>
            <button class="close text-danger" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-12 text-center div_spin">
                  <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
               </div>

               <div class="col-5 div_info">
                  <div id='img_contain' class="text-center mb-3">
                     <img class="rounded card card-body m-auto" id="user_photo" align='middle' src="{{ url('public/img/upload_def.png') }}" alt="upload image" style="width: 150px; height: 150px" />
                  </div>
                  <center><b id="username_here"></b></center>
               </div>
               
               <div class="col-7 div_info">
                  <p id="set_name"></p>
                  {{-- <p id="set_email"></p> --}}
                  <p id="set_emp_type"></p>
                  <form method="post" action="{{ route('update_emp_type') }}">
                     @csrf
                     <input type="hidden" id="user_id" name="user_id">
                     <div class="form-group mt-1">
                        <select name="employee_type_id" id="employee_type_id" class="form-control" required>
                           <option value="" disabled selected>SELECT TO UPDATE</option>
                           @foreach($emp_type as $row)
                              @if($row->id != 1)
                              <option value="{{ $row->id.md5($row->id) }}">{{ $row->employee_type }}</option>
                              @endif
                           @endforeach
                        </select>
                     </div>
                     <div>
                        <div class="custom-control custom-radio custom-control-inline">
                           <input type="radio" id="input_green" name="status" class="custom-control-input" value="ACTIVE">
                           <label class="custom-control-label green_label" for="input_green">Active</label>
                         </div>
                         <div class="custom-control custom-radio custom-control-inline">
                           <input type="radio" id="input_red" name="status" class="custom-control-input" value="INACTIVE">
                           <label class="custom-control-label red_label" for="input_red">Inactive</label>
                         </div>
                     </div>
                     <div class="form-check div_reset_pass">
                        <label class="form-check-label mt-2">
                          <input type="checkbox" class="form-check-input larger" name="reset_pass" value="1">Reset password?
                        </label>
                     </div>
                     {{-- sumbit button --}}
                     <button class="btn btn-sm btn-primary d-none" id="submit_button_update" type="submit">Update</button>
                  </form>
               </div>
               
            </div>
         </div>
         <div class="modal-footer">
            <div class="form-actions m-auto div_info">
               <button class="btn btn-sm btn-danger mr-2" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
               <button class="btn btn-sm btn-primary ml-2" id="trigger_update_button" type="button"><i class="fa fa-check"></i> Update</button>
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
{{-- END CONTENT --}}
{{-- BEGIN PAGE LEVEL PLUGIN --}}
@section('page_level_plugin')

@endsection
{{-- END PAGE LEVEL PLUGIN --}}
{{-- BEGIN PAGE LEVEL SCRIPT --}}
@section('page_level_script')
<script src="{{ asset('uidesign/js/custom/user_lists.js') }}"></script>
<script>
   $(document).ready(function(){
      $('#trigger_update_button').click(function() {
         $('#submit_button_update').click();
      });
   });

   function get_user_id(id) {
      $('.div_info').addClass('d-none');
      $('.div_reset_pass').addClass('d-none');

      $.ajax({
         url: "{{ url('/get_user_details') }}",
         type: 'get',
         data: {
               id: id
         },
         beforeSend: function(){
            $('.div_spin').removeClass('d-none');
            
         },
         success: function(ret) {
               $.each(ret, function(index, row) {
                  $('#set_name').text('NAME: ' + row.first_name + ' ' + row.last_name);
                  // $('#set_email').text('EMAIL: ' + row.email);
                  $('#set_emp_type').text('CURRENT: ' + row.employee_type);
                  $('#user_photo').attr('src', row.photo);
                  $('#user_id').val(id);
                  $('#employee_type_id').val(row.employee_type_id).change();
                  $('#username_here').text(row.username);

                  if(row.status == 'ACTIVE'){
                     $("#input_green").prop("checked", true);
                     $("#input_red").prop("checked", false);
                  }
                  else{
                     $("#input_red").prop("checked", true);
                     $("#input_green").prop("checked", false);
                  }

                  // means the default password was changed, show the checkbox for reset password
                  if(row.is_def_pass == 0){
                     $('.div_reset_pass').removeClass('d-none');
                  }
                  // ("div.id_100 select").val("val2").change();
                  $('.div_spin').addClass('d-none');
                  $('.div_info').removeClass('d-none');
               });
         },
         error: function(ret) {}
      });
   }

   function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        var filename = $("#inputGroupFile01").val();
        filename = filename.substring(filename.lastIndexOf('\\') + 1);
        reader.onload = function(e) {
            // debugger;
            $('#blah').attr('src', e.target.result);
            $('#blah').hide();
            $('#blah').fadeIn(500);
            $('.custom-file-label').text(filename);
        }
        reader.readAsDataURL(input.files[0]);
      }
   }

   function searchNames() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInputSearch");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTbody");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
        }
    }
    }

   $('#blah').click(function() {
      $('#inputGroupFile01').click();
   });
   $("#inputGroupFile01").change(function(event) {
      readURL(this);
   });
</script>
@endsection
{{-- END PAGE LEVEL SCRIPT --}}