<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // Ensure only authenticated users can access categories
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display a listing of categories that belong to the authenticated user
    public function index()
    {   
        $userId = Auth::id(); // Get the authenticated user's ID

        // Retrieve only categories that have tasks belonging to the authenticated user
        $categories = Category::whereHas('tasks', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['tasks' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();

        return view('categories.index', compact('categories'));
    }

    // Show the form for creating a new category
    public function create()
    {
        return view('categories.create');
    }

    // Store a newly created category in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name',
        ]);

        // Associate the category with the authenticated user
        $category = new Category($validated);
        $category->user_id = Auth::id();
        $category->save();

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    // Show the form for editing the specified category (only if owned by the user)
    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            return redirect()->route('categories.index')->with('error', 'Unauthorized access.');
        }

        return view('categories.edit', compact('category'));
    }

    // Update the specified category in storage (only if owned by the user)
    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            return redirect()->route('categories.index')->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    // Remove the specified category from storage (only if owned by the user)
    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            return redirect()->route('categories.index')->with('error', 'Unauthorized access.');
        }

        try {
            $category->delete();
            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return redirect()->route('categories.index')
                ->with('error', 'Failed to delete category. It may be linked to other records.');
        }
    }
}
