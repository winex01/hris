<?php 

namespace App\Http\Controllers\Admin\Traits;

/**
 * import in backpack crud controller
 * use in backpack crud controller
 */
trait CrudExtendTrait
{
    /*
    |--------------------------------------------------------------------------
    | Check Auth User Permissions
    |--------------------------------------------------------------------------
    */ 
    public function userPermissions($role = null)
    {
        // check access for current role
        $this->checkAccess($role);

        // always run access for admin
        $this->checkAccess('admin');

        // global filter
        $this->globalFilter();

    }

    private function globalFilter()
    {
        if (hasAuthority('admin_view')) {
            // if soft delete is enabled
            if ($this->crud->model->soft_deleting) {
                $this->crud->addFilter([
                  'type'  => 'simple',
                  'name'  => 'trashed',
                  'label' => 'Trashed'
                ],
                false,
                function($values) { // if the filter is active
                    $this->crud->query = $this->crud->query->onlyTrashed();
                });
            }//end if soft delete enabled
        }//end hasAuth
    }

    private function checkAccess($role)
    {
        if ($role != null) {
            $allRolePermissions = \Backpack\PermissionManager\app\Models\Permission::where('name', 'LIKE', "$role%")
                                ->pluck('name')->map(function ($item) use ($role) {
                                    $value = str_replace($role.'_', '', $item);
                                    $value = \Str::camel($value);
                                    return $value;
                                })->toArray();

            // deny all access first
            $this->crud->denyAccess($allRolePermissions);

            $permissions = auth()->user()->getAllPermissions()
                ->pluck('name')
                ->filter(function ($item) use ($role) {
                    return false !== stristr($item, $role);
                })->map(function ($item) use ($role) {
                    $value = str_replace($role.'_', '', $item);
                    $value = \Str::camel($value);
                    return $value;
                })->toArray();

            // allow access if user have permission
            $this->crud->allowAccess($permissions);

        }//end if $role != null
    }

    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */
    public function inputs($table = null)
    {
        if ($table == null) {
            $table = $this->crud->model->getTable();
        }

        $columns = getTableColumnsWithDataType($table);
        
        foreach ($columns as $col => $dataType) {

            $type = $this->fieldTypes()[$dataType];

            $this->crud->addField([
                'name' => $col,
                'label' => ucwords(str_replace('_', ' ', $col)),
                'type' => $type,
                'attributes' => [
                    'placeholder' => trans('lang.'.$table.'_'.$col)
                ]
            ]);
        }

    }

    public function fieldTypes()
    {
        $fieldType = [
            'varchar' => 'text',
            'date' => 'date',
            'text' => 'textarea',
        ];

        return $fieldType;
    }

    public function imageField($name, $tab = null, $others = [])
    {
        $data = [
            'label' => \Str::singular(__("lang.$name")),
            'name' => $name,
            'type' => 'image',
            'crop' => true, 
            'aspect_ratio' => 1, 
            // 'prefix' => 'images/profile',
        ];

        if ($tab != null) {
            $data['tab'] = $tab;
        }

        return array_merge($data, $others);
    }

	public function addField($name, $tab = null, $others = [])
	{
        $label = $this->removePrefix($name, $others);

		$data = [
    		'name' => $name,
            'label' => \Str::singular(__('lang.'.$name)),
    	];

        if ($tab != null) {
            $data['tab'] = $tab;
        }

        return array_merge($data, $others);
	}

    public function textField($name, $tab = null, $others = [])
    {
		return $this->addField($name, $tab, array_merge([
            'type' => 'text'
        ], $others));
    }

    // alias to textField
    public function varcharField($name, $tab = null, $others = [])
    {
        return $this->textField($name, $tab, $others);
    }

    public function dateField($name, $tab = null, $others = [])
    {
        return $this->addField($name, $tab, array_merge([
            'type' => 'date'
        ], $others));        
    }

