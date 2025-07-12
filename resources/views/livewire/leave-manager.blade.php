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
        <livewire:leave-table />
    </div>

    <!-- View Leave Modal -->
    @if($showViewModal && $selectedLeave)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="view-modal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Leave Details</h3>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Employee</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->employee->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Leave Type</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->leave_type }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duration</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->duration_in_days }} days</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reason</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->reason }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedLeave->status_badge }}">
                            {{ $selectedLeave->status }}
                        </span>
                    </div>
                    
                    @if($selectedLeave->approver_comment)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Approver Comment</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedLeave->approver_comment }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button wire:click="closeViewModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- PowerGrid Initialization Script -->
    <script>
        document.addEventListener('livewire:init', function() {
            console.log('Livewire initialized for leave manager');
        });
        
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded for leave manager');
            
            // Force PowerGrid to refresh after load
            setTimeout(function() {
                if (window.Livewire) {
                    window.Livewire.dispatch('pg:eventRefresh-leave-table');
                }
            }, 500);
        });

        // Additional script to ensure action buttons are visible
        document.addEventListener('DOMContentLoaded', function() {
            // Check for PowerGrid action buttons and ensure they're visible
            setTimeout(function() {
                const actionButtons = document.querySelectorAll('[wire\\:key*="action"], .powergrid-actions button');
                actionButtons.forEach(function(button) {
                    button.style.display = 'inline-flex';
                    button.style.visibility = 'visible';
                    button.style.opacity = '1';
                });
            }, 1000);
        });
    </script>
</div>