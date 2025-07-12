<div>
    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" data-testid="success-message">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" data-testid="error-message">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if (session()->has('info'))
        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert" data-testid="info-message">
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Payroll Management</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Generate and manage employee payroll records
            </p>
        </div>
        <button 
            wire:click="generatePayroll" 
            wire:loading.attr="disabled"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            data-testid="payroll-button-generate"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span wire:loading.remove>Generate Payroll for {{ $currentMonth }}</span>
            <span wire:loading>
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generating...
            </span>
        </button>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden" data-testid="payroll-table-container">
        <livewire:payroll-table wire:key="payroll-table-{{ now() }}"/>
    </div>

    <!-- PowerGrid Initialization Script -->
    <script>
        document.addEventListener('livewire:init', function() {
            console.log('Livewire initialized for payroll manager');
        });
        
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded for payroll manager');
        });
    </script>

    <!-- Payslip Modal -->
    @if($showPayslipModal && $viewingPayslip)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40" wire:click="closePayslipModal"></div>
    
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button wire:click="closePayslipModal" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" data-testid="close-payslip-modal">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 mb-4">Payslip Details</h3>
                        
                        <div class="mt-2 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $viewingPayslip->employee->name }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $viewingPayslip->employee->employee_id }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $viewingPayslip->employee->department }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Month</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($viewingPayslip->month)->format('F Y') }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Base Salary</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">₹{{ number_format($viewingPayslip->base_salary, 2) }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deductions</p>
                                <p class="text-sm text-red-600 dark:text-red-400">-₹{{ number_format($viewingPayslip->deductions, 2) }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Net Pay</p>
                                <p class="text-sm font-bold text-green-600 dark:text-green-400">₹{{ number_format($viewingPayslip->net_pay, 2) }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($viewingPayslip->status === 'Paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                    {{ $viewingPayslip->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>