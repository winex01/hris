<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
    public $permissions;

    /**
     * unique permission
     */
    public $adminRolePermissions;

    /**
     * if backpack config is null 
     * then default is web
     */
    public $guardName;

    public $adminRole;

    /**
     * 
     */
    public function __construct()
    {
        $this->roles = config('seeder.rolespermissions.roles');
        $this->permissions = config('seeder.rolespermissions.permissions');
        $this->adminRolePermissions = config('seeder.rolespermissions.admin_role_permissions');

        $this->guardName = config('backpack.base.guard') ?? 'web';
    
        $this->adminRole = 'admin';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create role $this->adminRole &
        // create permissions $this->adminRolePermissions
        $this->createAdminRolePermissions();

        // create role & permission by combining
        // $this->role & $this->permissions with underscore '_'
        $this->createRolePermissions();

        // assign all roles that exist in config seeder
        // to user with ID = 1 which is super admin
        $this->assignAllRolesInConfigToAdminUser();

        // sync or delete permissions that not exist in
        // $this->permissions & $this->adminRolePermissions
        $this->syncPermissions();
    }

    private function syncPermissions()
    {
        // TODO::
    }

    private function assignAllRolesInConfigToAdminUser()
    {
        // super admin ID = 1
        $admin = User::findOrFail(1);

        $roles = $this->roles; // get all roles
        
        // append in first $this->adminRole
        array_unshift($roles, $this->adminRole); 

        foreach ($roles as $role) {
            $admin->assignRole($role);
        }
    }


    private function createRolePermissions()
    {
        foreach ($this->roles as $role) {
            // create role
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => $this->guardName,
            ]);
            
            // create role_permission
            foreach ($this->permissions as $permission) {
               $permission = Permission::firstOrCreate([
                    'name' => $role.'_'.$permission,
                    'guard_name' => $this->guardName,
                ]);
                
                // assign role_permission to role
               $permission->assignRole($role);
            }
        }
    }

    private function createAdminRolePermissions()
    {
        // create admin role
        Role::firstOrCreate([
            'name' => $this->adminRole,
            'guard_name' => $this->guardName,
        ]);

        // create admin permissions
        foreach ($this->adminRolePermissions as $adminRolePermission) {
            $permission = Permission::firstOrCreate([
                'name' => $adminRolePermission,
                'guard_name' => $this->guardName,
            ]);

            $permission->assignRole($this->adminRole);
        }
    }

}
