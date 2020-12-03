<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuditTrailRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuditTrailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuditTrailCrudController extends CrudController
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
        CRUD::setModel(\App\Models\AuditTrail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/audittrail');
        CRUD::setEntityNameStrings(
            \Str::singular(__('lang.audit_trail')), 
            \Str::plural(__('lang.audit_trail')), 
        );

        $this->userPermissions('audit_trail');
    
        $this->crud->denyAccess('show');        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setColumns([
            [
                'label'     => __('lang.change_by'), 
                'name'      => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
            ],
            [
                'name'  => 'key',
                'label' => __('lang.column'),
                'type'  => 'text',
            ],
            [
                'name'  => 'old_value',
                'label' => __('lang.old_value'),
                'type'  => 'text',
            ],
            [
                'name'  => 'new_value',
                'label' => __('lang.new_value'),
                'type'  => 'text',
            ],
            [
                'name'  => 'revisionable_type',
                'label' => __('lang.revisionable_type'),
                'type'  => 'text',
            ],
            [
                'name'  => 'revisionable_id',
                'label' => __('lang.revisionable'),
                'type'  => 'text',
            ],
        ]);

        // filter
        $this->crud->addFilter(
            [
                'name'  => 'user',
                'type'  => 'select2',
                'label' => __('lang.filter_user'),
            ],
            \App\Models\User::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'user', function ($query) use ($value) {
                    $query->where('user_id', '=', $value);
                });
            }
        );
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AuditTrailRequest::class);

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
        CRUD::setValidation(AuditTrailRequest::class);

        CRUD::setFromDb(); // fields
    }

}
