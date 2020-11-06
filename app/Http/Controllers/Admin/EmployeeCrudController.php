<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeStoreRequest;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
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
        CRUD::setValidation(EmployeeStoreRequest::class);
        
        $this->inputs();
    }

    public function store()
    {
        // TODO:: refactor
        $this->crud->hasAccessOrFail('create');

        $this->crud->validateRequest();

        $input = $this->crud->getStrippedSaveRequest();
        
        $employeeInputs = [
            'badge_id' => $input['badge_id'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'middle_name' => $input['middle_name'],
        ];

        $personalDataInputs = removeArrayKeys($input, $employeeInputs);

        // insert item in the db
        $employee = Employee::create($employeeInputs);
        $personalDataInputs['employee_id'] = $employee->id;
        $personalData = PersonalData::updateOrCreate($personalDataInputs);
        
        return $this->flashMessageAndRedirect($employee);
    }

    public function edit($id)
    {
        // TODO::
        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $personalData = PersonalData::where('employee_id', $id)->first();

        $fields = $this->crud->getUpdateFields();
        $employeeAttributes = getModelAttributes(new Employee);
        
        foreach ($fields as $modelAttr => $field) {
            // dont assign value or override employee fields
            if (in_array($modelAttr, $employeeAttributes)) {
                continue;
            }
            $fields[$modelAttr]['value'] = $personalData->{$modelAttr};
        }

        $this->crud->setOperationSetting('fields', $fields);
        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function update()
    {
       return $this->extendUpdate(function() {
            // update personal data table
            PersonalData::where('employee_id', request()->id)->update(
                removeArrayKeys($this->crud->getStrippedSaveRequest(), 
                    collect(getModelAttributes(new Employee))->flip()->toArray()
                )
            );
       });
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
        // TODO:: language

    }

}
