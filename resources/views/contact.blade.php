<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact | KaryaSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
                    
                    <li class="nav-item"><a class="nav-link" href="{{ url('about') }}"><i class="bi bi-info-circle-fill"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('contact') }}"><i class="bi bi-envelope-fill"></i> Contact</a></li>
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

    <!-- Contact Section -->
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h2 class="mb-4 text-center"><i class="bi bi-envelope-fill"></i> Contact Us</h2>
                <p class="text-center mb-4">Have questions or feedback? Fill out the form below and we'll get back to you soon.</p>
                <!-- Contact form removed due to missing route -->
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="bg-light py-4">
        <div class="container text-center">
            <h5>Or reach us at:</h5>
            <p class="mb-1"><i class="bi bi-envelope"></i> support@karyaspace.com</p>
            <p><i class="bi bi-telephone"></i> +977 9745280448, +977 9826104486</p>
            <div>
                <a href="#" class="text-primary me-2"><i class="bi bi-facebook fs-4"></i></a>
                <a href="#" class="text-primary me-2"><i class="bi bi-twitter fs-4"></i></a>
                <a href="#" class="text-primary"><i class="bi bi-instagram fs-4"></i></a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-4 mt-5">
        <h3>We're here to help you succeed!</h3>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-light text-primary mt-3">
                <i class="bi bi-person-plus-fill"></i> Create an Account
            </a>
        @endif
        <p class="mt-4 mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
