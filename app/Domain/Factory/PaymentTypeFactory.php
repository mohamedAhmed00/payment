<?php

namespace App\Domain\Factory;

use App\Domain\PaymentTypes\CashAndInvoicePaymentType;
use App\Domain\PaymentTypes\IPaymentType;
use App\Domain\PaymentTypes\OnlinePaymentType;

class PaymentTypeFactory implements IPaymentTypeFactory
{
    public function getPaymentTypeObject(string $type): IPaymentType{
        return match ($type){
            'online' => new OnlinePaymentType(),
            'invoice', 'cash' => new CashAndInvoicePaymentType(),
        };
    }

}
