<?php

namespace App\Domain\Provider\Payfort\Factory;

use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Provider\Payfort\Methods\Invoice;
use App\Domain\Provider\Payfort\Methods\Online;

class PaymentMethodFactory
{
    public function getPaymentMethod($method): IPaymentMethod{

        return match ($method){
            'online' => new Online(),
            'invoice_with_mail' => new Invoice(),
        };
    }
}
