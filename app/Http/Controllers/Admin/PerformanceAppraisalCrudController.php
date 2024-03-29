<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PerformanceAppraisalRequest;
use App\Models\AppraisalInterpretation;
use App\Models\AppraisalType;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchAppraisalTypeTrait;

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
        
        $this->exportClass = '\App\Exports\PerformanceAppraisalExport';
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
        $this->crud->removeColumns([
            'job_function', 'productivity', 'attendance',
            'planning_and_organizing', 'innovation', 'technical_domain',
            'sense_of_ownership', 'customer_relation', 'professional_conduct'
        ]);
        
        $this->showEmployeeNameColumn();
        $this->showRelationshipColumn('appraisal_type_id');
        $this->showRelationshipColumn('appraiser_id');
        $this->downloadableAttachment();

        $field = 'individual_performance_rating';
        $this->crud->addColumn([
            'name' => $field,
            'label' => convertColumnToHumanReadable($field),
            'suffix' => ' %'
        ])->afterColumn('appraiser_id');        

        $field = 'job_competencies_rating';
        $this->crud->addColumn([
            'name' => $field,
            'label' => convertColumnToHumanReadable($field),
            'suffix' => ' %'
        ])->afterColumn('individual_performance_rating');

        $field = 'organizational_competencies_rating';
        $this->crud->addColumn([
            'name' => $field,
            'label' => convertColumnToHumanReadable($field),
            'suffix' => ' %'
        ])->afterColumn('job_competencies_rating');

        $field = 'total_rating';
        $this->crud->addColumn([
            'name' => $field,
            'label' => convertColumnToHumanReadable($field),
            'suffix' => ' %'
        ])->afterColumn('organizational_competencies_rating');

        $this->crud->addColumn([
            'name' => 'interpretation'
        ])->afterColumn('total_rating');

        // PerformanceAppraisalExport.php
        $this->crud->addFilter([
            'name' => 'totalRatingBetween', 
            'type' => 'dropdown',
            'label' => 'Interpretation'
        ],
        function () {
          return AppraisalInterpretation::get(['id', 'name', 'rating_from', 'rating_to'])
                    ->pluck('name_with_rating_percentage', 'id')
                    ->toArray();
        },
        function ($value) { // if the filter is active
            $item = AppraisalInterpretation::findOrFail($value);
            $this->crud->query->totalRatingBetween($item->rating_from, $item->rating_to);
        });

        // filter appraisal type
        $this->select2Filter('appraisalType'); 
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
        $this->setupCreateOperation(); 
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();
        $this->addInlineCreateField('appraisal_type_id');
        $this->addInlineCreateField('appraiser_id');
        $this->crud->modifyField('appraiser_id', [
            'hint'          => 'Select employee who appraise.',
            'attribute'     => 'full_name_with_badge',
            'model'         => 'App\Models\Employee',
            'inline_create' => null
        ]);

        $margin = '4';

        // individual peformance
        foreach ([
            'job_function', 'productivity', 'attendance'
        ] as $field) {
            $this->crud->modifyField($field, [
                'type'        => 'custom_performance_appraisal_select2',
                'options'     => $this->selectRatingLists(),
                'allows_null' => true,
                'hint'        => 'Select rating, <b>10</b> is the highest.',
                'wrapper' => [
                    'class' => 'form-group col-md-4 mt-'.$margin
                ],
                'attributes' => [
                    'class' => 'form-control select2_from_array individual-performance-group',
                    'data-placeholder' => trans('lang.select_placeholder')
                ]
            ]);
        }

        $indPerf = 'Job function + Productivity + Attendance = Individual Performance ( 50 % )';
        $this->crud->addField([
            'name'       => 'individual_performance',
            'label'      => 'Rating',
            'value'      => '0.00',
            'attributes' => [
                'disabled'   => 'disabled',
                'class' => 'form-control total-rating-group'
             ], 
            'suffix'     => '%',
            'hint'       => $indPerf,
        ])->afterField('attendance');
        // end individual performance


        // job competencies
        foreach ([
            'planning_and_organizing', 'innovation', 'technical_domain',
        ] as $field) {
            $this->crud->modifyField($field, [
                'type'        => 'custom_performance_appraisal_select2',
                'options'     => $this->selectRatingLists(),
                'allows_null' => true,
                'hint'        => 'Select rating, <b>10</b> is the highest.',
                'wrapper' => [
                    'class' => 'form-group col-md-4 mt-'.$margin
                ],
                'attributes' => [
                    'class' => 'form-control select2_from_array job-competencies-group',
                    'data-placeholder' => trans('lang.select_placeholder')
                ]
            ]);
        }

        $jobComp = 'Planning & Organizing + Innovation +Technical Domain = Job Competencies ( 25 % )';
        $this->crud->addField([
            'name'       => 'job_competencies',
            'label'      => 'Rating',
            'value'      => '0.00',
            'attributes' => [
                'disabled'   => 'disabled',
                'class' => 'form-control total-rating-group'
             ], 
            'suffix'     => '%',
            'hint'       => $jobComp,
        ])->afterField('technical_domain');
        // end job competencies


        // organizational competencies
        foreach ([
            'sense_of_ownership', 'customer_relation', 'professional_conduct',
        ] as $field) {
            $this->crud->modifyField($field, [
                'type'        => 'custom_performance_appraisal_select2',
                'options'     => $this->selectRatingLists(),
                'allows_null' => true,
                'hint'        => 'Select rating, <b>10</b> is the highest.',
                'wrapper' => [
                    'class' => 'form-group col-md-4 mt-'.$margin
                ],
                'attributes' => [
                    'class' => 'form-control select2_from_array organizational-competencies-group',
                    'data-placeholder' => trans('lang.select_placeholder')
                ]
            ]);
        }

        $orgComp = 'Sense Of Ownership + Customer Relation + Professional Conduct = Organizational Competencies ( 25 % )';
        $this->crud->addField([
            'name'       => 'organizational_competencies',
            'label'      => 'Rating',
            'value'      => '0.00',
            'attributes' => [
                'disabled'   => 'disabled',
                'class' => 'form-control total-rating-group'
             ], 
            'suffix'     => '%',
            'hint'       => $orgComp,
        ])->afterField('professional_conduct');

        $temp = AppraisalInterpretation::all();
        $hint = '';
        foreach ($temp as $obj) {
            $hint .= $obj->name_with_rating_percentage.'</br>';
        }

        // total rating
        $this->crud->addField([
            'name'       => 'total_rating',
            'label'      => 'Total Rating',
            'value'      => '0.00',
            'attributes' => [
                'disabled'   => 'disabled',
             ], 
            'suffix'     => '%',
            'hint'       => 'Individual Performance ( 50 % ) + Job Competencies ( 25 % ) + Organizational Competencies ( 25 % ) = 100 % </br>'.$hint,
            'wrapper' => [
                'class' => 'form-group col-sm-12 mt-'.$margin
            ]
        ])->afterField('organizational_competencies');

        $this->addAttachmentField();
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
