{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>KaryaSpace</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    /* Smooth transitions for buttons and links */
    a.btn, .nav-link {
      transition: all 0.3s ease;
    }
    a.btn:hover, a.btn:focus {
      text-decoration: none;
      filter: brightness(110%);
      outline: none;
      box-shadow: 0 0 8px rgba(13,110,253,0.6);
    }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link:focus {
      color: #bbdefb !important;
    }

    /* Hero section spacing */
    .hero-section {
      min-height: 65vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Footer button */
    footer a.btn {
      font-weight: 600;
    }
  </style>
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

  <!-- Hero Section -->
  <section class="hero-section bg-light text-center py-5">
    <div class="container">
      <h1 class="display-4 fw-bold mb-3">Welcome to KaryaSpace</h1>
      <p class="lead mb-4">Manage your tasks easily and collaborate with your team.</p>

      @if (Route::has('register'))
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg shadow-sm">
          <i class="bi bi-rocket-fill me-2" aria-hidden="true"></i> Get Started
        </a>
      @endif
    </div>
  </section>

  <!-- Features Section -->
  <section class="container py-5">
    <h2 class="text-center mb-4 fw-semibold">Features</h2>
    <div class="row text-center g-4">
      <div class="col-md-4">
        <i class="bi bi-list-check fs-1 text-primary mb-3" aria-hidden="true"></i>
        <h5 class="fw-bold">Task Assignment</h5>
        <p>Assign and organize tasks for your team efficiently.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-bar-chart-line fs-1 text-primary mb-3" aria-hidden="true"></i>
        <h5 class="fw-bold">Progress Tracking</h5>
        <p>Track task progress using visual boards and indicators.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-people-fill fs-1 text-primary mb-3" aria-hidden="true"></i>
        <h5 class="fw-bold">Team Collaboration</h5>
        <p>Communicate and stay in sync with your entire team.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-4">
    <h3 class="mb-3">Take control of your tasks today!</h3>

    @if (Route::has('register'))
      <a href="{{ route('register') }}" class="btn btn-light text-primary fw-semibold shadow-sm">
        <i class="bi bi-person-plus-fill me-2" aria-hidden="true"></i> Create an Account
      </a>
    @endif

    <p class="mt-4 mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
