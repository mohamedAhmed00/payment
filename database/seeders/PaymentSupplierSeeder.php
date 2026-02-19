<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\PaymentType;
use App\Models\Permission;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PaymentSupplierSeeder extends Seeder
{
    public function run()
    {
        $paymentType = PaymentType::where('key', 'online')->first();
        Supplier::upsert([
            ['name' => 'paytab', 'key' => 'paytab', 'payment_type_id' => $paymentType->id],
            ['name' => 'payfort', 'key' => 'payfort', 'payment_type_id' => $paymentType->id],
            ['name' => 'fawaterak', 'key' => 'fawaterak', 'payment_type_id' => $paymentType->id],
        ], ['key']);
    }
}
