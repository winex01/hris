<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PayrollPeriodRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PayrollPeriodCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PayrollPeriodCrudController extends CrudController
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
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Traits\FetchGroupingTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PayrollPeriod::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payrollperiod');

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
        $this->showRelationshipColumn('grouping_id');
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
        CRUD::setValidation(PayrollPeriodRequest::class);
        $this->inputs();
        // TODO:: fix request validation
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
        $this->crud->addField([
            'name' => 'name',
            'hint' => trans('lang.payroll_periods_name_hint'),
        ]);

        $this->crud->addField([
            'name' => 'description',
            'hint' => trans('lang.payroll_periods_description_hint')
        ]);
        
        $this->crud->addField([
            'name'  => 'year_month',
            'label' => trans('lang.payroll_period_month_year'),
            'type'  => 'month',
            'hint' => trans('lang.payroll_periods_month_year_hint')
        ]);

        // date_range
        $this->crud->addField([
            'name'  => ['payroll_start', 'payroll_end'], // db columns for start_date & end_date
            'label' => 'Payroll Start - Payroll End',
            'type'  => 'date_range',

            'date_range_options' => [
                'timePicker' => false,
                'locale' => ['format' => config('appsettings.date_format')]
            ],
            'hint' => trans('lang.payroll_periods_date_range_hint')
        ]);

        foreach ([
            'deduct_pagibig',
            'deduct_philhealth',
            'deduct_sss',
            'witholding_tax_basis',
        ] as $radioButton) {
            $this->crud->addField($radioButton);
            $this->addBooleanField($radioButton);
        }

        $this->crud->addField('grouping_id');
        $this->addInlineCreateField('grouping_id');
        $this->crud->modifyField('grouping_id', [
            'hint' => trans('lang.payroll_periods_grouping_hint')
        ]);
    }
}
