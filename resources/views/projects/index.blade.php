{{-- resources/views/projects/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Projects - KaryaSpace</title>

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

<!-- Page Content -->
<div class="container py-5">
  <div class="mb-4">
    <h2 class="fw-bold text-primary mb-1">
      Projects under the Space: <span class="text-dark">{{ $space->name }}</span>
    </h2>
    <p class="text-muted fst-italic">Manage and organize your projects within this space</p>
  </div>

  <!-- Back to Spaces -->
  <a href="{{ route('spaces.index') }}" class="btn btn-outline-secondary mb-4">
    <i class="bi bi-arrow-left"></i> Back to Spaces
  </a>

  <!-- Project List -->
  @if($space->projects->isEmpty())
    <div class="alert alert-info">No projects found in this space. Create one below!</div>
  @else
    @foreach($space->projects as $project)
      <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
        <div>
          <h5 class="mb-1">{{ $project->name }}</h5>
          <p class="mb-0 text-muted">{{ $project->description ?? 'No description provided.' }}</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('spaces.projects.edit', [$space->id, $project->id]) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
          </a>

          <form action="{{ route('spaces.projects.destroy', [$space->id, $project->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">
              <i class="bi bi-trash"></i> Delete
            </button>
          </form>
        </div>
      </div>
    @endforeach
  @endif

  <!-- Create New Project -->
  <div class="mt-4">
    <a href="{{ route('spaces.projects.create', $space->id) }}" class="btn btn-success">
      <i class="bi bi-plus-circle"></i> New Project
    </a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
