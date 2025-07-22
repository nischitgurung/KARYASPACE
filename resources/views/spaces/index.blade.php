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
            <form method="POST" action="{{ route('invite.handle') }}">
              @csrf
              <div class="mb-2">
                <label for="spaceInviteLink" class="form-label">Paste invite link</label>
                <input type="text" name="invite_link" class="form-control" id="spaceInviteLink" placeholder="https://karyaspace.com/join/..." required />
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

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('warning'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($spaces->isEmpty())
      <div class="alert alert-info">You don't have any spaces yet. Start by creating one!</div>
    @else
      <div class="row">
        @foreach($spaces as $space)
          @php $isOwner = $space->user_id === auth()->id(); @endphp
          <div class="col-md-4">
            <div class="card mb-4 shadow-sm h-100">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $space->name }}</h5>
                <p class="card-text flex-grow-1">{{ $space->description ?? 'No description' }}</p>
                <div class="mt-auto d-flex flex-wrap gap-2">
                  <a href="{{ route('spaces.show', $space->id) }}" class="btn btn-outline-primary flex-grow-1">
                    <i class="bi bi-eye"></i> View Projects
                  </a>

                  @if($isOwner)
                    <a href="{{ route('spaces.edit', $space->id) }}" class="btn btn-outline-secondary flex-grow-1">
                      <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('spaces.destroy', $space->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to delete this space?');">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash"></i> Delete
                      </button>
                    </form>
                  @else
                    <button class="btn btn-outline-secondary flex-grow-1" disabled>
                      <i class="bi bi-lock"></i> Can't Edit
                    </button>
                    <form action="{{ route('spaces.leave', $space->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to leave this space?');">
                      @csrf
                      <button type="submit" class="btn btn-outline-warning w-100">
                        <i class="bi bi-box-arrow-left"></i> Leave
                      </button>
                    </form>
                  @endif

                  <button type="button"
                    class="btn w-100"
                    style="background-color: rgb(74, 180, 50); color: white;"
                    onclick="openInviteModal('{{ $space->id }}')">
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

  <!-- Invite Modal -->
  <div class="modal fade" id="inviteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i> Share Invite Link</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="roleSelect" class="form-label">Select Role to Invite</label>
            <select id="roleSelect" class="form-select">
              <option value="Member" selected>Member</option>
              <option value="Project Manager">Project Manager</option>
              <option value="Admin">Admin</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Invite Link</label>
            <input type="text" class="form-control" id="inviteLinkInput" readonly>
          </div>
          <button class="btn btn-outline-secondary w-100 mb-3" onclick="copyInviteLink()">
            <i class="bi bi-clipboard"></i> Copy Link
          </button>
          <div class="d-flex justify-content-around">
            <a id="whatsappShare" target="_blank" class="btn btn-success" title="Share on WhatsApp">
              <i class="bi bi-whatsapp"></i>
            </a>
            <a id="facebookShare" target="_blank" class="btn btn-primary" title="Share on Facebook">
              <i class="bi bi-facebook"></i>
            </a>
            <a id="emailShare" target="_blank" class="btn btn-danger" title="Share via Email">
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

    function openInviteModal(spaceId) {
      currentSpaceId = spaceId;
      generateInviteLink(spaceId, document.getElementById('roleSelect').value);
      const modal = new bootstrap.Modal(document.getElementById('inviteModal'));
      modal.show();
    }

    document.getElementById('roleSelect').addEventListener('change', function() {
      if (currentSpaceId) {
        generateInviteLink(currentSpaceId, this.value);
      }
    });

    function generateInviteLink(spaceId, role) {
      const encodedRole = encodeURIComponent(role);
      fetch(`/spaces/${spaceId}/invite-link?role=${encodedRole}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.text())
      .then(link => {
        document.getElementById('inviteLinkInput').value = link;

        const encodedLink = encodeURIComponent(link);
        document.getElementById('whatsappShare').href = `https://wa.me/?text=${encodedLink}`;
        document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}`;
        document.getElementById('emailShare').href = `mailto:?subject=Join%20My%20KaryaSpace&body=${encodedLink}`;
      })
      .catch(err => console.error('Failed to generate invite link:', err));
    }

    function copyInviteLink() {
      const input = document.getElementById('inviteLinkInput');
      input.select();
      document.execCommand('copy');
      alert('Invite link copied to clipboard!');
    }
  </script>
</body>
</html>
