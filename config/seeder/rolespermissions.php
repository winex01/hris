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

    'roles' => [
    	'user', 
    	'role', 
    	'permission',
        'employee',
        'civil status',
        'blood type',
        'gender',
        'citizenship',
        'religion',
        'personal data',
    ],

    'permissions' => [
        'list',
        'create', 
        'update', 
        'delete', 
        'bulk_delete',
    ],

    'special_permissions' => [
    	'admin_view',
        'admin_force_delete',
        'admin_force_bulk_delete',
    ],

];


	
