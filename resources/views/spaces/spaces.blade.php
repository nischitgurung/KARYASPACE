@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 700px;">
    <h2 class="mb-4 text-center fw-bold">Create a New Space</h2>

    <!-- Create Space Form -->
    <form method="POST" action="{{ route('spaces.store') }}" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="spaceName" class="form-label fw-semibold">Space Name <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="spaceName" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="spaceDescription" class="form-label fw-semibold">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="spaceDescription" 
                      name="description" 
                      rows="3">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-semibold">Create Space</button>
    </form>

    @if(isset($space))
    <hr class="my-5">

    <h3 class="mb-4 text-center fw-bold">Projects in "{{ $space->name }}"</h3>

    <!-- Add Project Form -->
    <form method="POST" action="{{ route('spaces.projects.store', $space->id) }}" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" 
                   class="form-control @error('project_name') is-invalid @enderror" 
                   id="projectName" 
                   name="name" 
                   placeholder="New project name" 
                   required>
            <button type="submit" class="btn btn-success fw-semibold">Add Project</button>
        </div>
        @error('name')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </form>

    <!-- Project List -->
    @if($space->projects->count())
        <ul class="list-group shadow-sm">
            @foreach($space->projects as $project)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $project->name }}
                    {{-- You can add edit/delete buttons here if needed --}}
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-center text-muted fst-italic">No projects yet.</p>
    @endif
    @endif
</div>
@endsection
