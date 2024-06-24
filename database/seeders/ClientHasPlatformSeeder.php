<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientHasPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('client_has_platforms')->truncate();

        // A client can be on 1 to 3 different platforms
        $platforms = Platform::all();
        $clients = Client::all();
        $platforms_count = $platforms->count();

        $clients->each(function ($client) use ($platforms, $platforms_count) {
            $client->platforms()->attach($platforms->random(mt_rand(1, $platforms_count)));
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
