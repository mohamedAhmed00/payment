<?php

namespace App\Domain\Provider\Contract;

interface IPaymentMethod
{
    public function pay($transaction, $supplierSetting);
}
