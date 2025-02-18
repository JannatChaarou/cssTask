<?php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // Constructor to apply auth middleware to relevant methods
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Retrieve the filters from the request
        $priority = $request->get('priority');
        $title = $request->get('title');

        // Build the base query to fetch tasks for the authenticated user
        $tasks = Task::where('user_id', Auth::id())->with('category');  // Only fetch tasks for the authenticated user

        // Filter by priority
        if ($priority) {
            $tasks = $tasks->where('priority', $priority);
        }

        // Filter by title (task name)
        if ($title) {
            $tasks = $tasks->where('title', 'like', '%' . $title . '%');
        }

        // Retrieve filtered tasks
        $tasks = $tasks->get();

        // Return the view with the filtered tasks
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all(); // Fetch all categories to show in the dropdown
        return view('tasks.create', compact('categories'));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category_id' => 'required|exists:categories,id', // Ensure category exists
        ]);
    
        // Create the task, associating it with the authenticated user and selected category
        $task = Task::create(array_merge($validated, ['user_id' => Auth::id()]));
    
        // Redirect to the categories.index page with a success message
        return redirect()->route('categories.index')->with('success', 'Task created successfully!');
    }
    


    /**
     * Display the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        // Ensure that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')->with('error', 'You do not have permission to view this task.');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        // Ensure that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')->with('error', 'You do not have permission to edit this task.');
        }

        $categories = Category::all(); // Fetch all categories to show in the dropdown
        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        // Ensure that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')->with('error', 'You do not have permission to update this task.');
        }

        // Validate the input fields
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category_id' => 'required|exists:categories,id', // Validate category_id exists in categories table
        ]);

        // Update the task with the validated data
        $task->update($validated);

        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        // Ensure that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')->with('error', 'You do not have permission to delete this task.');
        }

        // Delete the task
        $task->delete();

        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function toggle(Task $task)
{
    // Ensure that the task belongs to the authenticated user
    if ($task->user_id !== Auth::id()) {
        return redirect()->route('tasks.index')->with('error', 'You do not have permission to update this task.');
    }

    // Toggle the completed status of the task
    $task->completed = !$task->completed; // If completed is true, set it to false and vice versa
    $task->save();

    // Redirect back to the task's details page with a success message
    return redirect()->route('tasks.show', $task->id)->with('success', 'Task updated successfully!');
}

public function generatePDF()
{
    // Retrieve all tasks
    $tasks = Task::all();

    // Load the view and pass the tasks data
    $pdf = PDF::loadView('tasks.pdf', compact('tasks'));

    // Download the PDF file
    return $pdf->download('tasks_list.pdf');
}

public function bulkDelete(Request $request)
{
    $taskIds = $request->task_ids;

    if (!$taskIds || !is_array($taskIds)) {
        return response()->json(['success' => false, 'message' => 'Invalid request']);
    }

    // Ensure tasks belong to the authenticated user before deleting
    Task::whereIn('id', $taskIds)->where('user_id', Auth::id())->delete();

    return response()->json(['success' => true, 'message' => 'Tasks deleted successfully']);
}



}
