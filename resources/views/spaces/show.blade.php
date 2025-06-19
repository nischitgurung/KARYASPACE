<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $space->name }} - KaryaSpace</title>

  <!-- Bootstrap CSS -->
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
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarUserLinks"
        aria-controls="navbarUserLinks"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarUserLinks">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('profile.show') }}">
              <i class="bi bi-person me-1"></i> Profile
            </a>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button
                class="nav-link btn btn-link text-danger m-0 p-0"
                type="submit"
                style="text-decoration: none;"
                aria-label="Logout"
              >
                <i class="bi bi-box-arrow-right me-1"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container py-5" style="max-width: 900px;">
    <h1 class="mb-2 fw-bold">{{ $space->name }}</h1>
    <p class="text-muted fs-5">{{ $space->description ?? 'No description provided.' }}</p>

    <hr class="my-4" />

    <h2 class="mb-3 fw-semibold">Projects</h2>

    @if ($space->projects->isEmpty())
      <div class="alert alert-info mt-3" role="alert">
        No projects found in this space.
      </div>
    @else
      <ul class="list-group mt-3 shadow-sm">
        @foreach($space->projects as $project)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $project->name }}</span>
            <div class="btn-group" role="group" aria-label="Project actions">
              <a
                href="{{ route('spaces.projects.edit', [$space->id, $project->id]) }}"
                class="btn btn-sm btn-outline-primary"
                title="Edit Project"
              >
                <i class="bi bi-pencil"></i> Edit
              </a>
              <form
                action="{{ route('spaces.projects.destroy', [$space->id, $project->id]) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this project?');"
              >
                @csrf
                @method('DELETE')
                <button
                  type="submit"
                  class="btn btn-sm btn-outline-danger"
                  title="Delete Project"
                >
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </div>
          </li>
        @endforeach
      </ul>
    @endif

    <div class="mt-4 d-flex flex-wrap gap-2">
      <a href="{{ route('spaces.projects.create', $space->id) }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i> Add New Project
      </a>
      <a href="{{ route('spaces.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Back to Spaces
      </a>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-3 mt-5 position-fixed bottom-0 w-100">
    <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <!-- Bootstrap JS Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
