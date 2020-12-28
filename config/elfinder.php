<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public).
    |
    */
    // 'dir' => ['uploads'],
    'dir' => ['storage'],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => [
        // 'uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */
    'route' => [
        'prefix'     => config('backpack.base.route_prefix', 'admin').'/elfinder',
        'middleware' => [
            'web', 
            config('backpack.base.middleware_key', 'admin'),
            '\App\Http\Middleware\CheckAccessFileManager'
        ], //Set to null to disable middleware filter
    ],

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */
    'roots'  => array(
        array(
            // Group for local volume (elFinder >= 2.1.15)
            'alias'        => 'LocalVolumes',
            'driver'       => 'Group',
            'id'           => 'l',
            'rootCssClass' => 'elfinder-navbar-root-local'
        ),
        // public
        array(
            'phash'  => 'gl_Lw', // set parent to 'LocalVolumes'
            'driver' => 'LocalFileSystem',
            'path'   => storage_path('app/public'),
            'URL'    => '/storage'
        ),
        // log files
        array(
            'phash'  => 'gl_Lw', 
            'driver' => 'LocalFileSystem',
            'path'   => storage_path('logs'),
            'URL'    => '/storage'
        ),
        // backup
        array(
            'phash'  => 'gl_Lw', 
            'driver' => 'LocalFileSystem',
            'path'   => storage_path('backups'),
            'URL'    => '/storage'
        ),

    ),


    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => [],

];
