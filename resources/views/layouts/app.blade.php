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
        body {
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 16rem;
            /* Match sidebar width */
        }

        table {

            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-add {
            background-color: green;
        }

        .btn-edit {
            background-color: rgba(31, 41, 55, 0.84);
            margin-right: 7px;
        }

        .btn-delete {
            background-color: rgb(31, 41, 55);
        }

        .form-container {
            margin-top: 20px;
        }

        .form-container input {
            padding: 10px;
            width: calc(100% - 22px);
            margin-bottom: 10px;
        }

        .form-container button {
            padding: 10px 15px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <div class="flex">
            <!-- Sidebar -->
            <x-layout.sidebar />

            <!-- Main Content -->
            <div class="main-content flex-1">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>

</html>
