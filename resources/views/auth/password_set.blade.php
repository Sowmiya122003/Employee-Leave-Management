<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>
<body>
    <div style="width: 430px; margin:40px auto; border-radius: 10px;box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);">
        <h4 style="text-align: center; color: #3282f9;" class="pt-3">Password Reset</h4>
        <form class="row g-3"  method="POST" action="{{ route('password.update') }}">
            @csrf
            {{-- @if($data) --}}
                <input type="text" name="token" value="{{ $data->token }}" hidden>
                <input type="text" name="email" value="{{ $data->email }}" hidden>
                <div class="col-md-12 px-3">
                    <label for="inputEmail4" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail4" name="email" value="{{ $data->email }}" disabled>
                </div>
            {{-- @endif --}}
            <div class="col-md-12 px-3">
                <label for="inputaddress" class="form-label">Address</label>
                <input type="text" class="form-control" id="inputaddress" name="address">
                <!-- {{-- <a href="{{ route('password-rest-form') }}" style="margin-left: 290px; text-decoration: none;">Forgot Password</a></small> --}} -->
            </div>
            <div class="col-md-12 px-3">
                <label for="inputPassword4" class="form-label">New Password</label>
                <input type="password" class="form-control" id="inputPassword4" name="password">
                {{-- <a href="{{ route('password-rest-form') }}" style="margin-left: 290px; text-decoration: none;">Forgot Password</a></small> --}}
            </div>
            <div class="col-md-12 px-3">
                <label for="inputPassword4" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="inputConfirmPassword4" name="confirmpassword">
                {{-- <a href="{{ route('password-rest-form') }}" style="margin-left: 290px; text-decoration: none;">Forgot Password</a></small> --}}
            </div>
            <div class="col-6 px-3 pb-3" style="margin-left: 150px">
                <button type="submit" class="btn btn-primary">Submit</button>
                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

            @if (session('error'))
                <script>
                    toastr.error("{{ session('error') }}");
                </script>
            @elseif(session('success'))
                <script>
                    toastr.success("{{ session('success') }}")
                </script>
            @endif
</body>
</html>
