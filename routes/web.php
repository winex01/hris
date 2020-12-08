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
	
	$data = [];
	
	// combine all permissions
	$permissions = [];
	foreach (config('seeder.rolespermissions.roles') as $role) {
		$temp = config('seeder.rolespermissions.permissions'); 
		foreach ($temp as $t) {
			$permissions[] = $role.'_'.$t;
		}
	}

	$specificPerms = collect(config('seeder.rolespermissions.specific_permissions'))->flatten()->unique()->toArray();
	$permissions = collect(array_merge($permissions, $specificPerms))->sort()->toArray();

	// loop combined roles
	$roles = array_merge(
		config('seeder.rolespermissions.roles'),
		collect(config('seeder.rolespermissions.specific_permissions'))->keys()->toArray()
	);
	$roles = collect($roles)->unique()->sort()->toArray();


	foreach ($roles as $role) {
		// filter using role
		$groupPermissions = collect($permissions)->filter(function ($item) use ($role) {
			return false !== stristr($item, $role.'_');
		});

		foreach ($groupPermissions as $perm) {
			$value = hasAuthority($perm);
			$data[$role][$perm] = $value;
		}
	}

	dd($data);

	dd();

});
