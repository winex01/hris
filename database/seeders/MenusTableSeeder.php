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
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-dashboard "></i>',
                'id' => 1,
                'label' => 'Dashboard',
                'lft' => 3,
                'parent_id' => 67,
                'permission' => NULL,
                'rgt' => 4,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'dashboard',
            ),
            1 => 
            array (
                'created_at' => '2020-12-16 07:00:21',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-user"></i>',
                'id' => 2,
                'label' => 'Employee Records',
                'lft' => 8,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 37,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            2 => 
            array (
                'created_at' => '2020-12-16 07:11:38',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-trophy\'></i>',
                'id' => 3,
                'label' => 'Award And Recognition\'s',
                'lft' => 11,
                'parent_id' => 2,
                'permission' => 'award_and_recognitions_list',
                'rgt' => 12,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'awardandrecognition',
            ),
            3 => 
            array (
                'created_at' => '2020-12-16 07:12:30',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-user-plus\'></i>',
                'id' => 4,
                'label' => 'Employees',
                'lft' => 9,
                'parent_id' => 2,
                'permission' => 'employees_list',
                'rgt' => 10,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'employee',
            ),
            4 => 
            array (
                'created_at' => '2020-12-16 07:13:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-industry\'></i>',
                'id' => 5,
                'label' => 'Government Examinations',
                'lft' => 23,
                'parent_id' => 2,
                'permission' => 'government_examinations_list',
                'rgt' => 24,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'governmentexamination',
            ),
            5 => 
            array (
                'created_at' => '2020-12-16 07:13:57',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-file-alt\'></i>',
                'id' => 6,
                'label' => 'Supporting Documents',
                'lft' => 31,
                'parent_id' => 2,
                'permission' => 'supporting_documents_list',
                'rgt' => 32,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'supportingdocument',
            ),
            6 => 
            array (
                'created_at' => '2020-12-16 07:20:23',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-bicycle\'></i>',
                'id' => 7,
                'label' => 'Training & Seminars',
                'lft' => 33,
                'parent_id' => 2,
                'permission' => 'training_and_seminars_list',
                'rgt' => 34,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'trainingandseminar',
            ),
            7 => 
            array (
                'created_at' => '2020-12-16 07:20:46',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-bolt\'></i>',
                'id' => 8,
                'label' => 'Work Experiences',
                'lft' => 35,
                'parent_id' => 2,
                'permission' => 'work_experiences_list',
                'rgt' => 36,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'workexperience',
            ),
            8 => 
            array (
                'created_at' => '2020-12-16 07:21:07',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-cog"></i>',
                'id' => 9,
                'label' => 'App Settings',
                'lft' => 60,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 125,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            9 => 
            array (
                'created_at' => '2020-12-16 07:21:32',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-eyedropper\'></i>',
                'id' => 10,
                'label' => 'Blood Type',
                'lft' => 65,
                'parent_id' => 9,
                'permission' => 'blood_types_list',
                'rgt' => 66,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'bloodtype',
            ),
            10 => 
            array (
                'created_at' => '2020-12-16 07:21:51',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-flag-o\'></i>',
                'id' => 11,
                'label' => 'Citizenship',
                'lft' => 67,
                'parent_id' => 9,
                'permission' => 'citizenships_list',
                'rgt' => 68,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'citizenship',
            ),
            11 => 
            array (
                'created_at' => '2020-12-16 07:22:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-neuter\'></i>',
                'id' => 12,
                'label' => 'Civil Status',
                'lft' => 69,
                'parent_id' => 9,
                'permission' => 'civil_statuses_list',
                'rgt' => 70,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'civilstatus',
            ),
            12 => 
            array (
                'created_at' => '2020-12-16 07:22:32',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-venus\'></i>',
                'id' => 13,
                'label' => 'Gender',
                'lft' => 85,
                'parent_id' => 9,
                'permission' => 'genders_list',
                'rgt' => 86,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'gender',
            ),
            13 => 
            array (
                'created_at' => '2020-12-16 07:22:56',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-church\'></i>',
                'id' => 14,
                'label' => 'Religion',
                'lft' => 113,
                'parent_id' => 9,
                'permission' => 'religions_list',
                'rgt' => 114,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'religion',
            ),
            14 => 
            array (
                'created_at' => '2020-12-16 07:23:11',
                'depth' => 1,
                'icon' => NULL,
                'id' => 15,
                'label' => 'Administrator Only',
                'lft' => 126,
                'parent_id' => NULL,
                'permission' => 'admin_view',
                'rgt' => 127,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            15 => 
            array (
                'created_at' => '2020-12-16 07:26:27',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon la la-history\'></i>',
                'id' => 16,
                'label' => 'Audit Trails',
                'lft' => 128,
                'parent_id' => NULL,
                'permission' => 'audit_trails_list',
                'rgt' => 129,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'audittrail',
            ),
            16 => 
            array (
                'created_at' => '2020-12-16 07:27:02',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-users"></i>',
                'id' => 17,
                'label' => 'Authentication',
                'lft' => 130,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 137,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            17 => 
            array (
                'created_at' => '2020-12-16 07:27:32',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-user"></i>',
                'id' => 18,
                'label' => 'Users',
                'lft' => 131,
                'parent_id' => 17,
                'permission' => 'users_list',
                'rgt' => 132,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'user',
            ),
            18 => 
            array (
                'created_at' => '2020-12-16 07:27:49',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-id-badge"></i>',
                'id' => 19,
                'label' => 'Roles',
                'lft' => 133,
                'parent_id' => 17,
                'permission' => 'roles_list',
                'rgt' => 134,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'role',
            ),
            19 => 
            array (
                'created_at' => '2020-12-16 07:28:08',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-key"></i>',
                'id' => 20,
                'label' => 'Permissions',
                'lft' => 135,
                'parent_id' => 17,
                'permission' => 'permissions_list',
                'rgt' => 136,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'permission',
            ),
            20 => 
            array (
                'created_at' => '2020-12-16 07:28:27',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-cogs"></i>',
                'id' => 21,
                'label' => 'Advanced',
                'lft' => 138,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 147,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => NULL,
            ),
            21 => 
            array (
                'created_at' => '2020-12-16 07:30:40',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-files-o"></i>',
                'id' => 22,
                'label' => 'File Manager',
                'lft' => 139,
                'parent_id' => 21,
                'permission' => 'advanced_file_manager',
                'rgt' => 140,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'elfinder',
            ),
            22 => 
            array (
                'created_at' => '2020-12-16 07:31:21',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-hdd-o\'></i>',
                'id' => 23,
                'label' => 'Backups',
                'lft' => 141,
                'parent_id' => 21,
                'permission' => 'advanced_backups',
                'rgt' => 142,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'backup',
            ),
            23 => 
            array (
                'created_at' => '2020-12-16 07:31:47',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-terminal\'></i>',
                'id' => 24,
                'label' => 'Logs',
                'lft' => 143,
                'parent_id' => 21,
                'permission' => 'advanced_logs',
                'rgt' => 144,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'log',
            ),
            24 => 
            array (
                'created_at' => '2020-12-16 07:32:02',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-cog\'></i>',
                'id' => 25,
                'label' => 'Settings',
                'lft' => 145,
                'parent_id' => 21,
                'permission' => 'advanced_settings',
                'rgt' => 146,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'setting',
            ),
            25 => 
            array (
                'created_at' => '2020-12-16 07:32:42',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon la la-list\'></i>',
                'id' => 26,
                'label' => 'Menu',
                'lft' => 148,
                'parent_id' => NULL,
                'permission' => 'menus_list',
                'rgt' => 149,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'menu',
            ),
            26 => 
            array (
                'created_at' => '2020-12-31 09:52:25',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-clone\'></i>',
                'id' => 28,
                'label' => 'Family & Contacts',
                'lft' => 21,
                'parent_id' => 2,
                'permission' => 'family_datas_list',
                'rgt' => 22,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'familydata',
            ),
            27 => 
            array (
                'created_at' => '2021-01-05 02:25:36',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-clone\'></i>',
                'id' => 29,
                'label' => 'Relation',
                'lft' => 111,
                'parent_id' => 9,
                'permission' => 'relations_list',
                'rgt' => 112,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'relation',
            ),
            28 => 
            array (
                'created_at' => '2021-01-07 05:08:46',
                'depth' => 2,
                'icon' => '<i class="nav-icon las la-graduation-cap"></i>',
                'id' => 30,
                'label' => 'Educational Level',
                'lft' => 79,
                'parent_id' => 9,
                'permission' => 'educational_levels_list',
                'rgt' => 80,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'educationallevel',
            ),
            29 => 
            array (
                'created_at' => '2021-01-07 05:47:39',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-graduation-cap\'></i>',
                'id' => 31,
                'label' => 'Educational Backgrounds',
                'lft' => 17,
                'parent_id' => 2,
                'permission' => 'educational_backgrounds_list',
                'rgt' => 18,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'educationalbackground',
            ),
            30 => 
            array (
                'created_at' => '2021-01-07 10:04:28',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-medkit\'></i>',
                'id' => 32,
                'label' => 'Medical Information',
                'lft' => 25,
                'parent_id' => 2,
                'permission' => 'medical_informations_list',
                'rgt' => 26,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'medicalinformation',
            ),
            31 => 
            array (
                'created_at' => '2021-01-07 12:43:27',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-address-card\'></i>',
                'id' => 33,
                'label' => 'Professional Organizations',
                'lft' => 27,
                'parent_id' => 2,
                'permission' => 'professional_orgs_list',
                'rgt' => 28,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'professionalorg',
            ),
            32 => 
            array (
                'created_at' => '2021-01-08 07:10:38',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-user-shield\'></i>',
                'id' => 34,
                'label' => 'Beneficiaries',
                'lft' => 13,
                'parent_id' => 2,
                'permission' => 'benefeciaries_list',
                'rgt' => 14,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'beneficiary',
            ),
            33 => 
            array (
                'created_at' => '2021-01-09 07:52:23',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-baby\'></i>',
                'id' => 35,
                'label' => 'Dependents',
                'lft' => 15,
                'parent_id' => 2,
                'permission' => 'dependents_list',
                'rgt' => 16,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'dependents',
            ),
            34 => 
            array (
                'created_at' => '2021-01-09 11:08:45',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-music\'></i>',
                'id' => 36,
                'label' => 'Skills & Talents',
                'lft' => 29,
                'parent_id' => 2,
                'permission' => 'skill_and_talents_list',
                'rgt' => 30,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'skillandtalent',
            ),
            35 => 
            array (
                'created_at' => '2021-01-11 09:58:11',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-credit-card\'></i>',
                'id' => 37,
                'label' => 'Payment Method',
                'lft' => 105,
                'parent_id' => 9,
                'permission' => 'payment_methods_list',
                'rgt' => 106,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'paymentmethod',
            ),
            36 => 
            array (
                'created_at' => '2021-01-12 04:58:02',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar-alt\'></i>',
                'id' => 38,
                'label' => 'Pay Basis',
                'lft' => 103,
                'parent_id' => 9,
                'permission' => 'pay_bases_list',
                'rgt' => 104,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'paybasis',
            ),
            37 => 
            array (
                'created_at' => '2021-01-12 05:36:56',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-user-tag\'></i>',
                'id' => 39,
                'label' => 'Job Status',
                'lft' => 93,
                'parent_id' => 9,
                'permission' => 'job_statuses_list',
                'rgt' => 94,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'jobstatus',
            ),
            38 => 
            array (
                'created_at' => '2021-01-12 07:05:31',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-user-tie\'></i>',
                'id' => 40,
                'label' => 'Employment Status',
                'lft' => 83,
                'parent_id' => 9,
                'permission' => 'employment_statuses_list',
                'rgt' => 84,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'employmentstatus',
            ),
            39 => 
            array (
                'created_at' => '2021-01-12 07:30:05',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-building\'></i>',
                'id' => 41,
                'label' => 'Company',
                'lft' => 71,
                'parent_id' => 9,
                'permission' => 'companies_list',
                'rgt' => 72,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'company',
            ),
            40 => 
            array (
                'created_at' => '2021-01-12 10:59:14',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-map-marked-alt\'></i>',
                'id' => 42,
                'label' => 'Location',
                'lft' => 99,
                'parent_id' => 9,
                'permission' => 'locations_list',
                'rgt' => 100,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'location',
            ),
            41 => 
            array (
                'created_at' => '2021-01-12 13:29:06',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-layer-group\'></i>',
                'id' => 43,
                'label' => 'Department',
                'lft' => 75,
                'parent_id' => 9,
                'permission' => 'departments_list',
                'rgt' => 76,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'department',
            ),
            42 => 
            array (
                'created_at' => '2021-01-12 13:44:42',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-star-half-alt\'></i>',
                'id' => 44,
                'label' => 'Division',
                'lft' => 77,
                'parent_id' => 9,
                'permission' => 'divisions_list',
                'rgt' => 78,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'division',
            ),
            43 => 
            array (
                'created_at' => '2021-01-12 15:29:49',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-stream\'></i>',
                'id' => 45,
                'label' => 'Section',
                'lft' => 115,
                'parent_id' => 9,
                'permission' => 'sections_list',
                'rgt' => 116,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'section',
            ),
            44 => 
            array (
                'created_at' => '2021-01-13 04:50:01',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-hard-hat\'></i>',
                'id' => 46,
                'label' => 'Position',
                'lft' => 107,
                'parent_id' => 9,
                'permission' => 'positions_list',
                'rgt' => 108,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'position',
            ),
            45 => 
            array (
                'created_at' => '2021-01-13 23:10:39',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-balance-scale\'></i>',
                'id' => 47,
                'label' => 'Level',
                'lft' => 97,
                'parent_id' => 9,
                'permission' => 'levels_list',
                'rgt' => 98,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'level',
            ),
            46 => 
            array (
                'created_at' => '2021-01-13 23:30:25',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-chalkboard\'></i>',
                'id' => 48,
                'label' => 'Rank',
                'lft' => 109,
                'parent_id' => 9,
                'permission' => 'ranks_list',
                'rgt' => 110,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'rank',
            ),
            47 => 
            array (
                'created_at' => '2021-01-14 14:24:53',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-object-group\'></i>',
                'id' => 49,
                'label' => 'Grouping',
                'lft' => 89,
                'parent_id' => 9,
                'permission' => 'groupings_list',
                'rgt' => 90,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'grouping',
            ),
            48 => 
            array (
                'created_at' => '2021-01-16 02:11:31',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar\'></i>',
                'id' => 50,
                'label' => 'Days Per Year',
                'lft' => 73,
                'parent_id' => 9,
                'permission' => 'days_per_years_list',
                'rgt' => 74,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'daysperyear',
            ),
            49 => 
            array (
                'created_at' => '2021-01-18 06:07:20',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-address-book\'></i>',
                'id' => 51,
                'label' => 'Employment Info',
                'lft' => 19,
                'parent_id' => 2,
                'permission' => 'employment_informations_list',
                'rgt' => 20,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'employmentinformation',
            ),
            50 => 
            array (
                'created_at' => '2021-01-21 16:45:46',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-table\'></i>',
                'id' => 52,
                'label' => 'Employment Info Fields',
                'lft' => 81,
                'parent_id' => 9,
                'permission' => 'employment_info_fields_list',
                'rgt' => 82,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'employmentinfofield',
            ),
            51 => 
            array (
                'created_at' => '2021-01-28 14:40:46',
                'depth' => 1,
                'icon' => '<i class="nav-icon las la-chalkboard"></i>',
                'id' => 53,
                'label' => 'Performance Mgt.',
                'lft' => 38,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 43,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            52 => 
            array (
                'created_at' => '2021-01-28 14:51:05',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-hands-helping\'></i>',
                'id' => 54,
                'label' => 'Performance Appraisals',
                'lft' => 41,
                'parent_id' => 53,
                'permission' => 'performance_appraisals_list',
                'rgt' => 42,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'performanceappraisal',
            ),
            53 => 
            array (
                'created_at' => '2021-01-29 02:51:46',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-project-diagram\'></i>',
                'id' => 55,
                'label' => 'Appraisal Types',
                'lft' => 63,
                'parent_id' => 9,
                'permission' => 'appraisal_types_list',
                'rgt' => 64,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'appraisaltype',
            ),
            54 => 
            array (
                'created_at' => '2021-01-29 03:12:19',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-award\'></i>',
                'id' => 56,
                'label' => 'Appraisal Interpretations',
                'lft' => 61,
                'parent_id' => 9,
                'permission' => 'appraisal_interpretations_list',
                'rgt' => 62,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'appraisalinterpretation',
            ),
            55 => 
            array (
                'created_at' => '2021-02-08 20:06:19',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-grimace\'></i>',
                'id' => 57,
                'label' => 'Offence Classifications',
                'lft' => 101,
                'parent_id' => 9,
                'permission' => 'offence_classifications_list',
                'rgt' => 102,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'offenceclassification',
            ),
            56 => 
            array (
                'created_at' => '2021-02-09 18:51:28',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-dizzy\'></i>',
                'id' => 58,
                'label' => 'Gravity Of Sanction',
                'lft' => 87,
                'parent_id' => 9,
                'permission' => 'gravity_of_sanctions_list',
                'rgt' => 88,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'gravityofsanction',
            ),
            57 => 
            array (
                'created_at' => '2021-02-09 19:07:27',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-book-reader\'></i>',
                'id' => 59,
                'label' => 'Offence & Sanction',
                'lft' => 39,
                'parent_id' => 53,
                'permission' => 'offence_and_sanctions_list',
                'rgt' => 40,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'offenceandsanction',
            ),
            58 => 
            array (
                'created_at' => '2021-02-12 20:40:44',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar\'></i>',
                'id' => 60,
                'label' => 'Shift Schedules',
                'lft' => 117,
                'parent_id' => 9,
                'permission' => 'shift_schedules_list',
                'rgt' => 118,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'shiftschedules',
            ),
            59 => 
            array (
                'created_at' => '2021-02-12 20:44:32',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon las la-hourglass-half\'></i>',
                'id' => 61,
                'label' => 'Daily Time Records',
                'lft' => 44,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 55,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            60 => 
            array (
                'created_at' => '2021-02-18 21:51:18',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-business-time\'></i>',
                'id' => 62,
                'label' => 'Employee Shift Schedules',
                'lft' => 49,
                'parent_id' => 61,
                'permission' => 'employee_shift_schedules_list',
                'rgt' => 50,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'employeeshiftschedule',
            ),
            61 => 
            array (
                'created_at' => '2021-03-01 12:22:07',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar-check\'></i>',
                'id' => 63,
                'label' => 'Change Shift Schedules',
                'lft' => 51,
                'parent_id' => 61,
                'permission' => 'change_shift_schedules_list',
                'rgt' => 52,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'changeshiftschedule',
            ),
            62 => 
            array (
                'created_at' => '2021-03-12 13:26:50',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar-times\'></i>',
                'id' => 64,
                'label' => 'Holidays',
                'lft' => 91,
                'parent_id' => 9,
                'permission' => 'holidays_list',
                'rgt' => 92,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'holiday',
            ),
            63 => 
            array (
                'created_at' => '2021-04-04 23:55:28',
                'depth' => 1,
                'icon' => '<i class=\'nav-icon lab la-laravel\'></i>',
                'id' => 65,
                'label' => 'Web Artisan Tinker',
                'lft' => 150,
                'parent_id' => NULL,
                'permission' => 'admin_web_tinker',
                'rgt' => 151,
                'updated_at' => '2021-10-09 18:32:31',
                'url' => 'tinker',
            ),
            64 => 
            array (
                'created_at' => '2021-04-10 11:36:26',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar-alt\'></i>',
                'id' => 66,
                'label' => 'DTR Logs',
                'lft' => 47,
                'parent_id' => 61,
                'permission' => 'dtr_logs_list',
                'rgt' => 48,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'dtrlogs',
            ),
            65 => 
            array (
                'created_at' => '2021-08-04 15:43:05',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-home"></i>',
                'id' => 67,
                'label' => 'Home',
                'lft' => 2,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 7,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            66 => 
            array (
                'created_at' => '2021-08-04 15:45:26',
                'depth' => 2,
                'icon' => '<li class="nav-item clock"> <a class="nav-link" href="#" title="Employee Time Clock"> <i class=\'nav-icon las la-clock\'></i>  My Clock </a> </li>',
                'id' => 68,
                'label' => 'My Clock',
                'lft' => 5,
                'parent_id' => 67,
                'permission' => 'employee_time_clock_show',
                'rgt' => 6,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => '#',
            ),
            67 => 
            array (
                'created_at' => '2021-08-05 19:20:11',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-users\'></i>',
                'id' => 69,
                'label' => 'Teams',
                'lft' => 119,
                'parent_id' => 9,
                'permission' => 'teams_list',
                'rgt' => 120,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'team',
            ),
            68 => 
            array (
                'created_at' => '2021-08-11 00:51:17',
                'depth' => 2,
                'icon' => '<i class="nav-icon la la-money-check"></i>',
                'id' => 70,
                'label' => 'Payroll Periods',
                'lft' => 57,
                'parent_id' => 71,
                'permission' => 'payroll_periods_list',
                'rgt' => 58,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'payrollperiod',
            ),
            69 => 
            array (
                'created_at' => '2021-08-11 00:52:46',
                'depth' => 1,
                'icon' => '<i class="nav-icon la la-th-list"></i>',
                'id' => 71,
                'label' => 'Payrolls',
                'lft' => 56,
                'parent_id' => NULL,
                'permission' => NULL,
                'rgt' => 59,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => NULL,
            ),
            70 => 
            array (
                'created_at' => '2021-08-17 00:35:55',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-question\'></i>',
                'id' => 72,
                'label' => 'Withholding Tax Versions',
                'lft' => 123,
                'parent_id' => 9,
                'permission' => 'withholding_tax_versions_list',
                'rgt' => 124,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'withholdingtaxversion',
            ),
            71 => 
            array (
                'created_at' => '2021-08-19 20:21:27',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon la la-question\'></i>',
                'id' => 73,
                'label' => 'Withholding Tax Basis',
                'lft' => 121,
                'parent_id' => 9,
                'permission' => 'withholding_tax_bases_list',
                'rgt' => 122,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'withholdingtaxbasis',
            ),
            72 => 
            array (
                'created_at' => '2021-09-29 00:51:57',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-calendar-day\'></i>',
                'id' => 74,
                'label' => 'Daily Time Records',
                'lft' => 45,
                'parent_id' => 61,
                'permission' => 'daily_time_records_list',
                'rgt' => 46,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'dailytimerecord',
            ),
            73 => 
            array (
                'created_at' => '2021-10-07 23:04:36',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-shapes\'></i>',
                'id' => 75,
                'label' => 'Leave Types',
                'lft' => 95,
                'parent_id' => 9,
                'permission' => 'leave_types_list',
                'rgt' => 96,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'leavetype',
            ),
            74 => 
            array (
                'created_at' => '2021-10-09 18:31:58',
                'depth' => 2,
                'icon' => '<i class=\'nav-icon las la-stream\'></i>',
                'id' => 76,
                'label' => 'Leave Credits',
                'lft' => 53,
                'parent_id' => 61,
                'permission' => 'leave_credits_list',
                'rgt' => 54,
                'updated_at' => '2021-10-09 18:32:30',
                'url' => 'leavecredit',
            ),
        ));
        
        
    }
}