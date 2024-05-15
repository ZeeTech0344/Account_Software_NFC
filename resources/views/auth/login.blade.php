<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <link href="{{ url('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ url('bootstrap/js/bootstrap.js') }}"></script>'
    
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
    <div class="container">
        <main class="login-form">
          
            <div class="cotainer">
             
                <div class="row justify-content-center" style="margin-top: 200px;">
                    <div class="col-md-8 d-flex justify-content-center">
                        <img src="{{ url('img/logo.jpg') }}" id="home_logo"  alt="nfc" style="width:270px; height:200px;border-radius:50%; border:2px solid  #4e73df; margin-bottom:30px;">
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Login</div>
                            <div class="card-body" style="background-color:#ffffff">

                                <form action="{{ route('login.post') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input type="text" id="email_address" class="form-control" name="name"
                                                required autofocus placeholder="User Name">
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input type="password" id="password" class="form-control" name="password"
                                                required placeholder="Password">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-0">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- <div class="row justify-content-center">
            <div class="col-6 border m-5 p-2">
                <form method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">User Name</label>
                        <input type="text" class="form-control" id="user_name" name="name"
                            placeholder="User Name">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary" name="login" id="login">Login</button>
                </form>
            </div>
        </div> --}}
    </div>
</body>

</html>
