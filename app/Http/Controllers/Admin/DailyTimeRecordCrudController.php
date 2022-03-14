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

    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchPayrollPeriodTrait;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\DailyTimeRecord::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dailytimerecord');

        $this->userPermissions();

        $this->exportClass = '\App\Exports\DailyTimeRecordExport';
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // hide rows/data that payrollPeriod has softDeleted
        $this->crud->query->has('payrollPeriod');

        $this->showColumns();
        $this->showEmployeeNameColumn();
        $this->showRelationshipColumn('payroll_period_id');
        $this->renameLabelColumn('ut', 'UT');
        $this->renameLabelColumn('ot', 'OT');

        // when employee column order is active , add this order too
        $this->addOrderInEmployeeNameColumn('date');

        $this->filters();

        // TODO::
        $this->crud->addColumn([
            'name' => 'POT',
            'type' => 'text'
        ])->afterColumn('ot');

        $this->crud->setDefaultPageLength(25);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(DailyTimeRecordRequest::class);
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();
    }

    private function filters()
    {
        $this->dateRangeFilter('date');
        $this->select2FromArrayFilter(
            'payroll_period_id',
            $this->fetchPayrollPeriodCollection()->sort()->pluck('name', 'id')->toArray()
        );
    }
}
// TODO:: disable order in these columns: Reg Hour, late, UT, OT, POT
// TODO:: shift_schedule TBD dont create column instead display custom col in list base on employee and shift date
// TODO:: dtr logs TBD no migration column only custom display col in list
// TODO:: leave TBD no migration column only custom display col in list

// TODO:: reg hour varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
// TODO:: late varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
// TODO:: UT varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
// TODO:: OT varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)

// TODO:: POT hh:mm, no migration col, custom col display in list

// TODO:: TBD add tooltip/title to shift column when row is hover and display all shit_schedule details.
// TODO:: add tooltip/title to other column rows.
// TODO:: https://github.com/winex01/hris/issues/176
