<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow-sm p-4 w-100" style="max-width: 420px;">
            <!-- Title -->
            <div class="text-center mb-3">
                <h4 class="text-primary">
                    <i class="bi bi-shield-lock me-1"></i> Reset Password
                </h4>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Hidden Token Input -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="you@example.com">
                </div>

                <!-- New Password -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                            <i class="bi bi-eye-slash" id="newPassIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3 position-relative">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="bi bi-eye-slash" id="confirmPassIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap CSS and Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Show/Hide Password Script -->
    <script>
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const newPasswordInput = document.getElementById('password');
        const newPassIcon = document.getElementById('newPassIcon');

        toggleNewPassword.addEventListener('click', () => {
            const isHidden = newPasswordInput.type === 'password';
            newPasswordInput.type = isHidden ? 'text' : 'password';
            newPassIcon.classList.toggle('bi-eye');
            newPassIcon.classList.toggle('bi-eye-slash');
        });

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const confirmPassIcon = document.getElementById('confirmPassIcon');

        toggleConfirmPassword.addEventListener('click', () => {
            const isHidden = confirmPasswordInput.type === 'password';
            confirmPasswordInput.type = isHidden ? 'text' : 'password';
            confirmPassIcon.classList.toggle('bi-eye');
            confirmPassIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</x-guest-layout>
