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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\CrudExtendTrait;

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
        // TODO
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

    public function store()
    {
        return $this->extendStore(function() {
            $inputs = $this->crud->getStrippedSaveRequest();

            // insert employee
            $employee = Employee::create(
                getOnlyAttributesFrom($inputs, new Employee)
            );
            // insert personal
            $employee->personalData()->create(
                getOnlyAttributesFrom($inputs, new PersonalData)
            );

            return $employee;
        });
    }

    public function edit($id)
    {
        return $this->extendEdit($id, function() use ($id) {
            $id = $this->crud->getCurrentEntryId() ?? $id;
            $personalData = PersonalData::firstOrCreate(['employee_id' => $id]);

            $fields = $this->crud->getUpdateFields();
            
           foreach (collectOnlyModelAttributes($fields, new PersonalData) as $modelAttr => $value){
                if ($modelAttr == 'id') { continue; } # do not override ID bec. its diff model
                $fields[$modelAttr]['value'] = $personalData->{$modelAttr};
            }

            // override
            $this->crud->setOperationSetting('fields', $fields);
        });
    }

    public function update()
    {
        $response = $this->traitUpdate();

        $inputs = $this->crud->getStrippedSaveRequest();
        $id = request()->id;

        $employee = Employee::findOrFail($id); 

        // update employee
        $employee->update(
            getOnlyAttributesFrom($inputs, new Employee)
        );

        // update personal data
        $employee->personalData()->update(
            getOnlyAttributesFrom($inputs, new PersonalData)
        );

        return $response;
    }

    private function inputs()
    {
        // Employee Name Tab
        $tabName = __('lang.employee_name');
        $this->crud->addFields([
            $this->textField('badge_id', $tabName, [
                'attributes' => ['placeholder' => 'Employee ID'], 
            ]),
            $this->textField('last_name', $tabName),
            $this->textField('first_name', $tabName),
            $this->textField('middle_name', $tabName),
        ]);

        // Personal Data Tab
        $tabName = __('lang.personal_data');
        $this->crud->addFields([
            $this->textField('address', $tabName),
            $this->textField('city', $tabName),
            $this->textField('country', $tabName),
            $this->textField('zip_code', $tabName),
            $this->dateField('birth_date', $tabName),
            $this->textField('birth_place', $tabName),
            $this->textField('mobile_number', $tabName),
            $this->textField('telephone_number', $tabName),
            $this->textField('company_email', $tabName),
            $this->textField('personal_email', $tabName),
            $this->textField('pagibig', $tabName),
            $this->textField('sss', $tabName),
            $this->textField('philhealth', $tabName),
            $this->textField('tin', $tabName),
            
            $this->select2FromArray('gender_id', function () {
                return \App\Models\Gender::all()->pluck('name', 'id')->toArray();
            }, $tabName),
            
            $this->select2FromArray('civil_status_id', function () {
                return \App\Models\CivilStatus::all()->pluck('name', 'id')->toArray();
            }, $tabName),

            $this->select2FromArray('citizenship_id', function () {
                return \App\Models\Citizenship::all()->pluck('name', 'id')->toArray();
            }, $tabName),

            $this->select2FromArray('religion_id', function () {
                return \App\Models\Religion::all()->pluck('name', 'id')->toArray();
            }, $tabName),

            $this->select2FromArray('blood_type_id', function () {
                return \App\Models\BloodType::all()->pluck('name', 'id')->toArray();
            }, $tabName),

            $this->dateField('date_applied', $tabName),
            $this->dateField('date_hired', $tabName),
        ]);

        // TODO:: try to use polymorphic5
        // spouse info
        // fathers info
        // mothers info
        // contacts 
        // TODO:: add show or preview display all

    }

}
