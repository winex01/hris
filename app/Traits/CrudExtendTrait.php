<?php 

namespace App\Traits;

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
    public function userPermissions($permission)
    {
        if (hasNoAuthority($permission.'_view')) {
            $this->crud->denyAccess('list');
        }

        if (hasNoAuthority($permission.'_add')) {
            $this->crud->denyAccess('create');
        }

        if (hasNoAuthority($permission.'_edit')) {
            $this->crud->denyAccess('update');
        }

        if (hasNoAuthority($permission.'_delete')) {
            $this->crud->denyAccess('delete');
        }

        if (hasNoAuthority($permission.'_bulk_delete')) {
            $this->crud->denyAccess('bulkDelete');
        }

        if (hasNoAuthority($permission.'_force_delete')) {
            $this->crud->denyAccess('forceDelete');
        }
    }

    public function uniqueRules($table, $requestInput = 'id')
    {
        return \Illuminate\Validation\Rule::unique($table)->ignore(
            request($requestInput)
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */
    public function imageField($name, $tab = null, $others = null)
    {
        $data = [
            'label' => strSingular(__("lang.$name")),
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

	public function addField($name, $tab = null, $others = null)
	{
		$data = [
    		'name' => $name,
            'label' => strSingular(__('lang.'.$name)),
    	];

        if ($tab != null) {
            $data['tab'] = $tab;
        }

        return arrayMerge($data, $others);
	}

    public function textField($name, $tab = null, $others = null)
    {
		return $this->addField($name, $tab, arrayMerge([
            'type' => 'text'
        ], $others));
    }

    public function dateField($name, $tab = null, $others = null)
    {
        return $this->addField($name, $tab, arrayMerge([
            'type' => 'date'
        ], $others));        
    }

    public function select2FromArray($name, $tab = null, $others = null)
    {   
        // remove _id suffix
        $label = str_replace('_id', '', $name);

    	$data = [   // select2_from_array
            'label'	=> strSingular(__('lang.'.$label)),
            'name'	=> $name,
            'type'	=> 'select2_from_array',
            'allows_null' => true,
        ];

        if ($tab != null) {
            $data['tab'] = $tab;
        }

        return arrayMerge($data, $others);
    }

    /*
    |--------------------------------------------------------------------------
    | Preview / show
    |--------------------------------------------------------------------------
    */
    public function imageRow($label, $value, $height = '200px', $width = null)
    {
        return $this->crud->addColumn([
            'label' => 'Photo',
            'type' => 'custom_image',
            'value' => $value,
            'height' => $height,
            'width' => $width,
        ]);
    }

    public function dataRow($label, $value, $name = null)
    {
        if ($name == null) {
            $name = str_replace('_id', '', $label);
            $name = str_replace(' ', '_', $name);
        }

        //remove _id from label
        $label = str_replace('_id', '', $label);
        $label = strSingular(__('lang.'.$label));

        return $this->crud->addColumn([
            'name' => $name,
            'label' => $label,
            'type' => 'custom_row',
            'value' => $value,
        ]);
    } 

    public function modifyDataRow($name, $value)
    {
        return $this->crud->modifyColumn($name, [
            'type' => 'custom_row',
            'value' => $value,
        ]);
    }

    public function dataPreview($modelArray, $array = [])
    {
        $removeColumn = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'employee_id',
        ];

        if (!empty($array)) {
            $removeColumn = array_merge($removeColumn, $array);
        }

        foreach ($modelArray as $modelInstance) {
            foreach ($modelInstance->AttributesToArray() as $modelAttr => $value){
                if ( in_array($modelAttr, $removeColumn) ) {
                    continue;;
                }
                $this->dataRow($modelAttr, $value);
            }
        }//end foreach
    }
}