<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config values
    |--------------------------------------------------------------------------
    |
    | This file is for some configuration for auto seeders.
    | After adding value here run: `php artisan db:seed` to persist to DB
    | or remove roles/permissions to DB
    |
    */



    /*
    |--------------------------------------------------------------------------
    | NOTE:: Roles are not sync with DB
    |--------------------------------------------------------------------------
    | Manually created roles wont be deleted here or sync
    | to allow user to create custom roles with diff. assign permissions
    */
    'roles' => [
    	'user', 
    	'role', 
    	'permission',
        'employee',
        'civil_status',
        'blood_type',
        'gender',
        'citizenship',
        'religion',
    ],

    
    /*
    |--------------------------------------------------------------------------
    | NOTE:: Permissions are sync with DB
    |--------------------------------------------------------------------------
    | Do not add manually permissions in Permissions CRUD(user interface) instead add it here
    | check here for more info: App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    */
    'permissions' => [
        'list',
        'create', 
        'update', 
        'delete', 
        'bulk_delete',
    ],



    /*
    |--------------------------------------------------------------------------
    | NOTE:: Are sync with DB
    |--------------------------------------------------------------------------
    */
    'admin_role_permissions' => [
        'admin_view',
        'admin_force_delete',
        'admin_force_bulk_delete',
        'admin_revise',
    ],

];


	
