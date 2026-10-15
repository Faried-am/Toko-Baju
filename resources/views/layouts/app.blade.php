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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            @if (session('success'))
            @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonColor: '#16a34a',
            timer: 2500,
            timerProgressBar: true,
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            html: @json(implode('<br>', $errors->all())),
            confirmButtonColor: '#16a34a',
        });
    </script>
@endif
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonColor: '#16a34a',
            timer: 2500,
            timerProgressBar: true,
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            html: @json(implode('<br>', $errors->all())),
            confirmButtonColor: '#16a34a',
        });
    </script>
@endif
        </div>
    </body>
</html>