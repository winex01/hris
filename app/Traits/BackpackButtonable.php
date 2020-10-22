<?php

namespace App\Traits;

trait BackpackButtonable
{
	private function showButtons()
    {
        $entityName = strtolower($this->crud->entity_name);

        // show buttons if authorize
        if( !hasAuthority($entityName.'_delete') ){
            $this->crud->removeButton('delete');
        }

        if( !hasAuthority($entityName.'_edit') ){
            $this->crud->removeButton('update');
        }

        if( !hasAuthority($entityName.'_create') ){
            $this->crud->removeButton('create');
        }
    }
}