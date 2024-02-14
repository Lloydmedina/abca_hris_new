@php
  $userAgent = App\Http\Controllers\UserAgent::parseUserAgent(request()->userAgent());
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/img/abaca_logo.png') }}">
  <title>THE ABACA GROUP | @yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Custom fonts for this template-->
  {{-- <link href="{{ asset('uidesign/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}
  <script src="https://kit.fontawesome.com/f4c7512fea.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="{{ asset('uidesign/css/sb-admin-2.min.css') }}" rel="stylesheet">

  {{-- You can also include the stylesheet separately if desired: --}}
  @if(in_array($userAgent->browser, ["Safari"]))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.js"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.css"
      id="theme-styles"
    />
  @else
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
      id="theme-styles"
    />
  @endif

  {{-- Sweetalert2 --}}
  <style>
    .swal2-popup {
      font-size: .85rem !important;
    }

    .no-border {
      border: 0 !important;
    }
  </style>

  @yield('page_level_css')

</head>
<div class="progress fixed-top" id="my_progressbar_div" style="height: 2px;">
  <div class="progress-bar bg-danger" id="my_progressbar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<body id="page-top">
  <!-- Page Wrapper -->
  
  <div id="wrapper">
    {{-- SIDEBAR HERE --}}
    @include('Templates.sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        {{-- TOPBAR HERE --}}
        @include('Templates.topbar')
          {{-- BEGIN CONTENT HERE --}}
          @yield('content')
          {{-- END CONTENT HERE --}}
      </div>
      <!-- End of Main Content -->

      {{-- WEEKLY HOUR MODAL --}}
      <div id="weekly_hour" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAdd" style=" padding-right: 17px;">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header bg-primary text-white">
                  <h4 class="modal-title">Weekly Hour Setup</h4>
                  <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true" title="Close">×</button>
               </div>
               <form class="form-material" action="{{ url('/update_weekly_hour') }}" method="post">
                  @csrf
                  <div class="modal-body">
                     <div class="form-body">
                        <div class="row p-t-20">
                           <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Total Weekly Hour</label>
                              <input type="number" id="total_hour" value="{{ session('weekly_hr')->total_hour }}" name="total_hour"  class="form-control" step="any" required>
                            </div>
                          </div>
                          <!--/span-->
                        </div>
                        <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Six Day</label>
                              <input type="number" id="six_days" name="six_days" class="form-control" step="any" value="{{ session('weekly_hr')->six_days }}" required>
                            </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Five Days</label>
                              <input type="number" id="five_days" name="five_days" class="form-control" step="any" value="{{ session('weekly_hr')->five_days }}" required>
                            </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Four Days</label>
                              <input type="number" id="four_days" name="four_days" class="form-control" step="any" value="{{ session('weekly_hr')->four_days }}" required>
                            </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Three Days</label>
                              <input type="number" id="three_days" name="three_days" class="form-control" step="any" value="{{ session('weekly_hr')->three_days }}" required>
                            </div>
                          </div>
                          <!--/span-->
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <div class="form-actions m-auto">
                        <button type="button" class="btn btn-sm btn-danger mr-2" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary ml-2"> <i class="fa fa-plus-circle"></i> Update</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      {{-- END WEEKLY HOUR MODAL --}}


      {{-- Notice MODAL --}}
        <!-- Modal -->
        <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="noticeModalLabel">⚠️ Notification: Important Notice 📄</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> --}}
              </div>
              <div class="modal-body">
                Hello {{ ucwords( strtolower(session('employee')->FirstName_Empl ?? "Admin") ) }},
                <br/>
                <br/>
                You have an important notice waiting for your attention. Please take a moment to read it and stay updated on crucial information.
                Click the link below formore details.
                <br/>
                <br/>
                Kindly review the notice at your earliest convenience to ensure you are up to date with the latest developments.
                <br/>
                <br>
                <a id="notice_route" href="{{ url('/notices') }}">Check the notice here!</a>
                <br>
                <br>
                Thank you for your prompt attention to this matter.

              </div>
              {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div> --}}
            </div>
          </div>
        </div>
      {{-- MEMO MODAL --}}


      {{-- MEMO MODAL --}}
        <!-- Modal -->
        <div class="modal fade" id="memoModal" tabindex="-1" aria-labelledby="memoModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="memoModalLabel">📢 Notification: Important Memo 📄</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> --}}
              </div>
              <div class="modal-body">
                Hello {{ ucwords( strtolower(session('employee')->FirstName_Empl ?? "Admin") ) }},
                <br/>
                <br/>
                You have an important memo waiting for your attention. Please take a moment to read it and stay updated on crucial information.
                Click the link below formore details.
                <br/>
                <br/>
                Kindly review the memo at your earliest convenience to ensure you are up to date with the latest developments.
                <br/>
                <br>
                  <a id="memo_route" href="{{ url('/memo') }}">Check the memo here!</a>
                <br>
                <br>
                Thank you for your prompt attention to this matter.

              </div>
              {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div> --}}
            </div>
          </div>
        </div>
      {{-- MEMO MODAL --}}
      

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; THE ABACA GROUP {{ date('Y') }}</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('uidesign/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('uidesign/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('uidesign/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('uidesign/js/sb-admin-2.min.js') }}"></script>

  {{-- PAGE LEVEL PLUGIN HERE --}}
  @yield('page_level_plugin')
  {{-- PAGE LEVEL SCRIPT --}}
  @yield('page_level_script')

  <script type="text/javascript">

    let check_memo_input = $('#check_memo_input').val();
    let check_memo_date_from = $('#check_memo_input').data('date_from');
    let check_memo_url = "<?php echo url('/memo?date_from=') ?>" + check_memo_date_from;
    
    let check_notice_input = $('#check_notice_input').val();
    let check_notice_date_from = $('#check_notice_input').data('date_from');
    let check_notice_url = "<?php echo url('/notices?date_from=') ?>" + check_notice_date_from;

    $(window).on('load', function() {
      if(check_notice_input == 1){
        $('#noticeModal').modal({backdrop: 'static', keyboard: false}, 'show');
        $('#notice_route').attr("href", check_notice_url);
      }
      else if(check_memo_input == 1){
        $('#memoModal').modal({backdrop: 'static', keyboard: false}, 'show');
        $('#memo_route').attr("href", check_memo_url);
      }
    });

    // for progressbar loader
    var i = 1;
    function myLoop () {
      setTimeout(function () {
          // console.log(i);
          $("#my_progressbar").css('width', i+'%');
          i += 25;
          if (i < 125) {         
            myLoop();             
          }
          if(i > 125){
            setTimeout(function () {
              $("#my_progressbar_div").remove();
            }, 800);
            
          }           
      }, 100)
    }
    myLoop();


    $(document).ready(function(){
      
      let base_url = window.location.origin + '/' + window.location.pathname.split('/')[1] + '/';
      if(window.location.origin != "http://localhost"){
          base_url = window.location.origin + '/';
      }

      // $(document).on('submit', '#change_pass_form', function(e){

      //   e.preventDefault();
      //   let change_pass_form = $(this).serialize();
      //   // let url = base_url+'change_pass';
      //   let url = $(this).attr('action');
      //   let current_password = $('#current_password').val();
      //   let new_password = $('#new_password').val();
      //   let confirm_password = $('#confirm_password').val();

      //   $('#header_success_pass_level').text('');
      //   $('#header_err_pass_level').text('');

      //   if(new_password != confirm_password){
      //     $('#header_err_pass_level').text('Password did not match');
      //   }
      //   else if(new_password.length <=7 ){
      //     $('#header_err_pass_level').text('Password at least 8 characters in length');
      //   }
      //   else{
      //     // do ajax
      //     $.ajax({
      //           url: url,
      //           type: 'POST',
      //           data: change_pass_form,
      //           headers: {
      //               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //           },
      //           success: function(result) {

      //               if(result.code == 1){
      //                 $('#header_success_pass_level').text(result.message);
      //                 $('#current_password').val('');
      //                 $('#new_password').val('');
      //                 $('#confirm_password').val('');

      //               }
      //               else if(result.code == 0){
      //                 $('#header_err_pass_level').text(result.message);
      //               }
      //           },
      //           error: function(result){
      //               console.log(result);
      //           }

      //       });
      //   }

      // });
    });

    function formatDateAbaca(date) {
      const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      const month = months[date.getMonth()];
      const day = date.getDate();
      const year = date.getFullYear();
      
      return `${month} ${day}, ${year}`;
    }



    
    
  </script>
</body>

</html>
