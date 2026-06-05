<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="auth-body">
    <main class="auth-page">
        <section class="auth-card">
            <form class="needs-validation" action="{{ route('login.submit') }}" method="POST">
                <div class="mb-4">
                    <p class="eyebrow mb-1">Secure Access</p>
                    <h1 class="h3 mb-1">Login</h1>
                    <p class="text-muted mb-0">Sign in to your admin workspace.</p>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="loginEmail">Email address</label>
                    <input class="form-control" id="loginEmail" type="email" name="email" required>
                    <div class="invalid-feedback">Enter a valid email.</div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="loginPassword">Password</label>
                        <a class="small fw-semibold" href="forgot-password.html">Forgot?</a>
                    </div>
                    <div class="position-relative">
                        <input class="form-control" id="loginPassword" type="password" name="password" minlength="6"
                            required>
                        <i class="bi bi-eye position-absolute" id="togglePassword"
                            style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-box-arrow-in-right"
                        aria-hidden="true"></i> Sign In</button>
            </form>
            <div class="auth-footer">New here? <a href="{{ route('register') }}">Create an account</a></div>
        </section>
    </main>
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
    <script>
        $(document).on('click', '#togglePassword', function() {
            let password = $('#loginPassword');

            if (password.attr('type') === 'password') {
                password.attr('type', 'text');
                $(this).removeClass('bi-eye').addClass('bi-eye-slash');
            }
            else {
                password.attr('type', 'password');
                $(this).removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    </script>
</body>

</html>
