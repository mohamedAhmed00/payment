<?php

namespace App\Domain\Provider\Fawaterak\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Fawaterak\Enum\Statuses;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Exceptions\ApiCustomException;
use App\Exceptions\PaymentException;
use Illuminate\Support\Facades\Http;
use Throwable;

class RefundAction implements IPaymentAction
{

    /**
     * @throws ApiCustomException
     */
    public function execute(...$data)
    {
        throw new ApiCustomException(__('Refund Not Available.'));
    }
}
