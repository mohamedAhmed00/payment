<?php

namespace App\Domain\Provider\Fawaterak\Factory;

use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Provider\Fawaterak\Methods\Online;

class PaymentMethodFactory
{
    public function getPaymentMethod($method): IPaymentMethod{

        return match ($method){
            'online' => new Online(),
        };
    }
}
