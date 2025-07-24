<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $space->name }} - KaryaSpace</title>

  <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    html, body {
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    main {
      flex-grow: 1;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
        <i class="bi bi-kanban-fill text-primary me-2 fs-3"></i>
        <span>KaryaSpace</span>
      </a>
      <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse d-lg-none" id="navbarUserLinks">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}"><i class="bi bi-person"></i> Profile</a></li>
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

  <!-- Main Content -->
  <main class="container pt-5" style="padding-bottom: 120px; max-width: 900px;">
    <div class="d-flex align-items-center mb-4">
      <h2 class="mb-0">{{ $space->name }} - Projects</h2>
      <div class="d-flex align-items-center gap-2 ms-auto">
        <a href="{{ route('spaces.projects.create', $space->id) }}" class="btn btn-success d-flex align-items-center gap-2">
          <i class="bi bi-plus-circle"></i> Add New Project
        </a>
        <a href="{{ route('spaces.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
          <i class="bi bi-arrow-left"></i> Back to Spaces
        </a>
      </div>
    </div>

    @if($space->projects->isEmpty())
      <div class="alert alert-info">No projects found in this space.</div>
    @else
      <div class="row">
        @foreach($space->projects as $project)
          <div class="col-md-6">
            <div class="card mb-4 shadow-sm h-100">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $project->name }}</h5>
                <p class="card-text flex-grow-1">{{ $project->description ?? 'No description provided.' }}</p>
                <div class="mb-2 small">
                  @if($project->deadline)
                    <span class="me-3 d-inline-block">
                      <i class="bi bi-calendar-event text-primary me-1"></i>
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
                    <span class="badge bg-{{ $badgeColor }}">
                      <i class="bi bi-exclamation-circle me-1"></i> {{ ucfirst($project->priority) }} Priority
                    </span>
                  @endif
                </div>

                <!-- Final Button Block -->
                <div class="d-grid gap-2 d-md-flex mt-auto">
                  <a href="{{ route('spaces.projects.tasks.index', [$space->id, $project->id]) }}"
                     class="btn btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bi bi-list-task"></i> View Tasks
                  </a>

                  <a href="{{ route('spaces.projects.edit', [$space->id, $project->id]) }}"
                     class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <i class="bi bi-pencil"></i> Edit
                  </a>

                  <form action="{{ route('spaces.projects.destroy', [$space->id, $project->id]) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this project?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-1">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </form>

                  <button type="button"
                          class="btn btn-success d-flex align-items-center gap-1"
                          onclick="openInviteModal('{{ $space->id }}', '{{ $project->id }}')">
                    <i class="bi bi-person-plus"></i> Invite
                  </button>
                </div>

              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-4">
    <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let currentSpaceId = null;
    let currentProjectId = null;

    function openInviteModal(spaceId, projectId) {
      currentSpaceId = spaceId;
      currentProjectId = projectId;
      generateInviteLink(spaceId, projectId, document.getElementById('roleSelect').value);
      const modal = new bootstrap.Modal(document.getElementById('inviteModal'));
      modal.show();
    }

    function copyInviteLink() {
      const input = document.getElementById('inviteLinkInput');
      input.select();
      input.setSelectionRange(0, 99999);
      document.execCommand('copy');
      alert('Invite link copied to clipboard!');
    }
  </script>
</body>
</html>