    public function select2FromArray($name, $tab = null, $others = [])
    {   
        // remove _id suffix
        $label = str_replace('_id', '', $name);

    	$data = [   // select2_from_array
            'label'	=> \Str::singular(__('lang.'.$label)),
            'name'	=> $name,
            'type'	=> 'select2_from_array',
            'allows_null' => true,
        ];

        if ($tab != null) {
            $data['tab'] = $tab;
        }

        return array_merge($data, $others);
    }

    public function selectList($array)
    {
        $selectList = [];
        foreach ($array as $column) {
            $selectList[$column] = $this->classInstance($column)->selectList();
        }

        return $selectList; 
    }

    /*
    |--------------------------------------------------------------------------
    | Preview / show
    |--------------------------------------------------------------------------
    */
    public function showColumns($table = null)
    {
        if ($table == null) {
            $table = $this->crud->model->getTable();
        }

        $columns = getTableColumns($table);

        foreach ($columns as $col) {
            $this->crud->addColumn([
                'name' => $col,
                'label' => ucwords(str_replace('_', ' ', $col)),
            ]);
        }

        // $this->downloadAttachment();
    }

    public function downloadAttachment($attachment = null)
    {
        if ($attachment == null) {
            $attachment = 'attachment';
        }

        $this->crud->modifyColumn($attachment, [
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->downloadAttachment();
            }
        ]);
    }

    public function dataRowHeader($header, $others = [])
    {   
        $data = [
            'escaped' => false,
        ];

        $data = array_merge($data, $others);

        $header = __('lang.'.$header);
        $header = strtoupper($header);

        $this->dataRow('', "<b>$header</b>", $data);
    }


    public function imageRow($label, $value, $others = null)
    {
        $data = [
            'label' => 'Photo',
            'type' => 'custom_image',
            'value' => $value,
            'height' => '200px'
        ];

        $data = array_merge($data, $others);
    
        return $this->crud->addColumn($data);
    }

    public function dataRow($label = '', $value = null, $others = [])
    {
        //remove _id from label
        if ($label != null && $label != '') {
            $label = str_replace('_id', '', $label);
        }

        $name = \Str::snake($label);

        $label = $this->removePrefix($label, $others);

        $label = \Str::singular(__('lang.'.$label));
        
        $data = [
            'name' => $name,
            'label' => $label,
            'type' => 'custom_row',
            'value' => $value,
        ];

        $data = array_merge($data, $others);

        return $this->crud->addColumn($data);
    } 

    public function modifyDataRow($name, $value)
    {
        return $this->crud->modifyColumn($name, [
            'type' => 'custom_row',
            'value' => $value,
        ]);
    }

    public function dataPreview($modelArray, $tab = null)
    {
        $removeColumn = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'employee_id',
        ];

        foreach ($modelArray as $modelInstance) {
            foreach ($modelInstance->AttributesToArray() as $modelAttr => $value){
                if ( in_array($modelAttr, $removeColumn) ) {
                    continue;;
                }
                $this->dataRow($modelAttr, $value, ['tab' => $tab]);
            }
        }//end foreach
    }
    
    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    public function uniqueRules($table, $requestInput = 'id')
    {
        return \Illuminate\Validation\Rule::unique($table)->ignore(
            request($requestInput)
        );
    }

    public function formInputs($inputs, $table, $prefix = null)
    {
        $columns = getTableColumns($table);

        if ($prefix != null) {
            $columns = collect($columns)
                ->map(function ($item) use ($prefix) {
                return $prefix.$item;
            });
        }

        return collect($inputs)
                ->only($columns)
                ->toArray();

    }

    public function formInputsRemovePrefix($inputs, $table, $prefix)
    {
        $dataInputs = collect($this->formInputs($inputs, $table,$prefix));

        $dataInputs = $dataInputs->mapWithKeys(function ($item, $key) use ($prefix) {
            return [str_replace($prefix, '', $key) => $item];
        });

        return $dataInputs->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Misc.
    |--------------------------------------------------------------------------
    */
    public function classInstance($class) 
    {
        return classInstance($class);
    }

    private function removePrefix($label, $others)
    {
        if (array_key_exists('removePrefix', $others)){
            $label = str_replace($others['removePrefix'], '', $label);
        }

        return $label;
    }


}