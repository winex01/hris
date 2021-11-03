<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PayrollPeriodCreateRequest;
use App\Http\Requests\PayrollPeriodUpdateRequest;
use App\Models\WithholdingTaxVersion;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { edit as traitEdit;  }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation; 
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Operations\PayrollPeriods\OpenOrClosePayrollOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchGroupingTrait;

    private function setExportClass()
    {
        return '\App\Exports\PayrollPeriodExport';
    }

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
        $this->showRelationshipColumn('withholding_tax_basis_id');
        $this->showRelationshipColumn('grouping_id');
        $this->booleanColumn('status');
        
        $this->conditionalLineButtons();
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
        CRUD::setValidation(PayrollPeriodCreateRequest::class);
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
        CRUD::setValidation(PayrollPeriodUpdateRequest::class);
        $this->inputs();
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

        // statutory
        foreach ([
            'deduct_pagibig',
            'deduct_philhealth',
            'deduct_sss',
        ] as $radioButton) {
            $this->crud->addField($radioButton);
            $this->addBooleanField($radioButton);
        }

        // wht basis
        $tempField = 'withholding_tax_basis_id';
        $this->crud->addField($tempField);
        $this->addRelationshipField($tempField);

        // filter wht basis dropdown select
        $this->crud->modifyField($tempField, [
            'options'   => (function ($query) {
                return $query->leftJoin('withholding_tax_versions', 'withholding_tax_versions.id', '=', 'withholding_tax_bases.withholding_tax_version_id')
                ->where('withholding_tax_versions.selected', 1)
                ->select('withholding_tax_bases.*')
                ->get();
            }),
            'hint' => WithholdingTaxVersion::where('selected', 1)->first()->name 
        ]);

        // grouping
        $this->crud->addField('grouping_id');
        $this->addInlineCreateField('grouping_id');
        $this->crud->modifyField('grouping_id', [
            'hint' => trans('lang.payroll_periods_grouping_hint')
        ]);

        // is_last_pay
        $field = 'is_last_pay';
        $this->crud->addField([
            'name' => $field,
            'hint' => trans('lang.payroll_periods_is_last_pay_hint')
        ]);
        $this->addBooleanField($field);
        
        // $this->dumpAllRequest();
    }

    /*
    |--------------------------------------------------------------------------
    | Conditional Line Buttons.
    |--------------------------------------------------------------------------
    | NOte:: please check method showTheseLineButtons in model
    */
    private function conditionalLineButtons()
    {
        $this->crud->addButtonFromView('line', 'forceDelete', 'conditional_buttons.custom_force_delete', 'beginning');
        $this->crud->addButtonFromView('line', 'delete', 'conditional_buttons.custom_delete', 'beginning');
        $this->crud->addButtonFromView('line', 'update', 'conditional_buttons.custom_update', 'beginning');
        $this->crud->addButtonFromView('line', 'show', 'conditional_buttons.custom_show', 'beginning');
        $this->crud->addButtonFromView('line', 'openOrClosePayroll', 'payroll_periods.conditional_buttons.custom_open_or_close_payroll', 'beginning');
    }

    public function edit($id)
    {
        if (!$this->canEditOrUpdate($id)) {
            $this->crud->denyAccess('update');
        }        
        
        return $this->traitEdit($id);
    }

    private function canEditOrUpdate($id)
    {
        $entry = $this->crud->getEntry($id);

        if (in_array('update', $entry->showTheseLineButtons())) {
            return true;
        }
        
        return false;        
    }
    // End conditional line buttons
}