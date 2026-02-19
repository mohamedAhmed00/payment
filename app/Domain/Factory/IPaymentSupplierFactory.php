<?php

namespace App\Domain\Factory;


use App\Domain\Provider\Contract\IPayAction;

interface IPaymentSupplierFactory
{
    public function getSupplierObject(string $supplier): IPayAction;
}
