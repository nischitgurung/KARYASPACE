<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 420px;">
            
            <div class="text-center mb-4">
                <x-authentication-card-logo />
                <h3 class="mt-3 text-primary"><i class="bi bi-box-arrow-in-right"></i> Login</h3>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">
                        Remember me
                    </label>
                </div>

                <!-- Forgot Password & Login Button -->
                <div class="d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            Forgot password?
                        </a>
                    @endif

                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-box-arrow-in-right"></i> Log in
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="text-center mt-3">
                    <small>Don't have an account? <a href="{{ route('register') }}">Register</a></small>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-guest-layout>
