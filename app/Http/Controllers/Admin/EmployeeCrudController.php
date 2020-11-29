<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\PersonalData;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; edit as traitEdit; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation { bulkDelete as traitBulkDelete; }
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
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
            strSingular(__('lang.employee')), 
            strPlural(__('lang.employee')), 
        );

        $this->userPermissions('employee');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
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
        
        $this->inputs();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(EmployeeUpdateRequest::class);
        
        $this->inputs();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $emp = Employee::findOrFail($id);
        $personalData = PersonalData::where('employee_id', $id)->info()->first();

        $this->imageRow('img', $emp->img_url);
        $this->dataPreview([
            $emp,
            $personalData
        ]);

        foreach ([
            'gender', 
            'civilStatus',
            'citizenship',
            'religion',
            'bloodType',
        ] as $modelAttr) {
            if ($personalData->{$modelAttr}) {
                $this->modifyDataRow(\Str::snake($modelAttr), $personalData->{$modelAttr}->name);
            }
        }

    }

    public function store()
    {
        $response = $this->traitStore();

        $inputs = $this->crud->getStrippedSaveRequest();

        // find employee
        $employee = Employee::firstOrCreate(
            $this->formInputs(
                $inputs, 
                $employee->getTable()
            )
        );
        
        // insert personal
        $employee->personalData()->create(
            $this->formInputs(
                $inputs, 
                $employee->personalData->getTable()
            )
        );

        // insert img
        $employee->img = $inputs['img'];
        
        return $response;
    }

    public function edit($id)
    {
        $response = $this->traitEdit($id);

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $fields = $this->crud->getUpdateFields();

        $personalData = PersonalData::firstOrCreate(['employee_id' => $id]);
        
        foreach (getTableColumns('personal_datas', ['employee_id']) as $modelAttr){
            $fields[$modelAttr]['value'] = $personalData->{$modelAttr};
        }

        // img
        $emp = $personalData->employee;
        if ($emp->image) {
            $fields['img']['value'] = $emp->img_url;
        }

        // override
        $this->crud->setOperationSetting('fields', $fields);

        return $response;
    }

    public function update()
    {
        $response = $this->traitUpdate();

        $inputs = $this->crud->getStrippedSaveRequest();

        $employee = Employee::findOrFail(request()->id); 

        // update employee
        $employee->update(
            $this->formInputs(
                $inputs, 
                $employee->getTable()
            )
        );

        // update personal data
        $employee->personalData()->update(
            $this->formInputs(
                $inputs, 
                $employee->personalData->getTable()
            )
        );

        // insert img
        $employee->img = $inputs['img'];

        return $response;
    }

    private function inputs()
    {
        // Employee Name Tab
        $tabName = __('lang.employee_name');
        $this->crud->addField($this->imageField('img', $tabName));

        foreach (getTableColumnsWithDataType('employees') as $column => $dataType) {
            $this->crud->addField(
                $this->{$dataType.'Field'}($column, $tabName, [
                    'attributes' => [
                        'placeholder' => ($column == 'badge_id') ? 'Employee ID' : ''
                    ], 
                ]),
            );
        }

        // Personal Data Tab
        $tabName = __('lang.personal_data');
        foreach (getTableColumnsWithDataType('personal_datas', [
            // except this column
            'employee_id'
        ]) as $column => $dataType) {
            if ($dataType == 'bigint') {
                
                $this->crud->addField(
                    $this->select2FromArray($column, $tabName, [
                        'options' => $this->classInstance($column)->selectList()
                    ])
                );

                continue;
            }
            
            $this->crud->addField(
                $this->{$dataType.'Field'}($column, $tabName),
            );
        }

        // Emergency Contact Tab 
        $tabName = __('lang.emergency_contact');
        foreach (getTableColumnsWithDataType('contacts', [
            // except this column
            'relation', 'contactable_id', 'contactable_type'
        ]) as $column => $dataType) {
            $this->crud->addField(
                $this->{$dataType.'Field'}('emergency_contact_'.$column, $tabName, [
                    'label' => __('lang.'.$column)
                ])
            );
        }
        

        // try to use polymorphic
        // TODO:: contact info 
        // TODO:: emergency contact store, update, delete
        // TODO:: add revision 
        // TODO:: app settings seeder 
    }

}
