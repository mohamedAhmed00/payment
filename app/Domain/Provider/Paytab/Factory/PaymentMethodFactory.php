<?php

namespace App\Domain\Provider\Paytab\Factory;

use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Provider\Paytab\Methods\Invoice;
use App\Domain\Provider\Paytab\Methods\Online;

class PaymentMethodFactory
{
    public function getPaymentMethod($method): IPaymentMethod{

        return match ($method){
            'online' => new Online(),
            'invoice_with_mail' => new Invoice(),
        };
    }
}
