<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/img/abaca_logo.png') }}">
    <title>ABACA BAKING - Job Application</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Custom fonts for this template-->
    <link href="{{ asset('uidesign/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('uidesign/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
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
                            <img src="public/img/abaca_logo.png" alt="" style="width: 45%">
                        </div>

                        @if (session('invalid'))
                            <div class="alert text-center text-danger m-0" role="alert">{{session('invalid')}}</div>
                        @endif
                        
                        @if (session('success_message'))
                            <div class="alert text-center text-success m-0" role="alert">{{ session('success_message') }}</div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Job Application</h1>
                                    </div>
                                    <form class="user" method="POST" action="{{ url('/applicant') }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label for="position">Position <span class="text-danger">*</span> </label>
                                            <select class="border form-control custom-select selectpicker {{ $errors->has('position') ? 'is-invalid' : '' }}" data-live-search="true" name="position" autofocus>
                                                <option value="" disabled selected>Select Position</option>
                                                @if(count($positions) > 0)
                                                    @foreach ($positions as $row)
                                                        <option value="{{ $row->Position_Empl }}" @if (old('position') == $row->Position_Empl) {{ 'selected' }} @endif>{{ $row->Position_Empl }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('position') }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="First Name">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('first_name') }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="middle_name">Middle Name</label>
                                            <input type="text" class="form-control {{ $errors->has('middle_name') ? 'is-invalid' : '' }}" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('middle_name') }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('last_name') }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="file">Resume / CV <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control-file {{ $errors->has('file') ? 'is-invalid' : '' }}" id="file" name="file" aria-describedby="fileHelp" accept="image/png, image/jpeg, application/pdf">
                                            <small id="fileHelp" class="form-text text-muted">(PDF, DOC, DOCX, PNG, JPEG, JPG).</small>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('file') }}
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-dark btn-block">
                                            Submit Application
                                        </button>
                                        <hr>
                                        <div class="text-center">
                                            <a class="" href="{{ url('/login') }}"><strong>EMPLOYEE?</strong></a>
                                        </div>
                                    </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
</body>

</html>