<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            <i class="bi bi-kanban-fill text-primary me-1"></i> {{ __('KaryaSpace Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 px-6">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="bi bi-kanban-fill text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500">Total Projects</p>
                            <h3 class="text-3xl font-bold text-blue-600">{{ $totalProjects }}</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition">
                    <div class="flex items-center space-x-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="bi bi-list-task text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500">Total Tasks</p>
                            <h3 class="text-3xl font-bold text-green-600">{{ $totalTasks }}</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition">
                    <div class="flex items-center space-x-4">
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <i class="bi bi-check2-circle text-indigo-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500">Completed Tasks</p>
                            <h3 class="text-3xl font-bold text-indigo-600">{{ $completedTasks }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h4 class="text-xl font-semibold text-gray-800 mb-4">📝 Recent Tasks</h4>

                @if ($recentTasks->isEmpty())
                    <p class="text-gray-500">No recent tasks to display.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($recentTasks as $task)
                            <li class="flex justify-between items-center py-3">
                                <div>
                                    <p class="text-md font-medium text-gray-700">{{ $task->title }}</p>
                                    <p class="text-sm text-gray-400">{{ $task->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if ($task->status == 'pending')
                                        bg-yellow-100 text-yellow-700
                                    @elseif ($task->status == 'in progress')
                                        bg-blue-100 text-blue-700
                                    @else
                                        bg-green-100 text-green-700
                                    @endif">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
