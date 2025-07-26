<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $project->name }} - Tasks - KaryaSpace</title>

    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa; /* Lighter page background */
        }
        .header-actions {
            flex-shrink: 0;
        }
        /* Task Board Grid Layout */
        .task-board-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            align-items: start;
        }
        .task-column {
            background-color: #eff2f5; /* Column background */
            border-radius: 0.75rem; /* Softer corners */
            min-height: 200px;
        }
        .column-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .column-body {
            padding: 1rem;
            height: 100%;
        }
        /* Task Card Styling */
        .task-card {
            border: 1px solid #e9ecef;
            border-left-width: 5px; /* Priority indicator */
            transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out;
            margin-bottom: 1rem;
        }
        .task-card:hover {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
            transform: translateY(-3px);
        }
        /* Priority Border Colors */
        .task-card[data-priority="low"] { border-left-color: #198754; }
        .task-card[data-priority="medium"] { border-left-color: #ffc107; }
        .task-card[data-priority="high"] { border-left-color: #fd7e14; }
        .task-card[data-priority="urgent"] { border-left-color: #dc3545; }
        .task-card[data-priority=""] { border-left-color: #6c757d; }

        .task-card .card-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .task-card .card-text {
            color: #6c757d;
            font-size: 0.9em;
        }
        /* Subtle Task Actions */
        .task-actions {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }
        .task-card:hover .task-actions {
            opacity: 1;
        }
        .footer {
            margin-top: 3rem;
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

    <main class="container py-5">
        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-5">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold">{{ $project->name }} - Tasks</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('spaces.index') }}">Spaces</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('spaces.projects.index', $space->id) }}">{{ $space->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tasks</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap header-actions">
                <a href="{{ route('spaces.projects.tasks.create', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 shadow-sm">
                    <i class="bi bi-plus-circle"></i> Add New Task
                </a>
                <a href="{{ route('spaces.projects.index', $space->id) }}" class="btn btn-secondary d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-left"></i> Back to Projects
                </a>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Task Board --}}
        @if($project->tasks->isEmpty())
            <div class="text-center py-5 px-4 bg-white rounded-3 shadow-sm">
                <i class="bi bi-journal-check" style="font-size: 4rem; color: #6c757d;"></i>
                <h4 class="mt-3 fw-bold">No tasks here yet!</h4>
                <p class="text-muted">Get started by adding the first task to your project.</p>
                <a href="{{ route('spaces.projects.tasks.create', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-primary px-4 py-2 mt-2">
                    <i class="bi bi-plus-circle me-1"></i> Create Your First Task
                </a>
            </div>
        @else
            <div class="task-board-container">
                @php
                    $columns = [
                        'To Do' => 'to_do',
                        'In Progress' => 'in_progress',
                        'Done' => 'done'
                    ];
                @endphp

                @foreach ($columns as $columnTitle => $statusValue)
                    @php
                        $tasksInColumn = $project->tasks->where('status', $statusValue);
                    @endphp
                    <div class="task-column shadow-sm">
                        <div class="column-header d-flex justify-content-between align-items-center bg-white">
                            <h5 class="mb-0 fw-bold">{{ $columnTitle }}</h5>
                            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $tasksInColumn->count() }}</span>
                        </div>
                        <div class="column-body">
                            @forelse($tasksInColumn as $task)
                                <div class="card task-card" data-priority="{{ strtolower($task->priority ?? '') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title">{{ $task->title }}</h5>
                                            @if($task->priority)
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'success',
                                                        'medium' => 'warning',
                                                        'high' => 'danger',
                                                        'urgent' => 'danger'
                                                    ];
                                                    $badgeColor = $priorityColors[strtolower($task->priority)] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $badgeColor }} text-capitalize flex-shrink-0 ms-2">{{ $task->priority }}</span>
                                            @endif
                                        </div>

                                        @if($task->due_date)
                                            <div class="mb-3">
                                               <span class="badge text-bg-light border"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                        
                                        {{-- Added weightage badge --}}
                                        @if(is_numeric($task->weightage) && $task->weightage >= 0)
                                            <div class="mb-2">
                                                <span class="badge text-bg-info border">
                                                    <i class="bi bi-percent me-1"></i> Weightage: {{ $task->weightage }}%
                                                </span>
                                            </div>
                                        @endif

                                        <p class="card-text">{{ $task->description ?? 'No description provided.' }}</p>

                                        <div class="task-actions text-end mt-3">
                                            <a href="{{ route('spaces.projects.tasks.edit', ['space' => $space->id, 'project' => $project->id, 'task' => $task->id]) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal" data-task-id="{{ $task->id }}" data-task-title="{{ $task->title }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted p-3">
                                    <small>No tasks in this column.</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="bg-primary text-white text-center py-4 footer">
        <p class="mb-0">© {{ date('Y') }} KaryaSpace. All rights reserved.</p>
    </footer>

    {{-- Delete Task Confirmation Modal --}}
    <div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTaskModalLabel">Confirm Task Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the task "<strong id="modalTaskTitle"></strong>"? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteTaskForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteTaskModal = document.getElementById('deleteTaskModal');
            if (deleteTaskModal) {
                deleteTaskModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const taskId = button.getAttribute('data-task-id');
                    const taskTitle = button.getAttribute('data-task-title');

                    const modalTaskTitle = deleteTaskModal.querySelector('#modalTaskTitle');
                    const deleteTaskForm = deleteTaskModal.querySelector('#deleteTaskForm');

                    modalTaskTitle.textContent = taskTitle;
                    const actionUrl = `{{ url('spaces/'.$space->id.'/projects/'.$project->id.'/tasks') }}/${taskId}`;
                    deleteTaskForm.setAttribute('action', actionUrl);
                });
            }
        });
    </script>
</body>
</html>
