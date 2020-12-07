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


Route::get('/test', function () {

	$data = [];
	foreach (collect(config('seeder.rolespermissions.specific_permissions')) as $role => $permissions) {
        foreach ($permissions as $permission) {
        	$value = hasAuthority($permission);
        	$data[$role][$permission] = $value;	
        }
    }

    dump($data);
   

    $data = [];
	foreach (config('seeder.rolespermissions.roles') as $role) {
		$permissions = config('seeder.rolespermissions.permissions'); 
		foreach ($permissions as $permission) {
			$permission = $role.'_'.$permission;
			$value = hasAuthority($permission);

			$data[$role][$permission] = $value;
	    }
	}


	// combine all roles
	// combine all permissions
	// collection loop and filter

	dump($data);

	dd();

});
