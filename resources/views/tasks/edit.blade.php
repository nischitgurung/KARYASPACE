<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Task: {{ old('title', $task->title ?? '') }} - KaryaSpace</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ route('dashboard') }}">
      <i class="bi bi-kanban-fill text-primary me-2"></i>KaryaSpace
    </a>
  </div>
</nav>

<main class="container py-4" style="max-width: 700px;">
  <h2>Edit Task</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @php
    // Set initial weightage value - prioritize old input, then task's weightage, fallback 1
    $weightageValue = old('weightage', $task->weightage ?? 1);
  @endphp

  <form action="{{ route('spaces.projects.tasks.update', ['space' => $space->id, 'project' => $project->id, 'task' => $task->id]) }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    {{-- Task Title --}}
    <div class="mb-3">
      <label for="title" class="form-label">Task Title</label>
      <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $task->title ?? '') }}"
        required
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
      >{{ old('description', $task->description ?? '') }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row mb-3">
      {{-- Status --}}
      <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select
          id="status"
          name="status"
          class="form-select @error('status') is-invalid @enderror"
          required
        >
          <option value="to_do" {{ old('status', $task->status) === 'to_do' ? 'selected' : '' }}>To Do</option>
          <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
          <option value="done" {{ old('status', $task->status) === 'done' ? 'selected' : '' }}>Done</option>
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
          required
        >
          <option value="" {{ old('priority', $task->priority) ? '' : 'selected' }}>Select Priority</option>
          <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
          <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
          <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
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
        value="{{ old('due_date', isset($task->due_date) && $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}"
        min="{{ date('Y-m-d') }}"
        required
      />
      @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Weightage --}}
    <div class="mb-3">
      <label for="weightage" class="form-label">Weightage <small>(1-100)</small></label>
      <input
        type="range"
        id="weightage"
        name="weightage"
        min="1"
        max="100"
        step="1"
        class="form-range @error('weightage') is-invalid @enderror"
        value="{{ $weightageValue }}"
        required
      />
      <div>Selected Weightage: <span id="weightageValue">{{ $weightageValue }}</span>%</div>
      @error('weightage')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="{{ route('spaces.projects.tasks.index', ['space' => $space->id, 'project' => $project->id]) }}" class="btn btn-secondary">
        Cancel
      </a>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const dueDateInput = document.getElementById('due_date');
    const prioritySelect = document.getElementById('priority');
    const weightageInput = document.getElementById('weightage');
    const weightageValue = document.getElementById('weightageValue');

    // Update displayed weightage value when slider moves
    weightageInput.addEventListener('input', () => {
      weightageValue.textContent = weightageInput.value;
    });

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

    prioritySelect.addEventListener('change', () => {
      prioritySelect.dataset.userChanged = 'true';
    });

    dueDateInput.addEventListener('change', () => {
      prioritySelect.dataset.userChanged = '';
      updatePriorityBasedOnDueDate();
    });

    // Initialize priority on page load
    updatePriorityBasedOnDueDate();
  });
</script>

</body>
</html>
