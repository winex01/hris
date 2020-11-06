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

     /*
    |--------------------------------------------------------------------------
    | Extend Backpack Operations
    |--------------------------------------------------------------------------
    */
    public function extendUpdate($pushCodeHere)
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $item = $pushCodeHere();

        if (empty($pushCodeHere)) {
            // update the row in the db
            $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                                $this->crud->getStrippedSaveRequest());
        }

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function extendEdit($id, $pushCodeHere)
    {
        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $pushCodeHere();

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function extendStore($pushCodeHere)
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $item = $pushCodeHere();

        if (empty($pushCodeHere)) {
            // insert item in the db
            $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        }

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

}