<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('clients')->truncate();

        Client::factory()->create([
            'name' => 'World Vision',
        ]);

        Client::factory()->create([
            'name' => 'War Child',
        ]);

        Client::factory()->create([
            'name' => 'DT Global',
        ]);

        Client::factory()->create([
            'name' => 'Windle Trust',
        ]);

        Client::factory()->create([
            'name' => 'Zoa Dorcas',
        ]);

        Client::factory()->create([
            'name' => 'Plan International',
        ]);

        Client::factory()->create([
            'name' => 'Care International',
        ]);

        Client::factory()->create([
            'name' => 'Across Africa',
        ]);

        Client::factory()->create([
            'name' => 'Mercy Corps',
        ]);

        Client::factory()->create([
            'name' => 'TearFund',
        ]);

        Client::factory()->create([
            'name' => 'Eve Organization',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
