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
            'type'  => 'simple',
            'name'  => 'remove_scope_'.$scope,
            'label' => $label
        ], 
        false, 
        function() use ($scope) { // if the filter is active
            $this->crud->query->withoutGlobalScope($scope);
            $this->crud->denyAccess('calendar');
            $this->crud->denyAccess('show');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
            $this->crud->denyAccess('bulkDelete');
            $this->crud->denyAccess('forceDelete');
            $this->crud->denyAccess('forceBulkDelete');
            $this->crud->denyAccess('revise');
        });
    }

    public function dateRangeFilter($col, $label = null)
    {
        $this->crud->addFilter([
            'name'  => 'date_range_filter_'.$col,
            'type'  => 'date_range',
            'label' => $label ?? convertColumnToHumanReadable($col).' Date Range',
        ],
        false,
        function ($value) use ($col) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->query->whereBetween($col, [$dates->from, $dates->to]);
        });
    }
}