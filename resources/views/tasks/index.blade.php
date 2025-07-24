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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa; /* A slightly off-white background */
            font-family: 'Inter', sans-serif; /* Using Inter font */
        }
        main {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 80px; /* Adjust based on footer height */
        }
        .footer {
            position: sticky;
            bottom: 0;
            width: 100%;
            z-index: 1030;
        }
        /* Kanban Board Styles */
        .task-board {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Adjusted minmax for better fit */
            gap: 1.5rem;
        }
        .task-column {
            background-color: #e9ecef;
            border-radius: 0.75rem; /* Slightly more rounded corners */
            padding: 1.25rem; /* Slightly more padding */
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05); /* Subtle shadow for columns */
        }
        .task-column h4 {
            font-weight: bold;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem; /* Increased padding */
            border-bottom: 2px solid #ced4da;
            color: #343a40; /* Darker heading color */
        }
        .task-card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-left-width: 5px;
            margin-bottom: 1rem;
            border-radius: 0.5rem; /* Rounded corners for cards */
            transition: all 0.2s ease-in-out; /* Smooth transition for hover effects */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Light shadow for cards */
        }
        .task-card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1); /* More prominent shadow on hover */
            transform: translateY(-3px); /* Slight lift on hover */
        }
        .task-card-body {
            padding: 1rem;
        }
        .task-card .card-title {
            font-weight: 600;
            color: #212529; /* Darker title color */
        }
        .task-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .task-actions .btn {
            flex-grow: 1;
            border-radius: 0.375rem; /* Rounded buttons */
        }

        /* Priority-based border colors for task cards */
        .border-low { border-left-color: #198754; } /* Green */
        .border-medium { border-left-color: #ffc107; } /* Yellow */
        .border-high { border-left-color: #dc3545; } /* Red */
        .border-default { border-left-color: #6c757d; } /* Grey */

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .header-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            .header-actions .btn {
                width: 100%;
            }
            .task-board {
                grid-template-columns: 1fr; /* Single column on small screens */
            }
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
        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold">{{ $project->name }} - Tasks</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('spaces.index') }}">Spaces</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('spaces.projects.index', $space->id) }}">{{ $space->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tasks</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap header-actions">
                <a href="{{ route('spaces.projects.tasks.create', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
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
            <div class="alert alert-info text-center py-5 rounded-3 shadow-sm">
                <h4 class="alert-heading">No tasks here yet! 🚧</h4>
                <p>Get started by adding the first task to this project.</p>
                <hr>
                <a href="{{ route('spaces.projects.tasks.create', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-info px-4 py-2">
                    <i class="bi bi-plus-circle me-1"></i> Create Your First Task
                </a>
            </div>
        @else
            <div class="task-board">
                @php
                    // Define columns and their corresponding statuses, aligned with Task model
                    $columns = [
                        'To Do' => 'pending',
                        'In Progress' => 'in_progress',
                        'Completed' => 'completed'
                    ];
                @endphp

                @foreach ($columns as $columnTitle => $statusValue)
                    <div class="task-column">
                        <h4>{{ $columnTitle }}</h4>
                        @php
                            // Filter tasks for the current column's status
                            $tasksInColumn = $project->tasks->where('status', $statusValue);
                        @endphp

                        @forelse($tasksInColumn as $task)
                            @php
                                // Determine the border color based on task priority
                                $priorityClass = 'border-default';
                                if ($task->priority) {
                                    $priorityClass = 'border-' . strtolower($task->priority);
                                }
                            @endphp
                            <div class="card task-card shadow-sm {{ $priorityClass }}">
                                <div class="card-body task-card-body">
                                    {{-- Use $task->title as per Task model --}}
                                    <h5 class="card-title">{{ $task->title }}</h5>
                                    <p class="card-text text-muted small">{{ $task->description ?? 'No description.' }}</p>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        {{-- Priority Badge --}}
                                        @if($task->priority)
                                            @php
                                                $priorityColors = [
                                                    'low' => 'success',
                                                    'medium' => 'warning',
                                                    'high' => 'danger',
                                                ];
                                                $badgeColor = $priorityColors[strtolower($task->priority)] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}"><i class="bi bi-flag-fill me-1"></i> {{ ucfirst($task->priority) }}</span>
                                        @endif
                                        {{-- Due Date Badge --}}
                                        @if($task->due_date)
                                            <span class="badge text-bg-light border"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                        @endif
                                    </div>

                                    <div class="task-actions">
                                        <a href="{{ route('spaces.projects.tasks.edit', ['space' => $space->id, 'project' => $project->id, 'task' => $task->id]) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        {{-- Delete button now triggers a modal --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal" data-task-id="{{ $task->id }}" data-task-title="{{ $task->title }}">
                                            <i class="bi bi-trash me-1"></i> Delete
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
        // JavaScript to handle the delete confirmation modal
        document.addEventListener('DOMContentLoaded', function () {
            const deleteTaskModal = document.getElementById('deleteTaskModal');
            deleteTaskModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                // Extract info from data-bs-* attributes
                const taskId = button.getAttribute('data-task-id');
                const taskTitle = button.getAttribute('data-task-title');

                // Update the modal's content.
                const modalTaskTitle = deleteTaskModal.querySelector('#modalTaskTitle');
                const deleteTaskForm = deleteTaskModal.querySelector('#deleteTaskForm');

                modalTaskTitle.textContent = taskTitle;
                // Set the form action dynamically
                deleteTaskForm.action = `{{ route('spaces.projects.tasks.destroy', ['space' => $space->id, 'project' => $project->id, 'task' => '__TASK_ID__']) }}`.replace('__TASK_ID__', taskId);
            });
        });
    </script>
</body>
</html>
