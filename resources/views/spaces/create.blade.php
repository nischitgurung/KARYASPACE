<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create New Space - KaryaSpace</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            <i class="bi bi-kanban-fill text-primary me-2 fs-3"></i>
            <span>KaryaSpace</span>
        </a>
        <!-- Hamburger button: only visible on small screens -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks" aria-controls="navbarUserLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Desktop: No profile/logout links visible, only hamburger toggler triggers collapse -->
        <div class="collapse navbar-collapse d-lg-none" id="navbarUserLinks">
            <ul class="navbar-nav ms-auto">
      
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.show') }}">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-link btn btn-link text-danger" type="submit" style="text-decoration:none;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <h2 class="mb-4">Create a New Space</h2>

      <!-- Display validation errors -->
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('spaces.store') }}">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Space Name</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Marketing Team" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description (optional)</label>
          <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe what this space is for...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle-fill"></i> Create Space
        </button>
        <a href="{{ route('spaces.index') }}" class="btn btn-secondary ms-2">
          <i class="bi bi-arrow-left"></i> Back to Spaces
        </a>
      </form>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="bg-primary text-white text-center py-4 mt-5 position-fixed bottom-0 w-100">
  <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
