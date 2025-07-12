<?php

namespace App\Livewire;

use App\Models\Leave;
use Livewire\Attributes\On;
use Livewire\Component;

class LeaveManager extends Component
{
    #[On('approve-leave')]
    public function approve($id)
    {
        $leave = Leave::find($id);
        if ($leave) {
            $leave->update(['status' => 'Approved']);
            session()->flash('message', 'Leave request has been approved.');

            // **THE FIX:** The event name must match the table's unique name.
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

            // **THE FIX:** The event name must match the table's unique name.
            $this->dispatch('pg:eventRefresh-leave-table');
        }
    }

    public function render()
    {
        return view('livewire.leave-manager');
    }
}
