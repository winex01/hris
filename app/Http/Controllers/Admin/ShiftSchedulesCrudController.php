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

    public function __construct()
    {
        parent::__construct();

        // use this export class instead of BaseExport
        $this->exportClass = '\App\Exports\ShiftScheduleExport';
    }
    
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
                'orderable'=> false,
                'type'     => 'closure',
                'function' => function($entry) use ($col) {
                    return $entry->{$col.'_as_text'};
                }
            ]);
        }

        $this->crud->removeColumn('open_time');

        $this->booleanFilter('dynamic_break');
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

        $class = 'form-group col-sm-12 open_time_hiddenable';

        foreach ($this->jsonColumns() as $col) {
            $this->crud->modifyField($col, [
                'type'     => 'custom_table',
                'fake'     => true,
                'store_in' => $col,
                'columns'  => [
                    'start' => 'Start',
                    'end' => 'End',
                ],
                'min'          => 1, // minimum rows allowed in the table
                'columns_type' => 'time',
                'hint'         => trans('lang.shift_schedules_'.$col.'_hint'),
                'wrapper' => [
                    'class' => $class
                ]
            ]);    
        }

        // toggle hidden fields using radio button
        foreach (['open_time', 'dynamic_break'] as $field) {
            $this->crud->modifyField($field, [
                'type'                 => 'custom_radio_hide_other_fields',
                'attributes'           => ['name' => $field.'_radio_button'],
                'toggle_class'         => $field.'_hiddenable',
                'show_fields_if_value' => ($field == 'open_time') ? 0 : 1, // No / Yes 
            ]);
        }

        $field = 'dynamic_break_credit';
        $this->crud->modifyField($field, [
            'type' => 'custom_timepicker',
            'wrapper' => [
                'class' => 'form-group col-sm-3 col-md-3 dynamic_break_hiddenable'
            ],
            'default' => '01:00',
        ]);
    }

    private function jsonColumns()
    {
        return [
            'working_hours',            
            'overtime_hours',            
        ];
    }
}
