<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\Widget;
use App\Http\Requests\LeaveApplicationCreateRequest;
use App\Http\Requests\LeaveApplicationUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LeaveApplicationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LeaveApplicationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\LeaveApplication\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\LeaveApplication\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Operations\LeaveApplication\StatusOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchLeaveTypeTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\LeaveApplication::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/leaveapplication');

        $this->userPermissions();

        $this->exportClass = '\App\Exports\LeaveApplicationExport';
        $this->statusButton = 'leave_applications.custom_status';
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {   
        $this->filters();
        $this->widgets();
        $this->showColumns();
        $this->showEmployeeNameColumn();
        $this->showRelationshipColumn('leave_type_id');
        $this->addColumnTitle('leave_type_id');

        $this->convertColumnToDouble('credit_unit', 1);
        $this->addColumnTitle('credit_unit', null, null, [ // title will show when hover
            1 => 'Whole Day',
            .5 => 'Half Day',
        ]);

        $this->showColumnFromArray('status', $this->statusOperationBadage());
        $this->downloadableAttachment();

        // all search business logic here
        $this->searchLogic();
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
        CRUD::setValidation(LeaveApplicationCreateRequest::class);
        $this->customInputs();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(LeaveApplicationUpdateRequest::class);
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();

        $this->addSelectEmployeeField();

        $this->addInlineCreateField('leave_type_id');
        $this->addSelectFromArrayField('credit_unit', $this->creditUnitLists());
    
        // disable / remove this field in create
        $this->crud->removeFields([
            'approved_level',
            'status',
        ]);

        $this->addAttachmentField();
    }

    public function creditUnitLists()
    {
        return [
            '1' => 'Whole Day (1)',
            '.5' => 'Half Day (.5)', // i use text index. so it will not convert .5 to 0(zero) when save
        ];
    }

    private function widgets()
    {
        Widget::add([
            'type'         => 'alert',
            'class'        => 'alert alert-light mb-2 text-info',
            'content'      => trans('lang.leave_applications_note'),
            // 'close_button' => true, // show close button or not
        ]);
    }

    private function filters()
    {
        $this->select2Filter('leave_type_id');
        $this->dateRangeFilter('date', 'Date');
        $this->select2FromArrayFilter('credit_unit', $this->creditUnitLists());
        $this->select2FromArrayFilter('status', $this->statusOperationOptions());
    }

    private function searchLogic()
    {
        // credit_unit search logic
        $this->crud->modifyColumn('credit_unit', [
            'searchLogic' => function ($query, $column, $searchTerm) {
                $searchTerm = strtolower($searchTerm);
                $value = null;

                foreach ($this->creditUnitLists() as $key => $temp) {
                    if ( str_contains(strtolower($temp), $searchTerm) ) {
                        $value = $key;
                    }else if (is_numeric($searchTerm)) {
                        $value = $searchTerm;
                    }
                }

                $query->orWhere('credit_unit', $value);
            }
        ]);

        $this->crud->modifyColumn('status', [
            // status order
            'orderLogic' => function ($query, $column, $columnDirection) {
                $query->orderByRaw($this->statusOperationOrderLogic($columnDirection));
            },
            // status searchLogic
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('status', $this->statusOperationSearchLogic($searchTerm));
            }
        ]);
    }
}

// TODO:: TBD search logic approvers column
// TODO:: TBD what to do in approved level

// TODO:: fix approvers export column

// TODO:: fix export column sort status, check employment info FIELD order
// TODO:: check export, check column sort, check column search

// TODO:: TBD create bulk create beside add leave app buttons / TBD: study repeatable if i can wrap all it in repeatable

// TODO:: TBD add payrol period filter
// TODO:: TBD make sure to hide or show only items that greather than first date of open payroll
// TODO:: or hide line buttons for items that is lessthan the first date of open payrolls TBD
