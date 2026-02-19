<?php

namespace App\Domain\Provider\Contract;

interface IPaymentAction
{
    public function execute(...$data);
}
