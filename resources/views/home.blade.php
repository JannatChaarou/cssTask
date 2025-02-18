@extends('layouts.app')

@section('content')
<div class="home-container" >
    <h1 class="home-h">Welcome Back to NewTask</h1>
    <p class="home-p">Manage your tasks efficiently and stay organized.</p>
    <a href="{{ route('tasks.create') }}" class="home-btn">Go to Add Your Tasks</a>
</div>
@endsection
