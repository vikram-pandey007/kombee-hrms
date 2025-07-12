<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;

class PayrollManager extends Component
{
    public $currentMonth;

    // **NEW:** Properties for the payslip modal
    public bool $showPayslipModal = false;
    public ?Payroll $viewingPayslip = null;

    public function mount()
    {
        $this->currentMonth = now()->format('F Y');
    }

    public function generatePayroll()
    {
        $month = now();
        $monthString = $month->format('Y-m');
        $activeEmployees = Employee::where('status', 'Active')->get();
        $recordsGenerated = 0;

        try {
            foreach ($activeEmployees as $employee) {
                if (Payroll::where('employee_id', $employee->id)->where('month', $monthString)->exists()) {
                    continue;
                }
                $unpaidLeaveDays = Leave::where('employee_id', $employee->id)->where('leave_type', 'Unpaid Leave')->where('status', 'Approved')->whereYear('start_date', $month->year)->whereMonth('start_date', $month->month)->sum('duration_in_days');
                $dailyRate = $employee->fixed_salary / 30;
                $deductions = $dailyRate * $unpaidLeaveDays;
                $netPay = $employee->fixed_salary - $deductions;

                Payroll::create(['employee_id' => $employee->id, 'month' => $monthString, 'base_salary' => $employee->fixed_salary, 'deductions' => $deductions, 'net_pay' => $netPay, 'status' => 'Unpaid']);
                $recordsGenerated++;
            }

            if ($recordsGenerated > 0) {
                session()->flash('message', "Successfully generated {$recordsGenerated} new payroll records.");
            } else {
                session()->flash('info', 'No new payroll records to generate for this month.');
            }
        } catch (Exception $e) {
            logger()->error('Payroll Generation Failed: ' . $e->getMessage());
            session()->flash('error', 'An unexpected error occurred while generating payroll.');
        }
        $this->dispatch('pg:eventRefresh-PayrollTable');
    }

    #[On('mark-as-paid')]
    public function markAsPaid($id)
    {
        $payroll = Payroll::find($id);
        if ($payroll) {
            $payroll->update(['status' => 'Paid']);
            $this->dispatch('pg:eventRefresh-default');
            session()->flash('message', 'Payroll record marked as Paid.');
        }
    }

    #[On('view-payslip')]
    public function viewPayslip($id)
    {
        try {
            $this->viewingPayslip = Payroll::with(['employee' => function ($query) {
                $query->select('id', 'name', 'employee_id', 'department', 'designation');
            }])->findOrFail($id);
            $this->showPayslipModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load payslip details.');
        }
    }

    public function closePayslipModal()
    {
        $this->showPayslipModal = false;
        $this->viewingPayslip = null;
    }

    public function render()
    {
        return view('livewire.payroll-manager');
    }
}
