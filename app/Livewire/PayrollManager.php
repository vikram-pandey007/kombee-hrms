<?php

namespace App\Livewire;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollManager extends Component
{
    use WithPagination;

    // Modal states
    public $showViewModal = false;
    public $showCreateModal = false;
    public $showIndividualModal = false;
    public $showEditModal = false;

    // Selected payroll for viewing
    public $selectedPayroll = null;
    public $editingPayrollId = null;

    // Filters
    public string $search = '';
    public string $monthFilter = '';
    public string $yearFilter = '';
    public string $employeeFilter = '';

    // Form properties for bulk payroll
    public array $selected_employees = [];
    public string $bulk_month = '';
    public string $bulk_status = 'Pending';
    public string $bulk_remarks = '';

    // Form properties for individual payroll
    public string $individual_employee_id = '';
    public string $individual_month = '';
    public string $individual_base_salary = '';
    public string $individual_leave_deductions = '';
    public string $individual_other_deductions = '';
    public string $individual_deduction_notes = '';
    public string $individual_net_pay = '';
    public string $individual_status = 'Pending';
    public string $individual_remarks = '';

    // Form properties for editing
    public string $employee_id = '';
    public string $month = '';
    public string $base_salary = '';
    public string $deductions = '';
    public string $net_pay = '';
    public string $status = 'Pending';
    public string $leave_deductions = '';
    public string $other_deductions = '';
    public string $deduction_notes = '';
    public string $remarks = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'monthFilter' => ['except' => ''],
        'yearFilter' => ['except' => ''],
        'employeeFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->resetValidation();
        $this->month = date('Y-m');
        $this->individual_month = date('Y-m');
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['selected_employees', 'bulk_month', 'bulk_status', 'bulk_remarks']);
        $this->bulk_month = date('Y-m');
        $this->showCreateModal = true;
    }

    public function save()
    {
        try {
            $this->validate([
                'selected_employees' => 'required|array|min:1',
                'selected_employees.*' => 'exists:employees,id',
                'bulk_month' => 'required|string|max:7',
                'bulk_status' => 'required|in:Pending,Paid,Cancelled',
            ], [
                'selected_employees.required' => 'Please select at least one employee.',
                'selected_employees.min' => 'Please select at least one employee.',
                'selected_employees.*.exists' => 'One or more selected employees do not exist.',
                'bulk_month.required' => 'Month is required.',
                'bulk_status.required' => 'Status is required.',
                'bulk_status.in' => 'Status must be Pending, Paid, or Cancelled.',
            ]);

            DB::transaction(function () {
                $createdCount = 0;
                $skippedCount = 0;

                foreach ($this->selected_employees as $employeeId) {
                    $employee = Employee::find($employeeId);
                    if (!$employee) continue;

                    // Check if payroll already exists for this employee and month
                    $existingPayroll = Payroll::where('employee_id', $employeeId)
                        ->where('month', $this->bulk_month)
                        ->first();

                    if ($existingPayroll) {
                        $skippedCount++;
                        continue; // Skip this employee
                    }

                    // Get base salary from employee record
                    $baseSalary = floatval($employee->fixed_salary ?? 0);

                    // Calculate leave deductions for this month
                    $leaveDeductions = $this->calculateLeaveDeductions($employeeId, $this->bulk_month);

                    // Calculate net pay
                    $netPay = $baseSalary - $leaveDeductions;

                    Payroll::create([
                        'employee_id' => $employeeId,
                        'month' => $this->bulk_month,
                        'base_salary' => $baseSalary,
                        'deductions' => $leaveDeductions,
                        'net_pay' => $netPay,
                        'status' => $this->bulk_status,
                        'leave_deductions' => $leaveDeductions,
                        'other_deductions' => 0,
                        'deduction_notes' => 'Auto-calculated from leave records',
                        'remarks' => trim($this->bulk_remarks ?? ''),
                    ]);

                    $createdCount++;
                }

                // Update success message with counts
                $message = '';
                if ($createdCount > 0) {
                    $message .= $createdCount . ' payroll record(s) created successfully.';
                }
                if ($skippedCount > 0) {
                    $message .= ($message ? ' ' : '') . $skippedCount . ' employee(s) skipped (payroll already exists for this month).';
                }

                session()->flash('message', $message);
            });

            $this->showCreateModal = false;
            $this->reset(['selected_employees', 'bulk_month', 'bulk_status', 'bulk_remarks']);
            session()->flash('message', count($this->selected_employees) . ' payroll records created successfully.');
            $this->dispatch('payroll-created');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating bulk payroll: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating bulk payroll: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to create payroll records. Please try again.');
        }
    }

    private function calculateLeaveDeductions($employeeId, $month)
    {
        // Parse month to get year and month
        $date = \DateTime::createFromFormat('Y-m', $month);
        if (!$date) return 0;

        $year = $date->format('Y');
        $monthNum = $date->format('m');

        // Get employee's daily salary (assuming 22 working days per month)
        $employee = Employee::find($employeeId);
        if (!$employee || !$employee->fixed_salary) return 0;

        $dailySalary = $employee->fixed_salary / 22;

        // Get approved leaves for this month
        $leaves = Leave::where('employee_id', $employeeId)
            ->where('status', 'Approved')
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $monthNum)
            ->get();

        $totalLeaveDays = 0;
        foreach ($leaves as $leave) {
            $totalLeaveDays += $leave->duration_in_days;
        }

        // Calculate deductions (assuming unpaid leave)
        return $dailySalary * $totalLeaveDays;
    }

    public function edit($id)
    {
        try {
            $this->resetValidation();
            $payroll = Payroll::findOrFail($id);

            $this->editingPayrollId = $payroll->id;
            $this->employee_id = $payroll->employee_id;
            $this->month = $payroll->month ?? '';
            $this->base_salary = $payroll->base_salary ?? '';
            $this->deductions = $payroll->deductions ?? '';
            $this->net_pay = $payroll->net_pay ?? '';
            $this->status = $payroll->status ?? 'Pending';
            $this->leave_deductions = $payroll->leave_deductions ?? '';
            $this->other_deductions = $payroll->other_deductions ?? '';
            $this->deduction_notes = $payroll->deduction_notes ?? '';
            $this->remarks = $payroll->remarks ?? '';

            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error editing payroll: ' . $e->getMessage());
            session()->flash('error', 'Failed to load payroll data. Please try again.');
        }
    }

    public function update()
    {
        try {
            // Calculate deductions and net pay before validation
            $this->calculateNetPay();

            $this->validate([
                'employee_id' => 'required|exists:employees,id',
                'month' => 'required|string|max:7',
                'base_salary' => 'required|numeric|min:0',
                'net_pay' => 'required|numeric|min:0',
                'status' => 'required|in:Pending,Paid,Cancelled',
                'leave_deductions' => 'nullable|numeric|min:0',
                'other_deductions' => 'nullable|numeric|min:0',
            ], [
                'employee_id.required' => 'Please select an employee.',
                'employee_id.exists' => 'Selected employee does not exist.',
                'month.required' => 'Month is required.',
                'base_salary.required' => 'Base salary is required.',
                'base_salary.numeric' => 'Base salary must be a number.',
                'net_pay.required' => 'Net pay is required.',
                'net_pay.numeric' => 'Net pay must be a number.',
                'net_pay.min' => 'Net pay cannot be negative.',
                'status.required' => 'Status is required.',
                'status.in' => 'Status must be Pending, Paid, or Cancelled.',
                'leave_deductions.numeric' => 'Leave deductions must be a number.',
                'other_deductions.numeric' => 'Other deductions must be a number.',
            ]);

            DB::transaction(function () {
                $payroll = Payroll::findOrFail($this->editingPayrollId);
                $payroll->update([
                    'employee_id' => $this->employee_id,
                    'month' => $this->month,
                    'base_salary' => $this->base_salary,
                    'deductions' => floatval($this->leave_deductions ?? 0) + floatval($this->other_deductions ?? 0),
                    'net_pay' => $this->net_pay,
                    'status' => $this->status,
                    'leave_deductions' => $this->leave_deductions ?? 0,
                    'other_deductions' => $this->other_deductions ?? 0,
                    'deduction_notes' => trim($this->deduction_notes ?? ''),
                    'remarks' => trim($this->remarks ?? ''),
                ]);
            });

            $this->showEditModal = false;
            $this->reset(['employee_id', 'month', 'base_salary', 'deductions', 'net_pay', 'status', 'leave_deductions', 'other_deductions', 'deduction_notes', 'remarks', 'editingPayrollId']);
            session()->flash('message', 'Payroll updated successfully.');
            $this->dispatch('payroll-updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating payroll: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating payroll: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to update payroll. Please try again.');
        }
    }

    public function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $payroll = Payroll::findOrFail($id);
                $payroll->delete();
            });

            session()->flash('message', 'Payroll deleted successfully.');
            $this->resetPage();
            $this->dispatch('payroll-deleted');
        } catch (\Exception $e) {
            Log::error('Error deleting payroll: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete payroll. Please try again.');
        }
    }

    public function view($id)
    {
        try {
            $this->selectedPayroll = Payroll::with('employee')->findOrFail($id);
            $this->showViewModal = true;
        } catch (\Exception $e) {
            Log::error('Error viewing payroll: ' . $e->getMessage());
            session()->flash('error', 'Failed to load payroll details. Please try again.');
        }
    }

    public function showIndividualPayroll()
    {
        $this->resetValidation();
        $this->reset(['individual_employee_id', 'individual_month', 'individual_base_salary', 'individual_leave_deductions', 'individual_other_deductions', 'individual_deduction_notes', 'individual_net_pay', 'individual_status', 'individual_remarks']);
        $this->individual_month = date('Y-m');
        $this->showIndividualModal = true;
    }

    public function saveIndividual()
    {
        try {
            // Calculate deductions and net pay before validation
            $this->calculateIndividualNetPay();

            $this->validate([
                'individual_employee_id' => 'required|exists:employees,id',
                'individual_month' => 'required|string|max:7',
                'individual_base_salary' => 'required|numeric|min:0',
                'individual_leave_deductions' => 'nullable|numeric|min:0',
                'individual_other_deductions' => 'nullable|numeric|min:0',
                'individual_net_pay' => 'required|numeric|min:0',
                'individual_status' => 'required|in:Pending,Paid,Cancelled',
            ], [
                'individual_employee_id.required' => 'Please select an employee.',
                'individual_employee_id.exists' => 'Selected employee does not exist.',
                'individual_month.required' => 'Month is required.',
                'individual_base_salary.required' => 'Base salary is required.',
                'individual_base_salary.numeric' => 'Base salary must be a number.',
                'individual_leave_deductions.numeric' => 'Leave deductions must be a number.',
                'individual_other_deductions.numeric' => 'Other deductions must be a number.',
                'individual_net_pay.required' => 'Net pay is required.',
                'individual_net_pay.numeric' => 'Net pay must be a number.',
                'individual_net_pay.min' => 'Net pay cannot be negative.',
                'individual_status.required' => 'Status is required.',
                'individual_status.in' => 'Status must be Pending, Paid, or Cancelled.',
            ]);

            DB::transaction(function () {
                // Check if payroll already exists for this employee and month
                $existingPayroll = Payroll::where('employee_id', $this->individual_employee_id)
                    ->where('month', $this->individual_month)
                    ->first();

                if ($existingPayroll) {
                    session()->flash('error', 'Payroll already exists for this employee and month. Please select a different month or employee.');
                    return;
                }

                Payroll::create([
                    'employee_id' => $this->individual_employee_id,
                    'month' => $this->individual_month,
                    'base_salary' => $this->individual_base_salary,
                    'deductions' => floatval($this->individual_leave_deductions ?? 0) + floatval($this->individual_other_deductions ?? 0),
                    'net_pay' => $this->individual_net_pay,
                    'status' => $this->individual_status,
                    'leave_deductions' => $this->individual_leave_deductions ?? 0,
                    'other_deductions' => $this->individual_other_deductions ?? 0,
                    'deduction_notes' => trim($this->individual_deduction_notes ?? ''),
                    'remarks' => trim($this->individual_remarks ?? ''),
                ]);
            });

            $this->showIndividualModal = false;
            $this->reset(['individual_employee_id', 'individual_month', 'individual_base_salary', 'individual_leave_deductions', 'individual_other_deductions', 'individual_deduction_notes', 'individual_net_pay', 'individual_status', 'individual_remarks']);
            session()->flash('message', 'Individual payroll created successfully.');
            $this->dispatch('payroll-created');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating individual payroll: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating individual payroll: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to create individual payroll. Please try again.');
        }
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedPayroll = null;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetValidation();
        $this->reset(['selected_employees', 'bulk_month', 'bulk_status', 'bulk_remarks']);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
        $this->reset(['employee_id', 'month', 'base_salary', 'deductions', 'net_pay', 'status', 'leave_deductions', 'other_deductions', 'deduction_notes', 'remarks', 'editingPayrollId']);
    }

    public function closeIndividualModal()
    {
        $this->showIndividualModal = false;
        $this->resetValidation();
        $this->reset(['individual_employee_id', 'individual_month', 'individual_base_salary', 'individual_leave_deductions', 'individual_other_deductions', 'individual_deduction_notes', 'individual_net_pay', 'individual_status', 'individual_remarks']);
    }

    public function calculateNetPay()
    {
        // Calculate total deductions from individual components
        $leaveDeductions = floatval($this->leave_deductions ?? 0);
        $otherDeductions = floatval($this->other_deductions ?? 0);
        $this->deductions = $leaveDeductions + $otherDeductions;

        // Calculate net pay
        if ($this->base_salary) {
            $baseSalary = floatval($this->base_salary);
            $this->net_pay = $baseSalary - $this->deductions;
        } else {
            $this->net_pay = 0;
        }
    }

    public function calculateIndividualNetPay()
    {
        if ($this->individual_base_salary) {
            $leaveDeductions = floatval($this->individual_leave_deductions ?? 0);
            $otherDeductions = floatval($this->individual_other_deductions ?? 0);
            $totalDeductions = $leaveDeductions + $otherDeductions;

            $baseSalary = floatval($this->individual_base_salary);
            $this->individual_net_pay = $baseSalary - $totalDeductions;
        } else {
            $this->individual_net_pay = 0;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedMonthFilter()
    {
        $this->resetPage();
    }

    public function updatedYearFilter()
    {
        $this->resetPage();
    }

    public function updatedEmployeeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        try {
            $query = Payroll::with('employee')
                ->when($this->search, function ($query) {
                    $query->whereHas('employee', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_id', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->monthFilter, function ($query) {
                    $query->where('month', $this->monthFilter);
                })
                ->when($this->yearFilter, function ($query) {
                    $query->where('month', 'like', $this->yearFilter . '-%');
                })
                ->when($this->employeeFilter, function ($query) {
                    $query->where('employee_id', $this->employeeFilter);
                });

            return view('livewire.payroll-manager', [
                'payrolls' => $query->latest()->paginate(10),
                'employees' => Employee::active()->get(),
                'months' => [
                    '2024-01' => 'January 2024',
                    '2024-02' => 'February 2024',
                    '2024-03' => 'March 2024',
                    '2024-04' => 'April 2024',
                    '2024-05' => 'May 2024',
                    '2024-06' => 'June 2024',
                    '2024-07' => 'July 2024',
                    '2024-08' => 'August 2024',
                    '2024-09' => 'September 2024',
                    '2024-10' => 'October 2024',
                    '2024-11' => 'November 2024',
                    '2024-12' => 'December 2024',
                    '2025-01' => 'January 2025',
                    '2025-02' => 'February 2025',
                    '2025-03' => 'March 2025',
                    '2025-04' => 'April 2025',
                    '2025-05' => 'May 2025',
                    '2025-06' => 'June 2025',
                    '2025-07' => 'July 2025',
                    '2025-08' => 'August 2025',
                    '2025-09' => 'September 2025',
                    '2025-10' => 'October 2025',
                    '2025-11' => 'November 2025',
                    '2025-12' => 'December 2025',
                ],
                'years' => range(2024, 2025),
                'statuses' => ['Pending', 'Paid', 'Cancelled']
            ]);
        } catch (\Exception $e) {
            Log::error('Error rendering payroll manager: ' . $e->getMessage());
            session()->flash('error', 'Failed to load payroll data. Please refresh the page.');

            return view('livewire.payroll-manager', [
                'payrolls' => collect([])->paginate(10),
                'employees' => collect([]),
                'months' => [],
                'years' => range(2024, 2025),
                'statuses' => ['Pending', 'Paid', 'Cancelled']
            ]);
        }
    }
}
