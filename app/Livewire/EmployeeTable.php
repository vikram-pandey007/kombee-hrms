<?php

namespace App\Livewire;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class EmployeeTable extends PowerGridComponent
{
    public string $tableName = 'employee-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Employee::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('employee_id')
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('department')
            ->add('designation')
            ->add('joining_date_formatted', fn(Employee $model) => Carbon::parse($model->joining_date)->format('M d, Y'))
            ->add('salary_formatted', fn(Employee $model) => '₹' . number_format($model->fixed_salary, 2))
            ->add('status')
            ->add('created_at_formatted', fn(Employee $model) => Carbon::parse($model->created_at)->format('M d, Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Employee ID', 'employee_id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Email', 'email')
                ->searchable()
                ->sortable(),

            Column::make('Phone', 'phone')
                ->searchable(),

            Column::make('Department', 'department')
                ->searchable()
                ->sortable(),

            Column::make('Designation', 'designation')
                ->searchable()
                ->sortable(),

            Column::make('Joining Date', 'joining_date_formatted', 'joining_date')
                ->sortable(),

            Column::make('Salary', 'salary_formatted', 'fixed_salary')
                ->sortable(),

            Column::make('Status', 'status')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::inputText('email'),
            Filter::inputText('employee_id'),
            Filter::select('department', 'department')
                ->dataSource(Employee::distinct()->pluck('department'))
                ->optionValue('department')
                ->optionLabel('department'),
            Filter::select('status', 'status')
                ->dataSource(['Active', 'Inactive'])
                ->optionValue('status')
                ->optionLabel('status'),
        ];
    }

    public function actions(Employee $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
                ->dispatch('edit-employee', ['id' => $row->id])
                ->attributes(['data-testid' => 'employee-table-edit-' . $row->id]),

            Button::add('toggle-status')
                ->slot($row->status === 'Active' ? 'Deactivate' : 'Activate')
                ->id()
                ->class($row->status === 'Active' ? 'px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm' : 'px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
                ->dispatch('toggle-employee-status', ['id' => $row->id])
                ->attributes(['data-testid' => 'employee-table-toggle-' . $row->id])
        ];
    }
}
