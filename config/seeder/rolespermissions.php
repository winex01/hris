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
    	'add', 
    	'edit', 
    	'delete', 
    	'view',
        'bulk_delete',
        'force_delete',
    ],

    'special_permissions' => [
    	'super_admin',
    ],

];


	
