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
            'label' => __('lang.'.$name),
            'type' => $type,
            
    	];

    	if (!is_null($tab)) {
    		$field['tab'] = $tab;
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
}