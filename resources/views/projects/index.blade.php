<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $space->name }} - Projects - KaryaSpace</title>

  <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
      padding-top: 2rem;
      padding-bottom: 80px;
    }
    .project-card .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
    }
    .project-card .card-text {
      flex-grow: 1;
      margin-bottom: 1rem;
    }
    .project-card .card-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: auto;
      justify-content: flex-start;
    }

    /* Equal width and alignment for all buttons, links, and forms inside card-actions */
    .project-card .card-actions > * {
      flex: 1 1 calc(33.33% - 0.5rem); /* Adjusted for 3 buttons now */
      min-width: 130px;
      display: flex;
    }

    /* Buttons and links inside direct children flex to fill container */
    .project-card .card-actions > * > button,
    .project-card .card-actions > a {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.3rem;
      height: 38px; /* consistent button height */
    }

    /* Delete button form styling */
    .project-card .card-actions form {
      margin: 0;
      display: flex;
    }

    .project-card .card-actions form button {
      width: 100%;
      height: 38px;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
      .project-card .card-actions > * {
        flex: 1 1 calc(50% - 0.5rem);
        min-width: auto;
      }
    }
    @media (max-width: 575.98px) {
      .header-actions {
        flex-direction: column;
        gap: 0.5rem;
      }
      .header-actions .btn {
        width: 100%;
      }
      .project-card .card-actions > * {
        flex: 1 1 100%;
        min-width: auto;
      }
    }

    .footer {
      position: sticky;
      bottom: 0;
      width: 100%;
      z-index: 1030;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
        <i class="bi bi-kanban-fill text-primary me-2 fs-3"></i>
        <span>KaryaSpace</span>
      </a>
      <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks" aria-controls="navbarUserLinks" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarUserLinks">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}"><i class="bi bi-person me-1"></i> Profile</a></li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button class="nav-link btn btn-link text-danger" type="submit" style="text-decoration:none;">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
      <h2 class="mb-3 mb-md-0">{{ $space->name }} - Projects</h2>
      <div class="d-flex align-items-center gap-2 flex-wrap header-actions">
        <a href="{{ route('spaces.projects.create', $space->id) }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
          <i class="bi bi-plus-circle"></i> Add New Project
        </a>
        <a href="{{ route('spaces.index') }}" class="btn btn-secondary d-flex align-items-center justify-content-center gap-2">
          <i class="bi bi-arrow-left"></i> Back to Spaces
        </a>
        <a href="{{ route('spaces.members.index', $space->id) }}" class="btn btn-info d-flex align-items-center justify-content-center gap-2">
          <i class="bi bi-people-fill"></i> View Members
        </a>
      </div>
    </div>

    @if($space->projects->isEmpty())
      <div class="alert alert-info text-center py-4">
        <h4 class="alert-heading">No projects found!</h4>
        <p>It looks like there are no projects in this space yet. Start by adding a new project!</p>
        <hr>
        <a href="{{ route('spaces.projects.create', $space->id) }}" class="btn btn-info">
          <i class="bi bi-plus-circle me-1"></i> Create Your First Project
        </a>
      </div>
    @else
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($space->projects as $project)
          @php
            $totalWeightage = $project->tasks->sum('weightage') ?? 0;
          @endphp
          <div class="col">
            <div class="card mb-2 shadow-sm h-100 project-card">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold text-primary">{{ $project->name }}</h5>
                <p class="card-text text-muted small">{{ $project->description ?? 'No description provided for this project.' }}</p>

                <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                  <div class="d-flex flex-wrap gap-2 flex-grow-1">
                    @if($project->deadline)
                      <span class="badge bg-light text-dark border border-secondary py-2 px-3 d-inline-flex align-items-center">
                        <i class="bi bi-calendar-event me-1 text-primary"></i>
                        Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                      </span>
                    @endif

                    @php
                      $priorityColors = [
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'urgent' => 'danger'
                      ];
                      $priority = strtolower($project->priority);
                      $badgeColor = $priorityColors[$priority] ?? 'secondary';
                    @endphp
                    @if($project->priority)
                      <span class="badge bg-{{ $badgeColor }} py-2 px-3 d-inline-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ ucfirst($project->priority) }} Priority
                      </span>
                    @endif
                  </div>

                  <span class="badge bg-info text-white py-2 px-3 d-inline-flex align-items-center">
                    <i class="bi bi-bar-chart-fill me-1"></i> Total Task Weightage: {{ $totalWeightage }}%
                  </span>
                </div>

                <div class="card-actions">
                  <a href="{{ route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i> View Tasks
                  </a>

                  <a href="{{ route('spaces.projects.edit', [$space->id, $project->id]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i> Edit
                  </a>

                  <form action="{{ route('spaces.projects.destroy', [$space->id, $project->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                      <i class="bi bi-trash me-1"></i> Delete
                    </button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

  <footer class="bg-primary text-white text-center py-4 footer">
    <p class="mb-0">© 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
