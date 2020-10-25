<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
	private $roles = [
		'user', 
    	'role', 
    	'permission',
	];

	private $ability = [
		'add', 
    	'edit', 
    	'delete', 
    	'view',
	];

	private $specialPermission = [
		// ex. manage_db
		'super_admin',
	];

	private $guardName;
	private $permissionType;

	/**
	 * 
	 * 
	 */
	public function __construct()
	{
		$this->guardName = config('backpack.base.guard') ?? 'web';

		$this->roles = collect($this->roles);
		$this->ability = collect($this->ability);
		$this->specialPermission = collect($this->specialPermission);

        $this->permissionType = $this->roles->crossJoin(
        	$this->ability
        );
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

    protected function assignPermissionsToRole()
    {
        // TODO:: assign permission to roles

    }

    protected function insertSpecialPermissions()
    {
        // insert special permission
        Permission::insert(
        	$this->specialPermission->map( function ($value) {
			    return [
			    	'name' => $value,
			      	'guard_name' => $this->guardName,
			      	'created_at' => now(),
			      	'updated_at' => now(),
			    ];
			})->toArray()
        );
    }

    protected function insertCommonPermissions()
    {
    	// insert common permisison like add, edit, delete, view
        Permission::insert(
        	$this->permissionType->map( function ($value) {
			    return [
			    	'name' => str_replace(' ', '_', implode('_', $value)),
			      	'guard_name' => $this->guardName,
			      	'created_at' => now(),
			      	'updated_at' => now(),
			    ];
			})->toArray()
        );
    }

    protected function insertRoles()
    {	
    	// insert super admin role
    	Role::firstOrCreate([
    		'name' => 'Super Admin'
    	]);

    	// insert roles
        Role::insert(
        	$this->roles->map( function ($value) {
			    return [
			    	'name' => ucwords($value),
			      	'guard_name' => $this->guardName,
			      	'created_at' => now(),
			      	'updated_at' => now(),
			    ];
			})->toArray()
        );
    }

    protected function assignSuperAdminRolePermissions()
    {
    	// 
    	$superAdmin = Role::where('name', 'Super Admin')->firstOrFail();
		
		$superAdmin->givePermissionTo(
			Permission::all()
		);

		# id 1 is super admin
		$superUser = User::findOrFail(1); 
		$superUser->assignRole($superAdmin);

    }

}
