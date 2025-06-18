<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="card shadow-sm p-4 w-100" style="max-width: 420px;">
            <!-- Logo area -->
            <div class="text-center mb-4">
                <!-- You can replace this with your logo img or SVG -->
                <x-authentication-card-logo />
            </div>

            <!-- Message -->
            <p class="mb-4 text-secondary small">
                Before continuing, could you verify your email address by clicking on the link we just emailed to you?  
                If you didn't receive the email, we will gladly send you another.
            </p>

            <!-- Success message -->
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success small mb-4" role="alert">
                    A new verification link has been sent to the email address you provided in your profile settings.
                </div>
            @endif

            <!-- Buttons: resend verification email & profile/logout -->
            <div class="d-flex justify-content-between align-items-center">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        Resend Verification Email
                    </button>
                </form>

                <div>
                    <a href="{{ route('profile.show') }}" class="text-decoration-underline small text-secondary me-3">
                        Edit Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 small text-secondary text-decoration-underline">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</x-guest-layout>
