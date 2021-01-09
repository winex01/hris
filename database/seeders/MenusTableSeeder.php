<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'created_at' => '2020-12-16 06:58:25',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-dashboard "></i>',
                'id' => 1,
                'label' => 'Dashboard',
                'lft' => 2,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 3,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'dashboard',
            ),
            1 => 
            array (
                'created_at' => '2020-12-16 07:00:21',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-user"></i>',
                'id' => 2,
                'label' => 'Employee Records',
                'lft' => 4,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 31,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => NULL,
            ),
            2 => 
            array (
                'created_at' => '2020-12-16 07:11:38',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-trophy\'></i>',
                'id' => 3,
                'label' => 'Awards & Recog.',
                'lft' => 7,
                'parent_id' => 2,
                'permission' => 'award_and_recognitions_list',
                'rgt' => 8,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'awardandrecognition',
            ),
            3 => 
            array (
                'created_at' => '2020-12-16 07:12:30',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-user-plus\'></i>',
                'id' => 4,
                'label' => 'Employees',
                'lft' => 5,
                'parent_id' => 2,
                'permission' => 'employees_list',
                'rgt' => 6,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'employee',
            ),
            4 => 
            array (
                'created_at' => '2020-12-16 07:13:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-industry\'></i>',
                'id' => 5,
                'label' => 'Gov. Exams',
                'lft' => 17,
                'parent_id' => 2,
                'permission' => 'government_examinations_list',
                'rgt' => 18,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'governmentexamination',
            ),
            5 => 
            array (
                'created_at' => '2020-12-16 07:13:57',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-file-alt\'></i>',
                'id' => 6,
                'label' => 'Supporting Docs.',
                'lft' => 25,
                'parent_id' => 2,
                'permission' => 'supporting_documents_list',
                'rgt' => 26,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'supportingdocument',
            ),
            6 => 
            array (
                'created_at' => '2020-12-16 07:20:23',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-bicycle\'></i>',
                'id' => 7,
                'label' => 'Training & Seminars',
                'lft' => 27,
                'parent_id' => 2,
                'permission' => 'training_and_seminars_list',
                'rgt' => 28,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'trainingandseminar',
            ),
            7 => 
            array (
                'created_at' => '2020-12-16 07:20:46',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-bolt\'></i>',
                'id' => 8,
                'label' => 'Work Experiences',
                'lft' => 29,
                'parent_id' => 2,
                'permission' => 'work_experiences_list',
                'rgt' => 30,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'workexperience',
            ),
            8 => 
            array (
                'created_at' => '2020-12-16 07:21:07',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-cog"></i>',
                'id' => 9,
                'label' => 'App Settings',
                'lft' => 32,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 47,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => NULL,
            ),
            9 => 
            array (
                'created_at' => '2020-12-16 07:21:32',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-eyedropper\'></i>',
                'id' => 10,
                'label' => 'Blood Type',
                'lft' => 33,
                'parent_id' => 9,
                'permission' => 'blood_types_list',
                'rgt' => 34,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'bloodtype',
            ),
            10 => 
            array (
                'created_at' => '2020-12-16 07:21:51',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-flag-o\'></i>',
                'id' => 11,
                'label' => 'Citizenship',
                'lft' => 35,
                'parent_id' => 9,
                'permission' => 'citizenships_list',
                'rgt' => 36,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'citizenship',
            ),
            11 => 
            array (
                'created_at' => '2020-12-16 07:22:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-neuter\'></i>',
                'id' => 12,
                'label' => 'Civil Status',
                'lft' => 37,
                'parent_id' => 9,
                'permission' => 'civil_statuses_list',
                'rgt' => 38,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'civilstatus',
            ),
            12 => 
            array (
                'created_at' => '2020-12-16 07:22:32',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-venus\'></i>',
                'id' => 13,
                'label' => 'Gender',
                'lft' => 43,
                'parent_id' => 9,
                'permission' => 'genders_list',
                'rgt' => 44,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'gender',
            ),
            13 => 
            array (
                'created_at' => '2020-12-16 07:22:56',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-church\'></i>',
                'id' => 14,
                'label' => 'Religion',
                'lft' => 45,
                'parent_id' => 9,
                'permission' => 'religions_list',
                'rgt' => 46,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'religion',
            ),
            14 => 
            array (
                'created_at' => '2020-12-16 07:23:11',
                'depth' => 1,
                'icon' => NULL,
                'id' => 15,
                'label' => 'Administrator Only',
                'lft' => 48,
                'parent_id' => NULL,
                'permission' => 'admin_view',
                'rgt' => 49,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => NULL,
            ),
            15 => 
            array (
                'created_at' => '2020-12-16 07:26:27',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon la la-history\'></i>',
                'id' => 16,
                'label' => 'Audit Trails',
                'lft' => 50,
                'parent_id' => NULL,
                'permission' => 'audit_trails_list',
                'rgt' => 51,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'audittrail',
            ),
            16 => 
            array (
                'created_at' => '2020-12-16 07:27:02',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-users"></i>',
                'id' => 17,
                'label' => 'Authentication',
                'lft' => 52,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 59,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => NULL,
            ),
            17 => 
            array (
                'created_at' => '2020-12-16 07:27:32',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-user"></i>',
                'id' => 18,
                'label' => 'Users',
                'lft' => 53,
                'parent_id' => 17,
                'permission' => 'users_list',
                'rgt' => 54,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'user',
            ),
            18 => 
            array (
                'created_at' => '2020-12-16 07:27:49',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-id-badge"></i>',
                'id' => 19,
                'label' => 'Roles',
                'lft' => 55,
                'parent_id' => 17,
                'permission' => 'roles_list',
                'rgt' => 56,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'role',
            ),
            19 => 
            array (
                'created_at' => '2020-12-16 07:28:08',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-key"></i>',
                'id' => 20,
                'label' => 'Permissions',
                'lft' => 57,
                'parent_id' => 17,
                'permission' => 'permissions_list',
                'rgt' => 58,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'permission',
            ),
            20 => 
            array (
                'created_at' => '2020-12-16 07:28:27',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-cogs"></i>',
                'id' => 21,
                'label' => 'Advanced',
                'lft' => 60,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 69,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => NULL,
            ),
            21 => 
            array (
                'created_at' => '2020-12-16 07:30:40',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-files-o"></i>',
                'id' => 22,
                'label' => 'File Manager',
                'lft' => 61,
                'parent_id' => 21,
                'permission' => 'advanced_file_manager',
                'rgt' => 62,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'elfinder',
            ),
            22 => 
            array (
                'created_at' => '2020-12-16 07:31:21',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-hdd-o\'></i>',
                'id' => 23,
                'label' => 'Backups',
                'lft' => 63,
                'parent_id' => 21,
                'permission' => 'advanced_backups',
                'rgt' => 64,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'backup',
            ),
            23 => 
            array (
                'created_at' => '2020-12-16 07:31:47',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-terminal\'></i>',
                'id' => 24,
                'label' => 'Logs',
                'lft' => 65,
                'parent_id' => 21,
                'permission' => 'advanced_logs',
                'rgt' => 66,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'log',
            ),
            24 => 
            array (
                'created_at' => '2020-12-16 07:32:02',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-cog\'></i>',
                'id' => 25,
                'label' => 'Settings',
                'lft' => 67,
                'parent_id' => 21,
                'permission' => 'advanced_settings',
                'rgt' => 68,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'setting',
            ),
            25 => 
            array (
                'created_at' => '2020-12-16 07:32:42',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon la la-list\'></i>',
                'id' => 26,
                'label' => 'Menu',
                'lft' => 70,
                'parent_id' => NULL,
                'permission' => 'menus_list',
                'rgt' => 71,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'menu',
            ),
            26 => 
            array (
                'created_at' => '2020-12-31 09:52:25',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-clone\'></i>',
                'id' => 28,
                'label' => 'Family & Contacts',
                'lft' => 15,
                'parent_id' => 2,
                'permission' => 'family_datas_list',
                'rgt' => 16,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'familydata',
            ),
            27 => 
            array (
                'created_at' => '2021-01-05 02:25:36',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-clone\'></i>',
                'id' => 29,
                'label' => 'Relation',
                'lft' => 41,
                'parent_id' => 9,
                'permission' => 'relations_list',
                'rgt' => 42,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'relation',
            ),
            28 => 
            array (
                'created_at' => '2021-01-07 05:08:46',
                'depth' => 2,
                'icon' => '<i class="nav-icon las la-graduation-cap"></i>',
                'id' => 30,
                'label' => 'Educational Level',
                'lft' => 39,
                'parent_id' => 9,
                'permission' => 'educational_levels_list',
                'rgt' => 40,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'educationallevel',
            ),
            29 => 
            array (
                'created_at' => '2021-01-07 05:47:39',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-graduation-cap\'></i>',
                'id' => 31,
                'label' => 'Educational Bg.',
                'lft' => 13,
                'parent_id' => 2,
                'permission' => 'educational_backgrounds_list',
                'rgt' => 14,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'educationalbackground',
            ),
            30 => 
            array (
                'created_at' => '2021-01-07 10:04:28',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-medkit\'></i>',
                'id' => 32,
                'label' => 'Medical Information',
                'lft' => 19,
                'parent_id' => 2,
                'permission' => 'medical_informations_list',
                'rgt' => 20,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'medicalinformation',
            ),
            31 => 
            array (
                'created_at' => '2021-01-07 12:43:27',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-address-card\'></i>',
                'id' => 33,
                'label' => 'Professional Org.',
                'lft' => 21,
                'parent_id' => 2,
                'permission' => 'professional_orgs_list',
                'rgt' => 22,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'professionalorg',
            ),
            32 => 
            array (
                'created_at' => '2021-01-08 07:10:38',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-user-shield\'></i>',
                'id' => 34,
                'label' => 'Beneficiaries',
                'lft' => 9,
                'parent_id' => 2,
                'permission' => 'benefeciaries_list',
                'rgt' => 10,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'beneficiary',
            ),
            33 => 
            array (
                'created_at' => '2021-01-09 07:52:23',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-baby\'></i>',
                'id' => 35,
                'label' => 'Dependents',
                'lft' => 11,
                'parent_id' => 2,
                'permission' => 'dependents_list',
                'rgt' => 12,
                'updated_at' => '2021-01-09 11:09:00',
                'url' => 'dependents',
            ),
            34 => 
            array (
                'created_at' => '2021-01-09 11:08:45',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-music\'></i>',
                'id' => 36,
                'label' => 'Skills & Talents',
                'lft' => 23,
                'parent_id' => 2,
                'permission' => 'skill_and_talents_list',
                'rgt' => 24,
                'updated_at' => '2021-01-09 11:26:27',
                'url' => 'skillandtalent',
            ),
        ));
        
        
    }
}