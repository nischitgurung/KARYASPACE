<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow-sm p-4 w-100" style="max-width: 420px;">
            <!-- Title -->
            <div class="text-center mb-3">
                <h4 class="text-primary">
                    <i class="bi bi-envelope-lock me-1"></i> Forgot Password
                </h4>
            </div>

            <!-- Info Message -->
            <div class="alert alert-info small mb-4 text-center">
                Forgot your password? No problem. Enter your email and we’ll send you a link to reset it.
            </div>

            <!-- Success Status Message -->
            @if (session('status'))
                <div class="alert alert-success mb-3 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Send Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-guest-layout>
