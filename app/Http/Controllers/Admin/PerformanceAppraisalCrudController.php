<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PerformanceAppraisalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PerformanceAppraisalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PerformanceAppraisalCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PerformanceAppraisal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/performanceappraisal');

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
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PerformanceAppraisalRequest::class);
        $this->customInputs(); 
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(PerformanceAppraisalRequest::class);
        $this->customInputs(); 
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();

        $this->crud->modifyField('appraisal_type_id', [
            'type'        => 'select2_from_array',
            'options'     => classInstance('AppraisalType')::pluck('name', 'id'),
            'allows_null' => true,
        ]);

        $this->crud->modifyField('appraiser_id', [
            'type'        => 'select2_from_array',
            'options'     => classInstance('Employee')::orderBy('last_name')
                            ->orderBy('first_name')
                            ->orderBy('middle_name')
                            ->orderBy('badge_id')
                            ->get([
                                'id', 'last_name', 'first_name', 'middle_name', 'badge_id'
                            ])->pluck('name', 'id'),
            'allows_null' => true,
            'hint'        => 'Select employee who appraise.',
        ]);

        $this->addAttachmentField();

        // tabs
        $tab = 'Individual Performance ( 50 % )';
        foreach ([
            'job_function', 'productivity', 'attendance'
        ] as $field) {
            $this->crud->modifyField($field, [
                'tab'         => $tab,
                'type'        => 'select2_from_array',
                'options'     => $this->selectRatingLists(),
                'allows_null' => true,
                'hint'        => 'Select rating, <b>10</b> is the highest.'
            ]);
        }

        $tab = 'Job Competencies ( 25 % )';
        foreach ([
            'planning_and_organizing', 'innovation', 'technical_domain',
        ] as $field) {
            $this->crud->modifyField($field, [
                'tab'         => $tab,
                'type'        => 'select2_from_array',
                'options'     => $this->selectRatingLists(),
                'allows_null' => true,
                'hint'        => 'Select rating, <b>10</b> is the highest.'
            ]);
        }

        $tab = 'Organizational Competencies ( 25 % )';
        foreach ([
            'sense_of_ownership', 'customer_relation', 'professional_conduct',
        ] as $field) {
            $this->crud->modifyField($field, [
                'tab'         => $tab,
                'type'        => 'select2_from_array',
                'options'     => $this->selectRatingLists(),
                'allows_null' => true,
                'hint'        => 'Select rating, <b>10</b> is the highest.'
            ]);
        }

        // TODO:: individual Perf Rating field disabled
        // TODO:: job compt rating field disabled
        // TODO:: org comp rating field disabled
        // TODO:: total / overall rating and interpretation disabled

        // TODO:: validation
        // TODO:: rating with custom field that totals all of em, on key change
    }

    private function selectRatingLists()
    {
        $range = [];
        for($i = 10; $i != 0; $i--) {
            $range[$i] = $i;
        }

        return $range;
    }
}
