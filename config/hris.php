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

    // file location 
    'how_to_input_days_per_year_file' => 'files/AnnexB.pdf',

    //overrided at backpack settings
    'log_query' => env('LOG_QUERY', false),
];
