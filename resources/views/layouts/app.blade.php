<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Global text size adjustments using Tailwind classes */
        .text-large {
            font-size: 1.25rem !important;  /* 20px */
        }
        .text-small {
            font-size: 0.875rem !important;  /* 14px */
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 flex flex-col min-h-screen">

    <div class="flex-1">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-auto">
        <div class="container mx-auto text-center">
            <button id="increase-text" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Increase Text Size
            </button>
            <button id="decrease-text" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Decrease Text Size
            </button>
        </div>
    </footer>

    <script>
        // Retrieve text size preference from localStorage
        const textSize = localStorage.getItem('textSize') || 'normal';

        // Apply text size globally based on stored preference
        if (textSize === 'large') document.body.classList.add('text-large');
        if (textSize === 'small') document.body.classList.add('text-small');

        // Event listeners for adjusting text size
        document.getElementById('increase-text').addEventListener('click', () => {
            document.body.classList.remove('text-small');
            document.body.classList.add('text-large');
            localStorage.setItem('textSize', 'large');
        });

        document.getElementById('decrease-text').addEventListener('click', () => {
            document.body.classList.remove('text-large');
            document.body.classList.add('text-small');
            localStorage.setItem('textSize', 'small');
        });
    </script>
</body>
</html>
