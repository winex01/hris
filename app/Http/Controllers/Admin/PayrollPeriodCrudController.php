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
    
        $this->crud->addField([
            'name' => 'name',
            'hint' => trans('lang.payroll_periods_name_hint'),
        ]);

        $this->crud->addField([
            'name' => 'description',
            'hint' => trans('lang.payroll_periods_description_hint')
        ]);
        
        $this->crud->addField([
            'name'  => 'year',
            'label' => 'Year',
            'type'  => 'datetime_picker',

            'datetime_picker_options' => [
                'format' => 'YYYY',
            ],
            // 'default' => currentDate(),
            'hint' => trans('lang.payroll_periods_year_hint')
        ]);

        $this->crud->addField([
            'name'  => 'month',
            'label' => 'Month',
            'type'  => 'datetime_picker',

            'datetime_picker_options' => [
                'format' => 'M',
            ],
            // 'default' => currentDate(),
            'hint' => trans('lang.payroll_periods_month_hint')
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

        $this->crud->addField('deduct_pagibig');
        $this->crud->addField('deduct_philhealth');
        $this->crud->addField('deduct_sss');
        $this->crud->addField('witholding_tax_basis');
        $this->crud->addField('grouping_id');
        $this->addInlineCreateField('grouping_id');

        $this->crud->modifyField('grouping_id', [
            'hint' => trans('lang.payroll_periods_grouping_hint')
        ]);

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
