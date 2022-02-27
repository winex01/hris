<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\Widget;
use App\Http\Requests\LeaveApplicationCreateRequest;
use App\Http\Requests\LeaveApplicationUpdateRequest;
use App\Models\LeaveApplication;
use App\Models\LeaveCredit;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Operations\StatusOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchLeaveTypeTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Operations\EmployeeFieldOnChangeOperation;

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

        $this->crud->modifyColumn('approvers', [
            'columns' => [
                'employee_name' => '',
            ] 
        ]);
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

        // TODO:: for approvers auto fill
        // $this->addSelectEmployeeField()->modifyField('employee_id', 
        //     // NOTE:: employee field on change
        //     $this->employeeFieldOnChangeOperationType()
        // ); 

        $this->addSelectEmployeeField();

        $this->addInlineCreateField('leave_type_id');
        $this->addSelectFromArrayField('credit_unit', $this->creditUnitLists());
    
        // disable / remove this field in create
        $this->crud->removeFields([
            'approved_level',
            'status',
        ]);

        $this->addAttachmentField();
        
        $this->crud->modifyField('approvers', [
            'name'  => 'approvers',
            'label' => 'Leave Approvers',
            'type'  => 'repeatable',
            'hint'  => 'Optional', // TODO:: TBD add link here to leave approver
            'fields' => [
                [
                    'name'        => 'employee_id', 
                    'label'       => convertColumnToHumanReadable('employee_id'),
                    'type'        => 'select2_from_array',
                    'options'     => employeeLists(),
                    'allows_null' => true,
                    'placeholder' => trans('lang.select_placeholder'), 
                ]
            ],
        
            // optional
            'new_item_label'  => 'Add Leave Approver', // customize the text of the button
            'min_rows'        => 0,    
        ]);
    }

    public function creditUnitLists()
    {
        return [
            '1' => 'Whole Day (1)',
            '.5' => 'Half Day (.5)', // i use text index. so it will not convert .5 to 0(zero) when save
        ];
    }

    /**
     * Show the view for performing the operation.
     * 
     * override from StatusOperation
     *
     * @return Response
     */
    public function status($id)
    {
        $this->crud->hasAccessOrFail('status');
        
        $id = $this->crud->getCurrentEntryId() ?? $id; // leaveAppId
        $newLeaveAppStatus = request()->status;

        // VALIDATE:: only accept this 3 values
        if (!in_array($newLeaveAppStatus, [0,1,2])) { // pending, approved, denied
            return;
        }

        // debug(request()->all());

        $leaveApp = modelInstance('LeaveApplication')->findOrFail($id);
        $leaveCredit = modelInstance('LeaveCredit')
                ->where('employee_id', $leaveApp->employee_id)
                ->where('leave_type_id', $leaveApp->leave_type_id)
                ->firstOrFail();

        $newLeaveCredit = $this->leaveCreditDiff($leaveApp, $leaveCredit);

        // VALIDATE:: duplicate leave approved on this date
        $duplicate = modelInstance('LeaveApplication')
                    ->where('employee_id', $leaveApp->employee_id)
                    ->where('date', $leaveApp->date)
                    ->approved()
                    ->first();
        
        if ($newLeaveAppStatus == 1 && $duplicate) { // duplicate entry for same date 
            $result['validationFail'] = true;
            $result['validationMsgText'] = trans('lang.leave_applications_employee_unique'); 
            return $result;
        }

        // VALIDATE:: if employee leave credit is less than 0
        if ($newLeaveCredit < 0) {
            $result['validationFail'] = true;
            $result['validationMsgText'] = trans('lang.leave_applications_leave_credits_required'); 
            return $result;
        }

        //validation success
        $leaveApp->status = $newLeaveAppStatus;
        $leaveApp->save();

        // if the same leave credit, meaning the employee didn't change it's status from prev. value
        // this is only to make sure if ever the frontent button hide/button is breach
        if ($newLeaveCredit != $leaveCredit->leave_credit) {
            $leaveCredit->leave_credit = $newLeaveCredit;
            $leaveCredit->save();
        }

        return true;
    }

    private function leaveCreditDiff(LeaveApplication $leaveApp, LeaveCredit $leaveCredit)
    {
        $currentLeaveAppStatus = $leaveApp->status;
        $newLeaveCredit = $leaveCredit->leave_credit;
        $newLeaveAppStatus = request()->status;

        if ($newLeaveAppStatus == 1) { // approved
            if ($currentLeaveAppStatus != 1) { // if currentLeaveAppStatus is not approved
                // then deduct employee leave credit regardless of currentLeaveAppStatus value
                $newLeaveCredit = $leaveCredit->leave_credit - $leaveApp->credit_unit;
            }                    
        }elseif ($newLeaveAppStatus == 2) { // denied
            if ($currentLeaveAppStatus == 1) { // if currentLeaveAppStatus is approved
                // then add employee leave credit
                $newLeaveCredit = $leaveCredit->leave_credit + $leaveApp->credit_unit;
            }
        }else {
            // do nothing :)
        }

        return $newLeaveCredit;
    }

    /**
     * Overrided from StatusOperation
     */
    private function addButtonFromViewStatusOperation() 
    {
        $this->crud->addButtonFromView('line', 'status', 'leave_applications.custom_status', 'beginning');
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

    // override use this export file class instead of BaseExport
    private function setExportClass()
    {
        return '\App\Exports\LeaveApplicationExport';
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

    /**
     * Delete multiple entries in one go.
     * Prohibit user from deleting items if status != pending
     * 
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('bulkDelete');

        $entries = request()->input('entries', []);
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {
                if ($entry->status == 0) { // allow only delete if status is pending
                    $deletedEntries[] = $entry->delete();
                }else {
                    $deletedEntries = false;
                }
            }
        }

        return $deletedEntries;
    }

    /**
     * Show the view for performing the operation.
     * * Prohibit user from deleting items if status != pending
     *
     * @return Response
     */
    public function forceBulkDelete()
    {
        $this->crud->hasAccessOrFail('forceBulkDelete');

        $entries = request()->input('entries');
        $returnEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model::findOrFail($id)) {
                if ($entry->status == 0) { // allow only delete if status is pending
                    $returnEntries[] = $entry->forceDelete();
                }else {
                    $returnEntries = false;
                }
            }
        }

        return $returnEntries;
    }
}

// TODO:: refactor remove approvers repeatable column and use relationship to leave approvers crud
// TODO:: TBD search logic approvers column
// TODO:: TBD what to do in approved level

// TODO:: fix approvers export column

// TODO:: fix export column sort status, check employment info FIELD order
// TODO:: check export, check column sort, check column search

// TODO:: TBD create bulk create beside add leave app buttons / TBD: study repeatable if i can wrap all it in repeatable

// TODO:: TBD add payrol period filter
// TODO:: TBD make sure to hide or show only items that greather than first date of open payroll
// TODO:: or hide line buttons for items that is lessthan the first date of open payrolls TBD
