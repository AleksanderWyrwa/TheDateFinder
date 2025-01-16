<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>The Date Finder</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg text-center w-full sm:w-96">
        <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mb-6">The Date Finder</h1>

        <!-- Buttons for Login and Register -->
        <div class="space-y-4">
            <button onclick="window.location.href='/login'" class="w-full py-3 px-6 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:outline-none">
                Login
            </button>
            <button onclick="window.location.href='/register'" class="w-full py-3 px-6 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:outline-none">
                Register
            </button>
        </div>
    </div>

</body>
</html>
