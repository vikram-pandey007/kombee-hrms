<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Payroll Management</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage employee payroll records and generate payslips.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <button 
                wire:click="create" 
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                data-testid="payroll-button-create"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span wire:loading.remove>Generate Bulk Payroll</span>
                <span wire:loading>
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating...
                </span>
            </button>
            <button 
                wire:click="showIndividualPayroll" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Generate Individual Payroll
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" x-on:click="show = false">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" x-on:click="show = false">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-6 flex flex-col lg:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" placeholder="Search payroll by employee name..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <select wire:model.live="monthFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">All Months</option>
                @foreach($months as $key => $month)
                    <option value="{{ $key }}">{{ $month }}</option>
                @endforeach
            </select>
            <select wire:model.live="yearFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <select wire:model.live="employeeFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">All Employees</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Payroll List -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Month/Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Base Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deductions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Net Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($payroll->employee->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payroll->employee->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $payroll->employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $months[$payroll->month] ?? $payroll->month }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">₹{{ number_format($payroll->base_salary, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-red-600 dark:text-red-400">-₹{{ number_format($payroll->deductions, 2) }}</div>
                                    @if($payroll->leave_deductions > 0)
                                        <div class="text-xs text-gray-500">Leave: ₹{{ number_format($payroll->leave_deductions, 2) }}</div>
                                    @endif
                                    @if($payroll->other_deductions > 0)
                                        <div class="text-xs text-gray-500">Other: ₹{{ number_format($payroll->other_deductions, 2) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">₹{{ number_format($payroll->net_pay, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payroll->status === 'Paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($payroll->status === 'Pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="view({{ $payroll->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="View Payslip">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="edit({{ $payroll->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                            title="Edit Payroll">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $payroll->id }})" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Delete Payroll"
                                            onclick="return confirm('Are you sure you want to delete this payroll record? This action cannot be undone.')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No payroll records found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new payroll record.</p>
                                <div class="mt-6">
                                    <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create Payroll
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payrolls->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>

    <!-- Event Listeners for State Management -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('payroll-created', () => {
                Livewire.dispatch('$refresh');
            });

            Livewire.on('payroll-updated', () => {
                Livewire.dispatch('$refresh');
            });

            Livewire.on('payroll-deleted', () => {
                Livewire.dispatch('$refresh');
            });
        });
    </script>

    <!-- View Payroll Modal -->
    @if($showViewModal && $selectedPayroll)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="view-modal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Payroll Details</h3>
                        <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Employee Information</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Name:</strong> {{ $selectedPayroll->employee->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>ID:</strong> {{ $selectedPayroll->employee->employee_id }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Department:</strong> {{ $selectedPayroll->employee->department->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Designation:</strong> {{ $selectedPayroll->employee->designation->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Payroll Information</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Month:</strong> {{ $months[$selectedPayroll->month] ?? $selectedPayroll->month }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Status:</strong> 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($selectedPayroll->status === 'Paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($selectedPayroll->status === 'Pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        {{ $selectedPayroll->status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4 border-t pt-4">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Salary Breakdown</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Base Salary</p>
                                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">₹{{ number_format($selectedPayroll->base_salary, 2) }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Deductions</p>
                                    <p class="text-lg font-semibold text-red-600 dark:text-red-400">-₹{{ number_format($selectedPayroll->deductions, 2) }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Net Pay</p>
                                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">₹{{ number_format($selectedPayroll->net_pay, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($selectedPayroll->leave_deductions > 0 || $selectedPayroll->other_deductions > 0)
                            <div class="mt-4 border-t pt-4">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Deduction Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($selectedPayroll->leave_deductions > 0)
                                        <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Leave Deductions</p>
                                            <p class="text-lg font-semibold text-red-600 dark:text-red-400">-₹{{ number_format($selectedPayroll->leave_deductions, 2) }}</p>
                                        </div>
                                    @endif
                                    @if($selectedPayroll->other_deductions > 0)
                                        <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Other Deductions</p>
                                            <p class="text-lg font-semibold text-red-600 dark:text-red-400">-₹{{ number_format($selectedPayroll->other_deductions, 2) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @if($selectedPayroll->deduction_notes || $selectedPayroll->remarks)
                            <div class="mt-4 border-t pt-4">
                                @if($selectedPayroll->deduction_notes)
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-1">Deduction Notes</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedPayroll->deduction_notes }}</p>
                                    </div>
                                @endif
                                @if($selectedPayroll->remarks)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-1">Remarks</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedPayroll->remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button wire:click="closeViewModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Create Payroll Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="create-modal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create New Payroll</h3>
                        <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit="save">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Employees</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                                @foreach($employees as $employee)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="selected_employees" value="{{ $employee->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $employee->name }} ({{ $employee->employee_id }})</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selected_employees') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="bulk_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                <input type="month" wire:model="bulk_month" id="bulk_month" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('bulk_month') border-red-500 @enderror">
                                @error('bulk_month') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="bulk_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select wire:model="bulk_status" id="bulk_status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('bulk_status') border-red-500 @enderror">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('bulk_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">How Bulk Payroll Works:</h4>
                            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li>• Base salary will be taken from each employee's fixed salary</li>
                                <li>• Leave deductions will be automatically calculated from approved leave records</li>
                                <li>• Net pay will be calculated as: Base Salary - Leave Deductions</li>
                                <li>• Only employees with fixed salary will be processed</li>
                                <li>• Employees with existing payroll for this month will be skipped</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <label for="bulk_remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                            <textarea wire:model="bulk_remarks" id="bulk_remarks" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('bulk_remarks') border-red-500 @enderror" placeholder="Optional remarks..."></textarea>
                            @error('bulk_remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" wire:click="closeCreateModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                <span wire:loading.remove>Create Payroll</span>
                                <span wire:loading>Creating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Payroll Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="edit-modal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Payroll</h3>
                        <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit="update">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
                                <select wire:model="employee_id" id="edit_employee_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('employee_id') border-red-500 @enderror">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                                    @endforeach
                                </select>
                                @error('employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                <input type="month" wire:model="month" id="edit_month" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('month') border-red-500 @enderror">
                                @error('month') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_base_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Base Salary</label>
                                <input type="number" step="0.01" wire:model="base_salary" id="edit_base_salary" wire:change="calculateNetPay" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('base_salary') border-red-500 @enderror">
                                @error('base_salary') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Deductions</label>
                                <input type="number" step="0.01" wire:model="deductions" id="edit_deductions" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 sm:text-sm @error('deductions') border-red-500 @enderror">
                                @error('deductions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_leave_deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leave Deductions</label>
                                <input type="number" step="0.01" wire:model="leave_deductions" id="edit_leave_deductions" wire:change="calculateNetPay" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('leave_deductions') border-red-500 @enderror">
                                @error('leave_deductions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_other_deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Deductions</label>
                                <input type="number" step="0.01" wire:model="other_deductions" id="edit_other_deductions" wire:change="calculateNetPay" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('other_deductions') border-red-500 @enderror">
                                @error('other_deductions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_net_pay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Net Pay</label>
                                <input type="number" step="0.01" wire:model="net_pay" id="edit_net_pay" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 sm:text-sm @error('net_pay') border-red-500 @enderror">
                                @error('net_pay') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="edit_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select wire:model="status" id="edit_status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="edit_deduction_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deduction Notes</label>
                            <textarea wire:model="deduction_notes" id="edit_deduction_notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('deduction_notes') border-red-500 @enderror" placeholder="Optional notes about deductions..."></textarea>
                            @error('deduction_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mt-4">
                            <label for="edit_remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                            <textarea wire:model="remarks" id="edit_remarks" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('remarks') border-red-500 @enderror" placeholder="Optional remarks..."></textarea>
                            @error('remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" wire:click="closeEditModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                <span wire:loading.remove>Update Payroll</span>
                                <span wire:loading>Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Individual Payroll Modal -->
    @if($showIndividualModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="individual-modal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Generate Individual Payroll</h3>
                        <button wire:click="closeIndividualModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit="saveIndividual">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="individual_employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
                                <select wire:model="individual_employee_id" id="individual_employee_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_employee_id') border-red-500 @enderror">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                                    @endforeach
                                </select>
                                @error('individual_employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="individual_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                <input type="month" wire:model="individual_month" id="individual_month" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_month') border-red-500 @enderror">
                                @error('individual_month') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="individual_base_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Base Salary</label>
                                <input type="number" step="0.01" wire:model="individual_base_salary" id="individual_base_salary" wire:change="calculateIndividualNetPay" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_base_salary') border-red-500 @enderror">
                                @error('individual_base_salary') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="individual_leave_deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leave Deductions</label>
                                <input type="number" step="0.01" wire:model="individual_leave_deductions" id="individual_leave_deductions" wire:change="calculateIndividualNetPay" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_leave_deductions') border-red-500 @enderror">
                                @error('individual_leave_deductions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="individual_other_deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Deductions</label>
                                <input type="number" step="0.01" wire:model="individual_other_deductions" id="individual_other_deductions" wire:change="calculateIndividualNetPay" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_other_deductions') border-red-500 @enderror">
                                @error('individual_other_deductions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="individual_net_pay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Net Pay</label>
                                <input type="number" step="0.01" wire:model="individual_net_pay" id="individual_net_pay" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 sm:text-sm @error('individual_net_pay') border-red-500 @enderror">
                                @error('individual_net_pay') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="individual_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select wire:model="individual_status" id="individual_status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_status') border-red-500 @enderror">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('individual_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="individual_deduction_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deduction Notes</label>
                            <textarea wire:model="individual_deduction_notes" id="individual_deduction_notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_deduction_notes') border-red-500 @enderror" placeholder="Optional notes about deductions..."></textarea>
                            @error('individual_deduction_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mt-4">
                            <label for="individual_remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                            <textarea wire:model="individual_remarks" id="individual_remarks" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('individual_remarks') border-red-500 @enderror" placeholder="Optional remarks..."></textarea>
                            @error('individual_remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" wire:click="closeIndividualModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                <span wire:loading.remove>Generate Payroll</span>
                                <span wire:loading>Generating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>