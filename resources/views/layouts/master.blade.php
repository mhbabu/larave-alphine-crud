<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel-App') }} | @yield('title', 'Home')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.7.1/dist/cdn.min.js" defer></script>
    <!-- Bootstrap CSS -->

    @yield('header-css')
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">

    <div class="container mx-auto px-4">
        <div class="w-full">
            @yield('content')
        </div>

    </div>
    @yield('footer-script')
</html>
