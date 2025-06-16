<x-guest-layout>
    <!-- Centered container for the register card -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <!-- Register card with shadow and padding -->
        <div class="card shadow p-4 mx-auto" style="width: 100%; max-width: 420px;">
            <!-- Register title with icon -->
            <div class="text-center mb-4">
                <h3 class="mt-3 text-primary"><i class="bi bi-person-plus"></i> Register</h3>
            </div>

            <!-- Validation Errors: displays form validation errors -->
            <x-validation-errors class="alert alert-danger mb-3" />

            <!-- Register form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name input field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>

                <!-- Email Address input field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                </div>

                <!-- Password input field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                </div>

                <!-- Confirm Password input field -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

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

                <!-- Register button -->
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

    <!-- Bootstrap CSS and Bootstrap Icons CDN links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-guest-layout>