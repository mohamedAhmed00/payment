<?php

namespace App\Domain\Provider\Paytab;

use App\Domain\Provider\Contract\ICallbackAction;
use App\Domain\Provider\Contract\IPayAction;
use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Contract\IPaymentSupplier;
use App\Domain\Provider\Contract\IRefundAction;
use App\Domain\Provider\Contract\IReturningAction;
use App\Domain\Provider\Paytab\Actions\CallbackAction;
use App\Domain\Provider\Paytab\Actions\PayAction;
use App\Domain\Provider\Paytab\Actions\RefundAction;
use App\Domain\Provider\Paytab\Actions\ReturnAction;

class Paytab implements IPayAction,IRefundAction,IReturningAction,ICallbackAction
{

    public function pay(): IPaymentAction
    {
        return new PayAction();
    }

    public function refund(): IPaymentAction
    {
        return new RefundAction();
    }

    public function returning(): IPaymentAction
    {
        return new ReturnAction();
    }

    public function callback(): IPaymentAction
    {
        return new CallbackAction();
    }
}
