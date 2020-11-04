<?php 

namespace App\Traits;

/**
 * use in backpack crud controller 
 */
trait CrudFieldTraits
{
    
	public function addField($name, $tab = null, $type)
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

        return $field;
	}

    public function textField($name, $tab = null)
    {
		return $this->addField($name, $tab, 'text');        
    }

    public function dateField($name, $tab = null)
    {
		return $this->addField($name, $tab, 'date');        
    }

    public function select2FromArray($name, $options, $tab = null)
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

        return $field;
    }

}