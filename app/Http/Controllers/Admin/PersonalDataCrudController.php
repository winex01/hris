<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonalDataRequest;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
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
            __('lang.personal_data'), 
            __('lang.personal_data'), 
        );

        $this->userPermissions();

        // NOTE:: add this soo modal details would not open when click employee column
        // but rather into checkbox column
        $this->crud->enableBulkActions();
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

        // $this->crud->modifyColumn('gender_id', [
        //     'label' => 'Gender',
        //     'type' => 'select2'
        // ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

}
