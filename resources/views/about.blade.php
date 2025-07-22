<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About KaryaSpace</title>

    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons CSS -->
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

    <main>
        <!-- Hero Section -->
        <section class="bg-light text-center py-5">
            <div class="container">
                <h1 class="mb-3">About KaryaSpace</h1>
                <p class="lead">
                    KaryaSpace is a modern task management platform designed to help teams and individuals organize, track, and complete their work efficiently. Our mission is to simplify collaboration and boost productivity for everyone.
                </p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-rocket-fill" aria-hidden="true"></i> Get Started
                    </a>
                @endif
            </div>
        </section>

        <!-- About Info Section -->
        <section class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=600&q=80" alt="Team collaborating in office workspace" class="img-fluid rounded shadow" />
                </div>
                <div class="col-md-6">
                    <h2>Why Choose KaryaSpace?</h2>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary" aria-hidden="true"></i> Intuitive and user-friendly interface</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary" aria-hidden="true"></i> Real-time collaboration and updates</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary" aria-hidden="true"></i> Customizable boards and workflows</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary" aria-hidden="true"></i> Secure and reliable platform</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary" aria-hidden="true"></i> Dedicated support for your team</li>
                    </ul>
                    <p class="mt-3">
                        Whether you're managing a small project or leading a large team, KaryaSpace adapts to your needs and helps you achieve your goals.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-4">
        <h3>Take control of your tasks today!</h3>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-light text-primary mt-3">
                <i class="bi bi-person-plus-fill" aria-hidden="true"></i> Create an Account
            </a>
        @endif
        <p class="mt-4 mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
