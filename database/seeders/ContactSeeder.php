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

            // obtain a random contact for this client, make that contact, primary 
            $client_with_contacts = $client->load('contacts');
            $primary_contact = $client_with_contacts->contacts->random();

            $primary_contact->is_primary_contact = true;
            $primary_contact->save();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
