<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeaveApprover;
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
        $this->addColumnTitle('credit_unit', null, null, [
            1 => 'Whole Day',
            .5 => 'Half Day',
        ]);

        $this->showColumnFromArray('status', $this->statusOperationBadage());
        $this->downloadableAttachment();

        // Approvers Column
        $this->crud->modifyColumn('approved_level', [
            'label' => 'Approvers',
            'type' => 'closure',
            'function' => function($entry) {
                $lists = '';
                $temp = $entry->approvers()->date($entry->created_at_as_date)->orderBy('level', 'asc')->get();
                foreach ($temp as $app){
                    $prefix = '';
                    $suffix = '';

                    if ($app->level <= $entry->approved_level) {
                        $prefix = '<s>';
                        $suffix = '</s>';
                    }

                    $lists .= $prefix.$app->approver->full_name_with_badge. $suffix . "<br>";                     
                }
                return $lists;
            },
            'orderable' => false, // disable column sort

            // NOTE:: this searchLogic is not perfect but doable, i'ts better than nothing
            'searchLogic' => function ($query, $column, $searchTerm) {
                // 1. search $searchTerm at approvers regardless of date_effectivity without global scope date.
                $temp = LeaveApprover::withoutGlobalScope('CurrentLeaveApproverScope')
                    ->whereHas('approver', function ($q) use ($searchTerm) {
                        $q->where('last_name', 'like', '%'.$searchTerm.'%');
                        $q->orWhere('first_name', 'like', '%'.$searchTerm.'%');
                        $q->orWhere('middle_name', 'like', '%'.$searchTerm.'%');
                        $q->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                })->get(['employee_id', 'effectivity_date']);

                // debug($temp->toArray());
                
                // 2. capture all the employee ID and date effeectivity(TBD:: perhaps effectivity date not needed)
                foreach ($temp as $obj) {
                    // 3. then create whereIN clause and put the captured employeeIds in it.
                    //          or loop the array result and just add orWhere for iteration.
                    $query->orWhere(function ($q) use ($obj) {
                        $q->where('employee_id', $obj->employee_id);
                        $q->where('date', $obj->effectivity_date);
                    });
                }
            }// end searhLogic
        ]);

        // TODO:: credit_unit search logic
        
        $this->crud->modifyColumn('status', [
            // TODO:: status order
            // 'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $method, $columnId) {
            //     return $query->leftJoin('employees as '.$method, $method.'.id', '=', $currentTable.'.'.$columnId)
            //             ->orderBy($method.'.last_name', $columnDirection)
            //             ->orderBy($method.'.first_name', $columnDirection)
            //             ->orderBy($method.'.middle_name', $columnDirection)
            //             ->orderBy($method.'.badge_id', $columnDirection)
            //             ->select($currentTable.'.*');
            // },

            // status searchLogic
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('status', $this->statusOperationSearchLogic($searchTerm));
            }
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

        // validate only accept this 3 values
        if (!in_array($newLeaveAppStatus, [0,1,2])) { // pending, approved, denied
            return;
        }

        // debug(request()->all());

        if ($id) {
            $leaveApp = modelInstance('LeaveApplication')->findOrFail($id);
            $leaveCredit = modelInstance('LeaveCredit')
                ->where('employee_id', $leaveApp->employee_id)
                ->where('leave_type_id', $leaveApp->leave_type_id)
                ->first();

            // if emplyoee has leave credit of that leave_type_id
            if ($leaveCredit != null) {
                $currentLeaveAppStatus = $leaveApp->status;
                $newLeaveCredit = $leaveCredit->leave_credit;

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

                // validate employee leave credit if less than 0
                if ($newLeaveCredit < 0) {
                    // validation fails
                    $result['validationFail'] = true;
                    $result['validationMsgText'] = trans('lang.leave_applications_leave_credits_required'); 
                    return $result;
                }else {  
                    //validation success
                    $leaveApp->status = $newLeaveAppStatus;
                    $leaveApp->save();

                    // if the same leave credit, meaning the employee didn't change it's status from prev. value
                    // this is only to make sure if ever the frontent button hide/button is breach
                    if ($newLeaveCredit != $leaveCredit->leave_credit) {
                        $leaveCredit->leave_credit = $newLeaveCredit;
                        $leaveCredit->save();
                    }

                }

            }// end if ($leaveCredit != null) {
            
            
        } // end if ($id) {

        return true; // success
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
}
// TODO:: check export, column sort, column search

// TODO:: create bulk create beside add leave app buttons

// TODO:: TBD add payrol period filter
// TODO:: TBD make sure to hide or show only items that greather than first date of open payroll
// TODO:: or hide line buttons for items that is lessthan the first date of open payrolls TBD


