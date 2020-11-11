<?php 

namespace App\Traits;

/**
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
	public function addField($name, $tab = null, $type, $others = null)
	{
		$field = [
    		'name' => $name,
            'label' => strSingular(__('lang.'.$name)),
            'type' => $type,
            'tab' => $tab,
            
    	];

    	if (is_null($tab)) {
        	unset($field['tab']);
        }

        if (!is_null($others)) {
            $field = array_merge($field, $others);
        }

        return $field;
	}

    public function textField($name, $tab = null, $others = null)
    {
		return $this->addField($name, $tab, 'text', $others);        
    }

    public function dateField($name, $tab = null, $others = null)
    {
		return $this->addField($name, $tab, 'date', $others);        
    }

    public function select2FromArray($name, $options, $tab = null, $others = null)
    {   
        // remove _id suffix
        $label = str_replace('_id', '', $name);

    	$field = [   // select2_from_array
            'name'	=> $name,
            'label'	=> strSingular(__('lang.'.$label)),
            'type'	=> 'select2_from_array',
            'options'	=> $options(),
            'allows_null' => true,
            'tab' => $tab,
        ];

        if (is_null($tab)) {
        	unset($field['tab']);
        }

         if (!is_null($others)) {
            $field = array_merge($field, $others);
        }

        return $field;
    }

    /*
    |--------------------------------------------------------------------------
    | Preview / show
    |--------------------------------------------------------------------------
    */
    public function previewRow($label, $value, $name = null)
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

    public function modifyPreviewRow($name, $value)
    {
        return $this->crud->modifyColumn($name, [
            'type' => 'custom_row',
            'value' => $value,
        ]);
    }

    public function previewTable($modelArray, $array = [])
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
                $this->previewRow($modelAttr, $value);
            }
        }//end foreach
    }
}