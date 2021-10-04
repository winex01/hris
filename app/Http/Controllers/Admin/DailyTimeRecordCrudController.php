<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DailyTimeRecordRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DailyTimeRecordCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DailyTimeRecordCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    // use \Backpack\ReviseOperation\ReviseOperation; // TODO:: revise if possible if not create link/anchor
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\DailyTimeRecord::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dailytimerecord');

        $this->userPermissions('daily_time_records');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // entries per page
        $this->crud->setDefaultPageLength(25);

        // columns
        $this->showEmployeeNameColumn('add');
        $this->crud->addColumn(['name' => 'date']);
        $this->crud->addColumn(['name' => 'shift']);
        $this->crud->addColumn([
            'name' => 'logs',
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->logs;
            } 
        ]);
        $this->crud->addColumn(['name' => 'leave']);
        $this->crud->addColumn(['name' => 'reg_hour']);
        $this->crud->addColumn(['name' => 'late']);
        $this->crud->addColumn(['name' => 'ut']);
        $this->crud->addColumn(['name' => 'ot']);
        $this->crud->addColumn(['name' => 'pot']);

        // filters
        $this->employeeFilter('id');        

        
        // $this->crud->query->where('date_temp', '2021-01-2');
        // TODO:: fix, permission
        // TODO:: TBD export
        // TODO:: check lists table search box, and column sort TBD.
        // TODO:: override search method in ListsOperation
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DailyTimeRecordRequest::class);
        CRUD::setFromDb();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
