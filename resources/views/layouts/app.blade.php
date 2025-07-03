<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'dashboard') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    .input-group-append {
        display: inline;
    }


    #DataTables_Table_0_length>label:nth-child(1)>select:nth-child(1) {
        width: 52px;
    }

    #DataTables_Table_0_filter>label:nth-child(1)>input:nth-child(1) {
        margin-bottom: 10px;
    }

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


/* Your existing CSS remains the same */
    .searchable-select-container {
        position: relative;
        margin-bottom: 15px;
    }

    #user_id {
        display: none;
    }

    .primary-conversations {
        margin-top: 20px;
    }

    .primary-conversations h3 {
        font-weight: 500;
        font-size: 1.125rem;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 35px;
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .select-options {
        display: none;
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 5px;
    }

    .user-option {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .user-option:hover {
        background-color: #f8f9fa;
    }

    .user-avatar {
        margin-right: 12px;
    }

    .avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .user-name {
        font-weight: 500;
    }

    .user-email {
        font-size: 0.85em;
    }

    .conversation-item {
        transition: background-color 0.2s ease-in-out;
    }

    .conversation-item:hover {
        background-color: #f8f9fa;
    }

    .avatar {
        font-size: 1rem;
        font-weight: bold;
    }
    .loader {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
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
            <div class="flex-1 overflow-auto">
                <!-- Page Heading -->
                @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                @endif

                <!-- Page Content -->
                @if (session('success'))
                    <div class="max-w-2xl mx-auto mt-6">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="max-w-2xl mx-auto mt-6">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    <!-- Preloader -->
    <!-- Preloader -->
    <div id="preloader"
        style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background:white;display:flex;align-items:center;justify-content:center;flex-direction:column;">
        <!-- Optional: Your logo -->
        {{-- <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" /> --}}
        <!-- Animated SVG spinner -->
        <svg class="animate-spin" width="50" height="50" viewBox="0 0 50 50">
            <circle class="opacity-25" cx="25" cy="25" r="20" stroke="#3498db" stroke-width="5" fill="none" />
            <circle class="opacity-75" cx="25" cy="25" r="20" stroke="#3498db" stroke-width="5" fill="none"
                stroke-dasharray="31.4 31.4" stroke-linecap="round" />
        </svg>
        <span style="margin-top:16px;color:#3498db;font-weight:500;">Loading, please wait...</span>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('preloader').style.display = 'none';
    });
    </script>
</body>
<!-- After your table -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('table').DataTable({
        responsive: true, // Optional: for mobile-friendly tables
        columnDefs: [{
                orderable: false,
                targets: [3]
            } // Disable sorting for Actions column
        ]
    });
});
</script>

</html>
