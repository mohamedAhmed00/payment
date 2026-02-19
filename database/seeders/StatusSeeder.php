<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::upsert([
            ['name' => 'initial', 'key' => 'initial'],
            ['name' => 'processing', 'key' => 'processing'],
            ['name' => 'paid', 'key' => 'paid'],
            ['name' => 'refund', 'key' => 'refund'],
            ['name' => 'failed', 'key' => 'failed'],
            ['name' => 'refunded', 'key' => 'refunded'],
            ['name' => 'fraud', 'key' => 'fraud'],
            ['name' => 'pending', 'key' => 'pending']
        ], ['key']);
    }
}
