<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DaysPerYearRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DaysPerYearCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DaysPerYearCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\DaysPerYear::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/daysperyear');

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
        foreach (getTableColumns(
            $this->crud->model->getTable()
        ) as $col) {
            $this->crud->addColumn([
                'name'     => $col,
                'label'    => convertColumnToHumanReadable($col),
                'type'     => 'number',
                'decimals' => 2,
            ]);
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DaysPerYearRequest::class);
        $this->fieldInputs(); 
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(DaysPerYearRequest::class);
        $this->fieldInputs(); 
    }

    private function fieldInputs()
    {
        foreach (getTableColumns(
            $this->crud->model->getTable()
        ) as $col) {
            $this->crud->addField([
                'name'  => $col,
                'label' => convertColumnToHumanReadable($col),
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
            ]);
        }

        $this->downloadableHint(
            trans('lang.days_per_year_info'),
            config('appsettings.how_to_input_days_per_year_file')
        );
    }
}