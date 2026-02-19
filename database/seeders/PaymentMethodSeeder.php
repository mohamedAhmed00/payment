<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Permission;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        PaymentMethod::upsert([
            ['name' => 'online', 'key' => 'online', 'supplier_id' => 1],
            ['name' => 'Invoice with mail', 'key' => 'invoice_with_mail', 'supplier_id' => 1],
            ['name' => 'online', 'key' => 'online', 'supplier_id' => 2],
            ['name' => 'Invoice with mail', 'key' => 'invoice_with_mail', 'supplier_id' => 2],
            ['name' => 'online', 'key' => 'online', 'supplier_id' => 3],
            ['name' => 'Invoice with mail', 'key' => 'invoice_with_mail', 'supplier_id' => 3],
        ], ['key', 'supplier_id']);

    }
}
