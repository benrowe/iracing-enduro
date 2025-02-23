@extends('layouts.base')

@section('base')
    <body class="h-screen flex flex-col bg-gray-50 env-{{ app()->environment() }}">
    <!-- Top Navigation -->
    <nav class="fixed top-0 left-0 w-full bg-white shadow-md p-4 flex justify-between items-center">
        <!-- Left-aligned navigation items -->
        <div class="flex space-x-6">
            <a href="#" class="nav-link text-gray-700 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">Team Builder</a>
        </div>

        <!-- Right-aligned settings icon -->
        <button class="text-gray-700 hover:text-blue-600 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>
    </nav>

    <!-- Main Content -->
    <div class="flex-1 mt-16 p-4">
        <p class="text-gray-700">@yield('content')</p>
    </div>
    </body>
@endsection
