@php
   $current_route = Route::current()->getName() ?? null;
   if($current_route == "page_locked") $current_route = Request::get('m');
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

   <!-- Sidebar - Brand -->
   <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
      <div class="sidebar-brand-icon">
         <img src="public/img/abaca_logo.png" alt="" style="width: 30%; ">
      </div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider my-0">

   {{-- Employee Sidebar --}}
   @include('Templates.sidebar_employee')

   {{-- Admin Sidebar --}}
   @include('Templates.sidebar_admin')

   {{-- <li class="nav-item">
      <a class="nav-link" href="{{ url('/datatables') }}">
      <i class="fas fa-fw fa-money-bill-alt"></i>
      <span>Data Tables</span></a>
   </li> --}}

   <!-- Divider -->
   <hr class="sidebar-divider d-none d-md-block">
   <!-- Sidebar Toggler (Sidebar) -->
   <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
   </div>

</ul>
<!-- End of Sidebar -->
