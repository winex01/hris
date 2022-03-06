<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Http\Requests\LeaveApproverCreateRequest;
use App\Http\Requests\LeaveApproverUpdateRequest;
use App\Models\LeaveApprover;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LeaveApproverCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LeaveApproverCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \App\Http\Controllers\Admin\Operations\LeaveApprover\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\LeaveApprover\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\LeaveApprover\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Operations\UpdateISCreateOperation;
    
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\LeaveApprover::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/leaveapprover');
        
        $this->userPermissions();

        $this->exportClass = '\App\Exports\LeaveApproverExport';
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

        $this->crud->modifyColumn('approvers', [
            'type'     => 'closure',
            'function' => function($entry) {
                return jsonToArrayImplode($entry->approvers, 'employee_name');
            },
        ]);

        $this->filter();
        $this->searchLogic();

        $this->disableSortColumn('approvers');
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
        CRUD::setValidation(LeaveApproverCreateRequest::class);
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
        CRUD::setValidation(LeaveApproverUpdateRequest::class);
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();

        $this->crud->modifyField('approvers', [
            'label' => 'Leave Approvers',
            'type'  => 'repeatable',
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

    /**
     * @UpdateOperation
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return $this->performCreateInsteadOfUpdate();
    }

    private function filter()
    {
        $this->select2MultipleFromArrayFilter(
            'add_scope_json_params_approversEmployeeId', // name & method & scope 
            employeeLists(), // options
            'approvers' // label
        );

        $this->dateRangeFilter('effectivity_date');

        // display history 
        $this->removeGlobalScopeFilter('CurrentLeaveApproverScope');
    }

    private function searchLogic()
    {
        $this->crud->modifyColumn('approvers', [
            'searchLogic' => function ($query, $column, $searchTerm) {
                
                $employeeIds = modelInstance('Employee')->searchEmployeeNameLike($searchTerm)->pluck('id')->all();
                
                if ($employeeIds) {
                    $leaveApproversId = modelInstance('LeaveApprover')
                    ->withoutGlobalScope('CurrentLeaveApproverScope')                    
                    ->approversEmployeeId($employeeIds)->pluck('id')->all();
                    
                    if ($leaveApproversId) {
                        $query->orWhereIn($this->crud->model->getTable().'.id', $leaveApproversId);
                    }
                }
               
            }
        ]);
    }
}