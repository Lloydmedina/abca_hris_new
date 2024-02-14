@if(session('employee'))
    @php
        $current_route = Route::current()->getName() ?? null;
        $check_user_account = DB::table('users')->where('username', session('employee')->UserID_Empl)->first();
        $pass_change_required = false;
        if ( $check_user_account && password_verify($check_user_account->username, $check_user_account->web_password) )
            $pass_change_required = true;
    @endphp
    
    @if($current_route != 'settings' && $pass_change_required)
        <div class="alert alert-warning alert-coupon" style="border: 1px dashed;">
            <h4 style="font-size: 20px; line-height: 1.5; font-weight: bold;">URGENT PASSWORD CHANGE REQUIRED</h4>
            <p>Your account security is our top priority. Please change your account password now to ensure the safety of your personal information. <a href="{{ route('settings') }}">Click here</a></p>
        </div>
    @endif
@endif


@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger text-danger fade show" role="alert">
            {{$error}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif

@if (session('invalid'))
    <div class="alert alert-danger text-danger fade show" role="alert">
        {{ session('invalid') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{-- <script>
        Swal.fire({ 
            width: "350", position: "top-right", 
            icon: "warning", title: "Invalid", 
            text: "{{ session('invalid') }}", 
            showConfirmButton: false, timer: 2000
        });
    </script> --}}
@endif

@if (session('success_message'))
    <div class="alert alert-success text-info fade show" role="alert">
        {{ session('success_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{-- <script>
        Swal.fire({ 
            width: "350", position: "top-right", 
            icon: "success", title: "Success", 
            text: "{{ session('success_message') }}", 
            showConfirmButton: false, timer: 2000
        });
    </script> --}}
@endif