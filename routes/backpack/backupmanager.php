<?php

/*
|--------------------------------------------------------------------------
| Backpack\BackupManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\BackupManager package.
|
*/

Route::group([
    'namespace'  => 'Backpack\BackupManager\app\Http\Controllers',
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => [
        'web', 
        config('backpack.base.middleware_key', 'admin'),
        '\App\Http\Middleware\CheckAccessBackups'
    ],
], function () {
    Route::get('backup', 'BackupController@index')->name('backup.index');
    Route::put('backup/create', 'BackupController@create')->name('backup.store');
    Route::get('backup/download/{file_name?}', 'BackupController@download')->name('backup.download');
    Route::delete('backup/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)')->name('backup.destroy');
});
