<?php

namespace App\Livewire;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;
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
    #[Rule('required|min:3')]
    public string $name = '';
    #[Rule('required|email')]
    public string $email = '';
    #[Rule('nullable|string')]
    public string $phone = '';
    #[Rule('required|string')]
    public string $department = '';
    #[Rule('required|string')]
    public string $designation = '';
    #[Rule('required|date')]
    public string $joining_date = '';
    #[Rule('required|numeric')]
    public string $fixed_salary = '';

    public function mount()
    {
        $this->resetValidation();
    }

    public function generateEmployeeId()
    {
        $lastEmployee = Employee::latest()->first();
        $lastId = $lastEmployee ? intval(substr($lastEmployee->employee_id, 4)) : 0;
        return 'EMP-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset();
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->validate();

        Employee::create([
            'employee_id' => $this->generateEmployeeId(),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'department' => $this->department,
            'designation' => $this->designation,
            'joining_date' => $this->joining_date,
            'fixed_salary' => $this->fixed_salary,
            'status' => 'Active',
        ]);

        $this->showCreateModal = false;
        $this->reset();
        session()->flash('message', 'Employee created successfully.');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $employee = Employee::findOrFail($id);

        $this->editingEmployeeId = $employee->id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->department = $employee->department;
        $this->designation = $employee->designation;
        $this->joining_date = $employee->joining_date;
        $this->fixed_salary = $employee->fixed_salary;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $employee = Employee::findOrFail($this->editingEmployeeId);
        $employee->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'department' => $this->department,
            'designation' => $this->designation,
            'joining_date' => $this->joining_date,
            'fixed_salary' => $this->fixed_salary,
        ]);

        $this->showEditModal = false;
        $this->reset();
        session()->flash('message', 'Employee updated successfully.');
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = $employee->status === 'Active' ? 'Inactive' : 'Active';
        $employee->save();
        session()->flash('message', 'Employee status updated successfully.');
    }

    public function render()
    {
        $query = Employee::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where('department', $this->departmentFilter);
            })
            ->when($this->designationFilter, function ($query) {
                $query->where('designation', $this->designationFilter);
            });

        return view('livewire.employee-manager', [
            'employees' => $query->latest()->paginate(10),
            'departments' => Employee::distinct()->pluck('department'),
            'designations' => Employee::distinct()->pluck('designation')
        ]);
    }
}
