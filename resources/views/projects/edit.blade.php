{{-- resources/views/projects/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Project - KaryaSpace</title>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks" aria-controls="navbarUserLinks" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarUserLinks">
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

<!-- Toast for success messages -->
@if(session('success'))
  <div id="successToast" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 1055;">
    <div class="toast show text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('success') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toastEl = document.querySelector('#successToast .toast');
      const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
      toast.show();
    });
  </script>
@endif

<!-- Main Page Content -->
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Project</h2>
    <a href="{{ route('spaces.projects.index', $space->id) }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Back
    </a>
  </div>

  <!-- Display Validation Errors -->
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Edit Project Form -->
  <form action="{{ route('spaces.projects.update', [$space->id, $project->id]) }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    <!-- Project Name -->
    <div class="mb-3">
      <label for="name" class="form-label">Project Name</label>
      <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $project->name) }}"
        required
      >
      <div class="invalid-feedback">
        Please enter the project name.
      </div>
    </div>

    <!-- Project Description -->
    <div class="mb-3">
      <label for="description" class="form-label">Description (optional)</label>
      <textarea
        name="description"
        id="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="4"
      >{{ old('description', $project->description) }}</textarea>
      <div class="invalid-feedback">
        Please enter a valid description.
      </div>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save"></i> Update Project
    </button>
  </form>
</div>

<!-- Footer -->
<footer class="bg-primary text-white text-center py-4 mt-5 position-fixed bottom-0 w-100">
  <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Client-side form validation -->
<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('form');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
</body>
</html>
