<?php

namespace App\Http\Controllers\Admin;

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
        $this->showColumns();
        $this->showEmployeeNameColumn();
        $this->showRelationshipColumn('leave_type_id');
        $this->addColumnTitle('leave_type_id');

        $this->convertColumnToDouble('credit_unit', 1);
        $this->addColumnTitle('credit_unit', null, null, [
            1 => 'Whole Day',
            .5 => 'Half Day',
        ]);

        $this->showColumnClosure('status', 'statusBadge');
        $this->downloadableAttachment();

        // Approvers Column
        $this->crud->modifyColumn('approved_level', [
            'label' => 'Approvers',
            'type' => 'closure',
            'function' => function($entry) {
                // debug($entry->approvers()->orderBy('level')->get());
                $lists = '';
                foreach ($entry->approvers()->orderBy('level', 'asc')->get() as $app){
                    $prefix = '';
                    $suffix = '';

                    if ($app->level <= $entry->approved_level) {
                        $prefix = '<s>';
                        $suffix = '</s>';
                    }

                    $lists .= $prefix.$app->approver->full_name_with_badge. $suffix . "<br>";                     
                }
                return $lists;
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
        
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $newLeaveAppStatus = request()->status;

        // validate only accept this 3 values
        if (!in_array($newLeaveAppStatus, [0,1,2])) { // pending, approved, denied
            return;
        }

        // debug(request()->all());

        if ($id) {
            $leaveApp = modelInstance('LeaveApplication')->findOrFail($leaveAppId);
            $leaveCredit = modelInstance('LeaveCredit')
                ->where('employee_id', $leaveApp->employee_id)
                ->where('leave_type_id', $leaveApp->leave_type_id)
                ->first();

            // if emplyoee has leave credit of that leave_type_id
            if ($leaveCredit != null) {
                $currentLeaveAppStatus = $leaveApp->status;

                // TODO:: refactor above codes and delete afterwards
                if ($newLeaveAppStatus == 1 && $currentLeaveAppStatus != 1) { // if newStatus is approved and prevCurrent is not approved
                    // then deduct employee leave credit regardless of currentLeaveAppStatus value
                }elseif ($newLeaveAppStatus == 2 && $currentLeaveAppStatus != 2) { // if newStatus is denied and prevCurrentStatus is not denied
                    // if currentLeaveAppStatus is pending then don't deduct leave credit
                    // if currentLeaveAppStatus is approved then add employee leave credit
                    // TODO:: HERE NAKU!!!!!!!!!!!!
                }else {
                    // do nothing :)
                }
            }
            




            // $item = classInstance($this->setModelStatusOperation())->findOrFail($id);
            // $remainingEmpCredit = $employeeLeaveApp->leave_credit - $item->credit_unit;


            // validaiton fail if employee dont have enough leave credit && status is approved(1)
            // if ($remainingEmpCredit < 0 && $status == 1) {
            //     $result['validationFail'] = true;
            //     $result['validationMsgText'] = trans('lang.leave_applications_leave_credits_required'); 
            //     return $result;
            // } else { // validation success
            //     $item->status = $status;
            //     $result = $item->save();
                
            //     if ($result) {
            //       // deduct employee leave credit / assisn $remainingEmpCredit to become the new employeeLeaveCredits
            //         // $employeeLeaveCredit->leave_credit = $remainingEmpCredit;
            //         if ($status == 1) { // approved
            //             $employeeLeaveCredit->leave_credit = $item->credit_unit;
            //         }elseif ($status == 2) { // denied

            //         }else { 
            //             // 
            //         }
            //     }

            //     return $result; // success

            // }
            
        }

        return;
    }

}

// TODO:: TBD soft delete and hard delete event deduct
// TODO:: deduct/add employee credit. when applying / deleting / soft deleting / TBD (if sa pag apply or sa pag approved ba) & also deduct when approving or denying item.
// TODO:: hide other action buttons if the status operation status is not pending

// TODO:: fix and check attachment
// TODO:: fix show op. display
// TODO:: TBD make sure to hide or show only items that greather than first date of open payroll
// TODO:: or hide line buttons for items that is lessthan the first date of open payrolls TBD
// TODO:: check permission and inline permission of leave type
// TODO:: fix status column in report
// TODO:: error approver column when sorted in leave approvers crud
// TODO:: create bulk create beside add leave app buttons