<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow-sm p-4 w-100" style="max-width: 420px;">
            <!-- Header -->
            <div class="text-center mb-3">
                <h4 class="text-primary">
                    <i class="bi bi-shield-lock-fill me-1"></i> Two-Factor Authentication
                </h4>
                <p class="small text-muted mb-0" id="authMessage">
                    Please enter the authentication code provided by your authenticator app.
                </p>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- 2FA Form -->
            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <!-- Authenticator Code Field -->
                <div class="mb-3" id="authCodeGroup">
                    <label for="code" class="form-label">Authentication Code</label>
                    <input id="code" class="form-control" type="text" inputmode="numeric" name="code" autocomplete="one-time-code" autofocus>
                </div>

                <!-- Recovery Code Field (Initially Hidden) -->
                <div class="mb-3 d-none" id="recoveryCodeGroup">
                    <label for="recovery_code" class="form-label">Recovery Code</label>
                    <input id="recovery_code" class="form-control" type="text" name="recovery_code" autocomplete="one-time-code">
                </div>

                <!-- Toggle Buttons -->
                <div class="mb-3 text-end">
                    <button type="button" id="toggleToRecovery" class="btn btn-link btn-sm p-0">
                        Use a recovery code
                    </button>
                    <button type="button" id="toggleToAuth" class="btn btn-link btn-sm p-0 d-none">
                        Use an authentication code
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Script for toggling between Authenticator and Recovery -->
    <script>
        const toggleToRecovery = document.getElementById('toggleToRecovery');
        const toggleToAuth = document.getElementById('toggleToAuth');
        const authCodeGroup = document.getElementById('authCodeGroup');
        const recoveryCodeGroup = document.getElementById('recoveryCodeGroup');
        const authMessage = document.getElementById('authMessage');

        toggleToRecovery.addEventListener('click', () => {
            authCodeGroup.classList.add('d-none');
            recoveryCodeGroup.classList.remove('d-none');
            toggleToRecovery.classList.add('d-none');
            toggleToAuth.classList.remove('d-none');
            authMessage.innerText = 'Please enter one of your recovery codes.';
        });

        toggleToAuth.addEventListener('click', () => {
            authCodeGroup.classList.remove('d-none');
            recoveryCodeGroup.classList.add('d-none');
            toggleToRecovery.classList.remove('d-none');
            toggleToAuth.classList.add('d-none');
            authMessage.innerText = 'Please enter the authentication code provided by your authenticator app.';
        });
    </script>
</x-guest-layout>
