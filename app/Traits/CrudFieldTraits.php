<?php 

namespace App\Traits;

/**
 * use in backpack crud controller 
 */
trait CrudFieldTraits
{
    
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

}