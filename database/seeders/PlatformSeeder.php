<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('platforms')->truncate();

        Platform::factory()->create([
            'name' => 'Starlink',
        ]);

        Platform::factory()->create([
            'name' => 'KU-Band',
        ]);

        Platform::factory()->create([
            'name' => 'C-Band',
        ]);

        Platform::factory()->create([
            'name' => 'Fiber Optics',
        ]);

        Platform::factory()->create([
            'name' => 'Fiber Optics Microwave',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
