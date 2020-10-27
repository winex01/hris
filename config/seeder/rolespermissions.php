<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config values
    |--------------------------------------------------------------------------
    |
    | This file is for some configuration for auto seeders.
    | After adding value here run: `php artisan db:seed` to persist to db
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
    ],

    'permissions' => [
    	'add', 
    	'edit', 
    	'delete', 
    	'view',
    ],

    'special_permissions' => [
    	'super_admin',
    ],

];


	
