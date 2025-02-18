<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <nav style="padding: 0px; background: #f0f0f0; display: flex;  justify-content: space-between; margin-bottom: 20px;">
            <div>
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width: 90px; height: 90px; border-radius: 50%;margin-left:20px;">
            </div>
            
            <div style="display: flex; ">
            <div class="button-container">
                <a href="{{ route('tasks.export.csv') }}" class="clear-all-btn csv-btn">Export Tasks as CSV</a>
                <a href="{{ route('tasks.generatePDF') }}" class="clear-all-btn pdf-btn">Download All Tasks as PDF</a>
            
                <div class="button-nav" >
                <a href="{{ route('tasks.create') }}" >Todo</a>
                <a href="{{ route('tasks.index') }}">Tasks</a>
                <a href="{{ route('categories.index') }}" >Categories</a>
                <a href="{{ url('/') }}" >Home</a>

                @auth
                    <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center;">
                        @csrf
                        <button type="submit" style=" padding: 5px 10px; cursor: pointer;" class="logout-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="margin-left: 20px;">Login</a>
                @endauth
            </div></div></div>
                
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
