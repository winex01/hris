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

	foreach (config('seeder.rolespermissions.admin_role_permissions') as $permission) {
        dump($permission.' - '.hasAuthority($permission));
    }

    $data = [];
	foreach (config('seeder.rolespermissions.roles') as $role) {
		foreach (config('seeder.rolespermissions.permissions') as $permission) {
			$permission = $role.'_'.$permission;
			$value = $permission.' - '.hasAuthority($permission);

			$data[$role][$permission] = $value;
	    }
	}

	dd($data);
});
