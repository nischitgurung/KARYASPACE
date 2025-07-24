<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create New Task - KaryaSpace</title>

    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
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
        .form-card {
            max-width: 800px;
            margin: 0 auto;
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
        <div class="form-card mb-4">
            <h2 class="fw-bold">Create New Task for {{ $project->name }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('spaces.index') }}">Spaces</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('spaces.projects.index', $space->id) }}">{{ $space->name }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id]) }}">Tasks</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>

        {{-- Create Task Form --}}
        <div class="card shadow-sm form-card">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('spaces.projects.tasks.store', ['space' => $space->id, 'project' => $project->id]) }}" method="POST">
                    @csrf

                    {{-- Task Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Task Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Design the new homepage mockup">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Task Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Add more details about the task...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="to_do" {{ old('status', 'to_do') == 'to_do' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label fw-bold">Priority</label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Due Date --}}
                    <div class="mb-4">
                        <label for="due_date" class="form-label fw-bold">Due Date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-primary text-white text-center py-4 footer">
        <p class="mb-0">© {{ date('Y') }} KaryaSpace. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
