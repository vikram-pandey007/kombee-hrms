<x-app-layout>
    <x-slot name="header">
        {{ __('Employee Management') }}
    </x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Employees</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manage your organization's employees and their information
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ \App\Models\Employee::count() }} Total Employees
                    </span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <livewire:employee-manager />
        </div>
    </div>
</x-app-layout>