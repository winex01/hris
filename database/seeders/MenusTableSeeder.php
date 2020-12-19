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
                'icon' => '<i class="la la-home nav-icon"></i>',
                'id' => 1,
                'label' => 'Dashboard',
                'lft' => 2,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 3,
                'updated_at' => '2020-12-19 03:34:54',
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
                'rgt' => 19,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => NULL,
            ),
            2 => 
            array (
                'created_at' => '2020-12-16 07:11:38',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-trophy\'></i>',
                'id' => 3,
                'label' => 'Awards & Recog',
                'lft' => 9,
                'parent_id' => 2,
                'permission' => 'award_and_recognitions_list',
                'rgt' => 10,
                'updated_at' => '2020-12-19 03:34:54',
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
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'employee',
            ),
            4 => 
            array (
                'created_at' => '2020-12-16 07:13:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-industry\'></i>',
                'id' => 5,
                'label' => 'Gov. Exams',
                'lft' => 11,
                'parent_id' => 2,
                'permission' => 'government_examinations_list',
                'rgt' => 12,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'governmentexamination',
            ),
            5 => 
            array (
                'created_at' => '2020-12-16 07:13:57',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-file-o\'></i>',
                'id' => 6,
                'label' => 'Supporting Docs.',
                'lft' => 13,
                'parent_id' => 2,
                'permission' => 'supporting_documents_list',
                'rgt' => 14,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'supportingdocument',
            ),
            6 => 
            array (
                'created_at' => '2020-12-16 07:20:23',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-bicycle\'></i>',
                'id' => 7,
                'label' => 'Training & Seminar',
                'lft' => 15,
                'parent_id' => 2,
                'permission' => 'trainings_and_seminars_list',
                'rgt' => 16,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'trainingsandseminar',
            ),
            7 => 
            array (
                'created_at' => '2020-12-16 07:20:46',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-bolt\'></i>',
                'id' => 8,
                'label' => 'Work Experiences',
                'lft' => 17,
                'parent_id' => 2,
                'permission' => 'work_experiences_list',
                'rgt' => 18,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'workexperience',
            ),
            8 => 
            array (
                'created_at' => '2020-12-16 07:21:07',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-cog"></i>',
                'id' => 9,
                'label' => 'App Settings',
                'lft' => 20,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 31,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => NULL,
            ),
            9 => 
            array (
                'created_at' => '2020-12-16 07:21:32',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-eyedropper\'></i>',
                'id' => 10,
                'label' => 'Blood Type',
                'lft' => 21,
                'parent_id' => 9,
                'permission' => 'blood_types_list',
                'rgt' => 22,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'bloodtype',
            ),
            10 => 
            array (
                'created_at' => '2020-12-16 07:21:51',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-flag-o\'></i>',
                'id' => 11,
                'label' => 'Citizenship',
                'lft' => 23,
                'parent_id' => 9,
                'permission' => 'citizenships_list',
                'rgt' => 24,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'citizenship',
            ),
            11 => 
            array (
                'created_at' => '2020-12-16 07:22:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-neuter\'></i>',
                'id' => 12,
                'label' => 'Civil Status',
                'lft' => 25,
                'parent_id' => 9,
                'permission' => 'civil_statuses_list',
                'rgt' => 26,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'civilstatus',
            ),
            12 => 
            array (
                'created_at' => '2020-12-16 07:22:32',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-venus\'></i>',
                'id' => 13,
                'label' => 'Gender',
                'lft' => 27,
                'parent_id' => 9,
                'permission' => 'genders_list',
                'rgt' => 28,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'gender',
            ),
            13 => 
            array (
                'created_at' => '2020-12-16 07:22:56',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-eye\'></i>',
                'id' => 14,
                'label' => 'Religion',
                'lft' => 29,
                'parent_id' => 9,
                'permission' => 'religions_list',
                'rgt' => 30,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'religion',
            ),
            14 => 
            array (
                'created_at' => '2020-12-16 07:23:11',
                'depth' => 1,
                'icon' => NULL,
                'id' => 15,
                'label' => 'Administrator Only',
                'lft' => 32,
                'parent_id' => NULL,
                'permission' => 'admin_view',
                'rgt' => 33,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => NULL,
            ),
            15 => 
            array (
                'created_at' => '2020-12-16 07:26:27',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon la la-history\'></i>',
                'id' => 16,
                'label' => 'Audit Trails',
                'lft' => 34,
                'parent_id' => NULL,
                'permission' => 'audit_trails_list',
                'rgt' => 35,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'audittrail',
            ),
            16 => 
            array (
                'created_at' => '2020-12-16 07:27:02',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-users"></i>',
                'id' => 17,
                'label' => 'Authentication',
                'lft' => 36,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 43,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => NULL,
            ),
            17 => 
            array (
                'created_at' => '2020-12-16 07:27:32',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-user"></i>',
                'id' => 18,
                'label' => 'Users',
                'lft' => 37,
                'parent_id' => 17,
                'permission' => 'users_list',
                'rgt' => 38,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'user',
            ),
            18 => 
            array (
                'created_at' => '2020-12-16 07:27:49',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-id-badge"></i>',
                'id' => 19,
                'label' => 'Roles',
                'lft' => 39,
                'parent_id' => 17,
                'permission' => 'roles_list',
                'rgt' => 40,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'role',
            ),
            19 => 
            array (
                'created_at' => '2020-12-16 07:28:08',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-key"></i>',
                'id' => 20,
                'label' => 'Permissions',
                'lft' => 41,
                'parent_id' => 17,
                'permission' => 'permissions_list',
                'rgt' => 42,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'permission',
            ),
            20 => 
            array (
                'created_at' => '2020-12-16 07:28:27',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-cogs"></i>',
                'id' => 21,
                'label' => 'Advanced',
                'lft' => 44,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 53,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => NULL,
            ),
            21 => 
            array (
                'created_at' => '2020-12-16 07:30:40',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-files-o"></i>',
                'id' => 22,
                'label' => 'File Manager',
                'lft' => 45,
                'parent_id' => 21,
                'permission' => 'advanced_file_manager',
                'rgt' => 46,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'elfinder',
            ),
            22 => 
            array (
                'created_at' => '2020-12-16 07:31:21',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-hdd-o\'></i>',
                'id' => 23,
                'label' => 'Backups',
                'lft' => 47,
                'parent_id' => 21,
                'permission' => 'advanced_backups',
                'rgt' => 48,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'backup',
            ),
            23 => 
            array (
                'created_at' => '2020-12-16 07:31:47',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-terminal\'></i>',
                'id' => 24,
                'label' => 'Logs',
                'lft' => 49,
                'parent_id' => 21,
                'permission' => 'advanced_logs',
                'rgt' => 50,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'log',
            ),
            24 => 
            array (
                'created_at' => '2020-12-16 07:32:02',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-cog\'></i>',
                'id' => 25,
                'label' => 'Settings',
                'lft' => 51,
                'parent_id' => 21,
                'permission' => 'advanced_settings',
                'rgt' => 52,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'setting',
            ),
            25 => 
            array (
                'created_at' => '2020-12-16 07:32:42',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon la la-list\'></i>',
                'id' => 26,
                'label' => 'Menu',
                'lft' => 54,
                'parent_id' => NULL,
                'permission' => 'menus_list',
                'rgt' => 55,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'menu',
            ),
            26 => 
            array (
                'created_at' => '2020-12-19 03:34:44',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-file\'></i>',
                'id' => 27,
                'label' => 'Personal Data',
                'lft' => 7,
                'parent_id' => 2,
                'permission' => 'personal_datas_list',
                'rgt' => 8,
                'updated_at' => '2020-12-19 03:34:54',
                'url' => 'personaldata',
            ),
        ));
        
        
    }
}