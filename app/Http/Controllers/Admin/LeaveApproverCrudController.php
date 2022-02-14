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

        // $this->showRelationshipColumn('approver_id'); // TODO:: fix search error
        $columnId = 'approver_id';
        $relationshipColumn = 'name';
        $col = str_replace('_id', '', $columnId);
        $method = relationshipMethodName($col);
        $currentTable = $this->crud->model->getTable();

        $this->crud->modifyColumn($columnId, [
            'label' => convertColumnToHumanReadable($col),
            'type'     => 'closure',
            'function' => function($entry) use ($method, $relationshipColumn) {
                if ($entry->{$method} == null) {
                    return;
                }
                return $entry->{$method}->{$relationshipColumn};
            },
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) use ($columnId) {
                    return employeeInListsLinkUrl($entry->{$columnId});
                },
                'class' => trans('lang.link_color')
            ],
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $method) {
                // TODO:: fix this, use approver instead of employee TBD
                return $query->leftJoin('employees', 'employees.id', '=', $currentTable.'.employee_id')
                        ->orderBy('employees.last_name', $columnDirection)
                        ->orderBy('employees.first_name', $columnDirection)
                        ->orderBy('employees.middle_name', $columnDirection)
                        ->orderBy('employees.badge_id', $columnDirection)
                        ->select($currentTable.'.*');
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->filters();
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

        // leave approver
        $this->addRelationshipField('approver_id');
        $this->crud->modifyField('approver_id', [
            'hint'          => trans('lang.leave_approvers_approver_id_hint'), 
            'attribute'     => 'full_name_with_badge',
            'model'         => 'App\Models\Employee',
            'inline_create' => null
        ]);

        $this->addSelectFromArrayField('level', 
            explodeStringAndStartWithIndexOne(',', config('appsettings.approver_level_lists'))
        );
        $this->addHintField('level', trans('lang.leave_approvers_level_hint')); 
    }

    /**
     * NOTE:: instead of update, i store new items 
     *
     * @return Response
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        
        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    private function filters()
    {
        // display history 
        $this->removeGlobalScopeFilter('CurrentLeaveApproverScope');
    }
}

// TODO:: check leave approver datatable search error
// TODO:: check export
// TODO:: check data table column search