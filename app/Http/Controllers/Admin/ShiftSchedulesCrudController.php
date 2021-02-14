<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ShiftScheduleCreateRequest;
use App\Http\Requests\ShiftScheduleUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ShiftSchedulesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ShiftSchedulesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ShiftSchedule::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shiftschedules');

        $this->userPermissions();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->showColumns();

        foreach ($this->jsonColumns() as $col) {
            $this->crud->modifyColumn($col, [
                'type'     => 'closure',
                'function' => function($entry) use ($col) {
                    return $entry->{$col.'_as_text'};
                }
            ]);
        }
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ShiftScheduleCreateRequest::class);
        $this->inputFields(); 
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(ShiftScheduleUpdateRequest::class);
        $this->inputFields(); 
    }

    private function inputFields()
    {
        $this->inputs();

        $this->crud->modifyField('name', [
            'hint' => trans('lang.shift_schedules_name_hint')
        ]);

        $class = 'form-group col-sm-12 group-hiddenable';

        foreach ($this->jsonColumns() as $col) {
            $hint = $col == 'working_hours' ? trans('lang.shift_schedules_working_hours_hint') : null;
            $this->crud->modifyField($col, [
                'type'     => 'custom_table',
                'fake'     => true,
                'store_in' => $col,
                'columns'  => [
                    'start' => 'Start',
                    'end' => 'End',
                ],
                'min'          => $col == 'working_hours' ? 1 : 0, // minimum rows allowed in the table
                'columns_type' => 'time',
                'hint'         => $hint,
                'wrapper' => [
                    'class' => $class
                ]
            ]);    
        }

        $this->crud->modifyField('dynamic_break', [
            'wrapper' => [
                'class' => $class
            ]
        ]);

        $this->crud->modifyField('open_time', [
            'type' => 'custom_shift_schedule_open_time',
            'attributes' => [
                'name' => 'open_time_radio_button'
            ] 
        ]);
    }

    private function jsonColumns()
    {
        return [
            'working_hours',            
            'overtime_hours',            
        ];
    }

    // TODO:: if open time = Yes then table column display should be empty or null in datatable
    // TODO:: factories
    // TODO:: check export and order column
    // TODO:: create seeder and name it with suffix example
}
