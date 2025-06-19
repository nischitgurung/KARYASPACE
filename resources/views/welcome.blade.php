<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>KaryaSpace</title>

  <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">

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

      <!-- Navbar toggler for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ url('about') }}"><i class="bi bi-info-circle-fill"></i> About</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('contact') }}"><i class="bi bi-envelope-fill"></i> Contact</a></li>

            {{-- Show Login link only if login route exists --}}
            @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i> Login
              </a>
            </li>
            @endif

            {{-- Show Register link only if register route exists --}}
            @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">
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
      <h1 class="mb-3">Welcome to KaryaSpace</h1>
      <p class="lead">Manage your tasks easily and collaborate with your team.</p>

      {{-- Show Get Started button only if register route exists --}}
      @if (Route::has('register'))
        <a href="{{ route('register') }}" class="btn btn-primary mt-3">
          <i class="bi bi-rocket-fill"></i> Get Started
        </a>
      @endif
    </div>
  </section>

  <!-- Features Section -->
  <section class="container py-5">
    <h2 class="text-center mb-4">Features</h2>
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <i class="bi bi-list-check fs-1 text-primary mb-3"></i>
        <h5>Task Assignment</h5>
        <p>Assign and organize tasks for your team efficiently.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="bi bi-bar-chart-line fs-1 text-primary mb-3"></i>
        <h5>Progress Tracking</h5>
        <p>Track task progress using visual boards and indicators.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
        <h5>Team Collaboration</h5>
        <p>Communicate and stay in sync with your entire team.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-4">
    <h3>Take control of your tasks today!</h3>

    {{-- Show Create Account button only if register route exists --}}
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
