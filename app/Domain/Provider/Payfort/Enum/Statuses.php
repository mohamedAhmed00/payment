<?php

namespace App\Domain\Provider\Payfort\Enum;

class Statuses
{
    public const SUCCESS = ['000'];

    public const HOLD = [19, 20];

    public const REFUND_COMMAND = 'REFUND';
}
