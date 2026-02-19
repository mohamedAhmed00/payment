<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\PaymentType;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PaymentTypeSeeder extends Seeder
{
    public function run()
    {
        PaymentType::upsert([
            ['name' => 'online', 'key' => 'online'],
            ['name' => 'cash', 'key' => 'cash'],
            ['name' => 'invoice', 'key' => 'invoice'],
        ], ['key']);
    }
}
