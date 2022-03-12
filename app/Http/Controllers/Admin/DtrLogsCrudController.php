<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DtrLogsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DtrLogsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DtrLogsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
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
        CRUD::setModel(\App\Models\DtrLog::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dtrlogs');

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
        $this->showEmployeeNameColumn();
        $this->showRelationshipColumn('dtr_log_type_id');
        $this->removeColumn('log');

        $this->accessorColumn('date_log', 'Date')->afterColumn('employee_id');
        $this->accessorColumn('time_log', 'Time')->afterColumn('date_log');

        $this->filters();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DtrLogsRequest::class);
        $this->inputs();
        $this->addSelectEmployeeField();
        $this->addTimestampField('log');
        $this->addRelationshipField('dtr_log_type_id');
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

    private function filters()
    {
        $this->dateRangeFilter('log');
        $this->select2Filter('dtr_log_type_id', 'id');
    }
}
// TODO:: add filter date and time instead of logs
// TODO:: TBD use badge in dtr log type column 
    // btn-info - time in
    // btn-danger - time out
    // btn-warning - break start
    // btn-success - break end
// TODO:: TBD don't allow delete if exist in dtr crud