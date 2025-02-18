<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Custom route for generating PDF for a task
Route::get('tasks/pdf', [TaskController::class, 'generatePDF'])->name('tasks.generatePDF');

// Default redirect to tasks index
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Category resource routes (handles CRUD operations for categories)
Route::resource('categories', CategoryController::class)->middleware('auth');

// Task resource routes (handles CRUD operations for tasks), only accessible by authenticated users
Route::resource('tasks', TaskController::class)->middleware('auth');

// Custom route for toggling task completion
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');


// Authentication Routes

// Register routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protect dashboard or home route for authenticated users
Route::get('/', function () {
    return view('home'); // You can add a home.blade.php if needed
})->middleware('auth')->name('home');

use App\Exports\TasksExport;
use Maatwebsite\Excel\Facades\Excel;

// Route to export tasks as Excel
Route::get('tasks/export', function () {
    return Excel::download(new TasksExport, 'tasks.xlsx');  // To export as Excel
})->name('tasks.export');

// Route to export tasks as CSV
Route::get('tasks/export/csv', function () {
    return Excel::download(new TasksExport, 'tasks.csv', \Maatwebsite\Excel\Excel::CSV);  // To export as CSV
})->name('tasks.export.csv');

Route::post('/tasks/bulk-delete', [TaskController::class, 'bulkDelete'])->name('tasks.bulkDelete');

