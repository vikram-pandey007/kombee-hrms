<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HRMS' }} - HR Management System</title>
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- PowerGrid Styles -->
    <style>
        /* PowerGrid Action Button Styles */
        .powergrid-actions {
            display: flex !important;
            gap: 0.5rem !important;
            flex-wrap: wrap !important;
            min-width: 200px !important;
        }
        
        .powergrid-actions button {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.375rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s !important;
            cursor: pointer !important;
            border: none !important;
            outline: none !important;
            min-width: 80px !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        .powergrid-actions button:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        
        .powergrid-actions button:focus {
            outline: 2px solid transparent !important;
            outline-offset: 2px !important;
            ring: 2px !important;
            ring-offset: 2px !important;
        }
        
        /* Ensure action column is visible */
        .powergrid-action-column {
            min-width: 200px !important;
            white-space: nowrap !important;
            display: table-cell !important;
        }
        
        /* Force PowerGrid table to show action buttons */
        .powergrid-table td:last-child {
            min-width: 200px !important;
            white-space: nowrap !important;
        }
        
        /* Fix for PowerGrid action buttons not showing */
        [wire\\:key*="action"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Ensure PowerGrid loads properly */
        .powergrid-container {
            min-height: 200px !important;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>