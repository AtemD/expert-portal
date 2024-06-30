<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();

        // Create an admin to login with 
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com'
        ]);

        // create 122 other normal users
        User::factory()->times(122)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
