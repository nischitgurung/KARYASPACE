@php
    use App\Models\Role;

    $user = auth()->user();

    // Get user's role in this project’s space
    $space = $project->space;
    $pivot = $space->users()->where('user_id', $user->id)->first()?->pivot;
    $rolesMap = Role::pluck('name', 'id')->toArray();
    $userRoleName = $pivot ? ($rolesMap[$pivot->role_id] ?? '') : '';
@endphp

<h1>Tasks for Project: {{ $project->name }}</h1>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

@foreach ($project->tasks as $task)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3>{{ $task->title }}</h3>
        <p>Description: {{ $task->description }}</p>
        <p>Deadline: {{ $task->deadline ?? 'N/A' }}</p>
        <p>Priority: {{ ucfirst($task->priority ?? 'N/A') }}</p>
        <p>Status: {{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>

        {{-- Edit/Delete buttons only for Admin or Project Manager --}}
        @if(in_array($userRoleName, ['Admin', 'Project Manager']))
            <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}">Edit</a> | 

            <form action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
            </form>
        @endif

        {{-- Status update form only for Members assigned to the task --}}
        @if($userRoleName === 'Member' && $task->users->contains($user->id))
            <form action="{{ route('tasks.updateStatus', [$project->id, $task->id]) }}" method="POST" style="margin-top:10px;">
                @csrf
                @method('PATCH')
                <label for="status_{{ $task->id }}">Update Status:</label>
                <select name="status" id="status_{{ $task->id }}">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit">Update Progress</button>
            </form>
        @endif
    </div>
@endforeach

{{-- Optionally add a link to create new task if Admin or Project Manager --}}
@if(in_array($userRoleName, ['Admin', 'Project Manager']))
    <a href="{{ route('projects.tasks.create', [$project->id]) }}">Create New Task</a>
@endif
