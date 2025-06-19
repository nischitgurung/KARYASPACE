<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create New Project - KaryaSpace</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
        <i class="bi bi-kanban-fill text-primary me-2 fs-3"></i>
        <span>KaryaSpace</span>
      </a>
      <button
        class="navbar-toggler d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarUserLinks"
        aria-controls="navbarUserLinks"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

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
              <button
                class="nav-link btn btn-link text-danger"
                type="submit"
                style="text-decoration:none;"
              >
                <i class="bi bi-box-arrow-right"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container py-5" style="max-width: 600px;">
    <h2>Create a New Project in <span class="text-primary">{{ $space->name }}</span></h2>

    <!-- Validation Errors -->
    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Whoops!</strong> Please fix the following errors:<br><br>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

<form method="POST" action="{{ route('spaces.projects.store', $space) }}">
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">
          Project Name <span class="text-danger">*</span>
        </label>
        <input
          type="text"
          class="form-control"
          id="name"
          name="name"
          placeholder="e.g. Website Redesign"
          value="{{ old('name') }}"
          required
          aria-required="true"
        >
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea
          class="form-control"
          id="description"
          name="description"
          rows="4"
          placeholder="Enter project description (optional)"
        >{{ old('description') }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary" title="Create this project">
          <i class="bi bi-check-circle-fill"></i> Create Project
        </button>
        <a href="{{ route('spaces.index') }}" class="btn btn-secondary mt-2 mt-sm-0" title="Back to your spaces list">
          <i class="bi bi-arrow-left"></i> Back to Spaces
        </a>
      </div>
    </form>
  </div>

  <!-- Footer -->
  <footer
    class="bg-primary text-white text-center py-4 mt-5 position-fixed bottom-0 w-100"
    style="z-index: 1030;"
  >
    <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
