<?php

namespace App\Domain\Provider\Fawaterak;

use App\Domain\Provider\Contract\ICallbackAction;
use App\Domain\Provider\Contract\IPayAction;
use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Contract\IRefundAction;
use App\Domain\Provider\Contract\IRenderIFrameAction;
use App\Domain\Provider\Contract\IReturningAction;
use App\Domain\Provider\Fawaterak\Actions\CallbackAction;
use App\Domain\Provider\Fawaterak\Actions\PayAction;
use App\Domain\Provider\Fawaterak\Actions\RefundAction;
use App\Domain\Provider\Fawaterak\Actions\RenderIFrameAction;
use App\Domain\Provider\Fawaterak\Actions\ReturnAction;

class Fawaterak implements IPayAction,IRefundAction,IReturningAction,ICallbackAction,IRenderIFrameAction
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

    public function renderIFrame(): IPaymentAction
    {
        return new RenderIFrameAction();
    }
}
