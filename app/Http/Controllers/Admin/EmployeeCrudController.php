<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeCrudController extends CrudController
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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee');
        CRUD::setEntityNameStrings(
            \Str::singular(trans('lang.employee')), 
            \Str::plural(trans('lang.employee')), 
        );

        $this->userPermissions('employees');
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
        $this->downloadableAttachment();
        $this->showEmployeeNameColumn();
    }

    protected function setupShowOperation()
    {
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
        CRUD::setValidation(EmployeeCreateRequest::class);

        $this->inputFields();
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

    private function inputFields()
    {
        foreach (getTableColumnsWithDataType('employees') as $col => $dataType) {
            $this->crud->addField([
                'name'        => $col,
                'label'       => ucwords(str_replace('_', ' ', $col)),
                'type'        => $this->fieldTypes()[$dataType],
                'tab'         => trans('lang.personal_data'),
            ]);
        }// end foreach

        // contacts
        foreach ([
            'mobile_number',
            'telephone_number',
            'company_email',
            'personal_email',
        ] as $col) {
            $this->crud->modifyField($col, [
                'tab' => trans('lang.contacts')
            ]);
        }

        // government 
        foreach ([
            'pagibig',
            'sss',
            'philhealth',
            'tin',
        ] as $col) {
            $this->crud->modifyField($col, [
                'tab' => trans('lang.government_info')
            ]);
        }

        // photo
        $this->crud->modifyField('photo', [
            'label' => trans('lang.photo'),
            'type' => 'image',
            'crop' => true, 
            'aspect_ratio' => 1, 
        ]);

        // badge_id
        $this->crud->modifyField('badge_id', [
            'attributes'  => [
                'placeholder' => trans('lang.enter_employee_id')
            ]
        ]);

        // relationship
        foreach ([
            'gender_id',
            'civil_status_id',
            'citizenship_id',
            'religion_id',
            'blood_type_id',
        ] as $col) {
            $this->crud->modifyField($col, [
                'type'      => 'select2',
                'label'     => trans('lang.'.$col),
                'entity'    => relationshipMethodName($col),
                'attribute' => 'name',
                'model'     => 'App\Models\\'.ucfirst(relationshipMethodName($col))
            ]);
        }

    }

}