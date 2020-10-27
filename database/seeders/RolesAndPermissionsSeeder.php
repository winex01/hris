<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
	/**
	 * 
	 */
	private $roles;

	/**
	 * common permission that
	 * every role has
	 */
	private $permission;

	/**
	 * unique permission
	 */
	private $specialPermission;

	/**
	 * if backpack config is null 
	 * then default is web
	 */
	private $guardName;

    /**
     * Super admin/role assigned all available roles
     * when seeder is run
     */
    private $superRole = 'Super Admin';

	/**
	 * 
	 */
	public function __construct()
	{
        $this->roles = config('seeder.rolespermissions.roles');
        $this->permissions = config('seeder.rolespermissions.permissions');
        $this->specialPermissions = config('seeder.rolespermissions.special_permissions');

		$this->guardName = config('backpack.base.guard') ?? 'web';
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->insertSpecialPermissions();
    	$this->insertCommonPermissions();
        $this->insertRoles();
        $this->assignPermissionsToRole();

        $this->assignSuperAdminRolePermissions();
    }

    protected function insertSpecialPermissions()
    {
        // insert special permission
        foreach ($this->specialPermissions as $specialPermission) {
        	Permission::firstOrCreate([
    			'name' => $this->strToLowerConvertSpaceWithUnderScore($specialPermission),
    			'guard_name' => $this->guardName,
    		]);
        }
    }

    protected function insertCommonPermissions()
    {
    	// insert all common permission combine with role in permissions table.
    	// ex: role_commonPermission - user_view
        foreach ($this->roles as $role) {
        	foreach ($this->permissions as $permission) {
        		$permissionType = $role.'_'.$permission;
        		$permissionType = $this->strToLowerConvertSpaceWithUnderScore($permissionType);

        		Permission::firstOrCreate([
        			'name' => $permissionType,
        			'guard_name' => $this->guardName,
        		]);
        	}
        }//outer each
    }

    protected function insertRoles()
    {	
    	// insert super admin role
    	Role::firstOrCreate([
    		'name' => $this->superRole,
    		'guard_name' => $this->guardName,
    	]);

    	// 
        foreach ($this->roles as $role) {
        	Role::firstOrCreate([
	    		'name' => ucwords($role),
    			'guard_name' => $this->guardName,
	    	]);
        }
    }

    protected function assignPermissionsToRole()
    {
    	// assign all corresponding permission to there respective role
    	// ex. all permisison that start with user_ assign to User role.
        foreach ($this->roles as $role) {
        	$currentRole = Role::where('name', ucwords($role))->firstOrFail();
        	$currentRole->givePermissionTo(
        		Permission::where(
        			'name', 
        			'LIKE', 
        			'%'.$this->strToLowerConvertSpaceWithUnderScore($role).'%'
        		)->get()
        	);
        }

    }

    protected function assignSuperAdminRolePermissions()
    {
    	// assign all existing permission to Super Admin role.
    	$superAdmin = Role::where('name', $this->superRole)->firstOrFail();
		
		$superAdmin->givePermissionTo(
			Permission::all()
		);

		# id 1 is super admin
		$superUser = User::findOrFail(1); 
		$superUser->assignRole($superAdmin);

    }

    private function strToLowerConvertSpaceWithUnderScore(string $str)
    {	
    	return strtolower(
    		str_replace(' ', '_', $str)
    	);
    }

}
