<?php

namespace App\Livewire;

use App\Models\Payroll;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PayrollTable extends PowerGridComponent
{
    public string $tableName = 'payroll-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Payroll::query()
            ->join('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->select('payrolls.*', 'employees.name as employee_name');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('employee_name')
            ->add('month')
            ->add('base_salary_formatted', fn(Payroll $model) => '₹' . number_format($model->base_salary, 2))
            ->add('deductions_formatted', fn(Payroll $model) => '₹' . number_format($model->deductions, 2))
            ->add('net_pay_formatted', fn(Payroll $model) => '₹' . number_format($model->net_pay, 2))
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Employee', 'employee_name', 'employees.name')->sortable()->searchable(),
            Column::make('Month', 'month')->sortable(),
            Column::make('Base Salary', 'base_salary_formatted', 'base_salary')->sortable(),
            Column::make('Deductions', 'deductions_formatted', 'deductions')->sortable(),
            Column::make('Net Pay', 'net_pay_formatted', 'net_pay')->sortable(),
            Column::make('Status', 'status')->sortable(),
            Column::action('Action')
        ];
    }

    public function actions(Payroll $row): array
    {
        $actions = [];

        if ($row->status === 'Unpaid') {
            $actions[] = Button::add('mark_as_paid')
                ->slot('Mark as Paid')
                ->id()
                ->class('px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
                ->dispatch('mark-as-paid', ['id' => $row->id])
                ->attributes(['data-testid' => 'payroll-row-action-mark-paid']);
        } else {
            // Show status for paid payrolls
            $actions[] = Button::add('status')
                ->slot('Paid')
                ->id()
                ->class('px-3 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-md border border-green-200')
                ->attributes(['data-testid' => 'payroll-row-status-' . $row->id]);
        }

        $actions[] = Button::add('view_payslip')
            ->slot('View Payslip')
            ->id()
            ->class('px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
            ->dispatch('view-payslip', ['id' => $row->id])
            ->attributes(['data-testid' => 'payroll-row-action-view-payslip']);

        // Always show edit button
        $actions[] = Button::add('edit')
            ->slot('Edit')
            ->id()
            ->class('px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
            ->dispatch('edit-payroll', ['id' => $row->id])
            ->attributes(['data-testid' => 'payroll-row-action-edit']);

        return $actions;
    }
}
