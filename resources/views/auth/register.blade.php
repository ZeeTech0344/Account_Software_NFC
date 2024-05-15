<!DOCTYPE html>
<html lang="en">
@php
   if(Auth::User()->user_type == "User"){
    header("Location:".url('/'));
    die();
   }

@endphp


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title></title>
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <link href="{{ url('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ url('bootstrap/js/bootstrap.js') }}"></script>

    <style>
        @keyframes anim{
    from{  
        transform: rotatey(0deg);
    }

    to{
        transform:rotatey(360deg);
    }
}

#home_logo{
    
    animation-name: anim;
    animation-duration: 2s;
    animation-iteration-count: 1;
    animation-direction: alternate-reverse;
    
}

    </style>

    
</head>

<body style="background-color:#4e73df">

    <div class="container col-6">
        <div class="row m-2 justify-content-center" >
            {{-- <div class="col-md-8 d-flex justify-content-center" style="margin-top: 200px;">
                <img src="{{ url('img/logo.jpg') }}" id="home_logo"  alt="nfc" style="width:270px; height:200px;border-radius:50%; border:2px solid  #4e73df; margin-bottom:30px;">
            </div> --}}
        </div>
        <div class="card">
            <div class="card-header">Registeration</div>
            <div class="card-body" style="background-color:#ffffff">
            
                <div class="alert alert-danger d-none invalid" role="alert">
                
                </div>
                <div class="alert alert-success d-none success" role="alert">
                   Register Successfully!
                </div>
                <form id="register-form">
                    <div class="form-group m-0">
                        <label>User Name</label>
                        <input type="text" class="form-control" id="user_name" name="name"
                            placeholder="User Name">
                    </div>
                    <div class="form-group m-0">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                    </div>
                    <div class="form-group m-0">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="Password">
                    </div>
                    <div class="form-group ">
                        <label>User Type</label>
                        <select name="user_type" id="user_type" class="form-control">
                            <option value="">Select User Type</option>
                            <option>Admin</option>
                            <option>User</option>
                            <option>Owner</option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label>Select Branch</label>
                        <select name="user_branch" id="user_branch" class="form-control">
                            <option value="">Select Branch</option>
                            <option>Head Office</option>
                            @foreach ($branches as $get_branch)
                                <option>{{ $get_branch->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Register">
                    
                </form>
                <div class="col-12">
                    <a class="ml-2 d-block text-center" href="{{url('/')}}">home page?</a>
                </div>
           
        
    </div>
</div>
</div>
  
</body>

</html>
<script>
    $("#register-form").on("submit", function(e) {
        e.preventDefault();
        var name = $("#user_name")[0].value;
        var password = $("#password")[0].value;
        // if((name=="" || name.length>20) || (password=="" || password.length>20)){
        //     $(".alert").removeClass('d-none');
        //     return false;
        // }else{
        var formData = new FormData(this);
        $(".alert").addClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('register-user') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
            if(JSON.parse(data) == "saved"){
                $(".invalid").addClass('d-none');
                $(".success").removeClass('d-none');
                $("#register-form").trigger("reset");
               }
            },
            error:function(data){
                $(".invalid").removeClass('d-none');
                $(".success").addClass('d-none');
                var errors = data.responseJSON;
                var invalid = $(".invalid");
                // console.log(invalid);
                $.each( errors, function( key, value ) {
                    var create = document.createElement("li");
                    create.innerText = value;
                    invalid.append(create);
                    // $(".invalid").append("<li>"+ value +"</li>");
                });
              
            }
        
        })
        // }
    })
</script>







{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
