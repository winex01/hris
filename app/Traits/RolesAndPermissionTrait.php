<?php 

namespace App\Traits;


trait RolesAndPermissionTrait
{
	/**
	 * @desc: common user permission/ability
	 */
    public function userPermissions($permission = null)
    {
    	if ($permission === null) {
    		$permission = strtolower($this->crud->entity_name);
    	}

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
}