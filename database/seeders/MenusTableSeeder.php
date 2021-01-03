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
                'id' => 1,
                'label' => 'Dashboard',
                'url' => 'dashboard',
                'icon' => '<i class="la la-home nav-icon"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 2,
                'rgt' => 3,
                'depth' => 1,
                'created_at' => '2020-12-16 06:58:25',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            1 => 
            array (
                'id' => 2,
                'label' => 'Employee Records',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-user"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 4,
                'rgt' => 21,
                'depth' => 1,
                'created_at' => '2020-12-16 07:00:21',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            2 => 
            array (
                'id' => 3,
                'label' => 'Awards & Recog.',
                'url' => 'awardandrecognition',
                'icon' => '<i class=\'nav-icon la la-trophy\'></i>',
                'permission' => 'award_and_recognitions_list',
                'parent_id' => 2,
                'lft' => 7,
                'rgt' => 8,
                'depth' => 2,
                'created_at' => '2020-12-16 07:11:38',
                'updated_at' => '2021-01-03 18:42:08',
            ),
            3 => 
            array (
                'id' => 4,
                'label' => 'Employees',
                'url' => 'employee',
                'icon' => '<i class=\'nav-icon la la-user-plus\'></i>',
                'permission' => 'employees_list',
                'parent_id' => 2,
                'lft' => 5,
                'rgt' => 6,
                'depth' => 2,
                'created_at' => '2020-12-16 07:12:30',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            4 => 
            array (
                'id' => 5,
                'label' => 'Gov. Exams',
                'url' => 'governmentexamination',
                'icon' => '<i class=\'nav-icon la la-industry\'></i>',
                'permission' => 'government_examinations_list',
                'parent_id' => 2,
                'lft' => 11,
                'rgt' => 12,
                'depth' => 2,
                'created_at' => '2020-12-16 07:13:14',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            5 => 
            array (
                'id' => 6,
                'label' => 'Supporting Docs.',
                'url' => 'supportingdocument',
                'icon' => '<i class=\'nav-icon la la-file-o\'></i>',
                'permission' => 'supporting_documents_list',
                'parent_id' => 2,
                'lft' => 15,
                'rgt' => 16,
                'depth' => 2,
                'created_at' => '2020-12-16 07:13:57',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            6 => 
            array (
                'id' => 7,
                'label' => 'Training & Seminars',
                'url' => 'trainingsandseminar',
                'icon' => '<i class=\'nav-icon la la-bicycle\'></i>',
                'permission' => 'trainings_and_seminars_list',
                'parent_id' => 2,
                'lft' => 17,
                'rgt' => 18,
                'depth' => 2,
                'created_at' => '2020-12-16 07:20:23',
                'updated_at' => '2021-01-03 18:41:49',
            ),
            7 => 
            array (
                'id' => 8,
                'label' => 'Work Experiences',
                'url' => 'workexperience',
                'icon' => '<i class=\'nav-icon la la-bolt\'></i>',
                'permission' => 'work_experiences_list',
                'parent_id' => 2,
                'lft' => 19,
                'rgt' => 20,
                'depth' => 2,
                'created_at' => '2020-12-16 07:20:46',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            8 => 
            array (
                'id' => 9,
                'label' => 'App Settings',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-cog"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 22,
                'rgt' => 33,
                'depth' => 1,
                'created_at' => '2020-12-16 07:21:07',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            9 => 
            array (
                'id' => 10,
                'label' => 'Blood Type',
                'url' => 'bloodtype',
                'icon' => '<i class=\'nav-icon la la-eyedropper\'></i>',
                'permission' => 'blood_types_list',
                'parent_id' => 9,
                'lft' => 23,
                'rgt' => 24,
                'depth' => 2,
                'created_at' => '2020-12-16 07:21:32',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            10 => 
            array (
                'id' => 11,
                'label' => 'Citizenship',
                'url' => 'citizenship',
                'icon' => '<i class=\'nav-icon la la-flag-o\'></i>',
                'permission' => 'citizenships_list',
                'parent_id' => 9,
                'lft' => 25,
                'rgt' => 26,
                'depth' => 2,
                'created_at' => '2020-12-16 07:21:51',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            11 => 
            array (
                'id' => 12,
                'label' => 'Civil Status',
                'url' => 'civilstatus',
                'icon' => '<i class=\'nav-icon la la-neuter\'></i>',
                'permission' => 'civil_statuses_list',
                'parent_id' => 9,
                'lft' => 27,
                'rgt' => 28,
                'depth' => 2,
                'created_at' => '2020-12-16 07:22:14',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            12 => 
            array (
                'id' => 13,
                'label' => 'Gender',
                'url' => 'gender',
                'icon' => '<i class=\'nav-icon la la-venus\'></i>',
                'permission' => 'genders_list',
                'parent_id' => 9,
                'lft' => 29,
                'rgt' => 30,
                'depth' => 2,
                'created_at' => '2020-12-16 07:22:32',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            13 => 
            array (
                'id' => 14,
                'label' => 'Religion',
                'url' => 'religion',
                'icon' => '<i class=\'nav-icon la la-eye\'></i>',
                'permission' => 'religions_list',
                'parent_id' => 9,
                'lft' => 31,
                'rgt' => 32,
                'depth' => 2,
                'created_at' => '2020-12-16 07:22:56',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            14 => 
            array (
                'id' => 15,
                'label' => 'Administrator Only',
                'url' => NULL,
                'icon' => NULL,
                'permission' => 'admin_view',
                'parent_id' => NULL,
                'lft' => 34,
                'rgt' => 35,
                'depth' => 1,
                'created_at' => '2020-12-16 07:23:11',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            15 => 
            array (
                'id' => 16,
                'label' => 'Audit Trails',
                'url' => 'audittrail',
                'icon' => '<i class=\'nav-icon la la-history\'></i>',
                'permission' => 'audit_trails_list',
                'parent_id' => NULL,
                'lft' => 36,
                'rgt' => 37,
                'depth' => 1,
                'created_at' => '2020-12-16 07:26:27',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            16 => 
            array (
                'id' => 17,
                'label' => 'Authentication',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-users"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 38,
                'rgt' => 45,
                'depth' => 1,
                'created_at' => '2020-12-16 07:27:02',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            17 => 
            array (
                'id' => 18,
                'label' => 'Users',
                'url' => 'user',
                'icon' => '<i class="nav-icon la la-user"></i>',
                'permission' => 'users_list',
                'parent_id' => 17,
                'lft' => 39,
                'rgt' => 40,
                'depth' => 2,
                'created_at' => '2020-12-16 07:27:32',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            18 => 
            array (
                'id' => 19,
                'label' => 'Roles',
                'url' => 'role',
                'icon' => '<i class="nav-icon la la-id-badge"></i>',
                'permission' => 'roles_list',
                'parent_id' => 17,
                'lft' => 41,
                'rgt' => 42,
                'depth' => 2,
                'created_at' => '2020-12-16 07:27:49',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            19 => 
            array (
                'id' => 20,
                'label' => 'Permissions',
                'url' => 'permission',
                'icon' => '<i class="nav-icon la la-key"></i>',
                'permission' => 'permissions_list',
                'parent_id' => 17,
                'lft' => 43,
                'rgt' => 44,
                'depth' => 2,
                'created_at' => '2020-12-16 07:28:08',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            20 => 
            array (
                'id' => 21,
                'label' => 'Advanced',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-cogs"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 46,
                'rgt' => 55,
                'depth' => 1,
                'created_at' => '2020-12-16 07:28:27',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            21 => 
            array (
                'id' => 22,
                'label' => 'File Manager',
                'url' => 'elfinder',
                'icon' => '<i class="nav-icon la la-files-o"></i>',
                'permission' => 'advanced_file_manager',
                'parent_id' => 21,
                'lft' => 47,
                'rgt' => 48,
                'depth' => 2,
                'created_at' => '2020-12-16 07:30:40',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            22 => 
            array (
                'id' => 23,
                'label' => 'Backups',
                'url' => 'backup',
                'icon' => '<i class=\'nav-icon la la-hdd-o\'></i>',
                'permission' => 'advanced_backups',
                'parent_id' => 21,
                'lft' => 49,
                'rgt' => 50,
                'depth' => 2,
                'created_at' => '2020-12-16 07:31:21',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            23 => 
            array (
                'id' => 24,
                'label' => 'Logs',
                'url' => 'log',
                'icon' => '<i class=\'nav-icon la la-terminal\'></i>',
                'permission' => 'advanced_logs',
                'parent_id' => 21,
                'lft' => 51,
                'rgt' => 52,
                'depth' => 2,
                'created_at' => '2020-12-16 07:31:47',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            24 => 
            array (
                'id' => 25,
                'label' => 'Settings',
                'url' => 'setting',
                'icon' => '<i class=\'nav-icon la la-cog\'></i>',
                'permission' => 'advanced_settings',
                'parent_id' => 21,
                'lft' => 53,
                'rgt' => 54,
                'depth' => 2,
                'created_at' => '2020-12-16 07:32:02',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            25 => 
            array (
                'id' => 26,
                'label' => 'Menu',
                'url' => 'menu',
                'icon' => '<i class=\'nav-icon la la-list\'></i>',
                'permission' => 'menus_list',
                'parent_id' => NULL,
                'lft' => 56,
                'rgt' => 57,
                'depth' => 1,
                'created_at' => '2020-12-16 07:32:42',
                'updated_at' => '2020-12-31 09:52:52',
            ),
            26 => 
            array (
                'id' => 28,
                'label' => 'Family & Contacts',
                'url' => 'familydata',
                'icon' => '<i class=\'nav-icon la la-foursquare\'></i>',
                'permission' => 'family_datas_list',
                'parent_id' => 2,
                'lft' => 9,
                'rgt' => 10,
                'depth' => 2,
                'created_at' => '2020-12-31 09:52:25',
                'updated_at' => '2021-01-03 18:41:10',
            ),
        ));
        
        
    }
}