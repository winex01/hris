<?php 

namespace App\Http\Controllers\Admin\Traits;

/**
 * NOTE:: Global filters are located at CrudExtendTrait
 */
trait FilterTrait
{
    /**
     * 
     * Universal filter method specially for CRUD in app settings
     * @param  string $method Model relationship method name.
     * @return CrudButton
     * 
     */
    public function appSettingsFilter($method)
    {
        if (method_exists($this->crud->model, $method)) {
            $this->crud->addFilter([
                    'name'  => $method,
                    'type'  => 'select2',
                    'label' => convertColumnToHumanReadable($method),
                ],
                $this->{relationshipMethodName('fetch_'.$method)}()->pluck('name', 'id')->toArray(),
                function ($value) use ($method){ 
                     $col = \Str::snake($method).'_id';
                     $this->crud->addClause('where', $col, $value); 
                }
            );
        }//end if
    }
}