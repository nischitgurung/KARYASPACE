<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KaryaSpace Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">KaryaSpace</a>
        <!-- Hamburger button: only visible on small screens -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks" aria-controls="navbarUserLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Desktop: No profile/logout links visible, only hamburger toggler triggers collapse -->
        <div class="collapse navbar-collapse d-lg-none" id="navbarUserLinks">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('space') }}">
                    <i class="bi bi-folder-plus"></i> Space
                </a>
            </li>
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

<!-- Dashboard -->
<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-kanban-fill text-primary me-2"></i>KaryaSpace Dashboard</h2>

    <!-- Overview Cards -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary-subtle p-3 rounded me-3">
                        <i class="bi bi-kanban-fill text-primary fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Total Projects</p>
                        <h3 class="text-primary">{{ $totalProjects }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success-subtle p-3 rounded me-3">
                        <i class="bi bi-list-task text-success fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Total Tasks</p>
                        <h3 class="text-success">{{ $totalTasks }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info-subtle p-3 rounded me-3">
                        <i class="bi bi-check2-circle text-info fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Completed Tasks</p>
                        <h3 class="text-info">{{ $completedTasks }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">📝 Recent Tasks</h5>
        </div>
        <div class="card-body">
            @if ($recentTasks->isEmpty())
                <p class="text-muted">No recent tasks to display.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($recentTasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $task->title }}</strong><br>
                                <small class="text-muted">{{ $task->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge
                                @if ($task->status == 'pending') bg-warning text-dark
                                @elseif ($task->status == 'in progress') bg-primary
                                @else bg-success
                                @endif">
                                {{ ucfirst($task->status) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
