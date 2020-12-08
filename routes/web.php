<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect()->route('backpack.auth.login');
});

Route::get('/pl', function () {
	
	// $rolePrefix = 'audit_trail';
	// $permissions = auth()->user()->getPermissionsViaRoles()->filter(function ($item) use ($rolePrefix) {
	// 	return false !== stristr($item->name, $rolePrefix);
	// })->pluck('name'); 


	$permissions = auth()->user()->getAllPermissions()->pluck('name')->sort();

	dd(
		
		$permissions
	);

});


Route::get('/test', function () {

	$audit = \App\Models\AuditTrail::select('revisionable_type')
			->groupBy('revisionable_type')
			->pluck('revisionable_type');

	$audit = $audit->mapWithKeys(function ($item) {
	    // return [$item['email'] => $item['name']];
	    return [$item => $item];
	});

	dd($audit);

});


