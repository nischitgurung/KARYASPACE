<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $space->name }} - Members - KaryaSpace</title>

  <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
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
    footer {
      margin-top: auto;
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
      <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks">
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
    <h2 class="mb-4">{{ $space->name }} - Members</h2>

    @if($members->isEmpty())
      <div class="alert alert-info">
        <i class="bi bi-person-x me-2"></i> This space has no members yet.
      </div>
    @else
      <div class="list-group">
        @php
          $currentUserId = auth()->id();
          $adminRoleId = \App\Models\Role::where('name', 'Admin')->value('id');
          $canManage = $space->created_by === $currentUserId || optional($space->users->firstWhere('id', $currentUserId))->pivot->role_id == $adminRoleId;
        @endphp

        @foreach($members as $user)
          <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom">
            <div>
              <div class="fw-semibold">{{ $user->name }}</div>
              <small class="text-muted">{{ $user->role_name ?? 'No Role' }}</small>
            </div>

            @if ($canManage && $user->id !== $currentUserId)
              <div class="dropdown">
                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-three-dots-vertical fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <form method="POST" action="{{ route('spaces.members.update', [$space->id, $user->id]) }}">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="role_id" value="{{ $adminRoleId }}">
                      <button type="submit" class="dropdown-item">
                        <i class="bi bi-shield-check text-success me-2"></i> Make Admin
                      </button>
                    </form>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('spaces.members.destroy', [$space->id, $user->id]) }}" onsubmit="return confirm('Kick out {{ $user->name }} from this space?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-person-x me-2"></i> Remove from Space
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

    <a href="{{ route('spaces.show', $space->id) }}" class="btn btn-outline-secondary mt-4">
      <i class="bi bi-arrow-left"></i> Back to Projects
    </a>
  </main>

  <footer class="bg-primary text-white text-center py-4">
    <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
