<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * 
     */
    protected $rolesAndPermissions = [

        'admin' => [
            'admin_view',
            'admin_trashed_filter', 
            'admin_debugbar', 
            'admin_web_tinker', 
        ],

        'employees' => [
            'employees_list',
            'employees_create', 
            'employees_show', 
            'employees_update', 
            'employees_delete', 
            'employees_bulk_delete',
            'employees_export',
            'employees_force_delete',
            'employees_force_bulk_delete',
            'employees_revise',
        ],

        'family_datas' => [
            'family_datas_list',
            'family_datas_create', 
            'family_datas_show', 
            'family_datas_update', 
            'family_datas_delete', 
            'family_datas_bulk_delete',
            'family_datas_export',
            'family_datas_force_delete',
            'family_datas_force_bulk_delete',
            'family_datas_revise',
        ],

        'award_and_recognitions' => [
            'award_and_recognitions_list',
            'award_and_recognitions_create', 
            'award_and_recognitions_show', 
            'award_and_recognitions_update', 
            'award_and_recognitions_delete', 
            'award_and_recognitions_bulk_delete',
            'award_and_recognitions_export',
            'award_and_recognitions_force_delete',
            'award_and_recognitions_force_bulk_delete',
            'award_and_recognitions_revise',
        ],

        'government_examinations' => [
            'government_examinations_list',
            'government_examinations_create', 
            'government_examinations_show', 
            'government_examinations_update', 
            'government_examinations_delete', 
            'government_examinations_bulk_delete',
            'government_examinations_export',
            'government_examinations_force_delete',
            'government_examinations_force_bulk_delete',
            'government_examinations_revise',
        ],

        'supporting_documents' => [
            'supporting_documents_list',
            'supporting_documents_create', 
            'supporting_documents_show', 
            'supporting_documents_update', 
            'supporting_documents_delete', 
            'supporting_documents_bulk_delete',
            'supporting_documents_export',
            'supporting_documents_force_delete',
            'supporting_documents_force_bulk_delete',
            'supporting_documents_revise',
        ],

        'training_and_seminars' => [
            'training_and_seminars_list',
            'training_and_seminars_create', 
            'training_and_seminars_show', 
            'training_and_seminars_update', 
            'training_and_seminars_delete', 
            'training_and_seminars_bulk_delete',
            'training_and_seminars_export',
            'training_and_seminars_force_delete',
            'training_and_seminars_force_bulk_delete',
            'training_and_seminars_revise',
        ],

        'work_experiences' => [
            'work_experiences_list',
            'work_experiences_create', 
            'work_experiences_show', 
            'work_experiences_update', 
            'work_experiences_delete', 
            'work_experiences_bulk_delete',
            'work_experiences_export',
            'work_experiences_force_delete',
            'work_experiences_force_bulk_delete',
            'work_experiences_revise',
        ],

        'blood_types' => [
            'blood_types_list',
            'blood_types_create', 
            'blood_types_update', 
            'blood_types_delete', 
        ],

        'citizenships' => [
            'citizenships_list',
            'citizenships_create', 
            'citizenships_update', 
            'citizenships_delete', 
        ],

        'civil_statuses' => [
            'civil_statuses_list',
            'civil_statuses_create', 
            'civil_statuses_update', 
            'civil_statuses_delete', 
        ],

        'genders' => [
            'genders_list',
            'genders_create', 
            'genders_update', 
            'genders_delete', 
        ],

        'religions' => [
            'religions_list',
            'religions_create', 
            'religions_update', 
            'religions_delete', 
        ],

        'audit_trails' => [
            'audit_trails_list',
            'audit_trails_show', 
            'audit_trails_delete',
            'audit_trails_bulk_delete',
            'audit_trails_export',
            'audit_trails_restore_revise',
            'audit_trails_bulk_restore_revise', 
        ],

        'users' => [
            'users_list',
            'users_create', 
            'users_update', 
            'users_delete', 
            'users_export', 
            'users_revise',
            'users_force_delete',
        ],

        'roles' => [
            'roles_list',
            'roles_create', 
            'roles_update', 
            'roles_delete', 
        ],

        'permissions' => [
            'permissions_list',
            'permissions_create', 
            'permissions_update', 
            'permissions_delete', 
        ],
       
        'advanced' => [
            'advanced_file_manager',
            'advanced_backups',
            'advanced_logs',
            'advanced_settings',
        ],

        'menus' => [
            'menus_list',
            'menus_create',
            'menus_reorder',
            'menus_update',
            'menus_delete',
        ],

        'relations' => [
            'relations_list',
            'relations_create', 
            'relations_update', 
            'relations_delete', 
        ],

        'educational_levels' => [
            'educational_levels_list',
            'educational_levels_create', 
            'educational_levels_update', 
            'educational_levels_delete', 
        ],

        'educational_backgrounds' => [
            'educational_backgrounds_list',
            'educational_backgrounds_create', 
            'educational_backgrounds_show', 
            'educational_backgrounds_update', 
            'educational_backgrounds_delete', 
            'educational_backgrounds_bulk_delete',
            'educational_backgrounds_export',
            'educational_backgrounds_force_delete',
            'educational_backgrounds_force_bulk_delete',
            'educational_backgrounds_revise',
        ],

        'medical_informations' => [
            'medical_informations_list',
            'medical_informations_create', 
            'medical_informations_show', 
            'medical_informations_update', 
            'medical_informations_delete', 
            'medical_informations_bulk_delete',
            'medical_informations_export',
            'medical_informations_force_delete',
            'medical_informations_force_bulk_delete',
            'medical_informations_revise',
        ],

        'professional_orgs' => [
            'professional_orgs_list',
            'professional_orgs_create', 
            'professional_orgs_show', 
            'professional_orgs_update', 
            'professional_orgs_delete', 
            'professional_orgs_bulk_delete',
            'professional_orgs_export',
            'professional_orgs_force_delete',
            'professional_orgs_force_bulk_delete',
            'professional_orgs_revise',
        ],

        'benefeciaries' => [
            'benefeciaries_list',
            'benefeciaries_create', 
            'benefeciaries_show', 
            'benefeciaries_update', 
            'benefeciaries_delete', 
            'benefeciaries_bulk_delete',
            'benefeciaries_export',
            'benefeciaries_force_delete',
            'benefeciaries_force_bulk_delete',
            'benefeciaries_revise',
        ],

        'dependents' => [
            'dependents_list',
            'dependents_create', 
            'dependents_show', 
            'dependents_update', 
            'dependents_delete', 
            'dependents_bulk_delete',
            'dependents_export',
            'dependents_force_delete',
            'dependents_force_bulk_delete',
            'dependents_revise',
        ],

        'character_references' => [
            'character_references_list',
            'character_references_create', 
            'character_references_show', 
            'character_references_update', 
            'character_references_delete', 
            'character_references_bulk_delete',
            'character_references_export',
            'character_references_force_delete',
            'character_references_force_bulk_delete',
            'character_references_revise',
        ],

        'skill_and_talents' => [
            'skill_and_talents_list',
            'skill_and_talents_create',
            'skill_and_talents_show',
            'skill_and_talents_update',
            'skill_and_talents_delete',
            'skill_and_talents_bulk_delete',
            'skill_and_talents_export',
            'skill_and_talents_force_delete',
            'skill_and_talents_force_bulk_delete',
            'skill_and_talents_revise',
        ],

        'payment_methods' => [
            'payment_methods_list',
            'payment_methods_create',
            'payment_methods_update',
            'payment_methods_delete',

        ],

        'pay_bases' => [
            'pay_bases_list',
            'pay_bases_create',
            'pay_bases_update',
            'pay_bases_delete',
        ],

        'job_statuses' => [
            'job_statuses_list',
            'job_statuses_create',
            'job_statuses_update',
            'job_statuses_delete',
        ],

        'employment_statuses' => [
            'employment_statuses_list',
            'employment_statuses_create',
            'employment_statuses_update',
            'employment_statuses_delete',
        ],

        'companies' => [
            'companies_list',
            'companies_create',
            'companies_show',
            'companies_update',
            'companies_delete',
            'companies_bulk_delete',
            'companies_export',
            'companies_revise',
        ],

        'locations' => [
            'locations_list',
            'locations_create',
            'locations_update',
            'locations_delete',
        ],

        'departments' => [
            'departments_list',
            'departments_create',
            'departments_update',
            'departments_delete',
        ],

        'divisions' => [
            'divisions_list',
            'divisions_create',
            'divisions_update',
            'divisions_delete',
        ],

        'sections' => [
            'sections_list',
            'sections_create',
            'sections_update',
            'sections_delete',
        ],

        'positions' => [
            'positions_list',
            'positions_create',
            'positions_update',
            'positions_delete',
        ],

    ];

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
        $this->guardName = config('backpack.base.guard') ?? 'web';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create specific permissions
        $this->createRolesAndPermissions();

        // assign all roles define in config/seeder to admin
        $this->assignAllRolesToAdmin();

    }

    private function assignAllRolesToAdmin()
    {
        // super admin ID = 1
        $admin = User::findOrFail(1);

        $roles = collect($this->rolesAndPermissions)->keys()->unique()->toArray();
        $admin->syncRoles($roles);
    }

    private function createRolesAndPermissions()
    {
        foreach ($this->rolesAndPermissions as $role => $permissions){
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
}
