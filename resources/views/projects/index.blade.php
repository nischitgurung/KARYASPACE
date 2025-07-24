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
    }
    .project-card .btn {
      flex: 1 1 auto;
      min-width: calc(33% - 0.5rem);
    }
    @media (max-width: 767.98px) {
      .header-actions {
        flex-direction: column;
        gap: 0.5rem;
      }
      .header-actions .btn {
        width: 100%;
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
          <div class="col">
            <div class="card mb-2 shadow-sm h-100 project-card">
              <div class="card-body">
                <h5 class="card-title fw-bold text-primary">{{ $project->name }}</h5>
                <p class="card-text text-muted small">{{ $project->description ?? 'No description provided for this project.' }}</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
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

                <div class="card-actions">
                  <a href="{{ route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i> View Tasks
                  </a>

                  <a href="{{ route('spaces.projects.edit', [$space->id, $project->id]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i> Edit
                  </a>

                  <form action="{{ route('spaces.projects.destroy', [$space->id, $project->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                      <i class="bi bi-trash me-1"></i> Delete
                    </button>
                  </form>

                  <button
                    type="button"
                    class="btn text-white"
                    style="background-color: #4ab432;"
                    onclick="openInviteModal('{{ $space->id }}', '{{ $project->id }}')"
                  >
                    <i class="bi bi-person-plus me-1"></i> Invite
                  </button>
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

  <!-- Invite Modal -->
  <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="inviteModalLabel"><i class="bi bi-person-plus me-2"></i> Share Invite Link</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="roleSelect" class="form-label fw-bold">Select Role for Invitation</label>
            <select id="roleSelect" class="form-select form-select-lg">
              <option value="Member" selected>Member</option>
              <option value="Project Manager">Project Manager</option>
              <option value="Admin">Admin</option>
            </select>
            <div class="form-text">Choose the role for the user you are inviting to this project.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Generated Invite Link</label>
            <div class="input-group">
              <input type="text" class="form-control" id="inviteLinkInput" readonly aria-label="Invite link">
              <button class="btn btn-outline-secondary" type="button" onclick="copyInviteLink()" title="Copy to clipboard">
                <i class="bi bi-clipboard"></i> Copy
              </button>
            </div>
          </div>
          <p class="text-center text-muted small">Share this link directly or via social media:</p>
          <div class="d-flex justify-content-center gap-3">
            <a id="whatsappShare" target="_blank" class="btn btn-success btn-lg rounded-circle" title="Share on WhatsApp">
              <i class="bi bi-whatsapp"></i>
            </a>
            <a id="facebookShare" target="_blank" class="btn btn-primary btn-lg rounded-circle" title="Share on Facebook">
              <i class="bi bi-facebook"></i>
            </a>
            <a id="emailShare" target="_blank" class="btn btn-danger btn-lg rounded-circle" title="Share via Email">
              <i class="bi bi-envelope-fill"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let currentSpaceId = null;
    let currentProjectId = null;

    function openInviteModal(spaceId, projectId) {
      currentSpaceId = spaceId;
      currentProjectId = projectId;
      document.getElementById('roleSelect').value = 'Member';
      generateInviteLink(spaceId, projectId, 'Member');
      const modal = new bootstrap.Modal(document.getElementById('inviteModal'));
      modal.show();
    }

    document.getElementById('roleSelect').addEventListener('change', function() {
      if (currentSpaceId && currentProjectId) {
        generateInviteLink(currentSpaceId, currentProjectId, this.value);
      }
    });

    function generateInviteLink(spaceId, projectId, role) {
      const encodedRole = encodeURIComponent(role);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

      fetch(`/spaces/${spaceId}/projects/${projectId}/invite-link?role=${encodedRole}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok ' + response.statusText);
        return response.json();
      })
      .then(data => {
        const link = data.invite_link;
        document.getElementById('inviteLinkInput').value = link;

        const encodedLink = encodeURIComponent(link);
        document.getElementById('whatsappShare').href = `https://wa.me/?text=You're invited to join a project on KaryaSpace! Click here: ${encodedLink}`;
        document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}`;
        document.getElementById('emailShare').href = `mailto:?subject=Join My KaryaSpace Project&body=You're invited to join a project on KaryaSpace! Click here: ${encodedLink}`;
      })
      .catch(err => {
        console.error('Failed to generate invite link:', err);
        alert('Error generating invite link. Please try again.');
      });
    }

    function copyInviteLink() {
      const input = document.getElementById('inviteLinkInput');
      input.select();
      input.setSelectionRange(0, 99999);
      try {
        document.execCommand('copy');
        alert('Invite link copied to clipboard!');
      } catch (err) {
        alert('Could not copy link. Please copy it manually.');
      }
    }
  </script>
</body>
</html>
