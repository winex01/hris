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
        CRUD::setFromDb(); // columns

    }

    protected function setupShowOperation()
    {
        CRUD::setFromDb(); // fields

        // convert column/field name attachment to downloadable link
        $this->downloadAttachment();
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

        CRUD::setFromDb(); // fields


        foreach ([
            'institution',
            'title',
            'date',
            'venue',
            'rating',
            'attachment',
        ] as $field) {
            if ($field == 'attachment') {
                $this->crud->modifyField($field, [
                    'attributes' => [
                        'placeholder' => __('lang.gov_exam_'.$field)
                    ],
                    'type'      => 'upload',
                    'upload'    => true,
                    'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
                ]);
                continue; //continue to next loop
            }

            $this->crud->modifyField($field, [
                'attributes' => [
                    'placeholder' => __('lang.gov_exam_'.$field)
                ], 
            ]);
        }

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

    }
}
