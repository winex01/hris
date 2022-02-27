<?php 

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Str;

/**
 * NOTE:: Global filters are located at CrudExtendTrait
 */
trait FilterTrait
{
    public function simpleFilter($col, $value = 1)
    {
        $this->crud->addFilter([
            'type'  => 'simple',
            'name'  => $col,
            'label' => convertColumnToHumanReadable($col),
        ], 
        false, 
        function() use($col, $value) { // if the filter is active
            $this->crud->query->where($col, '=', $value); // apply the "active" eloquent scope 
        } );
    }

    public function booleanFilter($col)
    {
        $this->crud->addFilter([
          'name'  => $col,
          'label' => convertColumnToHumanReadable($col),
          'type'  => 'dropdown',
        ], [ 0 => 'No', 1 => 'Yes'], 
        function($value) use ($col) { // if the filter is active
            $this->crud->addClause('where', $col, $value);
        });
    }

    public function select2Filter($col, $orderBy = 'name')
    {
        $method = relationshipMethodName($col);
        if (method_exists($this->crud->model, $method)) {
            $this->crud->addFilter([
                    'name'  => $method,
                    'type'  => 'select2',
                    'label' => convertColumnToHumanReadable($method),
                ],
                classInstance($method)::orderBy($orderBy)->pluck('name', 'id')->toArray(),
                function ($value) use ($method){ 
                     $col = \Str::snake($method).'_id';
                     $this->crud->addClause('where', $col, $value); 
                }
            );
        }//end if
    }

    public function select2FromArrayFilter($col, $options = [])
    {
        $this->crud->addFilter([
            'name' => $col,
            'type' => 'select2', 
            'label' => convertColumnToHumanReadable($col),
        ], 
        $options,
        function ($value) use ($col) { // if the filter is active
            $this->crud->query->where($col, '=', $value);
        });
    }

    public function select2MultipleFromArrayFilter($method, $options = [], $name = null)
    {
        $this->crud->addFilter([
            'name' => $method,
            'type' => 'select2_multiple', 
            'label' => convertColumnToHumanReadable($name ?: $method),
        ], 
        $options,
        function($values) use ($method) { // if the filter is active
            $this->crud->query->{$method}(json_decode($values));
        });
    }
    
    public function dateRangeFilter($col, $label = null)
    {
        $this->crud->addFilter([
            'name'  => 'date_range_filter_'.$col,
            'type'  => 'date_range',
            'label' => $label ?? convertColumnToHumanReadable($col),
        ],
        false,
        function ($value) use ($col) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $table = $this->crud->model->getTable();
            $this->crud->query->whereBetween($table.'.'.$col, [$dates->from, $dates->to]);
        });
    }

    public function removeGlobalScopeFilter($scope, $label = null)
    {
        if ($label == null) {
          $label = str_replace('Current', '', $scope);
          $label = str_replace('Scope', '', $label);
          $label = str_replace('_', ' ', Str::snake($label));
          $label = $label . ' History';
          $label = ucwords($label);
        }

        $this->crud->addFilter([
            // 'type'  => 'simple',
            'type'  => 'custom_simple_hide_bottom_buttons',
            'name'  => 'remove_scope_'.$scope,
            'label' => $label
        ], 
        false, 
        function() use ($scope) { // if the filter is active
            $this->crud->query->withoutGlobalScope($scope);
            disableLineButtons($this->crud); 
        });
    }

    /**
     *
     * @param  $overrideScope: if true then scopePayrollPeriod method was override in the model
     */
    public function openPayrollPeriodFilter($label = null, $overrideScope = false)
    {
        $scope = 'payrollPeriod'; // locate method at models/Model.php

        $this->crud->addFilter([
            'name'  => $scope.'_scope',
            'type'  => 'select2',
            'label' => ($label == null) ? 'Current Payroll Period' : $label,
        ],
        function () {
          return openPayrollGroupingIds();
        },
        function($value) use($scope, $overrideScope) { // if the filter is active
            if ($overrideScope == false) {
                $value = (int)explode('_', $value)[0]; // remove concatenated string and then cast to int
            }
            $this->crud->query->{$scope}($value);
        });
    }
}