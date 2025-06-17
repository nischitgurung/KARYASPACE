@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create a New Space</h2>
    <!-- Route: POST /spaces (to create a new space) -->
    <form method="POST" action="{{ route('spaces.store') }}">
        @csrf
        <div class="mb-3">
            <label for="spaceName" class="form-label">Space Name</label>
            <input type="text" class="form-control" id="spaceName" name="name" required>
        </div>
        <div class="mb-3">
            <label for="spaceDescription" class="form-label">Description</label>
            <textarea class="form-control" id="spaceDescription" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Space</button>
    </form>

    @if(isset($space))
    <hr>
    <h3>Projects in "{{ $space->name }}"</h3>
    <!-- Route: POST /spaces/{space}/projects (to add a project to a space) -->
    <form method="POST" action="{{ route('spaces.projects.store', $space->id) }}">
        @csrf
        <div class="mb-3">
            <label for="projectName" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="projectName" name="name" required>
        </div>
        <button type="submit" class="btn btn-success">Add Project</button>
    </form>

    @if($space->projects->count())
        <ul class="list-group mt-3">
            @foreach($space->projects as $project)
                <li class="list-group-item">{{ $project->name }}</li>
            @endforeach
        </ul>
    @else
        <p class="mt-3">No projects yet.</p>
    @endif
    @endif
</div>
@endsection