<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class EmployeeManager extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $editingEmployeeId = null;
    public string $search = '';
    public string $departmentFilter = '';
    public string $designationFilter = '';

    // Form properties
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $department_id = '';
    public string $designation_id = '';
    public string $joining_date = '';
    public string $fixed_salary = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'designationFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->resetValidation();
    }

    public function generateEmployeeId()
    {
        try {
            // Use database transaction to ensure atomicity
            return DB::transaction(function () {
                $lastEmployee = Employee::orderBy('id', 'desc')->first();

                if (!$lastEmployee) {
                    return 'EMP-0001';
                }

                // Extract the numeric part and increment
                $lastNumber = (int) substr($lastEmployee->employee_id, 4);
                $newNumber = $lastNumber + 1;

                return 'EMP-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            });
        } catch (\Exception $e) {
            Log::error('Error generating employee ID: ' . $e->getMessage());
            throw new \Exception('Failed to generate employee ID. Please try again.');
        }
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'phone', 'department_id', 'designation_id', 'joining_date', 'fixed_salary']);
        $this->showCreateModal = true;
    }

    public function save()
    {
        // Reset validation first
        $this->resetValidation();

        try {
            // Validate the data
            $this->validate(Employee::rules(), Employee::messages());

            // If validation passes, create the employee
            DB::transaction(function () {
                Employee::create([
                    'employee_id' => $this->generateEmployeeId(),
                    'name' => trim($this->name),
                    'email' => trim($this->email),
                    'phone' => trim($this->phone),
                    'department_id' => $this->department_id,
                    'designation_id' => $this->designation_id,
                    'joining_date' => $this->joining_date,
                    'fixed_salary' => $this->fixed_salary,
                    'status' => 'Active',
                ]);
            });

            $this->showCreateModal = false;
            $this->reset(['name', 'email', 'phone', 'department_id', 'designation_id', 'joining_date', 'fixed_salary']);
            session()->flash('message', 'Employee created successfully.');

            // Force refresh the component
            $this->dispatch('employee-created');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::info('Validation failed for employee creation: ' . json_encode($e->errors()));
            // Re-throw the validation exception so Livewire can handle it
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating employee: ' . $e->getMessage());
            session()->flash('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $this->resetValidation();
            $employee = Employee::findOrFail($id);

            $this->editingEmployeeId = $employee->id;
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->phone = $employee->phone ?? '';
            $this->department_id = $employee->department_id;
            $this->designation_id = $employee->designation_id;
            $this->joining_date = $employee->joining_date->format('Y-m-d');
            $this->fixed_salary = $employee->fixed_salary;

            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error editing employee: ' . $e->getMessage());
            session()->flash('error', 'Failed to load employee data. Please try again.');
        }
    }

    public function update()
    {
        // Reset validation first
        $this->resetValidation();

        try {
            // Validate the data
            $this->validate(Employee::rules($this->editingEmployeeId), Employee::messages());

            // If validation passes, update the employee
            DB::transaction(function () {
                $employee = Employee::findOrFail($this->editingEmployeeId);
                $employee->update([
                    'name' => trim($this->name),
                    'email' => trim($this->email),
                    'phone' => trim($this->phone),
                    'department_id' => $this->department_id,
                    'designation_id' => $this->designation_id,
                    'joining_date' => $this->joining_date,
                    'fixed_salary' => $this->fixed_salary,
                ]);
            });

            $this->showEditModal = false;
            $this->reset(['name', 'email', 'phone', 'department_id', 'designation_id', 'joining_date', 'fixed_salary', 'editingEmployeeId']);
            session()->flash('message', 'Employee updated successfully.');

            // Force refresh the component
            $this->dispatch('employee-updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::info('Validation failed for employee update: ' . json_encode($e->errors()));
            // Re-throw the validation exception so Livewire can handle it
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating employee: ' . $e->getMessage());
            session()->flash('error', 'Failed to update employee. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $employee = Employee::findOrFail($id);
                $employee->status = $employee->status === 'Active' ? 'Inactive' : 'Active';
                $employee->save();
            });

            session()->flash('message', 'Employee status updated successfully.');

            // Force refresh the component
            $this->dispatch('employee-status-updated');
        } catch (\Exception $e) {
            Log::error('Error toggling employee status: ' . $e->getMessage());
            session()->flash('error', 'Failed to update employee status. Please try again.');
        }
    }

    // public function delete($id)
    // {
    //     try {
    //         DB::transaction(function () use ($id) {
    //             $employee = Employee::findOrFail($id);

    //             // Check if employee has related records
    //             $leaveCount = $employee->leaves()->count();
    //             $payrollCount = $employee->payrolls()->count();

    //             if ($leaveCount > 0 || $payrollCount > 0) {
    //                 $message = 'Cannot delete employee with existing records: ';
    //                 $issues = [];
    //                 if ($leaveCount > 0) $issues[] = "{$leaveCount} leave record(s)";
    //                 if ($payrollCount > 0) $issues[] = "{$payrollCount} payroll record(s)";
    //                 $message .= implode(', ', $issues) . '. Please delete these records first.';
    //                 throw new \Exception($message);
    //             }

    //             $employee->delete();
    //         });

    //         session()->flash('message', 'Employee deleted successfully.');

    //         // Force refresh the component and reset pagination
    //         $this->resetPage();
    //         $this->dispatch('employee-deleted');

    //     } catch (\Exception $e) {
    //         Log::error('Error deleting employee: ' . $e->getMessage());
    //         session()->flash('error', $e->getMessage());
    //     }
    // }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetValidation();
        $this->reset(['name', 'email', 'phone', 'department_id', 'designation_id', 'joining_date', 'fixed_salary']);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
        $this->reset(['name', 'email', 'phone', 'department_id', 'designation_id', 'joining_date', 'fixed_salary', 'editingEmployeeId']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatedDesignationFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        try {
            $query = Employee::with(['department', 'designation'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_id', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->departmentFilter, function ($query) {
                    $query->where('department_id', $this->departmentFilter);
                })
                ->when($this->designationFilter, function ($query) {
                    $query->where('designation_id', $this->designationFilter);
                });

            return view('livewire.employee-manager', [
                'employees' => $query->latest()->paginate(10),
                'departments' => Department::active()->get(),
                'designations' => Designation::active()->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Error rendering employee manager: ' . $e->getMessage());
            session()->flash('error', 'Failed to load employee data. Please refresh the page.');

            return view('livewire.employee-manager', [
                'employees' => collect([])->paginate(10),
                'departments' => collect([]),
                'designations' => collect([])
            ]);
        }
    }
}
