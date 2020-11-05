<?php 

namespace App\Traits;

/**
 * use in backpack crud controller 
 */
trait CrudExtendTrait
{
    /**
     * @desc: common user permission/ability
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
    	$field = [   // select2_from_array
            'name'	=> $name,
            'label'	=> strSingular(__('lang.'.$name)),
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

    public function flashMessageAndRedirect($item)
    {
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

}