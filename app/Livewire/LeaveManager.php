<?php

namespace App\Livewire;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveManager extends Component
{
    use WithPagination;

    public $selectedLeave = null;
    public $showViewModal = false;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingLeaveId = null;
    public string $search = '';
    public string $statusFilter = '';
    public string $employeeFilter = '';

    // Form properties
    public string $employee_id = '';
    public string $leave_type = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $duration_in_days = '';
    public string $reason = '';
    public string $status = 'Pending';
    public string $approver_comment = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'employeeFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['employee_id', 'leave_type', 'start_date', 'end_date', 'duration_in_days', 'reason', 'status', 'approver_comment']);
        $this->showCreateModal = true;
    }

    public function save()
    {
        try {
            $this->validate(Leave::rules(), Leave::messages());

            DB::transaction(function () {
                Leave::create([
                    'employee_id' => $this->employee_id,
                    'leave_type' => $this->leave_type,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'duration_in_days' => $this->duration_in_days,
                    'reason' => trim($this->reason),
                    'status' => $this->status,
                    'approver_comment' => trim($this->approver_comment),
                ]);
            });

            $this->showCreateModal = false;
            $this->reset(['employee_id', 'leave_type', 'start_date', 'end_date', 'duration_in_days', 'reason', 'status', 'approver_comment']);
            session()->flash('message', 'Leave request created successfully.');

            // Force refresh the component
            $this->dispatch('leave-created');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are already handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to create leave request. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            $this->resetValidation();
            $leave = Leave::findOrFail($id);

            $this->editingLeaveId = $leave->id;
            $this->employee_id = $leave->employee_id;
            $this->leave_type = $leave->leave_type;
            $this->start_date = $leave->start_date->format('Y-m-d');
            $this->end_date = $leave->end_date->format('Y-m-d');
            $this->duration_in_days = $leave->duration_in_days;
            $this->reason = $leave->reason;
            $this->status = $leave->status;
            $this->approver_comment = $leave->approver_comment ?? '';

            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error editing leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to load leave request data. Please try again.');
        }
    }

    public function update()
    {
        try {
            $this->validate(Leave::rules($this->editingLeaveId), Leave::messages());

            DB::transaction(function () {
                $leave = Leave::findOrFail($this->editingLeaveId);
                $leave->update([
                    'employee_id' => $this->employee_id,
                    'leave_type' => $this->leave_type,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'duration_in_days' => $this->duration_in_days,
                    'reason' => trim($this->reason),
                    'status' => $this->status,
                    'approver_comment' => trim($this->approver_comment),
                ]);
            });

            $this->showEditModal = false;
            $this->reset(['employee_id', 'leave_type', 'start_date', 'end_date', 'duration_in_days', 'reason', 'status', 'approver_comment', 'editingLeaveId']);
            session()->flash('message', 'Leave request updated successfully.');

            // Force refresh the component
            $this->dispatch('leave-updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are already handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to update leave request. Please try again.');
        }
    }

    #[On('approve-leave')]
    public function approve($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $leave = Leave::findOrFail($id);
                $leave->update(['status' => 'Approved']);
            });

            session()->flash('message', 'Leave request has been approved.');

            // Force refresh the component
            $this->dispatch('leave-status-updated');
        } catch (\Exception $e) {
            Log::error('Error approving leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to approve leave request. Please try again.');
        }
    }

    #[On('reject-leave')]
    public function reject($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $leave = Leave::findOrFail($id);
                $leave->update(['status' => 'Rejected']);
            });

            session()->flash('message', 'Leave request has been rejected.');

            // Force refresh the component
            $this->dispatch('leave-status-updated');
        } catch (\Exception $e) {
            Log::error('Error rejecting leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to reject leave request. Please try again.');
        }
    }

    // public function delete($id)
    // {
    //     try {
    //         DB::transaction(function () use ($id) {
    //             $leave = Leave::findOrFail($id);
    //             $leave->delete();
    //         });

    //         session()->flash('message', 'Leave request deleted successfully.');

    //         // Force refresh the component and reset pagination
    //         $this->resetPage();
    //         $this->dispatch('leave-deleted');

    //     } catch (\Exception $e) {
    //         Log::error('Error deleting leave request: ' . $e->getMessage());
    //         session()->flash('error', 'Failed to delete leave request. Please try again.');
    //     }
    // }

    #[On('view-leave')]
    public function view($id)
    {
        try {
            $this->selectedLeave = Leave::with('employee')->findOrFail($id);
            $this->showViewModal = true;
        } catch (\Exception $e) {
            Log::error('Error viewing leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to load leave request details. Please try again.');
        }
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedLeave = null;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetValidation();
        $this->reset(['employee_id', 'leave_type', 'start_date', 'end_date', 'duration_in_days', 'reason', 'status', 'approver_comment']);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
        $this->reset(['employee_id', 'leave_type', 'start_date', 'end_date', 'duration_in_days', 'reason', 'status', 'approver_comment', 'editingLeaveId']);
    }

    public function calculateDuration()
    {
        if ($this->start_date && $this->end_date) {
            $start = \Carbon\Carbon::parse($this->start_date);
            $end = \Carbon\Carbon::parse($this->end_date);
            $this->duration_in_days = $start->diffInDays($end) + 1;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
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
            $query = Leave::with('employee')
                ->when($this->search, function ($query) {
                    $query->whereHas('employee', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_id', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->when($this->employeeFilter, function ($query) {
                    $query->where('employee_id', $this->employeeFilter);
                });

            return view('livewire.leave-manager', [
                'leaves' => $query->latest()->paginate(10),
                'statuses' => ['Pending', 'Approved', 'Rejected'],
                'employees' => Employee::active()->get(),
                'leaveTypes' => ['Casual Leave', 'Sick Leave', 'Annual Leave', 'Personal Leave', 'Other']
            ]);
        } catch (\Exception $e) {
            Log::error('Error rendering leave manager: ' . $e->getMessage());
            session()->flash('error', 'Failed to load leave data. Please refresh the page.');

            return view('livewire.leave-manager', [
                'leaves' => collect([])->paginate(10),
                'statuses' => ['Pending', 'Approved', 'Rejected'],
                'employees' => collect([]),
                'leaveTypes' => ['Casual Leave', 'Sick Leave', 'Annual Leave', 'Personal Leave', 'Other']
            ]);
        }
    }
}
