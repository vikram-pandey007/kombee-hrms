<?php

namespace App\Livewire;

use App\Models\Leave;
use Livewire\Attributes\On;
use Livewire\Component;

class LeaveManager extends Component
{
    public $selectedLeave = null;
    public $showViewModal = false;

    #[On('approve-leave')]
    public function approve($id)
    {
        $leave = Leave::find($id);
        if ($leave) {
            $leave->update(['status' => 'Approved']);
            session()->flash('message', 'Leave request has been approved.');

            // Refresh the table
            $this->dispatch('pg:eventRefresh-leave-table');
        }
    }

    #[On('reject-leave')]
    public function reject($id)
    {
        $leave = Leave::find($id);
        if ($leave) {
            $leave->update(['status' => 'Rejected']);
            session()->flash('message', 'Leave request has been rejected.');

            // Refresh the table
            $this->dispatch('pg:eventRefresh-leave-table');
        }
    }

    #[On('view-leave')]
    public function view($id)
    {
        $this->selectedLeave = Leave::with('employee')->find($id);
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedLeave = null;
    }

    public function render()
    {
        return view('livewire.leave-manager');
    }
}
