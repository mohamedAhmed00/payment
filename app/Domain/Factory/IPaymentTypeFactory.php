<?php

namespace App\Domain\Factory;

use App\Domain\PaymentTypes\IPaymentType;

interface IPaymentTypeFactory
{
    public function getPaymentTypeObject(string $type) : IPaymentType;
}
