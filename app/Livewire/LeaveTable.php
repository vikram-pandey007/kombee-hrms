<?php

namespace App\Livewire;

use App\Models\Leave;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Button;

final class LeaveTable extends PowerGridComponent
{
    public string $tableName = 'leave-table';

    public function mount(): void
    {
        parent::mount();
        // Force refresh on mount
        $this->refresh();
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Leave::query()
            ->join('employees', 'leaves.employee_id', '=', 'employees.id')
            ->select('leaves.*', 'employees.name as employee_name');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('employee_name')
            ->add('leave_type')
            ->add('start_date_formatted', fn(Leave $model) => Carbon::parse($model->start_date)->format('M d, Y'))
            ->add('end_date_formatted', fn(Leave $model) => Carbon::parse($model->end_date)->format('M d, Y'))
            ->add('duration_in_days')
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Employee', 'employee_name', 'employees.name')->sortable()->searchable(),
            Column::make('Type', 'leave_type')->sortable(),
            Column::make('Start Date', 'start_date_formatted', 'start_date')->sortable(),
            Column::make('End Date', 'end_date_formatted', 'end_date')->sortable(),
            Column::make('Days', 'duration_in_days'),
            Column::make('Status', 'status')->sortable(),
            Column::action('Action')
        ];
    }

    public function actions(Leave $row): array
    {
        $actions = [];

        if ($row->status === 'Pending') {
            $actions[] = Button::add('approve')
                ->slot('Approve')
                ->id()
                ->class('px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
                ->dispatch('approve-leave', ['id' => $row->id])
                ->attributes(['data-testid' => 'leave-row-action-approve']);

            $actions[] = Button::add('reject')
                ->slot('Reject')
                ->id()
                ->class('px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
                ->dispatch('reject-leave', ['id' => $row->id])
                ->attributes(['data-testid' => 'leave-row-action-reject']);
        } else {
            // Show status for non-pending leaves
            $actions[] = Button::add('status')
                ->slot($row->status)
                ->id()
                ->class($row->status === 'Approved' ? 'px-3 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-md border border-green-200' : 'px-3 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-md border border-red-200')
                ->attributes(['data-testid' => 'leave-row-status-' . $row->id]);
        }

        // Always show view button
        $actions[] = Button::add('view')
            ->slot('View')
            ->id()
            ->class('px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm')
            ->dispatch('view-leave', ['id' => $row->id])
            ->attributes(['data-testid' => 'leave-row-action-view']);

        return $actions;
    }

    public function refresh(): void
    {
        $this->dispatch('$refresh');
    }
}
