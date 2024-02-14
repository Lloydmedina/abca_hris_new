<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/img/abaca_logo.jpg') }}">
      <title>ABACA BAKING - Login</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- Custom fonts for this template-->
      <link href="{{ asset('uidesign/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
      <!-- Custom styles for this template-->
      <link href="{{ asset('uidesign/css/sb-admin-2.min.css') }}" rel="stylesheet">
      <style type="text/css"></style>
   </head>
   <!-- <body style="background-image:url('public/img/bg.jpg');
      background-repeat:no-repeat;
      background-size:cover;"> -->
    <body>
      <div class="container">
         <!-- Outer Row -->
         <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-5 col-md-5" style="margin-top: 1%">
               <div class="card o-hidden border-0 shadow-lg my-5">
                  <div class="card-body p-0">
                    <div class="text-center pt-2">
                      <img src="public/img/abaca_logo.jpg" alt="" style="width: 45%">
                    </div>
                     <!-- Nested Row within Card Body -->
                     {{-- @if ($errors->any())
                        @foreach ($errors->all() as $error)
                              <div class="alert text-center text-danger m-0" role="alert">{{$error}}</div>
                        @endforeach
                     @endif --}}
                     @if (session('invalid'))
                        <div class="alert text-center text-danger m-0" role="alert">{{ session('invalid') }}</div>
                     @endif
                     @if (session('logout_success'))
                        <div class="alert text-center text-success m-0" role="alert">{{ session('logout_success') }}</div>
                     @endif

                     <div class="row">
                        <div class="col-12">
                           <div class="p-5">
                              <div class="text-center">
                                 <h1 class="h4 text-gray-900 mb-4">Login Your Account!</h1>
                              </div>
                              <form class="user" method="POST" action="{{ url('/login') }}">
                                 @csrf
                                 {{-- form-control-user --}}
                                 <div class="form-group">
                                    <label for="user_email">Email or username</label>
                                    <input type="text" class="form-control {{ $errors->has('user_email') ? 'is-invalid' : '' }}" name="user_email" id="user_email" placeholder="Email or username" value="{{ old('user_email') }}" autofocus>
                                    <div class="invalid-feedback">
                                       {{ $errors->first('user_email') }}
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" placeholder="Password">
                                    <div class="invalid-feedback">
                                       {{ $errors->first('password') }}
                                    </div>
                                 </div>
                                 <button type="submit" class="btn btn-dark btn-block">
                                    {{-- btn-user --}}
                                 Login
                                 </button>
                              </form>
                              <hr>
                              <div class="text-center">
                                 <a class="" href="{{ url('/applicant') }}"><strong>APPLICANT?</strong></a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap core JavaScript-->
      <script src="{{ asset('uidesign/vendor/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('uidesign/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <!-- Core plugin JavaScript-->
      <script src="{{ asset('uidesign/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
      <!-- Custom scripts for all pages-->
      <script src="{{ asset('uidesign/js/sb-admin-2.min.js') }}"></script>
   </body>
</html>
