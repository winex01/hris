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
    public $specificPermissions;

    /**
     * if backpack config is null 
     * then default is web
     */
    public $guardName;

    /**
     * 
     */
    public function __construct()
    {
        $this->roles = [
            'user', 
            'role', 
            'permission',
            'employee',
            'civil_status',
            'blood_type',
            'gender',
            'citizenship',
            'religion',
            'audit_trail',
        ];

        $this->permissions = [
            'list',
            'create', 
            'update', 
            'delete', 
            'bulk_delete',
        ];

        $this->specificPermissions = [
            // admin role is for special permissions
            'admin' => [
                'admin_view',
                'admin_force_delete',
                'admin_force_bulk_delete',
                'admin_revise',
            ],

            // add specific permissions for above roles
            'audit_trail' => [
                'audit_trail_restore_revise',
                'audit_trail_bulk_restore_revise',
            ],

            'file_manager' => [
                'file_manager_list'
            ]
        ];

        $this->guardName = config('backpack.base.guard') ?? 'web';
    
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create roles and permissions
        $this->createRolesAndPermissions();

        // create specific permissions
        $this->createSpecificPermissions();

        // assign all roles define in config/seeder to admin
        $this->assignAllRolesToAdmin();

        // sync
    }

    private function assignAllRolesToAdmin()
    {
        // super admin ID = 1
        $admin = User::findOrFail(1);

        $roles = array_merge(
            $this->roles,
            collect($this->specificPermissions)->keys()->toArray()
        );

        $roles = collect($roles)->unique()->toArray();

        $admin->syncRoles($roles);

    }

    private function createSpecificPermissions()
    {
        foreach ($this->specificPermissions as $role => $permissions){
            // create role
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => $this->guardName,
            ]);

            foreach ($permissions as $rolePermission) {
               $permission = Permission::firstOrCreate([
                    'name' => $rolePermission,
                    'guard_name' => $this->guardName,
                ]);
                
                // assign role_permission to role
               $permission->assignRole($role);
            }
        }

    }

    private function createRolesAndPermissions()
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

}
