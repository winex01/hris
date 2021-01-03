<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config values
    |--------------------------------------------------------------------------
    |
    | This file is for some configuration
    |
    */

    //overrided at backpack settings
    'log_query' => env('LOG_QUERY', false),

    // attachments file limit in KB
    'attachment_file_limit' => 500, 

    // donot include this columns in exports
    'dont_include_in_exports' => [
        'attachment',
        'image',
        'file_link',
        'email_verified_at',
        'password',
        'remember_token',
        'photo',
    ],

    // decimal precision
    'decimal_precision' => 2,

];
