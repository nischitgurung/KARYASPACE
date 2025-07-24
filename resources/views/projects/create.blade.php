<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create New Project - KaryaSpace</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
      <div class="collapse navbar-collapse d-lg-none" id="navbarUserLinks">
        <ul class="navbar-nav ms-auto">
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

  <div class="container py-5" style="max-width: 600px; padding-bottom: 120px;">
    <h2>Create a New Project in <span class="text-primary">{{ $space->name }}</span></h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Whoops!</strong> Please fix the following errors:<br><br>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('spaces.projects.store', $space) }}" id="createProjectForm" novalidate>
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Website Redesign" value="{{ old('name') }}" required>
        <div class="invalid-feedback">Please enter the project name.</div>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter project description (optional)">{{ old('description') }}</textarea>
      </div>

      <div class="mb-3">
        <label for="deadline" class="form-label">Deadline</label>
        <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline') }}" required min="{{ date('Y-m-d') }}">
        <div class="invalid-feedback">Please select a valid deadline (today or later).</div>
      </div>

      <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <select class="form-select" id="priority" name="priority" required>
          <option value="" disabled {{ old('priority') ? '' : 'selected' }}>Select Priority</option>
          <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
          <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
          <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
        </select>
        <div class="invalid-feedback">Please select a priority.</div>
      </div>

      <div class="mb-3">
        <label for="project_manager_id" class="form-label ">Project Manager (optional)</label>
        <select name="project_manager_id" id="project_manager_id" class="form-select">
          <option value="">-- None --</option>
          @foreach($spaceMembers as $member)
            <option value="{{ $member->id }}" {{ old('project_manager_id') == $member->id ? 'selected' : '' }}>
              {{ $member->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle-fill"></i> Create Project
        </button>
        <a href="{{ route('spaces.index') }}" class="btn btn-secondary mt-2 mt-sm-0">
          <i class="bi bi-arrow-left"></i> Back to Spaces
        </a>
      </div>
    </form>
  </div>

  <footer class="bg-primary text-white text-center py-4 mt-5">
    <p class="mb-0">&copy; 2025 KaryaSpace. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (() => {
      'use strict';
      const form = document.getElementById('createProjectForm');
      const deadlineInput = document.getElementById('deadline');
      const prioritySelect = document.getElementById('priority');

      function autoSetPriority() {
        const selectedDate = new Date(deadlineInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (!deadlineInput.value || prioritySelect.dataset.userChanged === 'true') return;

        const diffDays = Math.ceil((selectedDate - today) / (1000 * 60 * 60 * 24));
        if (diffDays < 7) prioritySelect.value = 'high';
        else if (diffDays < 15) prioritySelect.value = 'medium';
        else prioritySelect.value = 'low';
      }

      prioritySelect.addEventListener('change', () => {
        prioritySelect.dataset.userChanged = 'true';
      });

      deadlineInput.addEventListener('change', autoSetPriority);

      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
          return;
        }

        const selectedDate = new Date(deadlineInput.value);
        const today = new Date();
        today.setHours(0,0,0,0);

        if (selectedDate < today) {
          event.preventDefault();
          event.stopPropagation();
          alert('Deadline cannot be before today.');
          deadlineInput.focus();
          return;
        }

        form.classList.add('was-validated');
      });

      window.addEventListener('DOMContentLoaded', () => {
        if (deadlineInput.value && !prioritySelect.value) {
          autoSetPriority();
        }
      });
    })();
  </script>
</body>
</html>
