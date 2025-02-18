@extends('layouts.app')

@section('content')
<div class="todo-container">
    <h1 class="todo-header">Create Task</h1>

    <form action="{{ route('tasks.store') }}" method="POST" class="todo-form">
        @csrf

        <!-- Title Input -->
        <div class="task-form-group">
            <label for="title" class="task-label">Title:</label>
            <input type="text" id="title" name="title" class="task-input" value="{{ old('title') }}" required>
        </div>

        <!-- Description Textarea -->
        <div class="task-form-group">
            <label for="description" class="task-label">Description:</label>
            <textarea id="description" name="description" rows="4" class="task-textarea" required>{{ old('description') }}</textarea>
        </div>

        <!-- Priority Dropdown -->
        <div class="task-form-group">
            <label for="priority" class="task-label">Priority:</label>
            <select id="priority" name="priority" class="task-select" required>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <!-- Category Dropdown -->
        <div class="task-form-group">
            <label for="category_id" class="task-label">Category:</label>
            <select id="category_id" name="category_id" class="task-select" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="task-submit-btn">Create Task</button>
        </div>
    </form>
</div>
@endsection
