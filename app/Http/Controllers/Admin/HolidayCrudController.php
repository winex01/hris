<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HolidayRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HolidayCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HolidayCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Holiday::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/holiday');

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
        $this->showRelationshipColumn('holiday_type_id');
        $this->showRelationshipPivotColumn('locations');
        $this->select2Filter('holiday_type_id');
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
        CRUD::setValidation(HolidayRequest::class);
        $this->inputs();
        $this->addRelationshipField('holiday_type_id');
        $this->addInlineCreatePivotField('locations', 'location', 'locations_create', route('holiday.fetchLocation'));
        $this->transferFieldAfter('description', 'locations', 'textarea');
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

    /*
    |--------------------------------------------------------------------------`
    | Inline Create Fetch
    |--------------------------------------------------------------------------
    */
    public function fetchLocation()
    {
        return $this->fetch(\App\Models\Location::class);
    }
}
// TODO:: validation https://stackoverflow.com/questions/51606998/how-to-add-remove-elements-from-array-that-is-in-request
// TODO:: validation location remove null value in array
// TODO:: fix export add pivot table column export
