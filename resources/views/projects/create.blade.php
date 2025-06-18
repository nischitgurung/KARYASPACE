<!-- resources/views/projects/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 mb-6">
        Create Project in "{{ $space->name }}"
    </h2>

    <form method="POST" action="{{ route('spaces.projects.store', $space->id) }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Project Name <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter project name"
            >
            @error('name')
                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
                name="description"
                id="description"
                rows="4"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter project description (optional)"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600"
            >
                Create Project
            </button>

            <a href="{{ route('spaces.show', $space->id) }}" class="ml-4 text-gray-600 hover:text-gray-900">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
