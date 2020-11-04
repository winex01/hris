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
        

        // New Employee Tab
        $this->crud->addFields([
            [
                'name' => 'badge_id',
                'label' => __('lang.badge_id'),
                'type' => 'text',
                'tab' => 'New Employee',
            ],
            [
                'name' => 'last_name',
                'label' => __('lang.name_last'),
                'type' => 'text',
                'tab' => 'New Employee',
            ],
            [
                'name' => 'first_name',
                'label' => __('lang.name_first'),
                'type' => 'text',
                'tab' => 'New Employee',
            ],
            [
                'name' => 'middle_name',
                'label' => __('lang.name_middle'),
                'type' => 'text',
                'tab' => 'New Employee',
            ],
        ]);

        
        // Personal Data Tab
        // TODO::
        $this->crud->addFields([
            [
                'name' => 'address',
                'label' => __('lang.address'),
                'type' => 'text',
                'tab' => __('lang.personal_data'),
            ],
            [
                'name' => 'city',
                'label' => __('lang.city'),
                'type' => 'text',
                'tab' => __('lang.personal_data'),
            ],
            [
                'name' => 'country',
                'label' => __('lang.country'),
                'type' => 'text',
                'tab' => __('lang.personal_data'),
            ],
            [
                'name' => 'zip_code',
                'label' => __('lang.zip_code'),
                // 'type' => 'number',
                'tab' => __('lang.personal_data'),
            ],
           
        ]);
        /*
            
            birth date
            birth place

            mobile #
            tel #

            company email
            personal email
            
            pagibig
            sss
            philhealth 
            TIN


            gender
            civil status
            citizenship
            religion
            blood type
            
            date applied
            date hired

            employee
        */

        CRUD::setFromDb(); // fields

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
}
