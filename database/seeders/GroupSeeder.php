<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class GroupSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('permission:seed');
        Group::factory()->create(['name' => 'admin']);
    }
}
