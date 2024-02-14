<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
   <!-- Sidebar Toggle (Topbar) -->
   <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
       <i class="fa fa-bars"></i>
   </button>
   <div class="ml-3">
       <h4 class="h4" style="font-size:1.6vw;">@yield('title')</h4>
   </div>
   <!-- Topbar Search -->
   <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
       <div class="input-group d-none">
           <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
               aria-label="Search" aria-describedby="basic-addon2">
           <div class="input-group-append">
               <button class="btn btn-primary" type="button">
                   <i class="fas fa-search fa-sm"></i>
               </button>
           </div>
       </div>
   </form>
   <!-- Topbar Navbar -->
   <ul class="navbar-nav ml-auto">
       <!-- Nav Item - Search Dropdown (Visible Only XS) -->
       <li class="nav-item dropdown no-arrow d-sm-none">
           <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-search fa-fw"></i>
           </a>
           <!-- Dropdown - Messages -->
           <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
               aria-labelledby="searchDropdown">
               <form class="form-inline mr-auto w-100 navbar-search">
                   <div class="input-group">
                       <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                           aria-label="Search" aria-describedby="basic-addon2">
                       <div class="input-group-append">
                           <button class="btn btn-primary" type="button">
                               <i class="fas fa-search fa-sm"></i>
                           </button>
                       </div>
                   </div>
               </form>
           </div>
       </li>
       <div class="topbar-divider d-none d-sm-block"></div>
       <!-- Nav Item - User Information -->
       <li class="nav-item dropdown no-arrow">
           <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
               <span
                   class="mr-2 d-none d-lg-inline text-gray-600 small">{{ session('user')->first_name . ' ' . session('user')->last_name }}</span>
               <span
                   class="mr-2 d-none d-lg-inline text-gray-600"><small>({{ session('user')->employee_type }})</small></span>
               @if (session('employee'))
                   @php
                       if (strtolower(session('employee')->gender) == 'male') {
                           $defCover = 'public/default/cover/Male.png';
                           $defProfile = 'public/default/profile/Male.jpg';
                       } else {
                           $defCover = 'public/default/cover/Female.png';
                           $defProfile = 'public/default/profile/Female.jpg';
                       }
                       $profilePic = session('employee')->picture_path ? session('employee')->picture_path : $defProfile;
                   @endphp
                   <img class="img-profile border rounded-circle" src="{{ $profilePic }}">
               @else
                   <img class="img-profile border rounded-circle" src="storage/uploads/profile_picture/default.png">
               @endif
           </a>
           <!-- Dropdown - User Information -->
           <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @if(session('employee'))
                    <a class="dropdown-item" href="{{ url('/employee?id=') . session('user')->emp_id . md5(1) }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                @endif
               {{-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#security_question">
                  <i class="fa-solid fa-gear mr-2 text-gray-400"></i>
                  Security Question
               </a> --}}
               <a class="dropdown-item" href="{{ url('/settings')}}">
                  <i class="fa-solid fa-gear mr-2 text-gray-400"></i>
                  Settings
              </a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                   <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                   Logout
               </a>
           </div>
       </li>
   </ul>
</nav>
<!-- End of Topbar -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
               <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
           <div class="modal-footer">
               <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
               <a class="btn btn-dark" href="{{ url('/logout?id=' . md5('str')) }}">Logout</a>
           </div>
       </div>
   </div>
</div>

<!-- Change pass Modal-->
{{-- <div class="modal fade" id="security_question" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Set your security question</h5>
               <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <form id="change_pass_form" method="POST" action="{{ route('change_pass') }}">
               <div class="modal-body">
                   <span class="text-danger" id="header_err_pass_level"></span>
                   <span class="text-primary" id="header_success_pass_level"></span>
                   @csrf
                   <div class="row">
                       <div class="col-12">
                           <div class="form-group">
                               <label class="control-label">Current Password</label>
                               <input type="password" id="current_password" name="current_password" value=""
                                   class="form-control" placeholder="***********" required>
                           </div>
                       </div>
                       <div class="col-12">
                           <div class="form-group">
                               <label class="control-label">New Password</label>
                               <input type="password" id="new_password" name="new_password" value=""
                                   class="form-control" placeholder="***********" required>
                           </div>
                       </div>
                       <div class="col-12">
                           <div class="form-group">
                               <label class="control-label">New Password</label>
                               <input type="password" id="confirm_password" name="confirm_password" value=""
                                   class="form-control" placeholder="***********" required>
                           </div>
                       </div>
                   </div>

               </div>
               <div class="modal-footer">
                   <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                   <button class="btn btn-primary" type="submit">Save</button>
               </div>
           </form>
       </div>
   </div>
</div> --}}
