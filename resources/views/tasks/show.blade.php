@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-3xl font-bold mb-4 {{ $task->completed ? 'line-through text-green-500' : 'text-gray-900' }}">
                {{ $task->title }}
            </h1>
            <p class="text-gray-500 text-sm mb-2">
                <strong>Category:</strong> {{ $task->category->name }}<br>
                <strong>Priority:</strong> {{ ucfirst($task->priority) }}<br>
                <strong>Created:</strong> {{ \Carbon\Carbon::parse($task->created_at)->format('d/m/Y H:i') }}
            </p>
            <p class="text-gray-800 mb-4">
                <strong>Description:</strong> {{ $task->description }}
            </p>

            <div class="flex justify-between">
                <a href="{{ route('tasks.edit', $task->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit Task</a>
                

                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        {{ $task->completed ? 'Mark Incomplete' : 'Mark Complete' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
