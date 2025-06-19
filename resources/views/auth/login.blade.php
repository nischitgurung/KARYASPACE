<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow-sm p-4 w-100" style="max-width: 420px;">
            <!-- Title -->
            <div class="text-center mb-4">
                <h3 class="text-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login         </h3>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="you@example.com">
                </div>

                <!-- Password with toggle visibility -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Remember me
                    </label>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Log in
                    </button>
                </div>
            </form>

            <!-- Register link -->
            @if (Route::has('register'))
                <div class="text-center mt-3">
                    <small>Don't have an account?
                        <a href="{{ route('register') }}">Register here</a>
                    </small>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-3">
        <a href="{{ url('/') }}" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Go Back</a>
    </div>
    <footer class="text-center mt-4 mb-2 text-muted">
        <small>&copy; {{ date('Y') }} KaryaSpace. All rights reserved.</small>
    </footer>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Password Toggle Script -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</x-guest-layout>
