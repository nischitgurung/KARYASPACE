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
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            <i class="bi bi-kanban-fill text-primary me-2 fs-3"></i>
            <span>KaryaSpace</span>
        </a>
        <!-- Hamburger button: only visible on small screens -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserLinks" aria-controls="navbarUserLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Desktop: No profile/logout links visible, only hamburger toggler triggers collapse -->
        <div class="collapse navbar-collapse d-lg-none" id="navbarUserLinks">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('space') }}">
                    <i class="bi bi-rocket-takeoff-fill"></i> Space
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
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-kanban-fill text-primary fs-2 me-2"></i>
        <h2 class="mb-0 fw-bold">Dashboard</h2>
    </div>

    <!-- Overview Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #f3f8fd 0%, #e0ecfa 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center shadow" style="width:60px; height:60px;">
                        <i class="bi bi-folder2-open text-primary fs-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small" style="font-family: 'Dancing Script', cursive;">Total Projects</p>
                        <h3 class="text-primary mb-0 fw-bold" style="font-family: 'Dancing Script', cursive;">{{ $totalProjects }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #fffef7 0%, #fffbe0 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-25 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center shadow" style="width:60px; height:60px;">
                        <i class="bi bi-list-check text-warning fs-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small" style="font-family: 'Dancing Script', cursive;">Active Tasks</p>
                        <h3 class="text-warning mb-0 fw-bold" style="font-family: 'Dancing Script', cursive;">{{ $totalTasks }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #f3fcf4 0%, #e0f7e9 100%);">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-25 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center shadow" style="width:60px; height:60px;">
                        <i class="bi bi-trophy-fill text-success fs-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small" style="font-family: 'Dancing Script', cursive;">Completed Tasks</p>
                        <h3 class="text-success mb-0 fw-bold" style="font-family: 'Dancing Script', cursive;">{{ $completedTasks }}</h3>
                    </div>
                </div>
                </div>
                </div>

                <!-- Recent Tasks -->
                <div class="card shadow border-0 rounded-4 mt-5" style="background:rgba(255,255,255,0.85);backdrop-filter:blur(8px);">
                    <div class="card-header bg-transparent d-flex align-items-center rounded-top-4 border-0" style="padding: 1.25rem 1.5rem;">
                        <i class="bi bi-clock-history text-dark me-2"></i>
                        <h5 class="mb-0 fw-semibold" style="font-family: 'Times New Roman', Times, serif; letter-spacing: -0.5px;">Recent Tasks</h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($recentTasks->isEmpty())
                        <div class="p-5 text-center text-muted" style="font-family: 'Times New Roman', Times, serif;">
                            <i class="bi bi-emoji-frown fs-1"></i>
                            <div class="mt-2">No recent tasks to display.</div>
                        </div>
                        @else
                        <ul class="list-group list-group-flush">
                            @foreach ($recentTasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom" style="background:transparent;font-family: 'Times New Roman', Times, serif;">
                                <div>
                                    <strong class="fs-6" style="color:#1d1d1f;">{{ $task->title }}</strong><br>
                                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ $task->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 fs-6
                                    @if ($task->status == 'pending')" style="background:#f5f5f7;color:#86868b;"
                                    @elseif ($task->status == 'in progress')" style="background:#e5f1fb;color:#0071e3;"
                                    @else" style="background:#e6f5ea;color:#299a47;"
                                    @endif
                                >
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
