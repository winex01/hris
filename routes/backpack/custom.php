<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('employee', 'EmployeeCrudController');
    Route::crud('civilstatus', 'CivilStatusCrudController');
    Route::crud('gender', 'GenderCrudController');
    Route::crud('bloodtype', 'BloodTypeCrudController');
    Route::crud('citizenship', 'CitizenshipCrudController');
    Route::crud('religion', 'ReligionCrudController');
}); // this should be the absolute last line of this file