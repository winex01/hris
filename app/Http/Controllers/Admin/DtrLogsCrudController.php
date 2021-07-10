<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DtrLogsRequest;
use App\Models\DtrLog;
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
    // use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    // use \App\Http\Controllers\Admin\Operations\CalendarOperation;

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
        $this->showTimestampColumn('log');
        $this->dateRangeFilter('log');
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

    /*
    |--------------------------------------------------------------------------
    | Use in Clock Logging Buttons at topbar_left_content.blade.php
    |--------------------------------------------------------------------------
    */
    protected function setupLoggedClockRoutes($segment, $routeName, $controller) {
        \Route::post($segment.'/logged-clock', [
            'as'        => $routeName.'.loggedClock',
            'uses'      => $controller.'@loggedClock',
        ]);
    }

    public function loggedClock()
    {
        $type = request()->type;
        $data = DtrLog::create([
            'employee_id' => request()->empId,
            'dtr_log_type_id' => request()->type
        ]);

        return [
            'text' => trans('lang.dtr_logs_logged_'.$type),
        ];
    }
}
