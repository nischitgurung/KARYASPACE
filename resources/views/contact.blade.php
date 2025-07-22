<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact | KaryaSpace</title>

    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

    <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Main navigation">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
        <i class="bi bi-kanban-fill fs-3" aria-hidden="true"></i>
        <span>KaryaSpace</span>
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu"
        aria-controls="navMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-1" href="{{ url('about') }}">
              <i class="bi bi-info-circle-fill" aria-hidden="true"></i> About
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-1" href="{{ url('contact') }}">
              <i class="bi bi-envelope-fill" aria-hidden="true"></i> Contact
            </a>
          </li>

          @if (Route::has('login'))
            @auth
              <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
              </li>
              <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="btn btn-link nav-link text-danger p-0" style="text-decoration:none;">
                    Logout
                  </button>
                </form>
              </li>
            @else
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-1" href="{{ route('login') }}">
                  <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Login
                </a>
              </li>
              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center gap-1" href="{{ route('register') }}">
                    <i class="bi bi-person-plus-fill" aria-hidden="true"></i> Register
                  </a>
                </li>
              @endif
            @endauth
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
