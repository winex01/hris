<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonalDataCreateRequest;
use App\Http\Requests\PersonalDataUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PersonalDataCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonalDataCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PersonalData::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/personaldata');
        CRUD::setEntityNameStrings(
            strSingular(__('lang.personal_data')), 
            strPlural(__('lang.personal_data')), 
        );

        $this->userPermissions('personal_data');

        // remove create button
        $this->crud->denyAccess('create');
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
        CRUD::setValidation(PersonalDataCreateRequest::class);


       
        
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
        CRUD::setValidation(PersonalDataUpdateRequest::class);

        $this->crud->addFields(
            $this->inputs()
        );
    }

    public function inputs()
    {
        // Personal Data Tab
        $tabName = __('lang.personal_data');
        return [
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
            
            $this->select2FromArray('gender_id', $tabName, [
                'options' => \App\Models\Gender::selectList()
            ]),
            
            $this->select2FromArray('civil_status_id', $tabName, [
                'options' => \App\Models\CivilStatus::selectList()
            ]),

            $this->select2FromArray('citizenship_id', $tabName, [
                'options' => \App\Models\Citizenship::selectList()
            ]),

            $this->select2FromArray('religion_id', $tabName, [
                'options' => \App\Models\Religion::selectList()
            ]),

            $this->select2FromArray('blood_type_id', $tabName, [
                'options' => \App\Models\BloodType::selectList()
            ]),

            $this->dateField('date_applied', $tabName),
            $this->dateField('date_hired', $tabName),
        ];
    }
}
