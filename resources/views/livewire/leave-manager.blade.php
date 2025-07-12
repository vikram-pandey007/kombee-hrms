<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" data-testid="success-message">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Leave Management</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Review and manage employee leave requests
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                {{ \App\Models\Leave::where('status', 'Pending')->count() }} Pending
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ \App\Models\Leave::where('status', 'Approved')->count() }} Approved
            </span>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden" data-testid="leave-table-container">
        <livewire:leave-table wire:key="leave-table-{{ now() }}"/>
    </div>

    <!-- PowerGrid Initialization Script -->
    <script>
        document.addEventListener('livewire:init', function() {
            console.log('Livewire initialized for leave manager');
        });
        
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded for leave manager');
        });
    </script>
</div>