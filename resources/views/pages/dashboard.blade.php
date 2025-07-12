<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Employees Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-testid="total-employees-card">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total Employees
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100" data-testid="total-employees-count">
                                {{ \App\Models\Employee::count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Leave Requests Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-testid="pending-leaves-card">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Pending Leaves
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100" data-testid="pending-leaves-count">
                                {{ \App\Models\Leave::where('status', 'Pending')->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Leaves Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-testid="approved-leaves-card">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Approved Leaves
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100" data-testid="approved-leaves-count">
                                {{ \App\Models\Leave::where('status', 'Approved')->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payroll Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-testid="total-payroll-card">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total Payroll
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100" data-testid="total-payroll-amount">
                                ₹{{ number_format(\App\Models\Payroll::sum('net_pay'), 0) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Leave Requests -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg" data-testid="recent-leaves-section">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Leave Requests</h3>
            </div>
            <div class="p-6">
                @php
                    $recentLeaves = \App\Models\Leave::with('employee')->latest()->take(5)->get();
                @endphp
                
                @if($recentLeaves->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentLeaves as $leave)
                            <div class="flex items-center justify-between" data-testid="leave-request-{{ $leave->id }}">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                            {{ $leave->employee->name[0] ?? 'E' }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $leave->employee->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $leave->leave_type }} - {{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $leave->status_badge }}" data-testid="leave-status-{{ $leave->id }}">
                                    {{ $leave->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent leave requests</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg" data-testid="quick-actions-section">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('employees') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors" data-testid="quick-action-employees">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-900 dark:text-blue-100">Add Employee</span>
                    </a>
                    
                    <a href="{{ route('leaves') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors" data-testid="quick-action-leaves">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-900 dark:text-green-100">Manage Leaves</span>
                    </a>
                    
                    <a href="{{ route('payroll') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors" data-testid="quick-action-payroll">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span class="text-sm font-medium text-purple-900 dark:text-purple-100">Process Payroll</span>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" data-testid="quick-action-profile">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
