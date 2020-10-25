<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$this->createSuperAdminAccount();

        $this->call([
            RolesAndPermissionsSeeder::class
        ]);

    }

    private function createSuperAdminAccount()
    {
        $user = User::firstOrNew();
        $user->name = 'Administrator';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('password'); 
        $user->save();
    }
}
