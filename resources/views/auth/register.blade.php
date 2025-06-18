<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow-sm p-4 w-100" style="max-width: 420px;">
            <!-- Title -->
            <div class="text-center mb-4">
                <h3 class="text-primary"><i class="bi bi-person-plus me-1"></i> Register</h3>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
                </div>

                <!-- Password -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3 position-relative">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="bi bi-eye-slash" id="eyeConfirmIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms Agreement -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                        <label class="form-check-label" for="terms">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-decoration-underline">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-decoration-underline">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </label>
                    </div>
                @endif

                <!-- Submit & Login -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <a href="{{ route('login') }}" class="small text-decoration-none">
                        Already registered?
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-person-plus"></i> Register
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Show/Hide Password Script -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const eyeConfirmIcon = document.getElementById('eyeConfirmIcon');

        toggleConfirmPassword.addEventListener('click', () => {
            const isHidden = confirmPasswordInput.type === 'password';
            confirmPasswordInput.type = isHidden ? 'text' : 'password';
            eyeConfirmIcon.classList.toggle('bi-eye');
            eyeConfirmIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</x-guest-layout>
