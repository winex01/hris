<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FamilyDataRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FamilyDataCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FamilyDataCrudController extends CrudController
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
        CRUD::setModel(\App\Models\FamilyData::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/familydata');
        CRUD::setEntityNameStrings(
            \Str::singular(__('lang.family_data')), 
            \Str::plural(__('lang.family_data')), 
        );

        $this->userPermissions();

        // TODO:: add relation to family datas, family_relation
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
        // TODO:: change this if this PR is accepted: https://github.com/Laravel-Backpack/CRUD/pull/3398
        $this->showEmployeeNameColumnUnsortable();
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
        CRUD::setValidation(FamilyDataRequest::class);

        $columns = getTableColumnsWithDataType(
            $this->crud->model->getTable()
        );

        foreach ($columns as $col => $dataType) {
            $placeholder = ($col == 'relation') ? 'Enter the relation, ex: Father, Mother, Contact or etc.' : null;
            $this->crud->addField([
                'name'        => $col,
                'label'       => ucwords(str_replace('_', ' ', $col)),
                'type'        => $this->fieldTypes()[$dataType],
                'attributes'  => [
                    'placeholder' => $placeholder,
                ]
            ]);
        }

        $this->addSelectEmployeeField();
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

}
