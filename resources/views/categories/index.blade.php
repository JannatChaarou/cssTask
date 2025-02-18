@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Here! Your tasks find their place</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-xl font-semibold text-center">{{ $category->name }}</h2>

                    <div class="mt-4">
                        @if($category->tasks->count() > 0)
                            <ul>
                                @foreach($category->tasks as $task)
                                    <li class="text-gray-700">- {{ $task->title }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">No tasks in this category.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

@endsection
