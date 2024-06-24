<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ContractStatus;
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

        $contract_statuses = ContractStatus::all();

        Client::factory()->create([
            'name' => 'World Vision',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'War Child',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'DT Global',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Windle Trust',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Zoa Dorcas',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Plan International',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Care International',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Across Africa',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Mercy Corps',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'TearFund',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        Client::factory()->create([
            'name' => 'Eve Organization',
            'contract_status_id' => $contract_statuses->random()->id,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
