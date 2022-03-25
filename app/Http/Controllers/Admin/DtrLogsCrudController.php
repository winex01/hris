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

        $this->exportClass = '\App\Exports\DtrLogExport';
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
        
        // use badge in dtr_log_type col
        $this->modifyColumnAsClosure('dtr_log_type_id', ['dtrLogType', 'nameBadge']);

        $this->removeColumn('log');

        $this->accessorColumn('date')->afterColumn('employee_id');
        $this->accessorColumn('time')->beforeColumn('dtr_log_type_id');

        $this->filters();

        $this->searchAndOrderLogic();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
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
        $this->dateRangeFilter('log' , 'Date');
        $this->select2Filter('dtr_log_type_id', 'id');
    }

    private function searchAndOrderLogic()
    {
        $this->crud->modifyColumn('date', [
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('log', 'like', '%'.$searchTerm.'%');
            },
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('log', $columnDirection);
            },
            'orderable'  => true,
        ]);
        
        $this->crud->modifyColumn('time', [
            // no searchLogic same column search as above in date
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('log', $columnDirection);
            },
            'orderable'  => true,
        ]);
    }
}
// TODO:: fix validation make employee_id, date, dtr_log_type_id unique
// TODO:: TBD don't allow delete if exist in dtr crud