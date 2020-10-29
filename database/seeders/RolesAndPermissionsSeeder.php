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
	public $roles;

	/**
	 * common permission that
	 * every role has
	 */
	public $permission;

	/**
	 * unique permission
	 */
	public $specialPermission;

	/**
	 * if backpack config is null 
	 * then default is web
	 */
	public $guardName;

    /**
     * Super admin/role assigned all available roles
     * when seeder is run
     */
    public $superRole = 'Super Admin';

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

        $this->syncRolesAndPermissions();
    }

    public function insertSpecialPermissions()
    {
        // insert special permission
        foreach ($this->specialPermissions as $specialPermission) {
        	Permission::firstOrCreate([
    			'name' => $this->strToLowerConvertSpaceWithUnderScore($specialPermission),
    			'guard_name' => $this->guardName,
    		]);
        }
    }

    public function insertCommonPermissions()
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

    public function insertRoles()
    {	
    	// insert super admin role
    	Role::firstOrCreate([
    		'name' => ucwords(strtolower($this->superRole)),
    		'guard_name' => $this->guardName,
    	]);

    	// 
        foreach ($this->roles as $role) {
        	Role::firstOrCreate([
	    		'name' => ucwords(strtolower($role)),
    			'guard_name' => $this->guardName,
	    	]);
        }
    }

    public function assignPermissionsToRole()
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

    public function assignSuperAdminRolePermissions()
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

    public function syncRolesAndPermissions()
    {
        // sync config seeders declared roles and permissions to DB
        // or delete roles and permissions in DB that doesn't exist in config

        // get all roles from DB
        $dbRoles = Role::pluck('name'); 

        // get all roles from config.seeder.prolespermissions
        $configRoles = collect($this->roles)->map( function($value) {
            return ucwords($value);
        });

        // remove Super Admin Role
        $dbRoles = $dbRoles->filter(function ($value) {
            return ucwords(strtolower($value)) !== ucwords($this->superRole);
        });

        // compare and select roles that exist in DB that didnt in config
        $deleteThisRoles = $dbRoles->diff(
            $configRoles
        );

        // delete
        Role::where(function ($query) use($deleteThisRoles) {
            foreach ($deleteThisRoles as $role) {
                $query->orWhere('name', 'LIKE', "%$role%");
            }
        })->delete();

        // TODO:: delete / sync permissions
    }

    public function strToLowerConvertSpaceWithUnderScore(string $str)
    {	
    	return strtolower(
    		str_replace(' ', '_', $str)
    	);
    }
}

// Backpack\PermissionManager\app\Models\Role::where(function ($query) {
//     $query->where('name', 'LIKE', '%wat%');
//     $query->orWhere('name', 'LIKE', '%wet%');
// })->delete();