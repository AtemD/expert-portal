<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contacts')->truncate();

        // for each client, create between 1 to 3 contacts
        $clients = Client::all();
        $clients->each(function ($client) {
            Contact::factory()->times(mt_rand(1, 3))->create([
                'client_id' => $client->id,
            ]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
