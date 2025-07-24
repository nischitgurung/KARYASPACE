<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Spaces - KaryaSpace</title>

    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .navbar-brand span {
            font-weight: bold;
        }
        .card {
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,.1);
            transform: translateY(-3px);
        }
        .card-body {
            display: flex;
            flex-direction: column;
        }
        .card-text {
            flex-grow: 1;
        }
        .mt-auto.d-flex.flex-wrap.gap-2 .btn {
            flex-grow: 1;
            border-radius: 0.375rem;
        }
        .footer {
            position: sticky;
            bottom: 0;
            width: 100%;
            z-index: 1030;
        }
        .toast-fixed {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
            background-color: #198754;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            pointer-events: none;
        }
        .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}"><i class="bi bi-person me-1"></i> Profile</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
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

<main class="container pt-5 pb-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
        <h2 class="mb-0 mb-md-0">Your Spaces</h2>
        <div class="d-flex align-items-center gap-2 flex-wrap mt-3 mt-md-0">
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($spaces->isEmpty())
        <div class="alert alert-info text-center py-5 rounded-3 shadow-sm">
            <h4 class="alert-heading">No spaces here yet! 🚀</h4>
            <p>Start by creating your first collaboration space or join an existing one.</p>
            <hr>
            <a href="{{ route('spaces.create') }}" class="btn btn-info px-4 py-2 text-white">
                <i class="bi bi-folder-plus me-1"></i> Create Your First Space
            </a>
        </div>
    @else
        <div class="row">
            @foreach($spaces as $space)
                @php $isOwner = $space->user_id === auth()->id(); @endphp
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $space->name }}</h5>
                            <p class="card-text flex-grow-1 text-muted">{{ $space->description ?? 'No description provided for this space.' }}</p>
                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <a href="{{ route('spaces.show', $space->id) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> View Projects
                                </a>
                                @if($isOwner)
                                    <a href="{{ route('spaces.edit', $space->id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSpaceModal" data-space-id="{{ $space->id }}" data-space-name="{{ $space->name }}">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="bi bi-lock me-1"></i> Can't Edit
                                    </button>
                                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#leaveSpaceModal" data-space-id="{{ $space->id }}" data-space-name="{{ $space->name }}">
                                        <i class="bi bi-box-arrow-left me-1"></i> Leave
                                    </button>
                                @endif
                                <button class="btn btn-success" onclick="openInviteModal('{{ $space->id }}')">
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
    <p class="mb-0">© {{ date('Y') }} KaryaSpace. All rights reserved.</p>
</footer>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i> Share Invite Link
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Invite Link</label>
                    <input type="text" class="form-control" id="inviteLinkInput" readonly>
                </div>
                <button class="btn btn-outline-secondary w-100 mb-3" onclick="copyInviteLink()">
                    <i class="bi bi-clipboard me-1"></i> Copy Link
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteSpaceModal" tabindex="-1" aria-labelledby="deleteSpaceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSpaceModalLabel">Confirm Space Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the space "<strong id="modalSpaceNameDelete"></strong>"? This action cannot be undone and will delete all associated projects and tasks.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSpaceForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Space</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Leave Modal -->
<div class="modal fade" id="leaveSpaceModal" tabindex="-1" aria-labelledby="leaveSpaceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveSpaceModalLabel">Confirm Leave Space</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to leave the space "<strong id="modalSpaceNameLeave"></strong>"? You will lose access to its projects and tasks.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="leaveSpaceForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-warning">Leave Space</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentSpaceId = null;

    function openInviteModal(spaceId) {
        currentSpaceId = spaceId;
        generateInviteLink(spaceId);
        const modal = new bootstrap.Modal(document.getElementById('inviteModal'));
        modal.show();
    }

    function generateInviteLink(spaceId) {
        fetch(`/spaces/${spaceId}/invite-link`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Invite link fetch failed');
            return response.json();
        })
        .then(data => {
            const link = data.invite_link;
            document.getElementById('inviteLinkInput').value = link;

            const encodedLink = encodeURIComponent(link);
            document.getElementById('whatsappShare').href = `https://wa.me/?text=Join my KaryaSpace: ${encodedLink}`;
            document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}`;
            document.getElementById('emailShare').href = `mailto:?subject=Join My KaryaSpace&body=Click to join: ${encodedLink}`;
        })
        .catch(err => console.error(err));
    }

    function copyInviteLink() {
        const input = document.getElementById('inviteLinkInput');
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');

        const toastEl = document.createElement('div');
        toastEl.classList.add('toast-fixed');
        toastEl.innerHTML = `Link copied to clipboard!`;
        document.body.appendChild(toastEl);
        setTimeout(() => toastEl.remove(), 3000);
    }
</script>

</body>
</html>
