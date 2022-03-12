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

    // carbon instance time format
    'carbon_time_format' => 'h:i A', // g:i A = 12 hours format, H:i = 24 hour format

    // date format of entire app
    'date_column_format' => 'text', // date / text

    'date_format_field' => 'MM/DD/YYYY',

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

    // decimal preciss
    'inputbox_decimal_precision' => 'any',

    //overrided at backpack settings
    'log_query' => env('LOG_QUERY', false),

    // calendar legend boxes color
    'legend_info'      => '#3a87ad',
    'legend_success'   => '#42ba96',
    'legend_primary'   => '#9933cc',
    'legend_warning'   => '#f88804',
    'legend_secondary' => '#f3969a',
];
