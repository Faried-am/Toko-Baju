<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-green-50 to-green-100 px-4 py-10">

            <h1 style="font-size: 28px; font-weight: 700; color: #15803d; margin-bottom: 16px;">Toko Baju</h1>

            <div class="w-full bg-white shadow-md rounded-2xl" style="max-width: 420px; padding: 32px;">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>