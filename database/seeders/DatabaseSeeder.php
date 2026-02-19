<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CurrencySeeder::class,
            StatusSeeder::class,
            PaymentTypeSeeder::class,
            PaymentSupplierSeeder::class,
            PaymentMethodSeeder::class,
        ]);

        if (in_array(App::environment(), ['testing', 'local'], true)) {
            $paytab = Supplier::where('key', 'paytab')->first();
            $payfort = Supplier::where('key', 'payfort')->first();
            $fawaterak = Supplier::where('key', 'fawaterak')->first();

            $domains = json_encode([
                'https://paymentservice.paymenthub.info'
            ]);
            $organization = Organization::factory()->create();
            $organization->paymentTypes()->attach([1, 2, 3]);
            $organization->suppliers()->attach($paytab->id, ['settings' => '{"supplier_currency": "EGP","supplier_id":1,"name":"paytab","supplier_name":"paytab 1","supplier_password":null,"supplier_server_key":"SZJNGNLLHD-JDZ22W6WBH-GGHMM9HHN6","supplier_profile_id":"101990", "supplier_pay_integration_url":"https://secure-egypt.paytabs.com/payment/", "supplier_refund_integration_url":"https://secure-egypt.paytabs.com/payment/"}']);
            $organization->suppliers()->attach($payfort->id, ['settings' => '{"supplier_currency": "EGP","supplier_id":1,"name":"payfort","supplier_name":"payfort 1","supplier_password":null,"supplier_access_code":"5PAbmqCjTaXDYm1M7YZt","supplier_merchant_identifier":"8cdb274a" ,"supplier_sha_request_phrase":"62Rj3f33pVfFzE6oQ.n26a!*","supplier_sha_response_phrase":"09Bf61VuN.PLekE001NKuB@?", "supplier_pay_integration_url":"https://sbcheckout.payfort.com/FortAPI/paymentPage","supplier_refund_integration_url":"https://sbpaymentservices.payfort.com/FortAPI/paymentApi"}']);
            $organization->suppliers()->attach($fawaterak->id, ['settings' => '{"supplier_currency": "EGP","supplier_id":1,"name":"fawaterak","fawaterak":"payfort 1","supplier_password":null,"api_key":"e757c0cd5a22f12bdd8d3d648da6c475fe02be03ebc66afc98","provider_key":"FAWATERAK.15173" ,"env_type":"test", "domains":'.$domains.'}']);

            $organization->paymentMethod()->attach([1]);
            $user = User::first();
            $user->paymentTypes()->attach([1 ,2 ,3]);
            $user->organization_supplier_id = $organization->suppliers()->first()->id;
            $user->save();
            foreach ($paytab->paymentMethods as $method)
            {
                $user->paymentMethod()->attach($method->id);
            }
        }
    }
}
