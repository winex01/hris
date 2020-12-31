<?php

namespace App\Http\Controllers\Admin;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Person::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/familydata');
        CRUD::setEntityNameStrings(
            __('lang.family_data'), 
            __('lang.family_data'), 
        );

        $this->userPermissions('family_datas');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $currentTable = $this->crud->model->getTable();

        $this->crud->addColumn([
            'name'     => 'employee',
            'label'    => 'Employee'.trans('lang.unsearchable_column'),
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->personable->full_name_with_badge;
            },
            'wrapper'  => [
            'href'     => function ($crud, $column, $entry, $related_key) {
                    $crud = str_replace('App\Models\\', '', $entry->personable_type);
                    $crud = strtolower($crud);
                    return backpack_url($crud.'?id='.$entry->personable_id);
                },
            ],
            'orderable' => true,
            'orderLogic' => function ($query, $column, $column_direction) use ($currentTable) {
                return $query->join('employees', 'employees.id', '=', $currentTable.'.personable_id')
                    ->orderBy('employees.last_name', $column_direction)
                    ->orderBy('employees.first_name', $column_direction)
                    ->orderBy('employees.middle_name', $column_direction)
                    ->orderBy('employees.badge_id', $column_direction);
            } 
        ]);
        $this->showColumns();
    }

}
