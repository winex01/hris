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
    use \App\Http\Controllers\Admin\Operations\LeaveApplication\EmployeeOnChangeOperation;

    
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
        $this->showColumns();
        $this->filters();
        $this->widgets();
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

        // display approvers column
        $this->crud->modifyColumn('leave_approver_id', [
            'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->leave_approver_id) {
                    return jsonToArrayImplode($entry->leaveApprover->approvers, 'employee_name');
                }
            },
            'orderable' => false,
            'searchLogic' => function ($query, $column, $searchTerm) {
                
                $employeeIds = modelInstance('Employee')->searchEmployeeNameLike($searchTerm)->pluck('id')->all();
                
                if ($employeeIds) {
                    $leaveApproversId = modelInstance('LeaveApprover')
                    ->withoutGlobalScope('CurrentLeaveApproverScope')                    
                    ->approversEmployeeId($employeeIds)->pluck('id')->all();

                    if ($leaveApproversId) {
                        $query->orWhereIn('leave_approver_id', $leaveApproversId);
                    }
                }
               
            }
        ]);

        $this->removeColumn('approved_approvers');
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

        $this->addSelectEmployeeField()->modifyField('employee_id', [
            'type' => 'leave_applications.custom_employee_on_change'
        ]);

        $this->addInlineCreateField('leave_type_id');
        $this->addSelectFromArrayField('credit_unit', $this->creditUnitLists());
    
        // disable / remove this field in create
        $this->crud->removeFields([
            'approved_approvers',
            'status',
        ]);

        // hide leave_approver_id in create
        $this->crud->modifyField('leave_approver_id', [
            'type' => 'hidden'
        ]);

        $this->addAttachmentField();

        // leave approvers textbox
        $this->crud->addField([
            'name'  => 'leave_approvers_paragraph',
            'type'  => 'custom_html',
            'value' => '<label>Leave Approvers</label><p id="leave_approvers_paragraph"></p>'
        ]);
    }

    public function creditUnitLists()
    {
        return creditUnitLists();
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

        $this->crud->addFilter([
            'name'  => 'select2_multiple_approvers',
            'type'  => 'select2_multiple',
            'label' => 'Approvers'
          ], 
          employeeLists(),
          function($values) { // if the filter is active
            $values = json_decode($values);
            $this->crud->query = $this->crud->query->whereHas('leaveApprover', function ($query) use ($values) {
                $query->approversEmployeeId($values);
            });
          });
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
                $query->orderByStatus($columnDirection);
            },
            // status searchLogic
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('status', $this->statusOperationSearchLogic($searchTerm));
            }
        ]);
    }
}

// TODO:: TBD create column(payroll_id) nullable to determined if leaveAPp is already use, and hide line buttons if it's not null
// TODO:: TBD create payroll filter base on payroll_id column above
// TODO:: put strikethrough in approvers that employee_ids exist in approved_approvers
