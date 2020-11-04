<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\CreatePersonalDataRequest;
use App\Http\Requests\StoreEmployeeRequest;
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
    use \App\Traits\RolesAndPermissionTrait;
    use \App\Traits\CrudFieldTraits;

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
        CRUD::setValidation(CreateEmployeeRequest::class);
        
        $this->addFields();


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(StoreEmployeeRequest::class);

        CRUD::setFromDb(); // fields
    }

    private function addFields()
    {
        // New Employee Tab
        $this->crud->addFields([
            $this->textField('badge_id', __('lang.new_employee')),
            $this->textField('last_name', __('lang.new_employee')),
            $this->textField('first_name', __('lang.new_employee')),
            $this->textField('middle_name', __('lang.new_employee')),
        ]);

        // Personal Data Tab
        $this->crud->addFields([
            $this->textField('address', __('lang.personal_data')),
            $this->textField('city', __('lang.personal_data')),
            $this->textField('country', __('lang.personal_data')),
            $this->textField('zip_code', __('lang.personal_data')),
            $this->dateField('birth_date', __('lang.personal_data')),
            $this->textField('birth_place', __('lang.personal_data')),
            $this->textField('mobile_number', __('lang.personal_data')),
            $this->textField('telepehone_number', __('lang.personal_data')),
            $this->textField('company_email', __('lang.personal_data')),
            $this->textField('personal_email', __('lang.personal_data')),
            $this->textField('pagibig', __('lang.personal_data')),
            $this->textField('sss', __('lang.personal_data')),
            $this->textField('philhealth', __('lang.personal_data')),
            $this->textField('tin', __('lang.personal_data')),
        ]);
        /*
           TODO::

            gender
            civil status
            citizenship
            religion
            blood type
            
            date applied
            date hired

            employee
        */
    }
}
