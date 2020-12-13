<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GovernmentExaminationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GovernmentExaminationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GovernmentExaminationCrudController extends CrudController
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
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\GovernmentExamination::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/governmentexamination');
        CRUD::setEntityNameStrings(
            \Str::singular(__('lang.gov_exam')), 
            \Str::plural(__('lang.gov_exam')), 
        );

        $this->userPermissions('gov_exam');
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
    }

    protected function setupShowOperation()
    {
        $this->showColumns();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(GovernmentExaminationRequest::class);

        $this->inputs();
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

    private function inputs()
    {
        $columns = $this->getTableColumns();

        foreach ($columns as $col) {

            $type = 'text';

            if ($col == 'date') {
                $type = 'date';
            }else if ($col == 'venue') {
                $type = 'textarea';
            }

            $this->crud->addField([
                'name' => $col,
                'label' => ucwords($col),
                'type' => $type,
                'attributes' => [
                    'placeholder' => trans('lang.gov_exam_'.$col)
                ]
            ]);
        }

        // attachment field
        $this->crud->modifyField('attachment', [
            'type'      => 'upload',
            'upload'    => true,
            'disk'      => 'public', 
        ]);


    }

    private function showColumns()
    {
        $columns = $this->getTableColumns();

        foreach ($columns as $col) {
            $this->crud->addColumn([
                'name' => $col,
                'label' => ucwords($col),
                'type' => 'text',
            ]);
        }

        $this->downloadAttachment();
    }

    private function getTableColumns()
    {
        return getTableColumns('government_examinations');
    }


}
