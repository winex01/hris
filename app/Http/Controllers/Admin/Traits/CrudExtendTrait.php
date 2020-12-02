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
    | Roles & Permissions
    |--------------------------------------------------------------------------
    */ 
    public function userPermissions($role)
    {
        // locate roles and permissions at seeder/rolespermissions
        foreach (config('seeder.rolespermissions.permissions') as $permission) {
            if (hasNoAuthority($role.'_'.$permission)) {
                $this->crud->denyAccess(\Str::camel($permission));
            }
        }

        foreach (config('seeder.rolespermissions.special_permissions') as $specialPermission) {
            if (hasNoAuthority($specialPermission)) {
                $access = str_replace('admin_', '', $specialPermission);
                $this->crud->denyAccess(\Str::camel($access));
            }
        }
        
    }
    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */
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

        return arrayMerge($data, $others);
    }

	public function addField($name, $tab = null, $others = [])
	{
		$data = [
    		'name' => $name,
            'label' => \Str::singular(__('lang.'.$name)),
    	];

        if ($tab != null) {
            $data['tab'] = $tab;
        }

        return arrayMerge($data, $others);
	}

    public function textField($name, $tab = null, $others = [])
    {
		return $this->addField($name, $tab, arrayMerge([
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
        return $this->addField($name, $tab, arrayMerge([
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

        return arrayMerge($data, $others);
    }

    public function classInstance($class) 
    {
        $class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
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

        $data = arrayMerge($data, $others);
    
        return $this->crud->addColumn($data);
    }

    public function dataRow($label = '', $value = null, $others = [])
    {
        //remove _id from label
        if ($label != null && $label != '') {
            $label = str_replace('_id', '', $label);
            $label = \Str::singular(__('lang.'.$label));
        }

        $data = [
            'name' => \Str::snake($label),
            'label' => $label,
            'type' => 'custom_row',
            'value' => $value,
        ];

        $data = arrayMerge($data, $others);

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
            $key = str_replace($prefix, '', $key);
            return [$key => $item];
        })->toArray();

        return $dataInputs;
    }
}