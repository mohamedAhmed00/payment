<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Services\Interfaces\IUserPaymentSettingsService;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class UserPaymentSettingsService implements IUserPaymentSettingsService
{
    public function getUserPaymentSettingsForDashboard(){

       return $this->getUserPaymentSettings(auth()->user());
    }

    public function getUserPaymentSettings(User|Authenticatable $user)
    {
        $paymentTypes = $user->paymentTypes;
        $paymentMethods = $user->paymentMethod()->with('supplier')->get();
        $settings = $user->supplierSettings?->settings;
        $paymentData = [];
        foreach ($paymentTypes as $paymentType){
            $method = [];
            $currency = '';
            if ($paymentType?->key == 'online'){
                if (!empty($settings)){
                    $currency = json_decode($settings)->supplier_currency;
                }
                foreach ($paymentMethods as $paymentMethod){
                    if ($paymentMethod->supplier?->payment_type_id == $paymentType?->id){
                        $method[] = [
                            'key' => $paymentMethod?->key,
                            'name' => $paymentMethod?->name,
                        ];
                    }
                }
            }
            $paymentData[] = [
                'key' => $paymentType?->key,
                'name' => $paymentType?->name,
                'currency' => $currency,
                'payment_methods' => $method
            ];
        }
        return $paymentData;
    }
}
