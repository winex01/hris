<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EducationalBackgroundRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EducationalBackgroundCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EducationalBackgroundCrudController extends CrudController
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FetchModelTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\EducationalBackground::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/educationalbackground');
        CRUD::setEntityNameStrings(
            \Str::singular(__('lang.educational_background')), 
            \Str::plural(__('lang.educational_background')), 
        );

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
        $this->showEmployeeNameColumnUnsortable();

        // TODO:: create helper
        $this->crud->modifyColumn('educational_level_id', [
           'label' => trans('lang.educational_level'),
           'type'     => 'closure',
            'function' => function($entry) {
                return $entry->educationalLevel->name;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('educationalLevel', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->downloadableAttachment();
        
        $this->appSettingsFilter('educationalLevel');
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
        CRUD::setValidation(EducationalBackgroundRequest::class);

        $this->inputs();
        $this->addSelectEmployeeField();

        $this->crud->removeField('educational_level_id');
        $this->crud->addField([
            'name'          => 'educationalLevel', 
            'label'         => trans('lang.educational_level'),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => false, 
            'inline_create' => hasAuthority('educational_levels_create') ? ['entity' => 'educationallevel'] : null
        ])->afterField('employee_id');

        $this->addAttachmentField();
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
