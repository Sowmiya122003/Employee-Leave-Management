<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="auth-body">
    <main class="auth-page">
        <section class="auth-card">
            <form class="needs-validation" method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="mb-4">
                    <p class="eyebrow mb-1">Secure Access</p>
                    <h1 class="h3 mb-1">Register</h1>
                    <p class="text-muted mb-0">Create your admin account.</p>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerName">Full name</label>
                    <input class="form-control" id="registerName" type="text" name="full_name" required>
                    <div class="invalid-feedback">Full name is required.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerEmail">Email address</label>
                    <input class="form-control" id="registerEmail" type="email" name="email" required>
                    <div class="invalid-feedback">Enter a valid email.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerDateofBirth">Date of Birth</label>
                    <input class="form-control" id="registerDateofBirth" type="date" name="date_of_birth" required>
                    <div class="invalid-feedback">Enter a valid date.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerPhone">Phone Number</label>
                    <input class="form-control" id="registerPhone" type="text" name="phone" required>
                    <div class="invalid-feedback">Enter a valid phone number.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerAddress">Address</label>
                    <textarea class="form-control" id="registerAddress" type="text" name="address" required></textarea>
                    <div class="invalid-feedback">Enter a valid Address.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerGender">Gender</label>
                    <select name="gender" id="registerGender" class="form-control" required>
                        <option value="F">Female</option>
                        <option value="M">Male</option>
                        <option value="O">Others</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="registerPassword">Password</label>
                    <div class="position-relative">
                        <input class="form-control" id="registerPassword" type="password" minlength="6" name="password"
                            required>
                        <i class="bi bi-eye position-absolute" id="togglePassword"
                            style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">I agree to the terms</label>
                    <div class="invalid-feedback">You must agree before continuing.</div>
                </div>
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-person-plus"
                        aria-hidden="true"></i> Create Account</button>
            </form>
            <div class="auth-footer">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>
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
            let password = $('#registerPassword');

            if (password.attr('type') === 'password') {
                password.attr('type', 'text');
                $(this).removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                password.attr('type', 'password');
                $(this).removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    </script>
</body>

</html>
