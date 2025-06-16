<x-guest-layout>
    <!-- Centered container for the login card -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <!-- Login card with shadow and padding -->
        <div class="card shadow p-4 mx-auto" style="width: 100%; max-width: 420px;">
            <!-- Login title with icon -->
            <div class="text-center mb-4">
                <h3 class="mt-3 text-primary"><i class="bi bi-box-arrow-in-right"></i> Login</h3>
            </div>

            <!-- Session Status: shows success messages (e.g., after password reset) -->
            @if (session('status'))
                <div class="alert alert-success mb-3 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors: displays form validation errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- Login form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf <!-- CSRF protection token -->

                <!-- Email Address input field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <!-- Password input field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
                </div>

                <!-- Remember Me checkbox -->
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">
                        Remember me
                    </label>
                </div>

                <!-- Forgot Password link and Login button -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    @if (Route::has('password.request'))
                        <!-- Link to password reset page -->
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            Forgot password?
                        </a>
                    @endif

                    <!-- Login submit button -->
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-box-arrow-in-right"></i> Log in
                    </button>
                </div>
            </form>

            <!-- Register link if registration is enabled -->
            @if (Route::has('register'))
                <div class="text-center mt-3">
                    <small>Don't have an account? <a href="{{ route('register') }}">Register</a></small>
                </div>
            @endif
        </div>
    </div>
    <div class="text-center mt-3">
        <small>
            <a href="{{ url('/') }}">Go Back</a>
        </small>
    </div>
    <div>
        <!-- Footer with copyright -->
        <footer class="text-center mt-4">
            <p class="text-muted">&copy; {{ date('Y') }} KaryaSpace. All rights reserved.</p>
        </footer>
    </div>


    <!-- Bootstrap CSS and Bootstrap Icons CDN links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-guest-layout>