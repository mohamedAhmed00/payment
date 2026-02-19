<?php

namespace App\Domain\Provider\Payfort\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Payfort\Factory\PaymentMethodFactory;

class PayAction implements IPaymentAction
{
    public function execute(...$data)
    {
        [$transaction, $supplierSetting] = $data;
        return resolve(PaymentMethodFactory::class)
            ->getPaymentMethod(request('payment_method'))->pay($transaction, $supplierSetting);
    }
}
