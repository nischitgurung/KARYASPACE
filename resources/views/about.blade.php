<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>KaryaSpace</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-kanban-fill"></i> KaryaSpace
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
                    <!-- Features button removed -->
                    <li class="nav-item"><a class="nav-link active" href="{{ url('about') }}"><i class="bi bi-info-circle-fill"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('contact') }}"><i class="bi bi-envelope-fill"></i> Contact</a></li>
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="btn btn-light text-primary ms-2" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-outline-light ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill"></i> Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-light text-center py-5">
        <div class="container">
            <h1 class="mb-3">About KaryaSpace</h1>
            <p class="lead">KaryaSpace is a modern task management platform designed to help teams and individuals organize, track, and complete their work efficiently. Our mission is to simplify collaboration and boost productivity for everyone.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-rocket-fill"></i> Get Started
                </a>
            @endif
        </div>
    </section>

    <!-- About Info Section -->
    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=600&q=80" alt="Teamwork" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2>Why Choose KaryaSpace?</h2>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary"></i> Intuitive and user-friendly interface</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary"></i> Real-time collaboration and updates</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary"></i> Customizable boards and workflows</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary"></i> Secure and reliable platform</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary"></i> Dedicated support for your team</li>
                </ul>
                <p class="mt-3">Whether you're managing a small project or leading a large team, KaryaSpace adapts to your needs and helps you achieve your goals.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-4">
        <h3>Take control of your tasks today!</h3>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-light text-primary mt-3">
                <i class="bi bi-person-plus-fill"></i> Create an Account
            </a>
        @endif
        <p class="mt-4 mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
