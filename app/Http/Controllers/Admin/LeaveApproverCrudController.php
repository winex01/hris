<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LeaveApproverCreateRequest;
use App\Http\Requests\LeaveApproverUpdateRequest;
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
        CRUD::setModel(\App\Models\LeaveApprover::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/leaveapprover');
        
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

        $this->crud->modifyColumn('approvers', [
            'columns' => [
                'employee_name' => '',
            ],
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
}

// TODO:: for edit, make it create new instead of update it behind by overriding store method
// TODO:: create filter
// TODO:: column approvers search logic
// TODO:: check permission for admin and test account.
// TODO:: check export