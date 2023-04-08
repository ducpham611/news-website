<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{asset('assets/clients/css/login.css')}}">

    <link rel="icon" href="Favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <title>Quên mật khẩu</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{route('home')}}">News Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/login">Đăng nhập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">Đăng ký</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/">Trang chủ</a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Quên mật khẩu</div>
                    <div class="card-body">
                        @if (session()->get('success-msg'))
                            <div class="alert alert-success">{{session()->get('success-msg')}}</div>   
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">Dữ liệu nhập vào không hợp lệ. Vui lòng kiểm tra lại</div>
                        @endif
                        <form action="" method="POST">
                            <div class="form-group row" style="justify-content: center;">
                                {{-- <label for="email_address" class="col-md-4 col-form-label text-md-right">Nhập email bạn đã dùng để đăng ký tài khoản</label> --}}
                                <div class="col-md-6">
                                    <label for="email_address" class="col-form-label">Nhập email bạn đã dùng để đăng ký tài khoản</label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{old('email')}}">  
                                    @error('email')
                                        <span style="color:red;">{{$message}}</span>
                                    @enderror                             
                                </div>
                            </div>

                            @csrf
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    Xác nhận
                                </button>
                                <a href="{{route('login')}}" class="btn btn-warning">Quay lại</a>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

</main>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>