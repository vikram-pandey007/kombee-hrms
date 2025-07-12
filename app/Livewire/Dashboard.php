<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalEmployees;
    public $activeEmployees;
    public $pendingLeaves;
    public $monthlyPayroll;

    public function mount()
    {
        $this->totalEmployees = Employee::count();
        $this->activeEmployees = Employee::where('status', 'Active')->count();
        $this->pendingLeaves = Leave::where('status', 'Pending')->count();

        // Calculate payroll for the current month
        $currentMonth = now()->format('Y-m');
        $this->monthlyPayroll = Payroll::where('month', $currentMonth)->sum('net_pay');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
