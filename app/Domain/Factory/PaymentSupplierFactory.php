<?php

namespace App\Domain\Factory;

use App\Domain\Provider\Contract\IPayAction;
use App\Domain\Provider\Fawaterak\Fawaterak;
use App\Domain\Provider\Payfort\Payfort;
use App\Domain\Provider\Paytab\Paytab;

class PaymentSupplierFactory implements IPaymentSupplierFactory
{
    public function getSupplierObject(string $supplier) : IPayAction
    {
        return match ($supplier){
            'paytab' => new Paytab(),
            'payfort' => new Payfort(),
            'fawaterak' => new Fawaterak()
        };
    }
}
