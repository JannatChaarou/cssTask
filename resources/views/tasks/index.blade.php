@extends('layouts.app')

@section('content')
    <head>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <div class="button-container">
            <button id="delete-selected-btn" class="clear-all-btn delete-btn" style="display: none;" onclick="deleteSelectedTasks()">Remove Selected Tasks</button>

        </div>
    <div class="todo-container">
        <h1 class="todo-header">Tasks</h1>
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Form -->
        <form action="{{ route('tasks.index') }}" method="GET" >
            <div class=" items-center space-x-4 todo-form" > <!-- ✅ Flexbox applied here -->
                
                <!-- Filter by Title (Search Input) -->
                <input type="text" id="title" name="title" class="todo-input" 
                    value="{{ request('title') }}" placeholder="Search by task name" 
                    style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <div class="category-select-container">
                <select id="priority" name="priority" class="category-select" 
                    style="padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

                <!-- Filter Button -->
                <button type="submit" class="todo-btn">Filter</button>
            </div>
        </form>

        <!-- Tasks Table -->
        <table class="todo-table">
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <!-- Checkbox for Selecting Task -->
                        <td>
                            <input type="checkbox" class="task-checkbox" value="{{ $task->id }}" onclick="toggleDeleteButton()">
                        </td>
                        <td>{{ $task->title }}</td>
                        <td>{{ ucfirst($task->priority) }}</td>
                        <td class="td-btns">
                            <button class="edit-btn"><a href="{{ route('tasks.show', $task->id) }}" class="a-btn">View</a></button>
                            <button class="edit-btn"><a href="{{ route('tasks.edit', $task->id) }}" class="a-btn">Edit</a></button>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="edit-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- JavaScript to Toggle Delete Button -->
    <script>
        function toggleDeleteButton() {
            let checkboxes = document.querySelectorAll('.task-checkbox');
            let deleteButton = document.getElementById('delete-selected-btn');
            let anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            deleteButton.style.display = anyChecked ? 'block' : 'none';
        }

        function deleteSelectedTasks() {
            let selectedTasks = Array.from(document.querySelectorAll('.task-checkbox:checked')).map(cb => cb.value);

            if (selectedTasks.length === 0) {
                alert("Please select at least one task to delete.");
                return;
            }

            if (!confirm("Are you sure you want to delete the selected tasks?")) {
                return;
            }

            // Send AJAX request to delete selected tasks
            fetch("{{ route('tasks.bulkDelete') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ task_ids: selectedTasks })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      location.reload(); // Reload page after deletion
                  } else {
                      alert("Something went wrong!");
                  }
              });
        }
    </script>
@endsection
