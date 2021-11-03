<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LeaveApplicationRequest;
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

        // show column title employee name if exist or user name if not
        $this->showRelationshipColumn('created_by_id');
        $this->crud->modifyColumn('created_by_id', [
            'wrapper'   => [
                'span' => function ($crud, $column, $entry, $related_key) {
                    return $entry->created_by_id;
                },
                'title' => function ($crud, $column, $entry, $related_key) {
                    if ($entry->createdBy->employee_id) { // if user has employee attach
                        return $entry->createdBy->employee->full_name_with_badge;
                    }

                    return $entry->createdBy->name;
                },
                'class' => trans('lang.column_title_text_color')
            ],
        ]); 

        $this->showColumnClosure('status', 'statusBadge');
        $this->downloadableAttachment();
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
        CRUD::setValidation(LeaveApplicationRequest::class);
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
        $this->setupCreateOperation();
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();
        $this->addInlineCreateField('leave_type_id');
        $this->addSelectFromArrayField('credit_unit', $this->creditUnitLists());
        
        // disable / remove this field in create
        $this->crud->removeFields([
            'last_approved_by_id',
            'approved_level',
            'status',
            'created_by_id',
        ]);

        $this->addAttachmentField();
    }

    public function store()
    {
        // add hidden field 
        $this->addHiddenField('created_by_id', user()->id);

        return $this->traitStore();
    }

    private function creditUnitLists()
    {
        return [
            '1' => 'Whole Day (1)',
            '.5' => 'Half Day (.5)', // i use text index. so it will not convert .5 to 0(zero) when save
        ];
    }
}

// TODO:: create an operation for last_approved_by (or create operation to approved status to approved)
// TODO:: deduct employee credit, add employee credit when deleted / soft deleted
// TODO:: fix and check attachment
// TODO:: fix show op. display
// TODO:: validation request (v.r for credit unit should only accept 1 and .5)
// TODO:: add validition request if employee still has leave credit
// TODO:: add to validation employee can only request 1 leave for a day
// TODO:: check permission and inline permission of leave type
// TODO:: fix status column in report
