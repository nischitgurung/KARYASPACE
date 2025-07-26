<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create New Task - {{ $project->name }} - KaryaSpace</title>

  <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
    }
    main.container {
      max-width: 700px;
      padding-top: 2rem;
      padding-bottom: 3rem;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ route('dashboard') }}">
      <i class="bi bi-kanban-fill text-primary me-2"></i>KaryaSpace
    </a>
  </div>
</nav>

<main class="container">
  <h2>Create New Task for {{ $project->name }}</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form
    action="{{ route('spaces.projects.tasks.store', ['space' => $space->id, 'project' => $project->id]) }}"
    method="POST"
    id="createTaskForm"
    novalidate
  >
    @csrf

    {{-- Task Title --}}
    <div class="mb-3">
      <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
      <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title') }}"
        required
        placeholder="e.g., Design the new homepage mockup"
      />
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Description --}}
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea
        id="description"
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Add more details about the task..."
      >{{ old('description') }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row mb-3">
      {{-- Status --}}
      <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select
          id="status"
          name="status"
          class="form-select @error('status') is-invalid @enderror"
          required
        >
          <option value="to_do" {{ old('status', 'to_do') === 'to_do' ? 'selected' : '' }}>To Do</option>
          <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
          <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Done</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- Priority --}}
      <div class="col-md-6">
        <label for="priority" class="form-label">Priority</label>
        <select
          id="priority"
          name="priority"
          class="form-select @error('priority') is-invalid @enderror"
        >
          <option value="" {{ old('priority') ? '' : 'selected' }}>Select Priority</option>
          <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
          <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
          <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
        </select>
        @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    {{-- Due Date --}}
    <div class="mb-3">
      <label for="due_date" class="form-label">Due Date</label>
      <input
        type="date"
        id="due_date"
        name="due_date"
        class="form-control @error('due_date') is-invalid @enderror"
        value="{{ old('due_date') }}"
        min="{{ date('Y-m-d') }}"
      />
      @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Weightage --}}
    <div class="mb-3">
      <label for="weightage" class="form-label">Weightage <small>(1-100)</small> <span class="text-danger">*</span></label>
      <input
        type="range"
        id="weightage"
        name="weightage"
        min="1"
        max="100"
        step="1"
        class="form-range @error('weightage') is-invalid @enderror"
        value="{{ old('weightage', 1) }}"
        required
      />
      <div>Selected Weightage: <span id="weightageValue">{{ old('weightage', 1) }}</span>%</div>
      @error('weightage')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="{{ route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-secondary">
        Cancel
      </a>
      <button type="submit" class="btn btn-primary">Create Task</button>
    </div>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('createTaskForm');
  const prioritySelect = document.getElementById('priority');
  const dueDateInput = document.getElementById('due_date');
  const weightageInput = document.getElementById('weightage');
  const weightageValue = document.getElementById('weightageValue');
  const submitBtn = form.querySelector('button[type="submit"]');

  // Update displayed weightage value on slider input
  weightageInput.addEventListener('input', () => {
    weightageValue.textContent = weightageInput.value;
  });

  // Auto-set priority based on due date
  function updatePriorityBasedOnDueDate() {
    const dueDate = dueDateInput.value;
    if (!dueDate) return;

    const today = new Date();
    today.setHours(0,0,0,0);

    const due = new Date(dueDate);
    due.setHours(0,0,0,0);

    if (due < today) {
      alert("Due date cannot be before today.");
      dueDateInput.value = "";
      prioritySelect.value = "";
      return;
    }

    const diffDays = Math.ceil((due - today) / (1000 * 60 * 60 * 24));
    let priority = 'low';

    if (diffDays <= 7) priority = 'urgent';
    else if (diffDays <= 14) priority = 'high';
    else if (diffDays <= 30) priority = 'medium';

    // Only auto-set priority if user hasn't manually changed it
    if (!prioritySelect.dataset.userChanged) {
      prioritySelect.value = priority;
    }
  }

  // Handle priority select option "Select Priority" vanish and reappear
  let selectPriorityOption = prioritySelect.querySelector('option[value=""]');

  prioritySelect.addEventListener('mousedown', () => {
    if (selectPriorityOption) {
      prioritySelect.removeChild(selectPriorityOption);
      selectPriorityOption = null;
    }
  });

  prioritySelect.addEventListener('blur', () => {
    if (!prioritySelect.value) {
      if (!selectPriorityOption) {
        selectPriorityOption = document.createElement('option');
        selectPriorityOption.value = '';
        selectPriorityOption.textContent = 'Select Priority';
        prioritySelect.insertBefore(selectPriorityOption, prioritySelect.firstChild);
        prioritySelect.value = '';
      }
    }
  });

  prioritySelect.addEventListener('change', () => {
    prioritySelect.dataset.userChanged = 'true';
  });

  dueDateInput.addEventListener('change', () => {
    prioritySelect.dataset.userChanged = '';
    updatePriorityBasedOnDueDate();
  });

  // Initialize priority on page load
  updatePriorityBasedOnDueDate();

  // Form validation on submit
  form.addEventListener('submit', e => {
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
      form.classList.add('was-validated');
    }
  });

  // Disable form if total weightage used is >= 100%
  const totalWeightageUsed = {{ $totalWeightageUsed ?? 0 }};
  if (totalWeightageUsed >= 100) {
    alert('This project already has total weightage 100%. You cannot add more tasks.');
    weightageInput.disabled = true;
    submitBtn.disabled = true;
  }
});
</script>

</body>
</html>
