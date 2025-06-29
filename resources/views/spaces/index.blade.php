<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Spaces - KaryaSpace</title>

  <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
        <i class="bi bi-kanban-fill text-primary me-2 fs-3"></i> <span>KaryaSpace</span>
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

  <div class="container pt-5" style="padding-bottom: 120px;">
    <div class="d-flex align-items-center mb-4">
      <h2 class="mb-0">Your Spaces</h2>
      <div class="d-flex align-items-center gap-2 ms-auto">
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-link-45deg"></i> Join Space
          </button>
          <div class="dropdown-menu p-3" style="width: 280px;">
            <form>
              <div class="mb-2">
                <label for="spaceInviteLink" class="form-label">Paste invite link</label>
                <input type="text" class="form-control" id="spaceInviteLink" placeholder="https://karyaspace.com/join/..." />
              </div>
              <button type="submit" class="btn btn-primary w-100">Join</button>
            </form>
          </div>
        </div>
        <a href="{{ route('spaces.create') }}" class="btn btn-success d-flex align-items-center gap-2">
          <i class="bi bi-folder-plus"></i> New Space
        </a>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($spaces->isEmpty())
    <div class="alert alert-info">You don't have any spaces yet. Start by creating one!</div>
    @else
    <div class="row">
      @foreach($spaces as $space)
      <div class="col-md-4">
        <div class="card mb-4 shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $space->name }}</h5>
            <p class="card-text flex-grow-1">{{ $space->description ?? 'No description' }}</p>
            <div class="mt-auto d-flex flex-wrap gap-2">
              <a href="{{ route('spaces.show', $space->id) }}" class="btn btn-outline-primary flex-grow-1">
                <i class="bi bi-eye"></i> View Projects
              </a>
              <a href="{{ route('spaces.edit', $space->id) }}" class="btn btn-outline-secondary flex-grow-1">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <form action="{{ route('spaces.destroy', $space->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to delete this space?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>

              <!-- Hidden input for dynamic invite link -->
              <input type="hidden" id="invite-link-{{ $space->id }}">

              <!-- Single Invite button that generates and copies -->
              <button type="button"
                      class="btn w-100"
                      style="background-color: rgb(74, 180, 50); color: white;"
                      onclick="generateAndCopyLink('{{ $space->id }}')">
                <i class="bi bi-person-plus"></i> Invite
              </button>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  <footer class="bg-primary text-white text-center py-4 mt-5 position-fixed bottom-0 w-100" style="z-index: 1030;">
    <p class="mb-0">© 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function generateAndCopyLink(spaceId) {
      const url = `/spaces/${spaceId}/invite-link`;

      fetch(url, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.text())
      .then(link => {
        const input = document.getElementById(`invite-link-${spaceId}`);
        input.value = link;
        return navigator.clipboard.writeText(link);
      })
      .catch(err => console.error('Failed to generate or copy link:', err));
    }
  </script>
</body>
</html>
