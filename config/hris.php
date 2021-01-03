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

    // attachments file limit in KB
    'attachment_file_limit' => 500, 

    //overrided at backpack settings
    'log_query' => env('LOG_QUERY', false),

    // decimal precision
    'decimal_precision' => 2,

    // don't include this columns in exports
    'dont_include_in_exports' => [
        'attachment',
        'email_verified_at',
        'file_link',
        'image',
        'password',
        'photo',
        'remember_token',
    ],
];
